<?php
namespace controllers; 
 
 require_once('app/models/m_login.php');
 require_once('app/views/v_login.php');

class LoginController { 
	private $view;
	private $model;
	
	public function __construct() {
		$this->model = new \models\LoginModel();
		$this->view = new \views\LoginView($this->model);
	}

	// ** returns String HTML **//
	public function doLogin() { 
		$cookieUsername = $this->view->getUsernameCookie();
		$cookiePassword = $this->view->getPasswordCookie();
		
		$this->model->startSession();

	
		if($this->model->checkUserAgent($this->view->getUserAgent()) === false){ 
			$this->model->destroySession();
			return $this->view->showLoginForm("");	
		} 
		
		if($this->handleManipulatedCookies()) { //<--true if cookie has been manipulated
			$this->view->deleteCookies();
			return $this->view->showLoginForm("Felaktig information i cookie");
		}
		
		
		if($cookieUsername != "" && ($this->model->checkLoginStatus() == false) ){//<--true if cookies are not empty and usersession doesn't exist
		
			if ($this->model->validateLogin($cookieUsername,$cookiePassword)) { //<--true if un & pw matches in .txt file //different frommm value name
				
				if($this->view->LogoutAttempt()){ //<--true is user pressed logout button
					$this->model->destroySession();
					$this->view->deleteCookies();
					return $this->view->showLoginForm("Du har nu loggat ut");	  
				}	
			
			return $this->view->showLoggedIn($cookieUsername,"Inloggningen lyckades via cookies!");
			
			}
			else {
				$this->model->destroySession();
				$this->view->deleteCookies();
				return $this->view->showLoginForm("Felaktig information i cookie");
			}
		 }
		 
		$username = $this->view->getUsernameInput(); 
		
		if($this->model->checkLoginStatus()){ //<--true if user is logged in

			if($this->view->LogoutAttempt()){ //<--true is user pressed logout button
				$this->model->destroySession();
				return $this->view->showLoginForm("Du har nu loggat ut"); //MIGHT BE DRY HERE- But had no time to change this
			}
			else{
				return $this->view->showLoggedIn($this->model->getSessionValue(), "");}	 
		}
		
		//Handles data
		if ($this->view->validateInput()) { //<-if validation in view is true
					
			// gets username and password from view and sends them to model for validation  
			if ($this->model->validateLogin($username, md5($this->view->getPasswordInput()))) { //<--true if un & pw matches
					$this->model->setSession($username);	
					if($this->view->isChecked()){//<--true if checkbox is clicked
						$this->view->rememberUser();  //cookies are added
						return $this->view->showLoggedIn($username, "Inloggning lyckades och vi kommer ihåg dig nästa gång"); 
					}				
					
					return $this->view->showLoggedIn($username,"Inloggningen lyckades!");
				}
			else {
				//parameter sets errorMessage in view
				return $this->view->showLoginForm("Felaktigt användarnamn och/eller lösenord");
			}	
		}
		
		return $this->view->showLoginForm("");	
	}

	public function handleManipulatedCookies() {
		$timeNow = $this->view->getTime();
			
		//save in file only if cookies are not already created
		if($this->view->getUsernameCookie() == ""){//<--true if cookies are empty 
			$this->model->addCookieTime($timeNow + 3600*24*30);  //adds expiredate to cookieTime.txt 
		}
		
		$creationDateCookie = $this->model->getCookieTime();
		
		if (time() > $creationDateCookie ){ //<-- if statement is true if cookie has been modified
			return true;
		}
		return false;
	}
}

		