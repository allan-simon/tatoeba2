<?php
/**
 * Tatoeba Project, free collaborative creation of multilingual corpuses project
 * Copyright (C) 2010 SIMON   Allan   <allan.simon@supinfo.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 *
 * @category PHP
 * @package  Tatoeba
 * @author   SIMON   Allan   <allan.simon@supinfo.com>
 * @license  Affero General Public License
 * @link     http://tatoeba.org
 */

/**
 * Model for join table Tags => sentences.
 *
 * @category Tags
 * @package  Models
 * @author   SIMON   Allan   <allan.simon@supinfo.com>
 * @license  Affero General Public License
 * @link     http://tatoeba.org
 */
class TagsSentences extends AppModel
{
    public $name = 'TagSentences';
    public $useTable = "tags_sentences";
    public $actsAs = array('Containable');


    public $belongsTo = array(
        'User',
        'Sentence',
        'Tag',
        );

 
    public function beforeSave()
    {
        $tagId = $this->data['TagsSentences']['tag_id'];
        $sentenceId = $this->data['TagsSentences']['sentence_id'];

        $result = $this->find(
            'first',
            array(
                'fields' => 'tag_id',
                'conditions' => array(
                    "tag_id" => $tagId,
                    "sentence_id" => $sentenceId),
                'contain' => array()
            )
        );
        return empty($result);
    }

    public function beforeDelete() {
        $tagId = $this->data['TagsSentences']['tag_id'];
        $sentenceId = $this->data['TagsSentences']['sentence_id'];
        
        $result = $this->find(
            'first',
            array(
                'fields' => 'user_id',
                'conditions' => array(
                    'sentence_id' => $sentenceId, 
                    'tag_id' => $tagId,
                ),
                'contain' => array() 
            )
        ); 
        if (empty($result)) {
            return false;
        }
        $taggerId = $result['TagsSentences']['user_id'];
        if (CurrentUser::canRemoveTagFromSentence($taggerId)) {
            return true;
        }
        return false;
    }


    public function tagSentence($sentenceId, $tagId, $userId)
    {
        $data = array(
            "TagsSentences" => array(
                "user_id" => $userId,
                "tag_id" => $tagId,
                "sentence_id" => $sentenceId,
                "added_time" => date("Y-m-d H:i:s")
            )
        );

        $this->save($data);

    }


    public function getAllTagsOnSentence($sentenceId)
    {
        return $this->find(
            'all',
            array(
                'fields' => array(
                    'Tag.internal_name',
                    'Tag.name',
                    'TagsSentences.user_id',
                    'TagsSentences.tag_id',
                    'TagsSentences.added_time'
                ),
                'conditions' => array(
                    'TagsSentences.sentence_id' => $sentenceId
                ),
                'contain' => array(
                    'Tag'
                )
            )
        );
    }

    public function removeTagFromSentence($tagId,$sentenceId) {
        $this->unBindModel(
            array(
                'belongsTo' => array('User', 'Tag', 'Sentence')
            )
        );
        $this->deleteAll(
            array(
                'tag_id' => $tagId,
                'sentence_id' => $sentenceId
            ),
            false // we don't want record to be delete in cascade as we only want
            // the relation to be broken
        );
    }
    
    
    /**
     * Get sentences with a certain tag that were tagged more than 2 weeks ago.
     *
     * @param int    $tagId Id of the tag.
     * @param string $lang  Language of the sentences.
     *
     * @return array
     */
    public function getSentencesForModerators($tagId, $lang)
    {
        $date = date('Y-m-d', strtotime("-2 weeks"));
        
        $sentenceConditions = array();
        
        if (!empty($lang)) {
            $sentenceConditions = array('lang' => $lang);
        }
        
        return $this->find(
            'all',
            array(
                'fields' => array('sentence_id'),
                'conditions' => array(
                    'tag_id' => $tagId,
                    'created <' => $date,
                    'text !=' => null
                ),
                'contain' => array(
                    'Sentence' => array(
                        'fields' => array('id', 'text', 'lang'),
                        'conditions' => $sentenceConditions
                    )
                ),
                'limit' => 100
            )
        );
    }

}
