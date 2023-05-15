<?php
require('./includes/config.inc.php');
$page_title = 'Register';
include('./includes/header.html');
require(MYSQL);
require('./includes/form_functions.inc.php');

//  p 84  / 101

//  $php_errors = array();
$reg_errors = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (preg_match('/^[A-Z\'.-]{2,20}$/i', $_POST['first_name'])) {
    $fn = mysqli_real_escape_string($dbc, $_POST['first_name']);
  } else {
    $reg_errors['first_name'] = 'Please enter your first name!';
  }

  if (preg_match('/^[A-Z\'.-]{2,40}$/i', $_POST['last_name'])) {
    $ln = mysqli_real_escape_string($dbc, $_POST['last_name']);
  } else {
    $reg_errors['last_name'] = 'Please enter your last name!';
  }

  if (preg_match('/^[A-Z0-9]{2,30}$/i', $_POST['username'])) {
    $u = mysqli_real_escape_string($dbc, $_POST['username']);
  } else {
    $reg_errors['username'] = 'Please enter a desired username!';
  }

  if (/* filter_var( */$_POST['email']/* , FILTER_VALIDATE_EMAIL) */) {
    $e = mysqli_real_escape_string($dbc, $_POST['email']);
  } else {
    $reg_errors['email'] = 'Please enter a valid email address!';
  }

  if (/* preg_match('/^(\w*(?=\w*\d)(?=\w*[a-z])(?=\w*[A-Z])\w*){6,20}$/', */$_POST['pass1'])/* ) */ {
    if ($_POST['pass1'] == $_POST['pass2']) {
      $p = mysqli_real_escape_string($dbc, $_POST['pass1']);
    } else {
      $reg_errors['pass2'] = 'Your password did not match the confirmed password!';
    }
  } else {
    $reg_errors['pass1'] = 'Please enter a valid password!';
  }   //  End of preg_match

  if (empty($reg_errors)) {
    $q = "SELECT email, username FROM users WHERE email='$e' OR username='$u'";
    $r = mysqli_query($dbc, $q);
    $rows = mysqli_num_rows($r);
    //  debug
    echo "connected to database";
    //  konec debug

    //  debug
    echo "<br /> rows = " . $rows;
    //  konec debug

    if ($rows == 0) { //  No problems!
      //  p 146 / 163
      //  debug
      echo "<br />lets try insert query";
      //  konec debug
      //  debug
      // $q = "INSERT INTO users (username, email, pass, first_name, last_name, date_expires)
      //   VALUES ('$u', '$e', '" . password_hash($p, PASSWORD_DEFAULT)/* create_password_hash($p) */ . "', '$fn', '$ln', ADDDATE(NOW(), INTERVAL 1 MONTH))";
      // $q = "INSERT INTO users (username, email, pass, first_name, last_name, date_expires)
      // VALUES ('$u', '$e', '" . password_hash($p, PASSWORD_DEFAULT)/* create_password_hash($p) */ . "', '$fn', '$ln', DATE_ADD(
      //       NOW(), 
      //       INTERVAL 1 DAY
      //   ))";
      $q = "INSERT INTO users (username, email, pass, first_name, last_name, date_expires)
           VALUES ('2test_u', '$e', '2test_pass', '2test_fn', '2test_ln', NOW())";
      //  konec debug
      //  p 146 / 163
      /* $q = "INSERT INTO users (username, email, pass, first_name, last_name, date_expires)
        VALUES ('$u', '$e', '" . get_password_hash($p) . "', '$fn', '$ln', SUBDATE(NOW(), INTERVAL 1 DAY))";
        */
      $r = mysqli_query($dbc, $q);
      //  debug
      echo "<br />VALUES; ";
      //  debug
      echo "<br /> u :  " . $u;
      //  debug
      echo "<br /> e :  " . $e;
      //  debug
      echo "<br /> password hash : " . password_hash($p, PASSWORD_DEFAULT);
      //  debug
      echo "<br /> fn :  " . $fn;
      //  debug
      echo "<br />ln :  " . $ln;
      //  debug
      //echo "<br /> DDDaTE : " . ADDDATE(NOW(), INTERVAL 1 MONTH);

      //  debug
      /* echo "<br />mysqli_affected_rows($dbc) == 1" . !!(mysqli_affected_rows($dbc) == 1); */ //  picovina
      //  echo "mysqli_num_rows($r) = " .  mysqli_num_rows($r);
      echo "<br />ide var_dump od r :<br />";
      var_dump($r);
      echo "<br />";
      echo "<br /> a tu kurva nic z r = " .  var_dump($r);
      //  konec debug

      if (mysqli_affected_rows($dbc) == 1) {
        //  p 146 / 163
        /* $uid = mysqli_insert_id($dbc);
        $_SESSION['reg_user_id'] = $uid; */
        //  page 145 / 162 update
        echo '<h3>Thanks!</h3>
          <p>
            Thank you for registering!  <!--
            /* To complete the process, please now lick the button below so that you may pay for your
            site access via PayPal. The cost is $10 (US) per year.<strong>Note: When
            you complete your payment at PayPal, please click the button to return to
            this site.</strong></p>"; -->
            <!--  asi netrba totok */ -->
            You may now log in and access the site\'s content.</p>
            -->
            ';
        //  page 146 / 163 update paypal sandbox button
        //  page 152 / 169
        /* echo '<form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post">
          <input type="hidden" name="custom" value="' . $uid . '">
          <input type="hidden" name="cmd" value="_s-xclick">
          <input type="hidden" name="email" value="' . $e . '">
          <input type="hidden" name="hosted_button_id" value="8YW8FZDELF296">
          <input type="image" src="https://www.sandbox.paypal.com/en_US/i/
            btn/btn_subscribeCC_LG.gif" border="0" name="submit" alt="PatPal - 
            The safer, easier way to pay online!">
          <img alt="" border="0" src="https://www.sandbox.paypal.com/en_US/i/
            scr/pixel.gif" width="1" height="1">
          </form>'; */
        $body = "Thnk you for registering at <whatever site>. Blah. Blah. Blah.\n\n";
        mail($_POST['email'], 'Registration Confirmation', $body, 'From: cordelfenevall@gmail.com');
        include('./includes/footer.html');
        exit();
      } else {
        trigger_error('You could not be registered due to a system error.
        We apologize for ny inconvenience.');
      }
    } else {
      if ($rows == 2) { //  Both are taken.
        $reg_errors['email'] = 'This email address has already been
          registered. If you have forgotten your password, use the link at
          right to have your password sent to you.';
        $reg_errors['username'] = 'This username has already been 
          registered. Please try another.';
      } else {  //  One or both may be taken.
        $row = mysqli_fetch_array($r, MYSQLI_NUM);
        if (($row[0] == $_POST['email']) && ($row[1] == $_POST['username'])) { //  Both match.
          $reg_errors['email'] = 'This email address has already been
            registered. If you have forgotten your password, use the link at
            right to have your password sent to you.';
          $reg_errors['username'] = 'This username has already been
            registered with this email address. If you have forgotten your
            password, use the link at right to have your password sent to you.';
        } elseif ($row[0] == $_POST['email']) { //  Email match.
          $reg_errors['email'] = 'This email address has already been
            registered. If you have forgotten your password, use the link at
            right to have your password sent to you.';
        } elseif ($row[1] == $_POST['username']) {  //  Username match.
          $reg_errors['username'] = 'This username has already been
          registered. Please try another.';
        }
      } //  End of $rows == 2 ELSE.
    } //  End of $rows == 0 IF.
  } //  End of empty($reg_errors)IF.
}   //  End of the main form submission conditional.




