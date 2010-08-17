<?php
/**
 * Tatoeba Project, free collaborative creation of multilingual corpuses project
 * Copyright (C) 2009-2010  HO Ngoc Phuong Trang <tranglich@gmail.com>
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
 * Controller for sentences.
 *
 * @category Sentences
 * @package  Controllers
 * @author   HO Ngoc Phuong Trang <tranglich@gmail.com>
 * @license  Affero General Public License
 * @link     http://tatoeba.org
 */
class SentencesController extends AppController
{
    public $persistentModel = true;
    public $name = 'Sentences';
    public $components = array (
        'GoogleLanguageApi',
        'CommonSentence',
        'Lucene',
        'Permissions'
    );
    public $helpers = array(
        'Sentences',
        'Menu',
        'SentenceButtons',
        'Html',
        'Logs',
        'Pagination',
        'Comments',
        'Navigation',
        'Languages',
        'Javascript',
        'CommonModules',
        'AttentionPlease'
    );
    public $paginate = array(
        'limit' => 100,
        "order" => "Sentence.modified DESC"
    );

    public $uses = array(
        'Sentence','SentenceNotTranslatedIn'
    );
    
    /**
     * Before filter.
     * 
     * @return void
     */
    public function beforeFilter()
    {
        parent::beforeFilter();
        
        // setting actions that are available to everyone, even guests
        $this->Auth->allowedActions = array(
            'index',
            'show',
            'search',
            'add_comment',
            'random',
            'go_to_sentence',
            'statistics',
            'count_unknown_language',
            'get_translations',
            'change_language',
            'several_random_sentences',
            'sentences_group',
            'get_neighbors_for_ajax',
            'show_all_in',
        );
    }

    /**
     * Redirects to a random sentence.
     * 
     * @return void
     */
    public function index()
    {
        $this->redirect(
            array(
                "action" => "show",
                "random"
            )
        );
    }
    
    /**
     * Show sentence of specified id (or a random one if no id specified).
     *
     * @param mixed $id Id of the sentence or language of the random sentence.
     *
     * @return void
     */
    public function show($id = null)
    {
        $this->helpers[] = 'Tags'; 

        $id = Sanitize::paranoid($id);
        
        $userId = $this->Auth->user('id');
        $groupId = $this->Auth->user('group_id');

        if ($id == "random" || $id == null || $id == "" ) {
            $id = $this->Session->read('random_lang_selected');
            $id = Sanitize::paranoid($id);
        }
        
        if (in_array($id, $this->Sentence->languages)) {
            // ----- if we want a random sentence in a specific language -----
            // here only to make things clearer as "id" is not a number 
            $lang = $id; 
            $randomId = $this->Sentence->getRandomId($lang);
            
            $this->Session->write('random_lang_selected', $lang);
            $this->redirect(array("action"=>"show", $randomId));  
        
        } elseif (is_numeric($id)) {
            // ----- if we give directly an id -----
            // Whether the sentence still exists or not, we retrieve the
            // contributions and the comments because we don't want them
            // to disappear just because the sentence was deleted.
            $contributions = $this->Sentence->getContributionsRelatedToSentence(
                $id
            );
            
            $comments = $this->Sentence->getCommentsForSentence($id);
            $commentsPermissions = $this->Permissions->getCommentsOptions(
                $comments,
                $userId,
                $groupId 
            );
            
            $this->set('sentenceComments', $comments);
            $this->set('commentsPermissions', $commentsPermissions);
            $this->set('contributions', $contributions); 
            

            // And now we retrieve the sentence
            $sentence = $this->Sentence->getSentenceWithId($id);


            // this way "next" and "previous"  
            $lang = $this->Session->read('random_lang_selected');
            $neighbors = $this->Sentence->getNeighborsSentenceIds($id, $lang);
            $this->set('nextSentence', $neighbors['next']);
            $this->set('prevSentence', $neighbors['prev']);

            // If no sentence, we don't need to go further.
            // We just set some variable so we don't get warnings.
            if ($sentence == null) {
                $this->set('sentenceId', $id);
                $this->set('tagsArray', array()); 
                return;
            }

            $tags = $this->Sentence->getAllTagsOnSentence($id); 

            
            $this->set('sentence', $sentence);
            
            // we get translations and split them
            $alltranslations = $this->Sentence->getTranslationsOf($id);
            $translations = $alltranslations['Translation'];
            $indirectTranslations = $alltranslations['IndirectTranslation'];
            
            $this->set('tagsArray', $tags); 
            $this->set('translations', $translations);
            $this->set('indirectTranslations', $indirectTranslations);
            
            
        } else {
            // ----- other case -----
            $max = $this->Sentence->getMaxId();
            $randId = rand(1, $max);
            $this->Session->write('random_lang_selected', 'und');
            $this->redirect(
                array(
                    "action"=>"show",
                    $randId
                )
            );
        }
    }
    
    
    /**
     * Display sentence of specified id.
     *
     * @return void
     */

