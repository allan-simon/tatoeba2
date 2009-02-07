<?php
class CommentsHelper extends AppHelper {

	var $helpers = array('Form', 'Date', 'Tooltip', 'Html');

	function displayComment($id, $username, $datetime, $comment){
		echo '<div class="comment">';
			echo '<div class="header">';
			echo '<span class="username">';
			echo $this->Html->link($username, array("controller" => "users", "action" => "show", $id));
			echo '</span>';
			echo '<span class="date">'.$this->Date->ago($datetime).'</span>';
			echo '</div>';
			
			echo '<div class="content">';
			$comment = $this->clickableURL($comment);
			echo nl2br($comment);
			echo '</div>';
		echo '</div>';
	}
	
	function clickableURL($comment){
		$comment = preg_replace('!((https?://[a-z0-9._/-?&%=#]{50}) ?([^< ])+)(<?)!i', '<a target="_blank" href="$1">$2[...]</a>$4', $comment);
		$comment = preg_replace('!([^">])(https?://[a-z0-9._/-?&%=#] ?[^<) ]+)(<?)!i', '$1<a target="_blank" href="$2">$2</a>$3',$comment);
		return $comment;
	}
	
	function displayCommentForm($sentence_id){
		echo $this->Form->create('SentenceComment', array("action"=>"save"));
		$this->Tooltip->display(__('Even though you can comment on the whole group of sentences, keep in mind that your comment will be <strong>linked only to the main sentence</strong> (at the top).',true));
		__('Add a comment : ');
		echo $this->Form->input('sentence_id', array("type"=>"hidden", "value"=>$sentence_id));
		echo $this->Form->input('text', array("label"=> ""));
		echo $this->Form->end('Submit');
	}
	
}