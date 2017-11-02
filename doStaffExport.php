<?php require_once('Connections/amfclients.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

mysql_select_db($database_amfclients, $amfclients);
$query_staff = "SELECT name, location, phone, email FROM staff";
$staff = mysql_query($query_staff, $amfclients) or die(mysql_error());
$row_staff = mysql_fetch_assoc($staff);
$totalRows_staff = mysql_num_rows($staff);

$e = array();
$i= 1;

do {
// loop for each staff

	$e[$i] = $row_staff;
	
	$i++; //increment counter
	
} while ($row_staff = mysql_fetch_assoc($staff));
	
// do output to CSV
	$fp = fopen('php://output', 'w');
	if ($fp) {
		header('Content-Type: text/csv');
		header('Content-Disposition: attachment; filename="AMF Staff (from Clients Update Application) - '.date("d-m-Y").'.csv"');
		header('Pragma: no-cache');
		header('Expires: 0');
		fputcsv($fp, array_keys($e[1]));
		for($i=1;$i<= count($e);$i++)
			fputcsv($fp, $e[$i]);		
		die;
	}

?>