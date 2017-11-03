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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "formAddClient")) {
  $insertSQL = sprintf("INSERT INTO clients (title, firstname, surname, designation, `level`, influenceRating, areaOfInterface, typeOfService, email, organisation, buildingNum, street, area, city, `state`, staff_id) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
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
                       GetSQLValueString($_POST['staff_id'], "int"));

  mysql_select_db($database_amfclients, $amfclients);
  $Result1 = mysql_query($insertSQL, $amfclients) or die(mysql_error());

  $insertGoTo = "clients.php?msg=Client Added!";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}
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
	//hide type of service by default
	$("#typeOfServiceArea").hide();
	
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
              <th>Add Client</th>
            </tr>
            <tr>
              <td><table width="100%" border="0" cellspacing="0" cellpadding="5">
                <tr>
                  <td width="19%" valign="top">Title</td>
                  <td width="44%" valign="top"><select type="text" id="title" name="title">
                    <option selected="selected">None</option>
                    <br>
                    <option value="Mr">Mr</option>
                    <option value="Ms">Ms</option>
                    <option value="Mrs">Mrs</option>
                    <option value="Dr">Dr</option>
                    <option value="Chief">Chief</option>
                    <option value="Prof">Prof</option>
                    <option value="Pastor">Pastor</option>
                    <option value="Alhaji">Alhaji</option>
                    <option value="Mallam">Mallam</option>
                    <option value="Hajia">Hajia</option>
                    <option value="Engr">Engr</option>
                    <option value="Arc">Arc</option>
                  </select></td>
                  <td width="37%" valign="top">&nbsp;</td>
                </tr>
                <tr>
                  <td valign="top">First Name</td>
                  <td valign="top"><span id="sprytextfield1">
                    <label for="firstname"></label>
                    <input name="firstname" type="text" class="w308" id="firstname" />
                  <span class="textfieldRequiredMsg">A value is required.</span></span></td>
                  <td valign="top">&nbsp;</td>
                </tr>
                <tr>
                  <td valign="top">Surname</td>
                  <td valign="top"><span id="sprytextfield2">
                    <label for="surname"></label>
                    <input name="surname" type="text" class="w308" id="surname" />
                  <span class="textfieldRequiredMsg">A value is required.</span></span></td>
                  <td valign="top">&nbsp;</td>
                </tr>
                <tr>
                  <td valign="top">Designation</td>
                  <td valign="top"><span id="sprytextfield3">
                    <label for="designation"></label>
                    <input name="designation" type="text" class="w308" id="designation" />
                  <span class="textfieldRequiredMsg">A value is required.</span></span></td>
                  <td valign="top" class="smallText">If you are filling in for a residential facility, simply state if the person is an EXCO Member or an Occupant.</td>
                </tr>
                <tr>
                  <td valign="top">Organisation/Residence</td>
                  <td valign="top"><span id="sprytextfield5">
                    <label for="organisation"></label>
                    <input name="organisation" type="text" class="w308" id="organisation" />
                    <span class="textfieldRequiredMsg">A value is required.</span></span></td>
                  <td valign="top">&nbsp;</td>
                </tr>


                <tr>
                  <td valign="top">Level</td>
                  <td valign="top"><span id="spryselect1">
                    <label for="level"></label>
                    <select name="level" class="w308" id="level">
                      <option value="">Select...</option>
                      <option value="Most Senior Executive">Most Senior Executive (e.g.CEO/MD/Chairman)</option>
                      <option value="Director/Executive">Director/Executive</option>
                      <option value="Senior Manager">Senior Manager</option>
                      <option value="Manager">Manager</option>
                      <option value="Officer">Officer</option>
                    </select>
                  <span class="selectRequiredMsg">Please select an item.</span></span></td>
                  <td valign="top" class="smallText">At what level is this individual in the client's organisation?</td>
                </tr>
                <tr>
                  <td valign="top">Influence Rating</td>
                  <td valign="top"><label for="influenceRating"></label>
                    <select name="influenceRating" id="influenceRating">
                      <option value="1">1</option>
                      <option value="2">2</option>
                      <option value="3">3</option>
                      <option value="4">4</option>
                      <option value="5">5</option>
                  </select></td>
                  <td valign="top" class="smallText">Please rate the level of influence this person has on our current/future work with his organisation      (1LO - 5HI)</td>
                </tr>
                <tr>
                  <td valign="top">Area of Interface</td>
                  <td valign="top"><span id="spryselect2">
                    <label for="areaOfInterface"></label>
                    <select name="areaOfInterface" class="w308" id="areaOfInterface">
                      <option value="">Select...</option>
                      <option value="Contract Award/Renewal">Contract Award/Renewal</option>
                      <option value="Payment Processing">Payment Processing</option>
                      <option value="Contract Manager">Contract Manager</option>
                      <option value="Work Supervision">Work Supervision</option>
                      <option value="Service Provider">Service Provider</option>
                      <option value="EXCO Member (Residential)">EXCO Member (Residential)</option>
                      <option value="End User">End User</option>
                      <option value="Government Officials">Government Officials</option>
                      <option value="Media(Journalist)">Media(Journalist)</option>
                      <option value="Doctors">Doctors</option>
                    </select>
                  <span class="selectRequiredMsg">Please select an item.</span></span></td>
                  <td valign="top" class="smallText">State area of interface with AMF</td>
                </tr>
                <tr id="typeOfServiceArea">
                  <td valign="top">&nbsp;</td>
                  <td valign="top"><label for="typeOfService"></label>
                  <input name="typeOfService" type="text" class="w308" id="typeOfService" placeholder="Type of Service" /></td>
                  <td valign="top" class="smallText">Please indicate what type of service this person/organization provides to AMF</td>
                </tr>
                <tr>
                  <td valign="top">Email Address</td>
                  <td valign="top"><span id="sprytextfield4">
                  <label for="email"></label>
                  <input name="email" type="text" class="w308" id="email" />
                  <span class="textfieldRequiredMsg">A value is required.</span><span class="textfieldInvalidFormatMsg">Invalid format.</span></span></td>
                  <td valign="top"><span class="smallText">Enter the client rep's email address</span></td>
                </tr>
                <tr>
                  <th valign="top"><strong>Delivery Address:</strong></th>
                  <th colspan="2" valign="top" class="smallText">(Please note that this is the address to which the client's appreciation will be delivered)</th>
                </tr>
                <tr>
                  <td valign="top">Building Number</td>
                  <td valign="top"><input type="text" name="buildingNum" id="buildingNum" /></td>
                  <td valign="top">&nbsp;</td>
                </tr>
                <tr>
                  <td valign="top">Street Name</td>
                  <td valign="top"><span id="sprytextfield6">
                    <label for="street"></label>
                    <input name="street" type="text" class="w308" id="street" />
                  <span class="textfieldRequiredMsg">A value is required.</span></span></td>
                  <td valign="top">&nbsp;</td>
                </tr>
                <tr>
                  <td valign="top">Area</td>
                  <td valign="top"><span id="sprytextfield7">
                    <label for="area"></label>
                    <input name="area" type="text" class="w308" id="area" />
                  <span class="textfieldRequiredMsg">A value is required.</span></span></td>
                  <td valign="top">&nbsp;</td>
                </tr>
                <tr>
                  <td valign="top">City</td>
                  <td valign="top"><span id="sprytextfield8">
                    <label for="city"></label>
                    <input name="city" type="text" class="w308" id="city" />
                  <span class="textfieldRequiredMsg">A value is required.</span></span></td>
                  <td valign="top">&nbsp;</td>
                </tr>
                <tr>
                  <td valign="top">State</td>
                  <td valign="top"><select name="state" id="state">
                    <option value="">Select...</option>
                    <?php include("oldsite/lists/_inc_NigeriaStates.php"); ?>
                  </select></td>
                  <td valign="top">&nbsp;</td>
                </tr>
                <tr>
                  <td valign="top">&nbsp;</td>
                  <td valign="top">&nbsp;</td>
                  <td valign="top">&nbsp;</td>
                </tr>
                <tr>
                  <td valign="top"><input name="submit" type="submit" class="button" id="submit" value="Submit" /></td>
                  <td valign="top"><input name="staff_id" type="hidden" id="staff_id" value="<?php echo $Staff['id'] ?>" /></td>
                  <td valign="top">&nbsp;</td>
                </tr>
              </table></td>
            </tr>
          </table>
        </div>
        <input type="hidden" name="MM_insert" value="formAddClient" />
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
mysql_free_result($clients);
?>
