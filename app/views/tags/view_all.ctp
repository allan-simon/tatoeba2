<?php
/**
 * Tatoeba Project, free collaborative creation of multilingual corpuses project
 * Copyright (C) 2010 Allan SIMON <allan.simon@supinfo.com>
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

$this->pageTitle = 'Tatoeba - '. __('All existing tags', true);
?>

<div id="annexe_content">
    
    <div class="annexeMenu">
        <h2><?php __('Related links'); ?></h2>
        <ul>
        <li class="item"><a href="http://blog.tatoeba.org/2010/11/tags-guidelines.html">Tags guidelines</a>, Trang</li>
        <li class="item"><a href="http://a4esl.com/temporary/tatoeba/#tags">Tags - Experimental, Example Layouts</a>, CK</li>
        <li class="item"><a href="http://martin.swift.is/tatoeba/tags-cleanup.html">Tags cleanup</a>, Swift</li>
        </ul>
    </div>
</div>



<div id="main_content">
    <div class="module">
        <h2><?php __('All Tags'); ?></h2> 
        <div>
            <?php
            foreach( $allTags as $tag) {
                ?>
                <span class="tag">
                    <?php
                    $tagName =  $tag['Tag']['name'];
                    $tagInternalName =  $tag['Tag']['internal_name'];
                    $count = $tag['Tag']['nbrOfSentences'];
                    $tags->displayTagInCloud($tagName, $tagInternalName, $count);
                    ?>
                </span>
            <?php
            }
            ?>
        </div>
    </div>
</div>
