<?php

class Initialiser{
	private $type;
	public function __construct(){
		$this->setFiles();
	}
	public function setFiles(){
		global $page, $database, $session, $cookie, $mail, $form, $options;
		require_once('includes/config.php');
		require_once('includes/page.php');
		require_once('includes/database.php');
		require_once('includes/session.php');
		require_once('includes/cookie.php');
		require_once('includes/mail.php');
		require_once('includes/form.php');
	}
}

$init = new Initialiser();

?>