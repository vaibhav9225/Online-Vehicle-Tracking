<?php

class CookieManager{
	public function getValue($cookieName){
		return $this->checkValue($cookieName) ? $_COOKIE[$cookieName] : '';
	}
	public function setValue($cookieName,$cookieValue,$timer){
		setcookie($cookieName,$cookieValue,time() + $timer);
	}
	public function checkValue($cookieName){
		return isset($_COOKIE[$cookieName]) ? true : false;
	}
	public function appendvalue($cookieName,$cookieValue){
		!$this->checkValue($cookieName) ? $_COOKIE[$cookieName] = '' : null;
		$_COOKIE[$cookieName] .= $cookieValue;
	}
	public function unsetCookie($cookieName){
		setcookie($cookieName,'',0);
	}
	public function unsetCookies(){
		foreach($_COOKIE as $key => $var){
			if($key != 'loginId' and $key != 'adminId'){
				$this->unsetCookie($key);
			}
		}
	}
	public function destroy(){
		foreach($_COOKIE as $key => $var){
			$this->unsetCookie($key);
		}
	}
}

$cookie = new CookieManager();

?>