<?php

namespace view;

class CookieStorage {
	
	public function save($name, $value, $timeNow ) {	
		setcookie( $name, $value, $timeNow + 3600*24*30 ,'/');  
	}

	public function load($name) {

		if (isset($_COOKIE[$name])) 
			$ret = $_COOKIE[$name];  
		else
			$ret = "";

	//	setcookie($name, "", time() -1,'/');

		return $ret;
	}
	
	public function delete($name) {
		setcookie( $name, "", time() - 3600*24*30,'/');
	}
}