<?php require_once('Connections/amfclients.php'); ?>
<?php 
session_start();
if(isset($_SESSION['StaffEmail'])) {
	//logged in
	//retrieve full staff details and store in an array
	$StaffEmail = $_SESSION['StaffEmail'];
	mysql_select_db($database_amfclients, $amfclients);
	$query_staff = "SELECT * FROM staff WHERE email = '$StaffEmail'";
	$staff = mysql_query($query_staff, $amfclients) or die(mysql_error());
	$row_staff = mysql_fetch_assoc($staff);	
	$Staff = $row_staff;
	
} else {
	//not logged in
	//redirect to login/register page
	header("Location: index.php?logerror=Please login to continue");
	exit;
}
?>