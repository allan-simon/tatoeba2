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
 * Helper to display navigation links (previous, next, random, go to)
 *
 * @category Default
 * @package  Helpers
 * @author   HO Ngoc Phuong Trang <tranglich@gmail.com>
 * @license  Affero General Public License
 * @link     http://tatoeba.org
 */
class NavigationHelper extends AppHelper
{
    public $helpers = array('Html', 'Form', 'Languages', 'Javascript', 'Session');
    
    /** 
     * Display navigation links for sentences.
     *
     * @param int $currentId Id of sentence currently displayed.
     *
     * @return void
     */
    public function displaySentenceNavigation(
        $currentId = null,
        $next = null,
        $prev = null
    ) {
        $controller = $this->params['controller'];
        $action = $this->params['action'];
        $input = $this->params['pass'][0];
        if ($currentId == null) {
            $currentId = intval($input);
            $next = $currentId + 1;
            $prev = $currentId - 1;
        }
        ?>
        <div class="navigation">
            <?php
            // go to form
            echo $this->Form->create(
                'Sentence',
                array(
                    "action" => "go_to_sentence",
                    "type" => "get"
                )
            );
            echo $this->Form->input(
                'sentence_id', 
                array(
                    "label" => __('Show sentence nº : ', true), 
                    "value" => $input
                )
            );
            echo $this->Form->end(__('OK', true));
            ?>
            
            <div class="languageSelect">
            <script type='text/javascript'>
            $(document).ready(function() {
            $('#randomLangChoiceInBrowse').data(
                'currentSentenceId', <?php echo $currentId ?>
            );
            });
            </script>
           
            <?php
            $this->Javascript->link('sentences.random.js', false);
            
            
            $langArray = $this->Languages->languagesArray();
            $selectedLanguage = $this->Session->read('random_lang_selected');
            
            echo $this->Form->select(
                "randomLangChoiceInBrowse", 
                $langArray, 
                $selectedLanguage, 
                null, 
                false
            );
            ?>
            
            <span class="smallTip">
            &lt;=
            <?php
            __('Language for previous, next or random sentence');
            ?>
            </span>
            </div>
            
            <ul>
            <li class="active" id="prevSentence">
            <?php
            // previous
            echo $this->Html->link(
                '« '.__('previous', true),
                array(
                    "controller" => $controller,
                    "action" => $action,
                    $prev
                )
            );
            ?></li>
            
            <li class="active" id="nextSentence">
            <?php
            // next
            echo $this->Html->link(
                __('next', true).' »',
                array(
                    "controller" => $controller,
                    "action" => $action,
                    $next
                )
            );
            ?></li>
            
            
            <li class="active" id="randomLink">
            <?php
            // random
            echo $this->Html->link(
                __('random', true),
                array(
                    "controller" => "sentences",
                    "action" => "show",
                    $selectedLanguage
                ),
                array(
                    "lang" => $this->params['lang']
                )
            );
            ?>
            </li>
            
            <li id="loadingAnimationForNavigation" style="display:none">
            <?php
            echo $this->Html->image('loading-small.gif');
            ?>
            </li>
            
            </ul>
        </div>
        <?php
    }
    
    /** 
     * Display navigation for users.
     *
     * @param int $currentId Id of user currently displayed.
     * @param int $username  Id of username of user currently displayed.
     *
     * @return void
     */
    public function displayUsersNavigation($currentId, $username = null)
    {
        echo '<div class="navigation">';
        
        if ($username == null) {
            $username = '';
        }
        echo $this->Form->create('User', array("action" => "search"));
        echo $this->Form->input(
            'username',
            array(
                "label" => __('Enter a username : ', true), 
                "value" => $username
            )
        );
        echo $this->Form->end(__('show user', true));

        echo '<ul>'."\n";

        // all
        echo '<li class="option">';
        echo $this->Html->link(
            __('all', true),
            array(
                'controller' => 'users',
                'action' => 'all'
            )
        );
        echo '</li>';

        // back to whole profile
        if ($this->params['controller'] != 'users' 
            AND $this->params['action'] != 'show'
        ) {
            echo '<li class="option">';
            echo $this->Html->link(
                sprintf(__('Profile of %s', true), $username),
                array(
                    'controller' => 'user',
                    'action' => 'profile',
                    $username
                )
            );

            echo '</li>';
        }

        echo '</ul>';

        echo '</div>';
    }

    /** 
     * Display navigation for sentences lists.
     *
     * @return void
     */
    public function displaySentencesListsNavigation()
    {
        echo '<div class="navigation">';
            echo '<ul>';
            echo '<li class="option">';
            echo $this->Html->link(
                __('all the lists', true),
                array(
                    "controller" => "sentences_lists",
                    "action" => "index"
                )
            );
            echo '</li>';
            echo '</ul>';
        echo '</div>';
    }

}
?>
