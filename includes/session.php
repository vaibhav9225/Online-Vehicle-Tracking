<?php

class SessionManager{
	public function __construct(){
		$this->start();
	}
	public function getValue($sessionName){
		return $this->checkValue($sessionName) ? $_SESSION[$sessionName] : '';
	}
	public function setValue($sessionName,$sessionValue){
		$_SESSION[$sessionName] = $sessionValue;
	}
	public function checkValue($varName){
		return isset($_SESSION[$varName]) ? true : false;
	}
	public function appendvalue($sessionName,$sessionValue){
		!$this->checkValue($sessionName) ? $_SESSION[$sessionName] = '' : null;
		$_SESSION[$sessionName] .= $sessionValue;
	}
	public function unsetSession($sessionName){
		global $page;
		if(!$page->isRedirected){
			unset($_SESSION[$sessionName]);
		}
	}
	public function unsetSessions(){
		global $page;
		if(!$page->isRedirected){
			foreach($_SESSION as $key => $var){
				if($key != 'success' and $key != 'adminId' and $key != 'dealerId' and $key != 'salesId'){
					$this->unsetSession($key);
				}
			}
		}
	}
	public function start(){
		session_start();
	}
	public function destroy(){
		session_destroy();
	}
}

$session = new SessionManager();

?>