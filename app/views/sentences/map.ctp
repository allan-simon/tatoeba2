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
?>

<div id="main_content">
	<div class="module">
		<?php
		$i = 1+ ($page-1)*10000;
		echo '<div id="sentencesMap">';
		foreach($all_sentences as $sentence){
			while($i < $sentence['Sentence']['id']){
				echo '<div class="empty" title="'.$i.'"></div>';
				$i++;
			}
			echo '<div class="'.$sentence['Sentence']['lang'].'_cluster" title="'.$i.', '.$sentence['Sentence']['lang'].'">';
			//echo $i.'<br/>'.$sentence['Sentence']['lang'];
			echo '</div>';
			$i++;
		}
		echo '</div>';
		?>
	</div>
</div>

