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
 * Display all comments on the sentences own by a specific user
 *
 * @category SentenceComments
 * @package  Views
 * @author   HO Ngoc Phuong Trang <tranglich@gmail.com>
 * @license  Affero General Public License
 * @link     http://tatoeba.org
 */

$this->pageTitle = 'Tatoeba - ' . sprintf(
    __("Comments on %s's sentences", true), $userName
); 
?>
<div id="annexe_content">
    <?php
        echo $this->element(
        'users_menu', 
        array('username' => $userName)
    );
    ?>
</div>

<div id="main_content">
    <div class="module">
    <?php
    if ($userExists === false) {
        echo '<h2>';
        echo sprintf(
            __("There's no user called %s", true),
            $userName
        );
        echo '</h2>';

        echo $html->link(__('Go back to previous page', true), $backLink);
    } elseif ($noComment === true) {
        echo '<h2>';
        echo sprintf(
            __("%s has no comment posted on his/her sentences", true),
            $userName
        );
        echo '</h2>';

        echo $html->link(__('Go back to previous page', true), $backLink);

    } else {
        ?>
        <h2>
            <?php 
            echo $paginator->counter(
                array(
                    'format' => sprintf(
                        __("Comments on %s's sentences (total %s)", true),
                        $userName,
                        '%count%'
                    )
                )
            ); 
            ?>
        </h2>
        
        
        <?php 
        $paginatorUrl = array($userName);
        $pagination->display($paginatorUrl);
        ?>
        
        
        <ol class="comments">
        <?php
        foreach ($userComments as $i=>$comment) {
            $comments->displaySentenceComment(
                $comment['SentenceComment'],
                $comment['User'],
                $comment['Sentence'],
                $commentsPermissions[$i]
            );
        }
        ?>
        </ol>
        
        
        <?php
        $pagination->display($paginatorUrl);
        
    }
    ?> 
    </div>
</div>




