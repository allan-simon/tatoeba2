<?php
/**
 * Tatoeba Project, free collaborative creation of multilingual corpuses project
 * Copyright (C) 2009  Allan SIMON <allan.simon@supinfo.com>
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
 * @author   Allan SIMON <allan.simon@supinfo.com>
 * @license  Affero General Public License
 * @link     http://tatoeba.org
 */

App::import('Core', 'Sanitize');

/**
 * Controller for favorite.
 *
 * @category TODEFINE 
 * @package  Controllers
 * @author   Allan SIMON <allan.simon@supinfo.com>
 * @license  Affero General Public License
 * @link     http://tatoeba.org
 */

class FavoritesController extends AppController
{

    public $name = 'Favorites' ;
    public $paginate = array('limit' => 50); 
    public $helpers = array('Navigation', 'Html');

    /**
     * to know who can do what
     *
     * @return void
     */

    public function beforeFilter()
    {
        parent::beforeFilter();
        
        // setting actions that are available to everyone, even guests
        $this->Auth->allowedActions = array('of_user');
    }
    
    /**
     * view all favorites sentences of a given user
     *
     * @param int $userId user to retrieve favorites 
     *
     * @return void
     */

    public function of_user($userId)
    {
        $userId = Sanitize::paranoid($userId);
        
        $favorites = $this->Favorite->getAllFavoritesOfUser($userId);
        $this->set('favorites', $favorites['Favorite']);
        $this->set('user', $favorites['User']);
    }

    /**
     * add a sentence to current user's ones
     *
     * @param int $sentenceId id of the sentence to favorite
     *
     * @return void
     */

    public function add_favorite($sentenceId)
    {
        $sentenceId = Sanitize::paranoid($sentenceId); 
        
        $userId =$this->Auth->user('id');
        
        if ($userId != null) {
            if ($this->Favorite->addFavorite($sentenceId, $userId)) {
                $this->set('saved', true);
            }
        }
    }

    /**
     * remove a favorite to current user's ones
     *
     * @param int $sentenceId id of the sentence to remove from favorites
     *
     * @return void
     */

    public function remove_favorite ($sentenceId)
    {
        $sentenceId = Sanitize::paranoid($sentenceId);
         
        $userId =$this->Auth->user('id');
        
        if ($userId != null) {
            
            if ($this->Favorite->removeFavorite($sentenceId, $userId)) {
                $this->set('saved', true);
            }
        }
    }


}
?>