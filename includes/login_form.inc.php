<?php
if (!isset($login_errors)) $login_errors = array();
require_once('./includes/form_functions.inc.php');
?>

<div class="title">
  <h4>Login</h4>
</div>
<!-- <form action="index.php" method="post" accept-charset="utf-8"> -->
<form action="./includes/login.inc.php" method="post" accept-charset="utf-8">
  <p>
    <?php
    if (array_key_exists('login', $login_errors)) {
      echo '<span class="error">' . $login_errors['login'] . '</span><br />';
    }
    ?>
    <label for="email"><strong>Email Address</strong></label>
    <br /><?php create_form_input('email', 'text', $login_errors); ?>
    <br /><label for="pass"><strong>Password</strong></label>
    <br /><?php create_form_input('pass', 'password', $login_errors); ?>
    <a href="forgot_password.php" align="right">Forgot?</a><br />
    <input type="submit" value="Login &rarr;">
  </p>
</form>

<?php
if (array_key_exists('login', $login_errors)) {
  echo '<span class="error">' . $login_errors['login'] . '</span><br />';
}
?>