    public function go_to_sentence()
    {
        $id = intval($this->params['url']['sentence_id']);
        if ($id == 0) {
            $id = 'random';
        }
        $this->redirect(array("action"=>"show", $id));
    }
    
    /**
     * Add a new sentence.
     *
     * @return void
     */

    public function add()
    {
        $userId = $this->Auth->user('id');
        $sentenceLang = $this->data['Sentence']['contributionLang'];
        $sentenceLang = Sanitize::paranoid($sentenceLang);
        $sentenceText = $this->data['Sentence']['text'];
        $sentenceText = trim($sentenceText);

        $this->Session->write('contribute_lang', $sentenceLang);

        if (empty($sentenceText) || empty($userId)) {
            return ;
        }

        // saving
        $isSaved = $this->CommonSentence->wrapper_save_sentence(
            $sentenceLang,
            $sentenceText,
            $userId
        );

        if ($isSaved) {
            $sentence = $this->Sentence->getSentenceWithId($this->Sentence->id);
            $this->set('sentence', $sentence);
        }
    }
    
    /**
     * Delete a sentence.
     *
     * @param int $id Id of the sentence.
     *
     * @return void
     */
    public function delete($id = null)
    {
        $id = Sanitize::paranoid($id);
        if (empty($id)) {
            return;
        }
        
        $this->Sentence->delete(
            $id,
            true
        );
        $this->flash(
            'The sentence #'.$id.' has been deleted.', '/sentences/show/'.$id
        );
    }
    
    /**
     * used by sentences.contribute.js
     * save an other new sentence 
     *
     * @return void
     */
    public function add_an_other_sentence()
    {

        if (!isset($_POST['value'])
            || !isset($_POST['selectedLang'])
        ) {
            //TODO add error handling
            return;
        }
        $userId = $this->Auth->user('id');

        $sentenceLang = Sanitize::paranoid($_POST['selectedLang']);
        $sentenceText = $_POST['value'];

        $isSaved = $this->CommonSentence->wrapper_save_sentence(
            $sentenceLang,
            $sentenceText,
            $userId
        );
        
        $this->Session->write('contribute_lang', $sentenceLang);
        
        // saving
        if ($isSaved) {
            $this->layout = null;
            
            $sentenceId = $this->Sentence->id;
            $sentence = $this->Sentence->getSentenceWithId($sentenceId);
            
            $this->set('sentence', $sentence);
        }

    }

