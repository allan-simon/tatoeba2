<?php
/**
 * Tatoeba Project, free collaborative creation of multilingual corpuses project
 * Copyright (C) 2009  HO Ngoc Phuong Trang <tranglich@gmail.com>
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
 * @author   HO Ngoc Phuong Trang <tranglich@gmail.com>
 * @license  Affero General Public License
 * @link     http://tatoeba.org
 */
 
/**
 * Component for permissions.
 *
 * @category Default
 * @package  Components
 * @author   HO Ngoc Phuong Trang <tranglich@gmail.com>
 * @license  Affero General Public License
 * @link     http://tatoeba.org
 */
class PermissionsComponent extends Object
{
    public $components = array('Auth', 'Acl');
    
    /**
     * Check which options user can access to and returns
     * data that is needed for the sentences menu.
     *
     * @param array $sentence      Sentence.
     * @param int   $currentUserId Id of currently logged in user.
     *
     * @return array
     */
    public function getSentencesOptions($sentence, $currentUserId)
    {
        $sentenceOwnerId = $sentence['Sentence']['user_id'];

        $specialOptions = array(
              'canComment' => false
            , 'canEdit' => false
            , 'canDelete' => false
            , 'canAdopt' => false
            , 'canLetGo' => false
            , 'canTranslate' => false
            , 'canFavorite' => false
            , 'canUnFavorite' => false
            , 'canAddToList' => false
            , 'belongsToLists' => array()
        );
        
        if ($this->Auth->user('id')) {
            // -- comment --
            $specialOptions['canComment'] = true;
            
            // -- translate --
            $specialOptions['canTranslate'] = true;    
            
            // -- favorite --
            $specialOptions['canFavorite'] = true;
            // if we have already favorite it then we just can unfavorite it
            // is_array is here to avoid a warning when favorites_users is empty
            if (is_array($sentence['Favorites_users'])) {
                foreach ($sentence['Favorites_users'] as $favoritingUser) {
                    if ($favoritingUser['user_id'] == $this->Auth->user('id')) {
                        $specialOptions['canUnFavorite'] = true;
                        $specialOptions['canFavorite'] = false;
                    }
                }
            }
            
            // -- edit --
            if ($this->Auth->user('group_id') < 3) {
                
                $specialOptions['canEdit'] = true;
                
            }
            
            
            // -- edit and adopt --
            if ($sentenceOwnerId == $currentUserId) {
            
                $specialOptions['canEdit'] = true;
                $specialOptions['canLetGo'] = true;
                
            }
            
            
            // -- delete --
            $specialOptions['canDelete'] = ($this->Auth->user('group_id') < 2);
            
            
            // -- add to list --
            $specialOptions['canAddToList'] = true;
            if (isset($sentence['SentencesList'])) {
                foreach ($sentence['SentencesList'] as $list) {
                    array_push($specialOptions['belongsToLists'], $list['id']);
                }
            }
            
            // -- adopt --
            if ($sentenceOwnerId == null OR $sentenceOwnerId == 0) {
                
                $specialOptions['canAdopt'] = true;
                
            }
        }
        
        return $specialOptions;
    }
}
?>