<?php 
echo '<h2>';
if($session->read('Auth.User.id')){
	__('Your Statistics');
}else{
	__('Statistics for anonym users');
}
echo '</h2>';
	
$total = 0;
echo '<table id="userStatistics">';
foreach($userStatistics as $statistics){
	$stats = $statistics['UsersStatistic'];

	$type = '';
	$status = '';
	
	if($stats['is_translation'] == 0){
		$type = 'sentence';
	}else{
		$type = 'link';
	}
	
	switch($stats['action']){
		case 'suggest' : 
			$type = 'correction';
			$status = 'Suggested'; 
			break;
		case 'insert' :
			$status = 'Added';
			break;
		case 'update' :
			$status = 'Modified';
			break;
		case 'delete' :
			$status = 'Deleted';
			break;
	}
	
		
	echo '<tr class="'.$type.$status.'">';
		echo '<td>';
		switch($type.$status){
			case 'sentenceAdded' :
				__('Number of sentences added');
				break;
			case 'sentenceModified';
				__('Number of sentences modified');
				break;
			case 'sentenceDeleted' :
				__('Number of sentences deleted');
				break;
			case 'correctionSuggested';
				__('Number of corrections suggested');
				break;
			case 'linkAdded' :
				__('Number of links added');
				echo ' ';
				$tooltip->display(__('Whenever you translate a sentence, it counts as <strong>1 sentence</strong> added and <strong>2 links added</strong>.',true));
				break;
			case 'linkDeleted';
				__('Number of links deleted');
				break;
		}
		echo '</td>';
		
		echo '<td>';
		echo $stats['quantity'];
		echo '</td>';
	echo '</tr>';
	
	$total += $stats['quantity'];
}

echo '<tr class="total">';
	echo '<td>';
	__('Total');
	echo '</td>';
	
	echo '<td>';
	echo $total;
	echo '</td>';
echo '</tr>';

echo '</table>';



echo '<br/>';



echo '<h2>';
__('Statistics of all contributors');
echo '</h2>';

echo '<table id="usersStatistics">';

echo '<tr>';
	echo '<th>';
	__('rank');
	echo '</th>';

	echo '<th>';
	__('username');
	echo '</th>';

	echo '<th>';
	__('total contributions');
	echo '</th>';
echo '</tr>';

$i = 1;
foreach ($usersStatistics as $usersStatistic){
	echo '<tr>';
		echo '<td>';
		echo $i;
		echo '</td>';
		
		echo '<td>';
		if(isset($usersStatistic['User']['username'])){
			echo $usersStatistic['User']['username'];
		}else{
			__('unknown');
		}
		echo '</td>';
		
		echo '<td>';
		echo $usersStatistic[0]['total'];
		echo '</td>';
	echo '</tr>';
	$i++;
}

echo '</table>';
?>