    /**
     * Edit sentence.
     * Used in AJAX request, in sentences.edit_in_place.js.
     *
     * @return void
     */
    public function edit_sentence()
    {
        $userId = $this->Auth->user('id');
        $sentenceText = '';
        $sentenceId = '';
        if (isset($_POST['value'])) {
            $sentenceText = trim($_POST['value']);
        }
        if (isset($_POST['id'])) {
            $sentenceId = $_POST['id']; // NOTE: do not Sanitize::paranoid() this
                                        // ...because hack mentionned below
        }
        
        if (!isset($sentenceText) || $sentenceText === '') {
            // if the sentence contain no text (empty or only space)
            // the we directly return without saving
            return;
        }
        
        if (isset($sentenceId)) {
            // TODO HACK SPOTTED $_POST['id'] store 2 informations, lang and id
            // related to HACK in edit in place.js 
            $hack_array = explode("_", $sentenceId);
            
            $realSentenceId = Sanitize::paranoid($hack_array[1]);
            $sentenceLang = Sanitize::paranoid($hack_array[0]);
            
            $this->Sentence->id = $realSentenceId;
            $data['Sentence']['lang'] = $sentenceLang;
            $data['Sentence']['text'] = $sentenceText;
            $isSaved = $this->Sentence->save($data);
                            
            if ($isSaved) {
                $this->layout = null;
                $this->set('sentence_text', $sentenceText);
            }
        
        } 
    }
    
    /**
     * Adopt a sentence. User can modify sentence and becomes
     * responsible of the sentence.
     *
     * @param int $id Id of the sentence.
     *
     * @return void
     */
    public function adopt($id)
    {
        $id = Sanitize::paranoid($id);
        $userId = $this->Auth->user('id');
        
        $this->Sentence->setOwner($id, $userId);
        
        $this->show($id);
        $this->render('sentences_group'); // We render with another view than "show"
    }
    
    /**
     * Let go a sentence. Sentence does not belong to user anymore,
     * i.e. user cannot modify it anymore, and is not responsible
     * of it either.
     *
     * @param int $id Id of the sentence.
     *
     * @return void
     */
    public function let_go($id)
    {
        $id = Sanitize::paranoid($id);
        $userId = $this->Auth->user('id');
        
        $this->Sentence->unsetOwner($id, $userId);
        
        $this->show($id);
        $this->render('sentences_group'); // We render with another view than "show"
    }
    
    /**
     * Save the translation.
     *
     * @return void
     */ 
    public function save_translation()
    {
        $sentenceId = Sanitize::paranoid($_POST['id']);
        $translationLang = Sanitize::paranoid($_POST['selectLang']);
        $withAudio = Sanitize::paranoid($_POST['withAudio']);
        $parentOwnerName = $_POST['parentOwnerName'];
        
        $userId = $this->Auth->user('id');
        $translationText = $_POST['value'];
        
        // we store the selected language to be reuse after
        // that way, as users are likely to contribute in the 
        // same language, they don't need to reselect each time
        $this->Session->write('contribute_lang', $translationLang);
        
        if (isset($translationText)
            && trim($translationText) != '' 
            && isset($sentenceId)
            && !(empty($userId))
        ) {
            // Language detection
            if ($translationLang == 'auto') {
                $translationLang = $this->GoogleLanguageApi->detectLang(
                    $translationText
                );
            }
            
            // Saving...
            $isSaved = $this->Sentence->saveTranslation(
                $sentenceId,
                $translationText,
                $translationLang
            );
            
            if ($isSaved) {
                // We reconstruct the translation to use it in the helper's function
                $translation['id'] = $this->Sentence->id;
                $translation['lang'] = $translationLang;
                $translation['text'] = $translationText;
                
                $ownerName = $this->Auth->user('username');
                
                $this->set('translation', $translation);
                $this->set('ownerName', $ownerName);
                $this->set('parentId', $sentenceId);
                $this->set('parentOwnerName', $parentOwnerName);
                $this->set('withAudio', $withAudio);
            }
        }
    }
    
