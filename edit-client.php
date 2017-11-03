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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "formAddClient")) {
  $updateSQL = sprintf("UPDATE clients SET title=%s, firstname=%s, surname=%s, designation=%s, `level`=%s, influenceRating=%s, typeOfService=%s, areaOfInterface=%s, email=%s, organisation=%s, buildingNum=%s, street=%s, area=%s, city=%s, `state`=%s WHERE id=%s",
                       GetSQLValueString($_POST['title'], "text"),
                       GetSQLValueString($_POST['firstname'], "text"),
                       GetSQLValueString($_POST['surname'], "text"),
                       GetSQLValueString($_POST['designation'], "text"),
                       GetSQLValueString($_POST['level'], "text"),
                       GetSQLValueString($_POST['influenceRating'], "int"),
                       GetSQLValueString($_POST['areaOfInterface'], "text"),
                       GetSQLValueString($_POST['typeOfService'], "text"),
                       GetSQLValueString($_POST['email'], "text"),
                       GetSQLValueString($_POST['organisation'], "text"),
                       GetSQLValueString($_POST['buildingNum'], "text"),
                       GetSQLValueString($_POST['street'], "text"),
                       GetSQLValueString($_POST['area'], "text"),
                       GetSQLValueString($_POST['city'], "text"),
                       GetSQLValueString($_POST['state'], "text"),
                       GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_amfclients, $amfclients);
  $Result1 = mysql_query($updateSQL, $amfclients) or die(mysql_error());

  $updateGoTo = "clients.php?msg=Client Updated!";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_client = "-1";
if (isset($_GET['id'])) {
  $colname_client = $_GET['id'];
}
mysql_select_db($database_amfclients, $amfclients);
$query_client = sprintf("SELECT * FROM clients WHERE id = %s", GetSQLValueString($colname_client, "int"));
$client = mysql_query($query_client, $amfclients) or die(mysql_error());
$row_client = mysql_fetch_assoc($client);
$totalRows_client = mysql_num_rows($client);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>AMF Client Representatives Update</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/jquery-1.6.1.min.js"></script>
<script src="http://www.modernizr.com/downloads/modernizr-latest.js"></script>
<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<script src="SpryAssets/SpryValidationSelect.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function(e) {
	//hide type of service by default if it's blank
	if($("#typeOfService").val() == '') $("#typeOfServiceArea").hide();
	
    $("#areaOfInterface").change(function(e) {
        if($(this).val() == 'Service Provider') {
			$("#typeOfServiceArea").show();
		} else {
			$("#typeOfServiceArea").hide();
			$("#typeOfService").val('');
		}
    });
	
	$("#formAddClient").submit(function(e) {
        if($("#areaOfInterface").val() == 'Service Provider' && $("#typeOfService").val() == '') {
			alert("Please specify this Service Provider's Type of Service");
			return false;
		}
    });
});
</script>
<script>
/**
 * HTML5 Placeholder Text, jQuery Fallback with Modernizr
 *
 * @url        http://uniquemethod.com/
 * @author    Unique Method
 */
$(function()
{
    // check placeholder browser support
    if (!Modernizr.input.placeholder)
    {
    
        // set placeholder values
        $(this).find('[placeholder]').each(function()
        {
            if ($(this).val() == '') // if field is empty
            {
                $(this).val( $(this).attr('placeholder') );
            }
        });
        
        // focus and blur of placeholders
        $('[placeholder]').focus(function()
        {
            if ($(this).val() == $(this).attr('placeholder'))
            {
                $(this).val('');
                $(this).removeClass('placeholder');
            }
        }).blur(function()
        {
            if ($(this).val() == '' || $(this).val() == $(this).attr('placeholder'))
            {
                $(this).val($(this).attr('placeholder'));
                $(this).addClass('placeholder');
            }
        });
        
        // remove placeholders on submit
        $('[placeholder]').closest('form').submit(function()
        {
            $(this).find('[placeholder]').each(function()
            {
                if ($(this).val() == $(this).attr('placeholder'))
                {
                    $(this).val('');
                }
            })
        });
        
    }
	
	//preselect state
	$('#state').val($('#state2').val());
});
</script>
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div id="container">
  <div id="header"><img src="images/header.jpg" width="850" height="126" alt="Welcome to Online Test" /></div>
  <div id="navbar">
    <div class="left"><a href="clients.php"><strong>Clients</strong></a> | <a href="add-client.php"><strong>Add Client</strong></a></div>
    <div class="right">Logged in as <strong><?php echo $Staff['name'] ?></strong> | <a href="logout.php"><strong>Logout</strong></a>
    </div>
    <div class="clear"></div>
  </div>
  <div id="body">
    <p class="bigHeading"><strong>Client Reps Update</strong></p>
      <p>&nbsp;</p>
      <form id="formAddClient" name="formAddClient" method="POST" action="<?php echo $editFormAction; ?>">
        <div class="formTableWrap">
          <table width="100%" border="0" cellspacing="0" cellpadding="10">
            <tr>
              <th>Edit Client</th>
            </tr>
            <tr>
              <td><table width="100%" border="0" cellspacing="0" cellpadding="5">
                <tr>
                  <td width="19%" valign="top">Title</td>
                  <td width="44%" valign="top"><select type="text" id="title" name="title">
                    <option selected="selected" value="" <?php if (!(strcmp("", $row_client['title']))) {echo "selected=\"selected\"";} ?>>None</option>
                    <option value="Mr" <?php if (!(strcmp("Mr", $row_client['title']))) {echo "selected=\"selected\"";} ?>>Mr</option>
                    <option value="Ms" <?php if (!(strcmp("Ms", $row_client['title']))) {echo "selected=\"selected\"";} ?>>Ms</option>
                    <option value="Mrs" <?php if (!(strcmp("Mrs", $row_client['title']))) {echo "selected=\"selected\"";} ?>>Mrs</option>
