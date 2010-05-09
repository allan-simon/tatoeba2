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

// without this, filters won't works with pagination
$paginator->options(
    array(
        'url' => $this->params['pass']
    ) 
);

?>

<div id="annexe_content">
    <div class="module">
    <h2><?php __('Tips'); ?></h2>
    <p><?php __('You can edit your sentences by clicking on them.'); ?></p>
    <p>
        <?php 
        __('You can change the language of a sentence by clicking on the flag.'); 
        ?>
    </p>
    </div>
    
    <?php $commonModules->createFilterByLangMod(); ?> 
</div>
    
    
<div id="main_content">
    <div class="module">
        <h2>
            <?php 
            echo $paginator->counter(
                array(
                    'format' => __(
                        'My sentences (total %count%)',
                        true
                    )
                )
            ); 
            ?>
        </h2>
        
        <div class="paging">
        <?php 
        echo $paginator->prev(
            '<< '.__('previous', true), 
            array(), 
            null, 
            array('class'=>'disabled')
        ); 
        ?>
        <?php echo $paginator->numbers(array('separator' => '')); ?>
        <?php 
        echo $paginator->next(
            __('next', true).' >>',
            array(),
            null, 
            array('class'=>'disabled')
        ); 
        ?>
        </div>
        
        <?php
        $javascript->link('jquery.jeditable.js', false);
        $javascript->link('sentences.edit_in_place.js', false);
        $javascript->link('sentences.change_language.js', false);
        
        foreach ($user_sentences as $sentence) {
            $sentences->displayEditableSentence($sentence['Sentence']);
        }
        ?>
        
        <div class="paging">
        <?php 
        echo $paginator->prev(
            '<< '.__('previous', true), 
            array(), 
            null, 
            array('class'=>'disabled')
        ); 
        ?>
        <?php echo $paginator->numbers(array('separator' => '')); ?>
        <?php 
        echo $paginator->next(
            __('next', true).' >>', 
            array(), 
            null, 
            array('class'=>'disabled')
        ); 
        ?>
        </div>

    </div>
</div>
