<?php
DEFINE('DB_USER', 'root');
DEFINE('DB_PASSWORD', 'root');
DEFINE('DB_HOST', 'localhost');
DEFINE('DB_NAME', 'ecommerce1');

$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
//  from gfx 18
//  If no connection could be made, trigger an error:
if (!$dbc) {
  trigger_error('Could not connect to MySQL: ' .  mysqli_connect_error());
} else {
  //  Otherwise, set the encoding:
  mysqli_set_charset($dbc, 'utf8');
}

mysqli_set_charset($dbc, 'utf8');

function escape_data($data)
{
  global $dbc;
  //  if (get_magic_quotes_gpc()) $data = stripslashes($data);
  return mysqli_real_escape_string($dbc, trim($data));
  //  return mysqli_real_escape_string(trim($data),$dbc);   //  its in the book like this but comes out with an error
} //  End of the escape_data( function)