    /**
     * Search sentences.
     *
     * @param string $query The research query.
     *
     * @return void
     */
    public function search($query = null)
    {
        if (!isset($_GET['query']) || empty($_GET['query'])) {
            $this->redirect(
                array(
                    "lang" => $this->params['lang'], 
                    "controller" => "pages", 
                    "action" => "search", 
                )
            );
        }
        
        
        $query = $_GET['query'];    
                
        $from = 'und'; 
        if (isset($_GET['from'])) {
            $from = $_GET['from'];
            $from = Sanitize::paranoid($from);
        }
       
        $to = 'und'; 
        if (isset($_GET['to'])) {
            $to = $_GET['to'];
            $to = Sanitize::paranoid($to);
        }
        
        // Session variables for search bar
        $this->Session->write('search_query', $query);
        $this->Session->write('search_from', $from);
        $this->Session->write('search_to', $to);

        // replace strange space
        $query = str_replace(
            array('　', ' '),
            ' ',
            $query
        );
 
        $sphinx = array(
            'index' => array($from . "_" . $to . '_index'),
            'matchMode' => SPH_MATCH_EXTENDED2, 
            'sortMode' => array(SPH_SORT_RELEVANCE => "")
        );
       
        $model = 'Sentence'; 
        $pagination = array(
            'Sentence' => array(
                'fields' => array(
                    'id',
                ),
                'contain' => array(),
                'limit' => 10,
                'sphinx' => $sphinx,
                'search' => $query
            )
        );

        $allSentences = $this->_common_sentences_pagination(
            $pagination,
            $model,
            $to
        ); 
        
        $this->set('query', $query);
        $this->set('results', $allSentences);
    }
    
    /**
     * Show all sentences in a specific language
     *
     * @param string $lang            Show all sentences in this language.
     * @param string $translationLang Show only translation in this lang.
     * @param string $notTranslatedIn Show only sentences which have no direct
     *                                translation in this language
     * @param string $filterAudioOnly Show only sentences which have a mp3
     *
     * @return void
     */
    public function show_all_in(
        $lang,
        $translationLang,
        $notTranslatedIn,
        $filterAudioOnly = "indifferent"
    ) {


        // TODO it's a hack need to find  in contribute.ctp how to make
        // a form who directly forge a cakephp-compliant url
        if (isset($_POST['data']['Sentence']['into'])) {
            $this->redirect(
                array(
                    "controller" => "sentences",
                    "action" => "show_all_in",
                    $_POST['data']['Sentence']['into'],
                    'none',
                    'none',
                    'indifferent'
                )
            );
        }

        $this->helpers[] = 'ShowAll'; 
        
        $model = 'Sentence';
        
        if ($lang == 'unknown') {
            $lang = null;
        }

        $pagination = array(
            'Sentence' => array(
                'fields' => array(
                    'id',
                ),
                'conditions' => array(
                    'lang' => $lang,
                ),
                'contain' => array(),
                'limit' => 10,
                'order' => "Sentence.id desc"
            )
        );

        // filter or not sentences-with-audio-only  
        $audioOnly = false ;
        if ($filterAudioOnly === "only-with-audio") {
            $audioOnly = true ;
            $pagination['Sentence']['conditions']['hasaudio !='] = "no";
        }


        if (!empty($notTranslatedIn) && $notTranslatedIn != 'none') {

            $model = 'SentenceNotTranslatedIn';
            $pagination = array(
                'SentenceNotTranslatedIn' => array(
                    'fields' => array(
                        'id',
                    ),
                    'conditions' => array(
                        'source' => $lang,
                        'translatedIn' => $translationLang,
                        'notTranslatedIn' => $notTranslatedIn,
                        'audioOnly' => $audioOnly,
                    ),
                    'contain' => array(),
                    'limit' => 10,
                )
            );
        }

        $allSentences = $this->_common_sentences_pagination(
            $pagination,
            $model,
            $translationLang
        ); 
        
        if ($lang === null) {
            $lang = 'unknown';
        }
        
        $this->set('lang', $lang);
        $this->set('filterAudioOnly', $filterAudioOnly);
        $this->set('notTranslatedIn', $notTranslatedIn);
        $this->set('translationLang', $translationLang);
        $this->set('results', $allSentences);
    }
    /**
     * Return all informations needed to display a paginate
     * list of sentences
     *
     * @param array  $pagination      The pagination request.
     * @param string $model           Model to use for pagination
     * @param string $translationLang If different of null, will only
     *                                retrieve translation in this language.
     *
     * @return array Big nested array of sentences + information related to senences
     */
    private function _common_sentences_pagination(
        $pagination,
        $model,
        $translationLang = null
    ) {
 
        $this->paginate = $pagination;
        $results = $this->paginate($model);

        $sentenceIds = array();

        foreach ($results as $i=>$sentence) {
            $sentenceIds[$i] = $sentence['Sentence']['id'];
        }
        
        if ($translationLang == "und") {
            $translationLang = null ;
        }

        $allSentences = $this->CommonSentence->getAllNeededForSentences(
            $sentenceIds,
            $translationLang
        );
        return $allSentences;     
    }
    /**
     * Show random sentence.
     *
     * @param string $lang Language of the random sentence.
     *
     * @return void
     */
    public function random($lang = null)
    {
        $lang = Sanitize::paranoid($lang);
        
        if ($lang == null) {
            $lang = $this->Session->read('random_lang_selected');
        }
        
        $randomId = $this->Sentence->getRandomId($lang);
        $randomSentence = $this->Sentence->getSentenceWithId($randomId);
        $alltranslations = $this->Sentence->getTranslationsOf($randomId);
        $translations = $alltranslations['Translation'];
        $indirectTranslations = $alltranslations['IndirectTranslation'];
       
        $this->Session->write('random_lang_selected', $lang);
        
        $this->set('random', $randomSentence);
        $this->set('sentenceScript', $randomSentence['Sentence']['script']);
        $this->set('translations', $translations);
        $this->set('indirectTranslations', $indirectTranslations);
    }
    