<option value="Dr" <?php if (!(strcmp("Dr", $row_client['title']))) {echo "selected=\"selected\"";} ?>>Dr</option>
                    <option value="Chief" <?php if (!(strcmp("Chief", $row_client['title']))) {echo "selected=\"selected\"";} ?>>Chief</option>
                    <option value="Prof" <?php if (!(strcmp("Prof", $row_client['title']))) {echo "selected=\"selected\"";} ?>>Prof</option>
                    <option value="Pastor" <?php if (!(strcmp("Pastor", $row_client['title']))) {echo "selected=\"selected\"";} ?>>Pastor</option>
<option value="Alhaji" <?php if (!(strcmp("Alhaji", $row_client['title']))) {echo "selected=\"selected\"";} ?>>Alhaji</option>
                    <option value="Mallam" <?php if (!(strcmp("Mallam", $row_client['title']))) {echo "selected=\"selected\"";} ?>>Mallam</option>
                    <option value="Hajia" <?php if (!(strcmp("Hajia", $row_client['title']))) {echo "selected=\"selected\"";} ?>>Hajia</option>
                    <option value="Engr" <?php if (!(strcmp("Engr", $row_client['title']))) {echo "selected=\"selected\"";} ?>>Engr</option>
                    <option value="Arc" <?php if (!(strcmp("Arc", $row_client['title']))) {echo "selected=\"selected\"";} ?>>Arc</option>
                  </select></td>
                  <td width="37%" valign="top">&nbsp;</td>
                </tr>
                <tr>
                  <td valign="top">First Name</td>
                  <td valign="top"><span id="sprytextfield1">
                    <label for="firstname"></label>
                    <input name="firstname" type="text" class="w308" id="firstname" value="<?php echo $row_client['firstname']; ?>" />
                  <span class="textfieldRequiredMsg">A value is required.</span></span></td>
                  <td valign="top">&nbsp;</td>
                </tr>
                <tr>
                  <td valign="top">Surname</td>
                  <td valign="top"><span id="sprytextfield2">
                    <label for="surname"></label>
                    <input name="surname" type="text" class="w308" id="surname" value="<?php echo $row_client['surname']; ?>" />
                  <span class="textfieldRequiredMsg">A value is required.</span></span></td>
                  <td valign="top">&nbsp;</td>
                </tr>
                <tr>
                  <td valign="top">Designation</td>
                  <td valign="top"><span id="sprytextfield3">
                    <label for="designation"></label>
                    <input name="designation" type="text" class="w308" id="designation" value="<?php echo $row_client['designation']; ?>" />
                  <span class="textfieldRequiredMsg">A value is required.</span></span></td>
                  <td valign="top" class="smallText">If you are filling in for a residential facility, simply state if the person is an EXCO Member or an Occupant.</td>
                </tr>
                
                <tr>
                  <td valign="top">Organisation/Residence</td>
                  <td valign="top"><span id="sprytextfield5">
                    <label for="organisation"></label>
                    <input name="organisation" type="text" class="w308" id="organisation" value="<?php echo $row_client['designation']; ?>" />
                    <span class="textfieldRequiredMsg">A value is required.</span></span></td>
                  <td valign="top">&nbsp;</td>
                </tr>
                
                <tr>
                  <td valign="top">Level</td>
                  <td valign="top"><span id="spryselect1">
                    <label for="level"></label>
                    <select name="level" class="w308" id="level">
                      <option value="" <?php if (!(strcmp("", $row_client['level']))) {echo "selected=\"selected\"";} ?>>Select...</option>
                      <option value="Most Senior Executive" <?php if (!(strcmp("Most Senior Executive", $row_client['level']))) {echo "selected=\"selected\"";} ?>>Most Senior Executive (e.g.CEO/MD/Chairman)</option>
                      <option value="Director/Executive" <?php if (!(strcmp("Director/Executive", $row_client['level']))) {echo "selected=\"selected\"";} ?>>Director/Executive</option>
                      <option value="Senior Manager" <?php if (!(strcmp("Senior Manager", $row_client['level']))) {echo "selected=\"selected\"";} ?>>Senior Manager</option>
                      <option value="Manager" <?php if (!(strcmp("Manager", $row_client['level']))) {echo "selected=\"selected\"";} ?>>Manager</option>
                      <option value="Officer" <?php if (!(strcmp("Officer", $row_client['level']))) {echo "selected=\"selected\"";} ?>>Officer</option>
                    </select>
                  <span class="selectRequiredMsg">Please select an item.</span></span></td>
                  <td valign="top" class="smallText">At what level is this individual in the client's organisation?</td>
                </tr>
                <tr>
                  <td valign="top">Influence Rating</td>
                  <td valign="top"><label for="influenceRating"></label>
                    <select name="influenceRating" id="influenceRating">
                      <option value="1" <?php if (!(strcmp(1, $row_client['influenceRating']))) {echo "selected=\"selected\"";} ?>>1</option>
                      <option value="2" <?php if (!(strcmp(2, $row_client['influenceRating']))) {echo "selected=\"selected\"";} ?>>2</option>
                      <option value="3" <?php if (!(strcmp(3, $row_client['influenceRating']))) {echo "selected=\"selected\"";} ?>>3</option>
                      <option value="4" <?php if (!(strcmp(4, $row_client['influenceRating']))) {echo "selected=\"selected\"";} ?>>4</option>
                      <option value="5" <?php if (!(strcmp(5, $row_client['influenceRating']))) {echo "selected=\"selected\"";} ?>>5</option>
                  </select></td>
                  <td valign="top" class="smallText">Please rate the level of influence this person has on our current/future work with his organisation      (1LO - 5HI)</td>
                </tr>
                <tr>
                  <td valign="top">Area of Interface</td>
                  <td valign="top"><span id="spryselect2">
                    <label for="areaOfInterface"></label>
                    <select name="areaOfInterface" class="w308" id="areaOfInterface">
                      <option value="" <?php if (!(strcmp("", $row_client['areaOfInterface']))) {echo "selected=\"selected\"";} ?>>Select...</option>
                      <option value="Contract Award/Renewal" <?php if (!(strcmp("Contract Award/Renewal", $row_client['areaOfInterface']))) {echo "selected=\"selected\"";} ?>>Contract Award/Renewal</option>
                      <option value="Payment Processing" <?php if (!(strcmp("Payment Processing", $row_client['areaOfInterface']))) {echo "selected=\"selected\"";} ?>>Payment Processing</option>
                      <option value="Contract Manager" <?php if (!(strcmp("Contract Manager", $row_client['areaOfInterface']))) {echo "selected=\"selected\"";} ?>>Contract Manager</option>
                      <option value="Work Supervision" <?php if (!(strcmp("Work Supervision", $row_client['areaOfInterface']))) {echo "selected=\"selected\"";} ?>>Work Supervision</option>
                      <option value="Service Provider" <?php if (!(strcmp("Service Provider", $row_client['areaOfInterface']))) {echo "selected=\"selected\"";} ?>>Service Provider</option>
                      <option value="EXCO Member (Residential)" <?php if (!(strcmp("EXCO Member (Residential)", $row_client['areaOfInterface']))) {echo "selected=\"selected\"";} ?>>EXCO Member (Residential)</option>
                      <option value="End User" <?php if (!(strcmp("End User", $row_client['areaOfInterface']))) {echo "selected=\"selected\"";} ?>>End User</option>
                      <option value="Government Officials"<?php if(!(strcmp("Government Officials", $row_client['areaOfInterface']))){echo "selected=\"selected\"";}?>>Government Officials</option>
                      <option value="Media(Journalist)"<?php if(!(strcmp("Media(Journalist)", $row_client['areaOfInterface']))){echo "selected=\"selected\"";}?>>Media(Journalist)</option>
                      <option value="Doctors"<?php if(!(strcmp("Doctors", $row_client['areaOfInterface']))){echo "selected=\"selected\"";}?>>Doctors</option>
                    </select>
                  <span class="selectRequiredMsg">Please select an item.</span></span></td>
                  <td valign="top" class="smallText">State area of interface with AMF</td>
                </tr>
                <tr id="typeOfServiceArea">
                  <td valign="top">&nbsp;</td>
                  <td valign="top"><label for="typeOfService"></label>
                    <input name="typeOfService" type="text" class="w308" id="typeOfService" placeholder="Type of Service" value="<?php echo $row_client['typeOfService']; ?>" /></td>
                  <td valign="top" class="smallText">Please indicate what type of service this person/organization provides to AMF</td>
                </tr>
                <tr>
                  <td valign="top">Email Address</td>
                  <td valign="top"><span id="sprytextfield4">
                  <label for="email"></label>
                  <input name="email" type="text" class="w308" id="email" value="<?php echo $row_client['email']; ?>" />
                  <span class="textfieldRequiredMsg">A value is required.</span><span class="textfieldInvalidFormatMsg">Invalid format.</span></span></td>
                  <td valign="top" class="smallText">Enter the client rep's email address</td>
                </tr>
                <tr>
                  <th valign="top"><strong>Delivery Address:</strong></th>
                  <th colspan="2" valign="top" class="smallText">(Please note that this is the address to which the client's appreciation will be delivered)</th>
                </tr>
                <tr>
                  <td valign="top">Building Number</td>
                  <td valign="top"><input name="buildingNum" type="text" id="buildingNum" value="<?php echo $row_client['buildingNum']; ?>" /></td>
                  <td valign="top">&nbsp;</td>
                </tr>
                <tr>
                  <td valign="top">Street Name</td>
                  <td valign="top"><span id="sprytextfield6">
                    <label for="street"></label>
                    <input name="street" type="text" class="w308" id="street" value="<?php echo $row_client['street']; ?>" />
                  <span class="textfieldRequiredMsg">A value is required.</span></span></td>
                  <td valign="top">&nbsp;</td>
                </tr>
                <tr>
                  <td valign="top">Area</td>
                  <td valign="top"><span id="sprytextfield7">
                    <label for="area"></label>
                    <input name="area" type="text" class="w308" id="area" value="<?php echo $row_client['area']; ?>" />
                  <span class="textfieldRequiredMsg">A value is required.</span></span></td>
                  <td valign="top">&nbsp;</td>
                </tr>
                <tr>
                  <td valign="top">City</td>
                  <td valign="top"><span id="sprytextfield8">
                    <label for="city"></label>
                    <input name="city" type="text" class="w308" id="city" value="<?php echo $row_client['city']; ?>" />
                  <span class="textfieldRequiredMsg">A value is required.</span></span></td>
                  <td valign="top">&nbsp;</td>
                </tr>
                <tr>
                  <td valign="top">State</td>
                  <td valign="top"><select name="state" id="state">
                    <option value="">Select...</option>
                    <?php include("lists/_inc_NigeriaStates.php"); ?>
                  </select>
                  <input name="state2" type="hidden" id="state2" value="<?php echo $row_client['state']; ?>" /></td>
                  <td valign="top">&nbsp;</td>
                </tr>
                <tr>
                  <td valign="top">&nbsp;</td>
                  <td valign="top">&nbsp;</td>
                  <td valign="top">&nbsp;</td>
                </tr>
                <tr>
                  <td valign="top"><a href="clients.php">
                    <button type="button">&laquo; Cancel</button>
                  </a></td>
                  <td valign="top"><input name="id" type="hidden" id="id" value="<?php echo $row_client['id']; ?>" /></td>
                  <td align="right" valign="top"><input name="submit" type="submit" class="button" id="submit" value="Update" /></td>
                </tr>
              </table></td>
            </tr>
          </table>
        </div>
        <input type="hidden" name="MM_update" value="formAddClient" />
      </form>
<p>&nbsp;</p>
    <div class="clear"></div>

  </div>
</div>
<script type="text/javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1");
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2");
var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3");
var spryselect1 = new Spry.Widget.ValidationSelect("spryselect1");
var spryselect2 = new Spry.Widget.ValidationSelect("spryselect2");
var sprytextfield4 = new Spry.Widget.ValidationTextField("sprytextfield4", "email", {validateOn:["blur"]});
var sprytextfield6 = new Spry.Widget.ValidationTextField("sprytextfield6");
var sprytextfield7 = new Spry.Widget.ValidationTextField("sprytextfield7");
var sprytextfield8 = new Spry.Widget.ValidationTextField("sprytextfield8");
var sprytextfield5 = new Spry.Widget.ValidationTextField("sprytextfield5");
</script>
</body>
</html>
<?php
mysql_free_result($client);
?>
