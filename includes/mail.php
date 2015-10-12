<?php

class MailManager{
	private $emailTo;
	private $emailFrom;
	private $emailSubject;
	private $emailBody;
	private $html;
	public function to($to){
		$this->emailTo = $to;
	}
	public function from($from){
		$this->emailFrom = $from;
	}
	public function subject($subject){
		global $page;
		$this->emailSubject = $subject;
	}
	public function body($body){
		global $page;
		$this->emailBody = $body;
	}
	public function send(){
		global $page;
		if(isset($this->emailTo,$this->emailSubject,$this->emailBody,$this->emailFrom)){
			mail($this->emailTo,$this->emailSubject,$this->emailBody,$this->emailFrom);
		}
	}
	public function isHtml(){
		$this->html = true;
		$temp = $this->emailFrom;
		$this->emailFrom  = "MIME-Version: 1.0 ".PHP_EOL;
		$this->emailFrom .= "Content-type: text/html; charset: utf8 ".PHP_EOL;
		$this->emailFrom .= "From: $temp ".PHP_EOL;
	}
}

$mail = new MailManager();

?>