    /**
     * show several random sentences a time
     * NOTE : TODO : this just a work around ! 
     *
     * @return void
     */
    public function several_random_sentences()
    {

        if (isset($_POST['data']['Sentence']['numberWanted'])) {
            $number = $_POST['data']['Sentence']['numberWanted'];
            $number = Sanitize::paranoid($number);
        } else {
            // default number of sentences when coming from "show more..."
            $number = 5; 
        }
        
        if (isset($_POST['data']['Sentence']['into'])) {
            $lang = $_POST['data']['Sentence']['into'] ;
            $lang = Sanitize::paranoid($lang);
            $this->Session->write('random_lang_selected', $lang);
        } else {
            // default language when coming from "show more..."
            $lang = $this->Session->read('random_lang_selected');
        }
        
        $type = null ;
        // to avoid "petit malin" 
        if ( $number > 15 or $number < 1) {
            $number = 5 ;
        } 
        
        // for far better perfomance we must do it in one request, but hmmm no time
        // for that and as said above that's a work around

        $randomIds = $this->Sentence->getSeveralRandomIds($lang, $number);
    
        $this->Session->write('random_lang_selected', $lang);
        
        $allSentences = $this->CommonSentence->getAllNeededForSentences($randomIds);
        
        $this->set("allSentences", $allSentences);
        $this->set('lastNumberChosen', $number);
    }
    
    
    /**
     * Save languages for unknown language page.
     *
     * @return void
     */
    public function set_languages()
    {

        if (!empty($this->data)) {

            $sentences = $this->data['Sentence'];

            // if the language is still unknow
            // we unset the lang field to save it as NULL
            foreach ($sentences as $i=>$sentence) {
                if ($sentence['lang'] === 'unknown') {
                    unset($sentences[$i]['lang']);
                }
            }

            // TODO don't forget to update language stats count 

            if ($this->Sentence->saveAll($sentences)) {
                $flashMsg = __('The languages have been saved.', true);
            } else {
                $flashMsg = __('A problem occured while trying to save.', true);
            }
        } else {
            $flashMsg = __('There is nothing to save.', true);
        }
        
        $this->flash(
            $flashMsg,
            '/sentences/unknown_language/'
        );
        
    }
   
