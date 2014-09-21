<?php 
namespace models;

class LoginModel {
	private $sessionName = "LoginModel::LoggedIn";
	
	function checkLoginStatus() {  //@return = true if user is already logged in
		
		if(isset($_SESSION[$this->sessionName])== false){
			return false;
		}
		else {//if session exists
			return true;
		}
	}
	function getSessionValue() {
		if(isset($_SESSION[$this->sessionName]))
		return $_SESSION[$this->sessionName];
	}
	
	//creates new session for user
	function setSession($username){
		$_SESSION[$this->sessionName] = $username; 
	}
	
	/** - Checks if username and password exists 
		!!Change this function to a more secure one (store in database instead of .txt file) **/
		
	function validateLogin($username, $password){
		$password_file = "textfiles/pass.txt";
	 	$match = 0;
		
			if(!$fh = fopen($password_file, "r")) {die("<P>Could Not Open Password File");}

			
			while(!feof($fh)) { //as long as file has not reached its end
				$line = fgets($fh, 4096);
				$user_pass = explode(":", $line);
				
				if($user_pass[0] === $username) {
					if(rtrim($user_pass[1]) === $password) {
					$match = 1;
					break;
					}
				}
			}
			if($match) {
				return true;
				} 
			else {
				return false;
				}	
			fclose($fh);
	}

	public function checkUserAgent($browser){ //@return = false if the user is in another browser and has manipulated the session
		//Sets session for browser
		 if(isset($_SESSION['httpUserAgent']) === false){
		  $_SESSION["httpUserAgent"] = $browser;
			 return true;
		 }
		 
		 if($_SESSION['httpUserAgent'] !== $browser){
		  return false;
		 }
	}
	
	function startSession(){
		session_start();
	}
	
	function destroySession(){
		session_destroy(); 
	}
	
	public function getCookieTime() {

		$line = '';
		$file = 'textfiles/cookieTime.txt';
		if($f = fopen($file, 'r')){
		  $line = fgets($f); // read until first newline
		  fclose($f);
		}
		else{ $line = 0;}
		
		return $line;
	}
	
	public function addCookieTime($time) {

		$fp = @fopen("textfiles/cookieTime.txt", 'w');
		fwrite($fp, $time . "\n");
	}
}