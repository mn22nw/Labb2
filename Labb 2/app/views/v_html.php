<?php

class HTMLView {

		public function echoHTML($title, $head, $body) {
			if ($body === NULL) {
				throw new \Exception("HTMLView::echoHTML does not allow body to be null");
			}
	
			echo "
				<!DOCTYPE html>
				<html>
				<head>
				<meta charset=\"utf-8\">
					<title> $title </title>
					$head
				</head>
				<body>
					<div id='page'>
					$body
					</div>
				</body>
				</html>";
		}
}