<?php require('_inc_checkSession.php'); ?>
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

$staff_id = $Staff['id'];
mysql_select_db($database_amfclients, $amfclients);
$query_clients = "SELECT * FROM clients WHERE staff_id = '$staff_id'";
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
 <div id="header"><img src="images/AMF-Logo-Glow.gif" width="400" height="126" alt="Welcome to Online Test" />
  <img src="images/iso.gif" width="400" height="156">
  </div>
  <div id="navbar">
    <div class="left"><a href="clients.php"><strong>Clients</strong></a> | <a href="add-client.php"><strong>Add Client</strong></a></div>
    <div class="right">Logged in as <strong><?php echo $Staff['name'] ?></strong> | <a href="logout.php"><strong>Logout</strong></a>
    </div>
    <div class="clear"></div>
  </div>
  <div id="body">
    <p class="bigHeading"><strong>Client Reps Update</strong></p>
    <p>&nbsp;</p>
    <p class="subHead"><strong>Your Clients</strong>    </p>
    <p class="subHead">&nbsp;</p>
    <p class="subHead">
      <a href="add-client.php">
      <button type="button" class="button">+ Add Client</button>
    </a> </p>
    <p>&nbsp;</p>
    <?php if ($totalRows_clients == 0) { // Show if recordset empty ?>
    <div class="msgBoxRed">Your Clients List is empty!</div>
    <?php } // Show if recordset empty ?>
    <?php if (isset($_GET['msg'])) { ?>
      <div class="msgBoxGreen"><?php echo $_GET['msg'] ?></div>
      <?php } ?>
    <?php if ($totalRows_clients > 0) { // Show if recordset not empty ?>
  <div class="formTableWrap">
    <table width="100%" border="0" cellspacing="0" cellpadding="5">
      <tr>
        <th width="22%">Client&nbsp;Name</th>
        <th width="20%">Designation</th>
        <th width="18%">Level</th>
        <th width="10%" align="center">Influence</th>
        <th width="23%">Organisation</th>
        <th width="7%">&nbsp;</th>
      </tr>
      <?php do { ?>
        <tr>
          <td><?php echo $row_clients['title']; ?> <?php echo $row_clients['firstname']; ?> <?php echo $row_clients['surname']; ?></td>
          <td><?php echo $row_clients['designation']; ?></td>
          <td><?php echo $row_clients['level']; ?></td>
          <td align="center"><?php echo $row_clients['influenceRating']; ?></td>
          <td><?php echo $row_clients['organisation']; ?></td>
          <td><a href="edit-client.php?id=<?php echo $row_clients['id']; ?>" title="Edit Client" ><img src="images/glyphicons_030_pencil.png" alt="" width="16" border="0" /></a>&nbsp;
          <a href="delete-client.php?id=<?php echo $row_clients['id']; ?>" title="Delete Client" class="ValidateAction" data-confirm-msg="Are you sure you want to delete this Client?"><img src="images/glyphicons_016_bin.png" alt="Delete" width="16" border="0" /></a></td>
        </tr>
        <?php } while ($row_clients = mysql_fetch_assoc($clients)); ?>
    </table>
  </div>
  <?php } // Show if recordset not empty ?>
<p>&nbsp;</p>
    <div class="clear"></div>

  </div>
</div>
</body>
</html>
<?php
mysql_free_result($clients);
?>
