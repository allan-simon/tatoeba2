<?php
if(isset($query)){
	$query = stripslashes($query);
	
	echo '<h2>Search : ' . $query . ', <em>' . $resultsInfo['sentencesCount'] . ' results</em></h2>';
	
	if(isset($results)){
		
		$pagination->displaySearchPagination($resultsInfo['pagesCount'], $resultsInfo['currentPage'], $query);
		
		$i = 0;
		
		foreach($results as $sentence){
			echo '<div class="sentences_set search">';
			// sentence menu (translate, edit, comment, etc)
			$sentences->displayMenu($sentence['Sentence']['id'], $specialOptions, $scores[$i]);
			$i++;

			// sentence and translations
			$sentences->displayGroup($sentence['Sentence'], $sentence['Translation']);
			echo '</div>';
		}
		
		$pagination->displaySearchPagination($resultsInfo['pagesCount'], $resultsInfo['currentPage'], $query);
		
	}else{
		__('No results for this search');
	}
}
?>