<?php
/**
 * Tatoeba Project, free collaborative creation of multilingual corpuses project
 * Copyright (C) 2010  Allan SIMON <allan.simon@supinfo.com>
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

$languageName = $languages->codeToName($lang);

$title = sprintf(__('All sentences in %s', true), $languageName);
if (!empty($notTranslatedIn) && $notTranslatedIn != 'none') {
    $notTranslatedInName = $languages->codeToName($notTranslatedIn);
    $title = sprintf(
        __('Sentences in %1$s not translated in %2$s', true), 
        $languageName, $notTranslatedInName
    );
}

$this->pageTitle = $title;
?>

<div id="annexe_content">
    <?php
    $attentionPlease->tatoebaNeedsYou();

    $showAll->displayShowAllInSelect($lang);
    $showAll->displayShowOnlyTranslationInSelect($translationLang);
    $showAll->displayShowNotTranslatedIn($notTranslatedIn);
    $showAll->displayFilterOrNotAudioOnly($filterAudioOnly);
    ?>


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
        $paginationUrl = array(
            $lang,
            $translationLang,
            $notTranslatedIn,
            $filterAudioOnly,
        );
        $pagination->display($paginationUrl);
        
        foreach ($results as $sentence) {
            $sentences->displaySentencesGroup(
                $sentence['Sentence'], 
                $sentence['Translations'], 
                $sentence['User'],
                $sentence['IndirectTranslations']
            );
        }
        
        $pagination->display($paginationUrl);
    } 
    ?>
    </div>
</div>
