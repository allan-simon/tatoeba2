<?php
/*
    Tatoeba Project, free collaborative creation of multilingual corpuses project
    Copyright (C) 2009  HO Ngoc Phuong Trang <tranglich@gmail.com>

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU Affero General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU Affero General Public License for more details.

    You should have received a copy of the GNU Affero General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

class SentenceComment extends AppModel{
	var $name = 'SentenceComment';
	var $actsAs = array('Containable');
	var $belongsTo = array('Sentence', 'User');

    /*
    ** get Number of sentences posted by a User
    */
    function numberOfCommentsOwnedBy ($userId){
        return $this->find(
            'count',
            array(
                'conditions' => array( 'SentenceComment.user_id' => $userId)
             )
        );

    }
	
	/**
	 * Return latest comments for each language.
	 */
	function getLatestCommentsInEachLanguage(){
		$langs = array('eng', 'fra', 'jpn', 'spa', 'deu');
		$sentenceComments = array();
		
		$this->recursive = 1;
		
		foreach($langs as $lang){
			$sentenceComments[$lang] = $this->find(
				"all",
				array( 
					"conditions" => array("SentenceComment.lang" => $lang),
					"limit"=> 10,
					"order" => "SentenceComment.created DESC"
				)
			);
		}
		
		$sentenceComments['unknown'] = $this->find(
			"all",
			array( 
				"conditions" => array("NOT" => array("SentenceComment.lang" => $langs)),
				"limit"=> 10,
				"order" => "SentenceComment.created DESC"
			)
		);
		
		return $sentenceComments;
	}
    
	/**
	 * Return comments for given sentence.
	 */
	function getCommentsForSentence($sentenceId){
		return $this->find('all', 
			array(
				'conditions' => array('SentenceComment.sentence_id' => $sentenceId),
				'order' => 'SentenceComment.created'
			)
		);
	}
	
	/**
	 * Return latest comments.
	 */
	function getLatestComments($limit){
		return $this->find(
			'all'
			, array('order' => 'SentenceComment.created DESC', 'limit' => $limit)
		);
	}
	
	/**
	 * Return emails of users who posted a comment on the sentence
	 * and who didn't disable notification.
	 */
	function getEmailsFromComments($sentenceId){
		$emails = array();
		$comments = $this->find(
			'all'
			, array(
				'conditions' => array('SentenceComment.sentence_id' => $sentenceId)
				, 'contain' => array ('User')
			)
		);
		foreach($comments as $comment){
			if($comment['User']['send_notifications']){
				$emails[] = $comment['User']['email'];
			}
		}
		$emails = array_unique($emails);
		return $emails;
	}
	
	/**
	 * Return email of owner of the sentence.
	 */
	function getEmailFromSentence($sentenceId){
		$sentence = $this->Sentence->find(
			'first'
			, array(
				'conditions' => array('Sentence.id' => $sentenceId)
				, 'contain' => array ('User')
			)
		);
		if(isset($sentence) AND $sentence['User']['send_notifications']){
			return $sentence['User']['email'];
		}else{
			return null;
		}
	}
}
?>
