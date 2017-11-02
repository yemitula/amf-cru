<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_amfclients = "localhost";
$database_amfclients = "amfacili_clients";
$username_amfclients = "amfacili_mysqlU";
$password_amfclients = "Asql8855";
$amfclients = mysql_pconnect($hostname_amfclients, $username_amfclients, $password_amfclients) or trigger_error(mysql_error(),E_USER_ERROR); 
?>