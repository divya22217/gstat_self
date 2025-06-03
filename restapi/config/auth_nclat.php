<?php

class AuthNclat{
	private $auth_token = 'M5SdiLzMKyMpSu.wjtz#1-vrrq<?8qB3GWgdpTk0';
	public function token_auth(){

		$token = $this->auth_token;
		$headers = apache_request_headers();
		$header_token = (isset($headers['token']))?$headers['token']:'';
		if (!$header_token) {
			header('HTTP/1.1 401 Authorization Required');
			header('WWW-Authenticate: Basic realm="Access denied"');
			echo "Token not found";
			exit;
		}else if ($header_token != $token) {
			header('HTTP/1.1 401 Authorization Required');
			header('WWW-Authenticate: Basic realm="Access denied"');
			echo "Token Mismath";
			exit;
		}
		
	}
	
	public function basic_auth(){
		$AUTH_USER = 'admin';
		$AUTH_PASS = 'admin';
		header('Cache-Control: no-cache, must-revalidate, max-age=0');
		$has_supplied_credentials = !(empty($_SERVER['PHP_AUTH_USER']) && empty($_SERVER['PHP_AUTH_PW']));
		$is_not_authenticated = (
			!$has_supplied_credentials ||
			$_SERVER['PHP_AUTH_USER'] != $AUTH_USER ||
			$_SERVER['PHP_AUTH_PW']   != $AUTH_PASS
		);
		if ($is_not_authenticated) {
			header('HTTP/1.1 401 Authorization Required');
			header('WWW-Authenticate: Basic realm="Access denied"');
			exit;
		}
	}
}