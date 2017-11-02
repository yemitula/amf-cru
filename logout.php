<?php
// *** Logout the current user.
$logoutGoTo = "index.php";
if (!isset($_SESSION)) {
  session_start();
}
$_SESSION['StaffEmail'] = NULL;
unset($_SESSION['StaffEmail']);
if ($logoutGoTo != "") {header("Location: $logoutGoTo");
exit;
}
?>