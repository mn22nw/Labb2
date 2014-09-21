<?php
namespace views;

require_once("src/CookieStorage.php");

class LoginView {
	private $model;
	private $messages;
	private $loggedInStatus="Ej inloggad";
	private $errorMessage;
	private $unValue = "";
	private $pwValue = "";
	private $username = "LoginView::UserName";
	private $password = "LoginView::Password";
	private $checkBoxTicked = false;
	private $timeNow;
	
	public function __construct(\models\LoginModel $model) {  
		$this->model = $model;
		$this->messages = new \view\CookieStorage();
		$this->timeNow = time();
		if(isset($_POST["LoginView::Checked"])) {
			$this->checkBoxTicked = true;
		}
	}

	public function LoginAttempt() {
		if (isset($_POST["Login_Btn"]))
			return true;
		return false;
	}
	
	public function LogoutAttempt() {
		if (isset($_POST["Logout_Btn"]))
			return true;
		return false;
	}

	public function getClientIdentifier(){
		return $_SERVER["REMOTE_ADDR"];  // returns ipadress
	}
	// returns HTML that shows the login-form + ev. errormessages
	public function showLoginForm($errorMessage) {
		$this->errorMessage = $errorMessage;
		$ret = "";
	
		// validate input if user tries to log in
		$this->validateInput();
			$ret .= "
				<h2>Logga in</h2>
				<div class='LoginBox'>
					<h2> $this->loggedInStatus </h2>
					<p>Skriv in användarnamn och lösenord</p>
					<p class='errorMessage'>$this->errorMessage</p>
					<form action='?login' method='post'>
						<p>Username</p>
						<input type='text' name='LoginView::UserName' id='username' class ='textbox' autocomplete='off' value='$this->unValue'/>
						<p>Password</p>
						<input type='password' name='LoginView::Password' class='textbox' value='$this->pwValue'/>
						<label for='AutologinID' >Håll mig inloggad  :</label>
						<input type='checkbox' name='LoginView::Checked' class='checkbox' id='AutologinID' />
						<input type='submit' name='Login_Btn' class='LoginBtn' value='Logga in' />
					</form>
				</div>";
		return $ret;
	}

	public function showLoggedIn($user, $message){
		$ret = "";
		$this->loggedInStatus = $user. " är inloggad";  
			$ret .= "<h2> $this->loggedInStatus </h2>
					 $message
					 <form action='?login' method='post'>
					 <input type='submit' name='Logout_Btn' class='LogoutBtn' value='Logga ut' />
					 </form>";
		return $ret;
	}
	
	public function getUsernameInput(){
		if($this->LoginAttempt()) {
			return $_POST[$this->username];
		}
	}
	
	public function getPasswordInput(){
		if($this->LoginAttempt()) {
			return $_POST[$this->password];
		}
	}

	public function validateInput(){
		if($this->LoginAttempt()) {
		$un = $_POST[$this->username];
		$pw = $_POST[$this->password];
		
		// Checks that username-input is not empty
		if (empty($un)) {
				$this->errorMessage .= "Användarnamn saknas!";
			if(isset($pw)){
				$this->pwValue = $pw;
			}
			return false;
		}
		
		// Checks that password-input is not empty
		else if (empty($pw)) {
				$this->errorMessage .= "Lösenord saknas!";
				$this->unValue = $un;
				return false;
			}
		return true;
		}
	}
	
	public function getTime(){
		return $this->timeNow;
	}
	
	public function rememberUser() {//store user in cookie if checkbox is checked
		if(isset($_POST["LoginView::Checked"])){
			$this->messages->save($this->username, $_POST[$this->username],$this->timeNow );
			$this->messages->save($this->password,md5($_POST[$this->password]), $this->timeNow );
		}  //kan de va den sätter nya cookies som är tomma?
	}
	
	public function isChecked(){
		return $this->checkBoxTicked;
	}
	
	public function deleteCookies() {
		$this->messages->delete($this->username);
		$this->messages->delete($this->password);
	}
	
	public function getUsernameCookie(){
		return $this->messages->load($this->username); 
	}
	public function getPasswordCookie(){
		return $this->messages->load($this->password);
	}
	
	public function getUserAgent() {
	  return $_SERVER["HTTP_USER_AGENT"];
	 }
}	