<?php 
session_start();
if(isset($_SESSION['CRUAdmin'])) {
	//logged in
} else {
	//not logged in
	//redirect to login/register page
	header("Location: a-login.php?logerror=Please login to continue");
	exit;
}
?>