    /**
     * Show all the sentences of a given user
     *
     * @param string $userName The user's name.
     * @param string $lang     Filter only sentences in this language.
     *
     * @return void
     */
    public function of_user($userName, $lang = null)
    {
        $lang = Sanitize::paranoid($lang);
        
        $this->loadModel('User');
        $userId = $this->User->getIdFromUserName($userName);
        
        $backLink = $this->referer(array('action'=>'index'), true);
        // if there's no such user no need to do more computation
        
        $this->set('backLink', $backLink);
        $this->set("userName", $userName);
        if (empty($userId)) {

            $this->set("userExists", false);
            return; 
        }
        
        $this->set("userExists", true);
        $this->_sentences_of_user_common($userId, $lang);
        $this->set("lang", $lang);
        
    } 
    
    /**
     * Private function to factorize my_sentences and of_user
     *
     * @param int    $userId The id of the user whose we want the sentences.
     * @param string $lang   Filter only the sentences in this language.
     *
     * @return void
     */
    private function _sentences_of_user_common($userId, $lang)
    {
        $this->paginate = array(
            'Sentence' => array(
                'fields' => array(
                    'id',
                    'text',
                    'lang',
                    'user_id',
                    'correctness'
                ),
                'contain' => array(
                    'User' => array(
                        'fields' => array('username')
                    )
                ),
                'limit' => 100,
                'order' => "Sentence.modified DESC"

            )
        );


        $conditions = array(
            'Sentence.user_id' => $userId,
        );
        // if the lang is specified then we also filter on the language
        if (!empty($lang)) {
            $conditions = array(
                "AND" => array(
                    'Sentence.user_id' => $userId,
                    'Sentence.lang' => $lang
                )
            ); 
        }
        
        $sentences = $this->paginate(
            'Sentence',
            $conditions
        );
        
        $this->set('user_sentences', $sentences);
    }
    
    /**
     * Display how the sentences are clustered according to their language.
     *
     * @param int $page Page of the map.
     *
     * @return void
     */
    public function map($page = 1)
    {
        $page = Sanitize::paranoid($page);

        $total = 10000;
        $start = ($page-1) * $total;
        $end = $start + $total;
        
        $sentences = $this->Sentence->getSentencesForMap($start, $end);
        $this->set('page', $page);
        $this->set('all_sentences', $sentences);
    }
    
    /**
     * Change language of a sentence.
     * Used in AJAX request in sentences.change_language.js.
     *
     * @return void
     */
    public function change_language()
    {
        if (isset($_POST['id'])
            && isset($_POST['newLang'])
            && isset($_POST['prevLang'])
        ) {
            Configure::write('debug', 0);
            
            $newLang = Sanitize::paranoid($_POST['newLang']);
            $prevLang = Sanitize::paranoid($_POST['prevLang']);
            $id = Sanitize::paranoid($_POST['id']);
            
            $lang = $this->Sentence->changeLanguage($id, $prevLang, $newLang);
            $this->set('lang', $lang);
        }
    }

    /**
     * Use by ajax only, get previous and next valid sentence id
     *
     * @param int    $id   Id of the current sentence.
     * @param string $lang Previous and next in this language.
     *
     * @return void
     */
    public function get_neighbors_for_ajax($id, $lang)
    {
        Configure::write('debug', 0);
        $this->layout = null;

        $this->Session->write('random_lang_selected', $lang);
        $neighbors = $this->Sentence->getNeighborsSentenceIds($id, $lang);
        $this->set('nextSentence', $neighbors['next']);
        $this->set('prevSentence', $neighbors['prev']);
    }
    
    
    /**
     * action uses to display the import forms
     *
     * @TODO maybe move this in pages controller
     *
     * @return void
     */
    public function import()
    {
        if (! CurrentUser::isModerator()) {
            $this->redirect(
                array(
                    "controller" => "pages", 
                    "action" => "home", 
                )
            );
           
        }
    }
}
?>
