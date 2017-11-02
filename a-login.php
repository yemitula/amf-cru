<?php require_once('Connections/amfclients.php'); ?>
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
  $MM_redirectLoginSuccess = "a-index.php";
  $MM_redirectLoginFailed = "a-login.php?logerror=Login Failed! Username or Password Incorrect.";
  $MM_redirecttoReferrer = false;
  if ($loginUsername == 'admin' && $password == 'amfcru1520') {
	if (PHP_VERSION >= 5.1) {session_regenerate_id(true);} else {session_regenerate_id();}
    //declare two session variables and assign them
    $_SESSION['CRUAdmin'] = $loginUsername;
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
</head>

<body>
<div id="container">
  <div id="header"><img src="images/header.jpg" width="850" height="126" alt="Welcome to Online Test" /></div>
  <div id="body">
    <p class="bigHeading"><strong>Client Reps Update</strong>    </p>
    <p class="subHead">&nbsp;</p>
    <div class="leftColumn">
      <?php if (isset($_GET['logerror'])) { ?>
      <div class="msgBoxRed"><?php echo $_GET['logerror'] ?></div>
      <?php } ?>
      <div class="formTableWrap">
        <table width="100%" border="0" cellspacing="0" cellpadding="10">
          <tr>
            <th>Admin Login</th>
          </tr>
          <tr>
            <td style="border-bottom:#006600 1px solid"><form id="formLogin" name="formLogin" method="POST" action="<?php echo $loginFormAction; ?>">
              <table width="100%" border="0" cellspacing="0" cellpadding="5">
                <tr>
                  <td width="21%">Username</td>
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
              </table>
            </form></td>
          </tr>
        </table>
      </div>
    </div>
    <div class="clear"></div>

  </div>
</div>
</body>
</html>