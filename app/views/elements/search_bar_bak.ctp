<?php
/*
    Tatoeba Project, free collaborativ creation of languages corpuses project
    Copyright (C) 2009  TATOEBA Project(should be changed)

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
if (isset($this->params['lang'])) { 
	Configure::write('Config.language',  $this->params['lang']); 
}
?>

<div class="search_bar">

<?php
$languages = array(
	  'en' => __('English', true)
	, 'jp' => __('Japanese', true)
	, 'fr' => __('French', true)
	, 'de' => __('German', true)
	, 'es' => __('Spanish', true)
	, 'it' => __('Italian', true)
	, 'id' => __('Indonesian', true)
	, 'vn' => __('Vietnamese', true)
	, 'pt' => __('Portuguese', true)
	, 'ru' => __('Russian', true)
	, 'zh' => __('Chinese', true)
	, 'ko' => __('Korean', true)
	, 'nl' => __('Dutch', true)
);
asort($languages);
$selectedLanguageFrom = $session->read('search_from');
$selectedLanguageTo = $session->read('search_to');

echo $form->create('Sentence', array("action" => "search", "type" => "get"));
echo $form->select('from', $languages, $selectedLanguageFrom);
echo ' >> ';
echo $form->select('to', $languages, $selectedLanguageTo);
echo $form->input('query', array("label" => '', "value" => $session->read('search_query')));
echo $form->end(__('search',true));

echo $html->link(__('show examples',true), array("controller" => "sentences", "action" => "search"	));
?>
</div>