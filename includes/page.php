<?php

class PageManager{
	public $redirectedFrom;
	public $isRedirected = false;
	public function __construct(){
		isset($_SERVER['HTTP_REFERER']) ? $redirectedFrom = $_SERVER['HTTP_REFERER'] : $redirectedFrom = '';
		//$this->error();
	}
	public function redirect($location=null){
		$this->isRedirected = true;
		$location == null ? $location = $redirectedFrom : null;
		header("Location: $location");
		//die('The page redirection failed.');
	}
	public function error($level=0){
		error_reporting($level);
	}
	public function encrypt($string){
		return md5($string);
	}
	public function timeline($string, $time=null){
		if($time != null)
			return date($string, strtotime($time));
		else
			return date($string);
	}
	public function strip($string){
		return html_entity_decode(stripslashes($string));
	}
	public function capitalize($string){
		return ucwords($string);
	}
	public function lower($string){
		return strtolower($string);
	}
	public function random($min, $max){
		return rand($min, $max);
	}
	public function length($string){
		return strlen($string);
	}
	public function check($string){
		if(isset($string) and !empty($string)){
			return true;
		}
		else{
			return false;
		}
	}
	public function upper($string){
		return strtoupper($string);
	}
	public function cut($string, $start, $end){
		return substr($string, $start, $end);
	}
	public function shuffle($string){
		return str_shuffle($string);
	}
	public function split($object, $string){
		return explode($object, $string);
	}
	public function join($object, $array){
		return implode($object, $array);
	}
	public function replace($replace, $replacement, $string){
		return str_replace($replace, $replacement, $string);
	}
	public function end($string){
		require_once('includes/database.php');
		require_once('includes/session.php');
		require_once('includes/page.php');
		global $database,$session,$page;
		if($session->checkValue('adminId'))
			require_once('admin_header.php');
		else if($session->checkValue('dealerId'))
			require_once('dealer_header.php');
		else if($session->checkValue('salesId'))
			require_once('sales_header.php');
		else
			require_once('content_header.php');
		echo "<div id='wrapper'><div class='end_box'><br><ul><li>$string</li></ul><br></div></div>";
		die();
	}
	public function stop(){
		die();
	}
	public function kill($legend, $string){
		require_once('includes/database.php');
		require_once('includes/session.php');
		require_once('includes/page.php');
		global $database,$session,$page;
		if($session->checkValue('adminId'))
			require_once('admin_header.php');
		else if($session->checkValue('dealerId'))
			require_once('dealer_header.php');
		else if($session->checkValue('salesId'))
			require_once('sales_header.php');
		else
			require_once('content_header.php');
		echo "<div id='wrapper'><div class='end_box'><br><legend>$legend</legend><ul><li>$string</li></ul><br></div></div>";
		die();
	}
	public function magnify(){
		ini_set('post_max_size','5000M');
		ini_set('upload_max_filesize','5000M');
		ini_set('memory_limit','5000M');
		ini_set('session.gc_maxlifetime',5000);
		ini_set('max_input_time',5000);
		ini_set('max_execution_time',5000);
	}
}

$page = new PageManager();

?>