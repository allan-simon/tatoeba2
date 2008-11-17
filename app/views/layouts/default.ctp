<?php
/* SVN FILE: $Id: default.ctp 7118 2008-06-04 20:49:29Z gwoo $ */
/**
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) :  Rapid Development Framework <http://www.cakephp.org/>
 * Copyright 2005-2008, Cake Software Foundation, Inc.
 *								1785 E. Sahara Avenue, Suite 490-204
 *								Las Vegas, Nevada 89104
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright		Copyright 2005-2008, Cake Software Foundation, Inc.
 * @link				http://www.cakefoundation.org/projects/info/cakephp CakePHP(tm) Project
 * @package			cake
 * @subpackage		cake.cake.libs.view.templates.layouts
 * @since			CakePHP(tm) v 0.10.0.1076
 * @version			$Revision: 7118 $
 * @modifiedby		$LastChangedBy: gwoo $
 * @lastmodified	$Date: 2008-06-04 13:49:29 -0700 (Wed, 04 Jun 2008) $
 * @license			http://www.opensource.org/licenses/mit-license.php The MIT License
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php echo $html->charset(); ?>
	<title>
		<?php echo $title_for_layout; ?>
	</title>
	<?php
		echo $html->meta('icon');

		echo $html->css('tatoeba.generic');

		echo $scripts_for_layout;
	?>
</head>
<body>
	<div id="container">
		<!-- ---------------- HEADER ---------------- -->
		<div id="header">
			<?php 
			if($session->read('Auth.User.id')){
				__('You are logged in as ');
				echo $session->read('Auth.User.username'); 
				echo ' (group ' . $session->read('Auth.User.group_id') . ')';
				echo '<br/>';
				echo $html->link(
					__('Log out',true),
					array(
						"controller" => "users",
						"action" => "logout"
					));
			}else{
				echo $html->link(
					__('Log in',true),
					array(
						"controller" => "users",
						"action" => "login"
					));
			}
			?>
		</div>
		
		<!-- ---------------- MENU ---------------- -->
		<div id="menu">
			<?php echo $this->element('menu'); ?>
		</div>
		
		<!-- ---------------- CONTENT---------------- -->
		<div id="content">
			<?php
				if ($session->check('Message.flash')):
						$session->flash();
				endif;
			?>

			<?php echo $content_for_layout; ?>

		</div>
		
		<!-- ---------------- FOOTER---------------- -->
		<div id="footer">
			<?php echo $html->link(
							$html->image('cake.power.gif', array('alt'=> __("CakePHP: the rapid development php framework", true), 'border'=>"0")),
							'http://www.cakephp.org/',
							array('target'=>'_new'), null, false
						);
			?>
		</div>
	</div>
	<?php echo $cakeDebug ?>
</body>
</html>
