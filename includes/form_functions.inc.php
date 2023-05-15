<?php
function create_form_input($name, $type, $errors)
{
  $value = false;
  if (isset($_POST[$name])) $value = $_POST[$name];
  //  if ($value && get_magic_quotes_gpc()) $value = stripslashes($value);


  if (($type == 'text') || ($type == 'password')) {
    echo '<input type="' . $type . '" name="' . $name . '"id="' . $name . '"';
    if ($value) echo 'value="' . htmlspecialchars($value) . '"';


    if (array_key_exists($name, $errors)) {
      echo 'class="error" /><span class="error">' . $errors[$name] . '</span>';
    } else {
      echo '/>';
    }
    //  page 76 / 93 8. check if the input type is a textarea
  } elseif ($type == 'textarea') {
    if (array_key_exists($name, $errors)) echo '<span class="error">' . $errors[$name] . '</span>';
    echo '<textarea name="' . $name . '"id="' . $name . '" rows="5" cols="75"';
    if (array_key_exists($name, $errors)) {
      echo ' class="error">';
    } else {
      echo '>';
    }
    if ($value) echo $value;
    echo '</textarea>';
  } //  End of primary IF-ELSE.
} //  End of the create_form_input() function.

//  p.78  / 95
/* 
function get_password_hash($password)
{
  global $dbc;
  return mysqli_real_escape_string($dbc, hash_hmac('sha256', $password, 'c#haRl891', true));
}
 */

//  declred in config.inc.php
// function redirect_invalid_user($check = 'user_id', $destination = 'index.php', $protocol = ''/* 'http://' */) //  localhost dont need protocol
// {
//   if (!isset($_SESSION[$check])) {
//     $url = $protocol . BASE_URL . $destination;
//     header("Location:$url");
//     exit();
//   }
// }

if (!headers_sent()) {
  //  Redirect code.
} else {
  include_once('./includes/header.html');
  trigger_error('You do not hve permission to access this page.
    Plese log in and try again');
  include_once('./includes/footer.html');
}
