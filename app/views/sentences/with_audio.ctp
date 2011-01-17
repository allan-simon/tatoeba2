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
if (empty($lang)){
    $title = __('Sentences with audio', true);
} else {
    $title = sprintf(
        __('Sentences in %s with audio', true), 
        $languages->codeToName($lang)
    );
}
?>

<div id="annexe_content">
    <?php echo $this->element('audio_stats', array('stats' => $stats)); ?>
</div>

<div id="main_content">
    <div class="module">
    <?php
    if (!empty($results)) {
        ?>
        
        <h2>
        <?php 
        echo $title;
        echo ' ';
        echo $paginator->counter(
            array(
                'format' => __('(%count% results)', true)
            )
        ); 
        ?>
        </h2>
        
        
        <?php
        $pagination->display(array($lang));
        
        foreach ($results as $sentence) {
            $sentences->displayGenericSentence(
                $sentence['Sentence'], 
                null,
                'mainSentence'
            );
        }
        
        $pagination->display(array($lang));
    } 
    ?>
    </div>
</div>