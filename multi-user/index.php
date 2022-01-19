<?php
session_start();
include_once("./includes/load-yourls.php");
include_once("mufunctions.php");
include_once("muhtmlfunctions.php");

if(YOURLS_PRIVATE === false) {
	die(); // NO DIRECT CALLS IF PUBLIC!
}

$act = $_GET['act'];
if($act == "logout") {
	$_SESSION['user'] = "";
	unset($_SESSION);
	unset($_SESSION["user"]);
	$error_msg = "Signed off.";
}

if(!isLogged()) {
	yourls_html_head( 'login' );
	// Login form
	switch($act) {
		case "login":
			$username = yourls_escape($_POST['username']);
			$password = $_POST['password'];
			if ( empty($username) || empty($password ) ) {
				$error_msg = "Neither username or password can be blank.";
				require_once 'form.php';
			} else {
				if(isValidUser($username, $password)) {
					$token = getUserTokenByEmail($username);
					$id = getUserIdByToken($token);
					$_SESSION['user'] = array("id" => $id, "user" => $username, "token" => $token);
					yourls_redirect("index.php");
				} else {
					$error_msg = "Wrong username or password.";
					require_once 'form.php';
				}
			}

			break;
		default:
			require_once 'form.php';

	}

	yourls_html_footer();
	die();
} else {
	include("admin.php");
}
