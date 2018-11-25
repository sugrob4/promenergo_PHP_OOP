<?php
class DbException extends Exception {
	
	protected $message;
	
	public function __construct($text) {
		$this->message = $text;
		
		$file = $this->getFile();
		$line = $this->getLine();
		
		$_SESSION['error']['file'] = $file;
		$_SESSION['error']['line'] = $line;
		
		header("Location:".SITE_URL.'error/mes/'.rawurlencode($text));
	}
}
?>