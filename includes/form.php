<?php

class FormValidator{
	public $sessionError = 'error';
	public $sessionSuccess = 'success';
	private $previousPassword = null;
	public function __construct(){
		global $session;
		!$session->checkValue($this->sessionError) ? $session->setValue($this->sessionError,'') : null;
		!$session->checkValue($this->sessionSuccess) ? $session->setValue($this->sessionError,'') : null;
	}
	public function findString($string,$sessionName = 'field',$bool=true){
		global $session;
		global $page;
		if($bool){
			if(!isset($string) or $string == '' or ctype_space($string)){
				$session->appendValue($this->sessionError,'<li>'.$page->capitalize($sessionName).' cannot be empty.</li>');
				return false;
			}
			else{
				$session->setValue($sessionName,$string);
				return true;
			}
		}
		else{
			$session->setValue($sessionName,$string);
			return true;
		}	
	}
	public function setMessage($string){
		global $session;
		$session->setValue($sessionSuccess,$string);
	}
	public function checkEmail($email,$sessionName='email',$bool=true,$tableName=null,$colName=null){
		global $session;
		global $database;
		if(!$this->findString($email,$sessionName,$bool)){
			return false;
		}
		if($tableName != null and $colName != null){
			if($database->checkValue($tableName,$colName,$email)){
				$session->appendValue($this->sessionError,'<li>Email alerady exists.</li>');
				return false;
			}
		}
		if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
			$session->appendValue($this->sessionError,'<li>Invalid email address.</li>');
			return false;
		}
		else{
			return true;
		}
	}
	public function checkUrl($url,$sessionName='url',$bool=true){
		global $session;
		if(!$this->findString($url,$sessionName,$bool)){
			return false;
		}
		if(!filter_var($url, FILTER_VALIDATE_URL)){
			$session->appendValue($this->sessionError,'<li>Invalid URL.</li>');
			return false;
		}
		else{
			return true;
		}
	}
	public function checkUsername($username,$sessionName='username',$bool=true,$tableName=null,$colName=null){
		global $session;
		global $database;
		if(!$this->findString($username,$sessionName,$bool)){
			return false;
		}
		if($tableName != null and $colName != null){
			if($database->checkValue($tableName,$colName,$username)){
				$session->appendValue($this->sessionError,'<li>Username alerady exists.</li>');
				return false;
			}
		}
		if($this->checkSpecials($username)){
			$session->appendValue($this->sessionError,'<li>Username cannot have special characters.</li>');
			return false;		
		}
		else if(!$this->checkSpaces($username)){
			$session->appendValue($this->sessionError,'<li>Username cannot have spaces in between.</li>');
			return false;		
		}
		else{
			return true;
		}
	}
	public function checkPassword($password,$sessionName='password',$bool=true){
		global $session;
		global $page;
		if(!$this->findString($password,$sessionName,$bool)){
			return false;
		}
		if(strlen($password) < 7){
			$session->appendValue($this->sessionError,'<li>'.$page->capitalize($sessionName).' must be minimum of 8 characters.</li>');
			return false;
		}
		if($this->previousPassword == null){
			$this->previousPassword = $password;
		}
		else{
			if($this->previousPassword != $password){
				$session->appendValue($this->sessionError,'<li>Confirm Password must be same as password.</li>');
				return false;				
			}
		}
		if(strlen($password) > 8){
			return true;
		}
	}
	public function checkDate($date,$sessionName='date',$bool=true){
		global $session;
		if(!$this->findString($date,$sessionName,$bool)){
			return false;
		}
		$array = explode('-',$date);
		if(count($array) != 3){
			unset($array);
			$array = explode('-',$date);
			if(count($array) != 3){
				$session->appendValue($this->sessionError,'<li>Invalid Date Format</li>');
				return false;
			}
		}
		if(checkdate($array[0],$array[1],$array[2])
			or checkdate($array[0],$array[2],$array[1])
			or checkdate($array[1],$array[0],$array[2])
			or checkdate($array[1],$array[2],$array[0])
			or checkdate($array[2],$array[0],$array[1])
			or checkdate($array[2],$array[1],$array[0])){
			return true;
		}
		else{
			$session->appendValue($this->sessionError,'<li>Invalid Date Format</li>');
			return false;
		}
	}
	public function checkString($string,$sessionName = 'field',$bool=true){
		global $session;
		global $page;
		if(!$this->findString($string,$sessionName,$bool)){
			return false;
		}
		if($this->checkSpecials($string)){
			$session->appendValue($this->sessionError,'<li>'.$page->capitalize($sessionName).' cannot have special characters.</li>');
			return false;
		}
		else{
			return true;
		}
	}
	public function checkZipcode($zipcode,$sessionName = 'Zipcode',$bool=true){
		global $session;
		if(!$this->findString($zipcode,$sessionName,$bool)){
			return false;
		}
		if(strlen($zipcode) < 3){
			$session->appendValue($this->sessionError,'<li>Zipcode must be minimum of 3 characters.</li>');
			return false;
		}
		else if(!$this->checkInt($zipcode,$sessionName)){
			return false;
		}
		else{
			return true;
		}
	}
	public function checkInt($int,$sessionName = 'field',$bool=true){
		global $session;
		global $page;
		if(!$this->findString($int,$sessionName,$bool)){
			return false;
		}
		if(!ctype_digit($int) and $bool == true){
			$session->appendValue($this->sessionError,'<li>'.$page->capitalize($sessionName).' must be numeric.</li>');
			return false;
		}
		else{
			return true;
		}
	}
	public function checkFloat($float,$sessionName = 'field',$bool=true){
		global $session;
		global $page;
		if(!$this->findString($float,$sessionName,$bool)){
			return false;
		}
		if(!is_numeric($float) and $bool == true){
			$session->appendValue($this->sessionError,'<li>'.$page->capitalize($sessionName).' must be numeric.</li>');
			return false;
		}
		else{
			return true;
		}
	}
	public function checkText($string,$sessionName = 'text',$bool=true){
		global $session;
		if(!$this->findString($string,$sessionName,$bool)){
			return false;
		}
	}
	public function checkSpecials($string){
		$flag = false;
		$stringArray = str_split($string);
		$array = array('!','@','#','$','%','^','&','*','(',')','-','=','_','+','`','~','{','[','}',']',';',':','\'','"','|','\\');
		foreach($array as $item){
			if($flag == true){
				break;
			}
			foreach($stringArray as $char){
				if($item == $char){
					$flag = true;
					break;
				}
			}
		}
		return $flag;
	}
	public function checkSpaces($string){
		return preg_match('/\s/',$string) ? false : true;
	}
	public function checkPost($varName){
		return isset($_POST[$varName]) ? true : false;
	}
	public function checkFile($varName, $sessionName = 'filename', $bool=true){
		global $session;
		global $page;
		if(isset($_FILES[$varName]) and !empty($_FILES[$varName])){
			return true;
		}
		else{
			$bool ? $session->appendValue('error','<li>'.$page->capitalize($sessionName).' cannot be empty.</li>') : null;
			return false;
		}
	}
	public function getFile($varName,$type='name'){
		if($this->checkFile($varName)){
			if($type == 'temp'){
				return $_FILES[$varName]['tmp_name'];
			}
			else if($type == 'name'){
				return $_FILES[$varName]['name'];
			}
		}
		else{
			return "";
		}
	}
	public function moveFile($inputFileName, $newPath){
		move_uploaded_file($inputFileName, $newPath);
	}
	public function checkGet($varName){
		return isset($_GET[$varName]) ? true : false;
	}
	public function getPost($varName){
		return $this->checkPost($varName) ? $_POST[$varName] : '';
	}
	public function getGet($varName){
		return $this->checkGet($varName) ? $_GET[$varName] : '';
	}
	public function setPost($varName,$varValue){
		$_POST[$varName] = $varValue;
	}
	public function setGet($varName,$varValue){
		$_GET[$varName] = $varValue;
	}
}

$form = new FormValidator();

?>