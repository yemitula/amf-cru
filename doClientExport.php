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
$query_clients = "SELECT title, firstname, surname, designation, level, influenceRating, areaOfInterface, typeOfService, c.email AS ClientEmail, organisation, buildingNum, street, area, state, s.name AS 'Added By', s.location AS 'Site/Location', s.phone AS 'Phone' FROM clients c LEFT JOIN staff s ON (c.staff_id = s.id)";
$clients = mysql_query($query_clients, $amfclients) or die(mysql_error());
$row_clients = mysql_fetch_assoc($clients);
$totalRows_clients = mysql_num_rows($clients);

$e = array();
$i= 1;

do {
// loop for each staff

	$e[$i] = $row_clients;
	
	$i++; //increment counter
	
} while ($row_clients = mysql_fetch_assoc($clients));
	
// do output to CSV
	$fp = fopen('php://output', 'w');
	if ($fp) {
		header('Content-Type: text/csv');
		header('Content-Disposition: attachment; filename="AMF Client Reps - '.date("d-m-Y").'.csv"');
		header('Pragma: no-cache');
		header('Expires: 0');
		fputcsv($fp, array_keys($e[1]));
		for($i=1;$i<= count($e);$i++)
			fputcsv($fp, $e[$i]);		
		die;
	}
?>