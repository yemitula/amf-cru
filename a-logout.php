<?php
// *** Logout the current user.
$logoutGoTo = "a-login.php";
if (!isset($_SESSION)) {
  session_start();
}
$_SESSION['CRUAdmin'] = NULL;
unset($_SESSION['CRUAdmin']);
if ($logoutGoTo != "") {header("Location: $logoutGoTo");
exit;
}
?>