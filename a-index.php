<?php require('_inc_checkAdminSession.php'); ?>
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
$query_clients = "SELECT * FROM clients";
if(isset($_GET['staff']) && $_GET['staff']!='') {
	$staff_id = $_GET['staff'];
	$query_clients .= " WHERE staff_id = '$staff_id'";
}
$clients = mysql_query($query_clients, $amfclients) or die(mysql_error());
$row_clients = mysql_fetch_assoc($clients);
$totalRows_clients = mysql_num_rows($clients);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>AMF Client Representatives Update</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/jquery-1.6.1.min.js"></script>
<script type="text/javascript" src="js/validateAction.js"></script>
</head>

<body>
<div id="container">
  <div id="header"><img src="images/header.jpg" width="850" height="126" alt="Welcome to Online Test" /></div>
  <div id="navbar">
    <div class="left"></div>
    <div class="right">Logged in as<strong> admin </strong>| <a href="a-logout.php"><strong>Logout</strong></a> </div>
    <div class="clear"></div>
  </div>
  <div id="body">
    <p class="bigHeading"><strong>Client Reps Update</strong></p>
    <p>&nbsp;</p>
    <div class="leftColumn">
      <div class="formTableWrap">
        <table width="100%" border="0" cellspacing="0" cellpadding="10">
          <tr>
            <th>Client List</th>
          </tr>
          <tr>
            <td style="border-bottom:#006600 1px solid"><p>Do a CSV export of all the Clients Added to the Database.&nbsp;This export also contains basic information on who added each Client.</p>
              <p>&nbsp;</p>
              <p><a href="doClientExport.php">
                <input name="exportClients" type="submit" class="button" id="exportClients" value="Export Client List Now" />
            </a></p></td>
          </tr>
        </table>
      </div>
    </div>
    <div class="clear"></div>
<p class="subHead">&nbsp;</p>
    <p>&nbsp;</p>
    <div class="clear"></div>

  </div>
</div>
</body>
</html>
<?php
mysql_free_result($clients);
?>