?><h3>Register</h3>
<p>Access to the site's content is available to registered users at a cost
  of $10.00(US) per year. Use the form below to begin the registration
  process. <strong>Note: All fields are required.</strong> After
  completing this form, you'll be presented with the opportunity to
  securely pay for your yearly subscriptin via
  <a href="http://www.paypal.com">
    PayPal
  </a>.
</p>

<form action="register.php" method="post" accept-charset="utf-8" style="padding-left:100px">
  <p><label for="first_name"><strong>First Name</strong></label>
    <br />
    <?php create_form_input('first_name', 'text', $reg_errors); ?>
  </p>
  <p><label for="last_name"><strong>Last Name</strong></label>
    <br />
    <?php create_form_input('last_name', 'text', $reg_errors); ?>
  </p>
  <p><label for="username"><strong>Desired Username</strong></label>
    <br />
    <?php create_form_input('username', 'text', $reg_errors); ?>
    <small>Only letters and numbers are allowed.</small>
  </p>
  <p><label for="email"><strong>Email Address</strong></label>
    <br />
    <?php create_form_input('email', 'text', $reg_errors); ?>
  </p>
  <p><label for="pass1"><strong>Password</strong></label>
    <br />
    <?php create_form_input('pass1', 'password', $reg_errors); ?>
    <!-- debug
      <span><?php echo "POST[pass1] = " . $_POST['pass1']; ?></span> 
    end debug   -->
    <small>Must be between 6 and 20 characters long, with at least one
      lowercase letter, one uppercase letter, and one number.</small>
  </p>
  <p><label for="pass2"><strong>Confirm Password</strong></label>
    <br />
    <?php create_form_input('pass2', 'password', $reg_errors); ?>
  </p>
  <input type="submit" name="submit_button" value="Next &rarr;" id="submit_button" class="formbutton" />
</form>


<?php
include('./includes/footer.html');



?>