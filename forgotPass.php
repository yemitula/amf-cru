<?php require_once('Connections/amfclients.php'); ?>
<?php require_once('swiftMailer/lib/swift_required.php'); ?>
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

$colname_staff = "-1";
if (isset($_POST['email'])) {
	$colname_staff = $_POST['email'];
	mysql_select_db($database_amfclients, $amfclients);
	$query_staff = sprintf("SELECT * FROM staff WHERE email = %s", GetSQLValueString($colname_staff, "text"));
	$staff = mysql_query($query_staff, $amfclients) or die(mysql_error());
	$row_staff = mysql_fetch_assoc($staff);
	$totalRows_staff = mysql_num_rows($staff);
	
	if($totalRows_staff > 0) {
		//staff account was found
		
		//send password to the email address
		
		//Create the Transport
		//$transport = Swift_MailTransport::newInstance();
		$transport = Swift_SmtpTransport::newInstance('localhost', 25)
		  ->setUsername('alerts@amfacilities.com')
		  ->setPassword('amfa2012')
		  ;
		//Create the Mailer using your created Transport
		$mailer = Swift_Mailer::newInstance($transport);
	
		extract($row_staff);
		
		$subject2 = "Password Recovery on AMF Clients Rep Application";
			  
		$body2 = "
		<p>Dear $name,</p>
		<p>You requested for a Password Recovery on the AMF Clients Rep Application.</p>
		<p>Your Password is <strong>$password</strong></p>
		<p>Please make note of it for future references</p>
		<p>Regards</p>
		<p><strong>AMF Webmaster</strong></p>
		";
		//die($body);
			
			//Create a message
			$message2 = Swift_Message::newInstance($subject2)
				->setFrom(array('webmaster@amfacilities.com' => "AMFacilities Webmaster"))
				->setTo(array($email => $name))
				->setBody($body2, 'text/html')
				;
			//send the message
			$result2 = $mailer->send($message2);
			
			header("location: forgotPass.php?msg=Password Recovery Successful! Your password has been sent to $email");
			exit;
					
	} else {
		//staff account was NOT found
		header("location: forgotPass.php?err=Sorry, Password Recovery Failed! There is no staff account for $colname_staff in the database.");
		exit;
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
</head>

<body>
<div id="container">
  <div id="header"><img src="images/header.jpg" width="850" height="126" alt="Welcome to Online Test" /></div>
  <div id="body">
    <p class="bigHeading"><strong>Client Reps Update</strong>    </p>
    <p class="subHead">&nbsp;</p>
    <div class="leftColumn">
      <?php if (isset($_GET['err'])) { ?>
      <div class="msgBoxRed"><?php echo $_GET['err'] ?></div>
      <?php } ?>
      <?php if (isset($_GET['msg'])) { ?>
      <div class="msgBoxGreen"><?php echo $_GET['msg'] ?></div>
      <?php } ?>
      <div class="formTableWrap">
        <table width="100%" border="0" cellspacing="0" cellpadding="10">
          <tr>
            <th>Recover Password</th>
          </tr>
          <tr>
            <td style="border-bottom:#006600 1px solid"><form id="formLogin" name="formLogin" method="POST" action="<?php echo $loginFormAction; ?>">
              <table width="100%" border="0" cellspacing="0" cellpadding="5">
                <tr>
                  <td width="21%">Email</td>
                  <td width="79%"><label for="email"></label>
                    <span id="sprytextfield1">
                    <input name="email" type="text" class="w260" id="email" />
                    <span class="textfieldRequiredMsg">A value is required.</span><span class="textfieldInvalidFormatMsg">Invalid format.</span></span></td>
                </tr>
                <tr>
                  <td>&nbsp;</td>
                  <td><input name="login" type="submit" class="button" id="login" value="Recover" /></td>
                </tr>
                <tr>
                  <td>&nbsp;</td>
                  <td><a href="index.php"><strong>Back to Login</strong></a></td>
                </tr>
              </table>
            </form></td>
          </tr>
        </table>
      </div>
    </div>
    <div class="clear"></div>

  </div>
</div>
<script type="text/javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1", "email", {validateOn:["blur"]});
</script>
</body>
</html>
<?php
mysql_free_result($staff);
?>
