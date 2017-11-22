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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "formEmployee")) {
  session_start();
	
  mysql_select_db($database_amfclients, $amfclients);
	//check if email is already used
	$email = $_POST['email'];
	$checkSQL = "SELECT * FROM staff WHERE email = '$email'";
	$checkRS = mysql_query($checkSQL, $amfclients) or die(mysql_error());
	$emailTaken = mysql_num_rows($checkRS);
	
	if($emailTaken) {
		header("Location: index.php?regerror=Sorry, this email has already been used for Staff Registration. If you have already registered, please LOGIN");
		exit;
	}
	
  $insertSQL = sprintf("INSERT INTO staff (name, location, phone, email, password) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['name'], "text"),
                       GetSQLValueString($_POST['location'], "text"),
                       GetSQLValueString($_POST['phone'], "text"),
                       GetSQLValueString($_POST['email'], "text"),
                       GetSQLValueString($_POST['password'], "text"));

  $Result1 = mysql_query($insertSQL, $amfclients) or die(mysql_error());
  
  //log user in
  $_SESSION['StaffEmail'] = $email;

  $insertGoTo = "clients.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}
?>
<?php
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['loginEmail'])) {
  $loginUsername=$_POST['loginEmail'];
  $password=$_POST['loginPass'];
  $MM_fldUserAuthorization = "";
  $MM_redirectLoginSuccess = "clients.php";
  $MM_redirectLoginFailed = "index.php?logerror=Login Failed! Username or Password Incorrect.";
  $MM_redirecttoReferrer = false;
  mysql_select_db($database_amfclients, $amfclients);
  
  $LoginRS__query=sprintf("SELECT email, password FROM staff WHERE email=%s AND password=%s",
    GetSQLValueString($loginUsername, "text"), GetSQLValueString($password, "text")); 
   
  $LoginRS = mysql_query($LoginRS__query, $amfclients) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);
  if ($loginFoundUser) {
     $loginStrGroup = "";
    
	if (PHP_VERSION >= 5.1) {session_regenerate_id(true);} else {session_regenerate_id();}
    //declare two session variables and assign them
    $_SESSION['StaffEmail'] = $loginUsername;

    if (isset($_SESSION['PrevUrl']) && false) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
	  unset($_SESSION['PrevUrl']);
    }
    header("Location: " . $MM_redirectLoginSuccess );
  }
  else {
    header("Location: ". $MM_redirectLoginFailed );
  }
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
<script src="SpryAssets/SpryValidationConfirm.js" type="text/javascript"></script>
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
<link href="SpryAssets/SpryValidationConfirm.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div id="container">
  <div id="header"><img src="images/AMF-Logo-Glow.gif" width="400" height="126" alt="Welcome to Online Test" />
  <img src="images/iso.gif" width="400" height="156">
  </div>
  <div id="body">
    <p class="bigHeading"><strong>Client Reps Update</strong></p>
    <p>&nbsp;</p>
    <p class="subHead"><strong>Dear  Employee,</strong></p>
    <p class="subHead"> We are updating our Clients Rep Database for the end of the year Clients Appreciation. </p>
    <p class="subHead">&nbsp;</p>
    <div class="leftColumn">
      <?php if (isset($_GET['logerror'])) { ?>
      <div class="msgBoxRed"><?php echo $_GET['logerror'] ?></div>
      <?php } ?>
      <div class="formTableWrap">
        <table width="100%" border="0" cellspacing="0" cellpadding="10">
          <tr>
            <th>Coming Back? Login Now</th>
          </tr>
          <tr>
            <td style="border-bottom:#006600 1px solid"><form id="formLogin" name="formLogin" method="POST" action="<?php echo $loginFormAction; ?>">
              <table width="100%" border="0" cellspacing="0" cellpadding="5">
                <tr>
                  <td width="21%">Email</td>
                  <td width="79%"><label for="loginEmail"></label>
                    <input name="loginEmail" type="text" class="w260" id="loginEmail" /></td>
                </tr>
                <tr>
                  <td>Password</td>
                  <td><label for="loginPass"></label>
                    <input name="loginPass" type="password" class="w260" id="loginPass" /></td>
                </tr>
                <tr>
                  <td>&nbsp;</td>
                  <td><input name="login" type="submit" class="button" id="login" value="Login" /></td>
                </tr>
                <tr>
                  <td>&nbsp;</td>
                  <td><strong><a href="forgotPass.php">Forgot Password? Recover it now!</a></strong></td>
                </tr>
              </table>
            </form></td>
          </tr>
        </table>
      </div>
    </div>
    <div class="rightColumn">
    <?php if (isset($_GET['regerror'])) { ?>
      <div class="msgBoxRed"><?php echo $_GET['regerror'] ?></div>
      <?php } ?>
      <div class="formTableWrap">
        <table width="100%" border="0" cellspacing="0" cellpadding="10">
          <tr>
            <th>New Here? Register Now</th>
          </tr>
          <tr>
            <td style="border-bottom:#006600 1px solid"><form id="formEmployee" name="formEmployee" method="POST" action="<?php echo $editFormAction; ?>">
              <table width="100%" border="0" cellspacing="0" cellpadding="5">
                <tr>
                  <td width="24%"><p>Name</p></td>
                  <td width="76%"><span id="sprytextfield1">
                  <label for="name"></label>
                  <input name="name" type="text" class="w260" id="name" placeholder="Your full name" />
                  <span class="textfieldRequiredMsg">A value is required.</span></span></td>
                </tr>
                <tr>
                  <td><p>Site/Location</p></td>
                  <td><span id="sprytextfield2">
                  <label for="location"></label>
                  <input name="location" type="text" class="w260" id="location" placeholder="Site or Location where you work" />
                  <span class="textfieldRequiredMsg">A value is required.</span></span></td>
                </tr>
                <tr>
                  <td><p>Phone
                      <label for="phone"></label>
                    </p></td>
                  <td><span id="sprytextfield3">
                    <input name="phone" type="text" class="w260" id="phone" placeholder="AMF VPN Number" />
                    <span class="textfieldRequiredMsg">A value is required.</span></span></td>
                </tr>
                <tr>
                  <td>Email</td>
                  <td><span id="sprytextfield4">
                    <label for="email"></label>
                    <input name="email" type="text" class="w260" id="email" placeholder="AMF Email Address" />
                    <span class="textfieldRequiredMsg">A value is required.</span></span></td>
                </tr>
                <tr>
                  <td>Password</td>
                  <td><span id="sprytextfield5">
                    <label for="password"></label>
                    <input name="password" type="password" class="w260" id="password" placeholder="********" />
                    <span class="textfieldRequiredMsg">A value is required.</span></span></td>
                </tr>
                <tr>
                  <td>Retype</td>
                  <td><span id="spryconfirm1">
                    <label for="password2"></label>
                    <input name="password2" type="password" class="w260" id="password2"  placeholder="********" />
                    <span class="confirmRequiredMsg">A value is required.</span><span class="confirmInvalidMsg">The values don't match.</span></span></td>
                </tr>
                <tr>
                  <td>&nbsp;</td>
                  <td><input name="submit" type="submit" class="button" id="submit" value="Register" /></td>
                </tr>
              </table>
              <input type="hidden" name="MM_insert" value="formEmployee" />
            </form></td>
          </tr>
        </table>
      </div>
    </div>
    <div class="clear"></div>

  </div>
</div>
<script type="text/javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1");
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2");
var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3");
var sprytextfield4 = new Spry.Widget.ValidationTextField("sprytextfield4");
var sprytextfield5 = new Spry.Widget.ValidationTextField("sprytextfield5");
var spryconfirm1 = new Spry.Widget.ValidationConfirm("spryconfirm1", "password");
</script>
</body>
</html>