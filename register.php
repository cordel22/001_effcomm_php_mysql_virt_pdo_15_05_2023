<?php
require('./includes/config.inc.php');
$page_title = 'Register';
include('./includes/header.html');
require(MYSQL);
require('./includes/form_functions.inc.php');

/* 
//debug
$ord = '11';

$u = $ord . "test_u";

$e = $ord . "test_e";

$p = $ord . "test_p";

$pass = 'password_hash($p, PASSWORD_DEFAULT)';

$fn = $ord . "test_fn";

$ln = $ord . "test_ln";


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
 */
//  konec debug


$reg_errors = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  //  nezobere opakovane hodnoty, ale nevaruje o tom
  if (preg_match('/^[A-Z\'.-]{2,20}$/i', $_POST['first_name'])) {
    //  $fn = mysqli_real_escape_string($dbc, $_POST['first_name']);
    $fn = $_POST['first_name'];
  } else {
    $reg_errors['first_name'] = 'Please enter your first name!';
  }

  if (preg_match('/^[A-Z\'.-]{2,40}$/i', $_POST['last_name'])) {
    //  $ln = mysqli_real_escape_string($dbc, $_POST['last_name']);
    $ln = $_POST['last_name'];
  } else {
    $reg_errors['last_name'] = 'Please enter your last name!';
  }

  if (preg_match('/^[A-Z0-9]{2,30}$/i', $_POST['username'])) {
    //  $u = mysqli_real_escape_string($dbc, $_POST['username']);
    $u = $_POST['username'];
  } else {
    $reg_errors['username'] = 'Please enter a desired username!';
  }

  if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    //  $e = mysqli_real_escape_string($dbc, $_POST['email']);
    $e = $_POST['email'];
  } else {
    $reg_errors['email'] = 'Please enter a valid email address!';
  }

  /* if (preg_match('/^(\w*(?=\w*\d)(?=\w*[a-z])(?=\w*[A-Z])\w*){6,20}$/', $_POST['pass1'])) { */
  if (preg_match('/^(?=.*\d)(?=.*[A-Z])(?=.*[a-z]).{6,20}$/', $_POST['pass1'])) {
    if ($_POST['pass1'] == $_POST['pass2']) {
      //  $p = mysqli_real_escape_string($dbc, $_POST['pass1']);
      $p = $_POST['pass1'];
    } else {
      $reg_errors['pass2'] = 'Your password did not match the confirmed password!';
    }
  } else {
    $reg_errors['pass1'] = 'Please enter a valid password!';
  }   //  End of preg_match

  if (empty($reg_errors)) {
    /* 
    $q = "SELECT email, username FROM users WHERE email='$e' OR username='$u'";
    $r = mysqli_query($dbc, $q);
 */
    $stmt = $pdo->query("SELECT email, username FROM users WHERE email='$e' OR username='$u'");


    //  $rows = mysqli_num_rows($r);
    $row_count = $stmt->rowCount();

    //  if ($rows == 0) { //  No problems!
    if ($row_count == 0) {
      /* 
      $q = "INSERT INTO users (username, email, pass, first_name, last_name, date_expires)
           VALUES ('$u', '$e', '" . substr(password_hash($p, PASSWORD_DEFAULT), 5, 30) . "', '$fn', '$ln', ADDDATE(NOW(), INTERVAL 1 MONTH))"; */
      //  p 146 / 163
      /* $q = "INSERT INTO users (username, email, pass, first_name, last_name, date_expires)
        VALUES ('$u', '$e', '" . get_password_hash($p) . "', '$fn', '$ln', SUBDATE(NOW(), INTERVAL 1 DAY))";
        */
      /* toto funguje v mysqli cico
      $q = "INSERT INTO users (username, email, pass, first_name, last_name, date_expires)
          VALUES ('$u', '$e', '" . substr(password_hash($p, PASSWORD_DEFAULT), 5, 30) . "', '$fn', '$ln', SUBDATE(NOW(), INTERVAL 1 DAY))";

      $r = mysqli_query($dbc, $q);
 */

      $dat = date("Y-m-d", strtotime('tomorrow'));

      $sql = "INSERT INTO users (username, email, pass, first_name, last_name, date_expires)
              VALUES (:username, :email, :pass, :first_name, :last_name, :date_expires)";
      $stmt = $pdo->prepare($sql);
      $stmt->execute(array(
        ':username' => $u,
        ':email' => $e,
        ':pass' => substr($p, 5, 30),
        ':first_name' => $fn,
        ':last_name' => $ln,
        //  ':date_expires' => 'SUBDATE(NOW(), INTERVAL 1 DAY)'
        ':date_expires' => $dat
      ));



      //  if (mysqli_affected_rows($dbc) == 1) {
      if ($stmt->rowCount() == 1) {
        //  p 146 / 163
        //  $uid = mysqli_insert_id($dbc);
        $uid = $pdo->lastInsertId();
        $_SESSION['reg_user_id'] = $uid;
        //  page 145 / 162 update
        echo '<h3>Thanks!</h3>
          <p>
            Thank you for registering!  
            To complete the process, please now lick the button below so that you may pay for your
            site access via PayPal. The cost is $10 (US) per year.<strong>Note: When
            you complete your payment at PayPal, please click the button to return to
            this site.</strong></p>";
            <!--  asi netrba totok  -->
            You may now log in and access the site\'s content.</p>
            -->
            ';
        //  page 146 / 163 update paypal sandbox button LPQD8LG7S2NF8
        //  page 152 / 169
        // echo '<form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post">
        //   /* <input type="hidden" name="custom" value="' . $uid . '"> */
        //   <input type="hidden" name="cmd" value="_s-xclick">
        //   /* <input type="hidden" name="email" value="' . $e . '"> */
        //   <input type="hidden" name="hosted_button_id" value="8YW8FZDELF296">
        //   <input type="image" src="https://www.sandbox.paypal.com/en_US/i/btn/btn_subscribeCC_LG.gif" border="0" name="submit" alt="PayPal - 
        //     The safer, easier way to pay online!">
        //   <img alt="" border="0" src="https://www.sandbox.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
        //   </form>';

        // priamo z paypalu subscription a  fynguje ale naozaj sa plati
        // echo '<div id="paypal-button-container-P-0P680311AX3030015MJBVBOY"></div>
        // <script src="https://www.paypal.com/sdk/js?client-id=AXsG165I5IZH5-zvBbLuZqinEgzCLiSUIL53QkLDZOmepLQic8ejLy3HbaojHHxOEM4cuAH_Zb2ptfhl&vault=true&intent=subscription" data-sdk-integration-source="button-factory"></script>
        // <script>
        //   paypal.Buttons({

        //       createSubscription: function(data, actions) {
        //         return actions.subscription.create({

        //           plan_id: "P-0P680311AX3030015MJBVBOY"
        //         });
        //       },
        //       onApprove: function(data, actions) {
        //         alert(data.subscriptionID); // You can add optional success message for the subscriber here
        //       }
        //   }).render("#paypal-button-container-P-0P680311AX3030015MJBVBOY")
        // </script>';


        //  subscribtion so sandboxu!!!
        echo '<div id="paypal-button-container-P-67C163112J3933803MJBYPUY"></div>
        <script src="https://www.paypal.com/sdk/js?client-id=Af7KvhKgor3tDrGkVgpWv4xunqMtqnRB9uHhr9k3p9Z1NYOlCksPahTBa3-fsOxcXpwHb6RvChdpXCTy&vault=true&intent=subscription" data-sdk-integration-source="button-factory"></script>
        <script>
          paypal.Buttons({
              
              createSubscription: function(data, actions) {
                return actions.subscription.create({
                  /* Creates the subscription */
                  plan_id: "P-67C163112J3933803MJBYPUY"
                });
              },
              onApprove: function(data, actions) {
                alert(data.subscriptionID); // You can add optional success message for the subscriber here
              }
          }).render("#paypal-button-container-P-67C163112J3933803MJBYPUY"); // Renders the PayPal button
        </script>';



        $body = "Thank you for registering at <whatever site>. Blah. Blah. Blah.\n\n";
        //  samozrejme smtp nefunguje at localserver...
        //  mail($_POST['email'], 'Registration Confirmation', $body, 'From: cordelfenevall@gmail.com');
        include('./includes/footer.html');
        exit();
      } else {
        trigger_error('You could not be registered due to a system error.
        We apologize for ny inconvenience.');
      }
    } else {
      //  if ($rows == 2) { //  Both are taken.
      if ($row_count == 2) {
        $reg_errors['email'] = 'This email address has already been
          registered. If you have forgotten your password, use the link at
          right to have your password sent to you.';
        $reg_errors['username'] = 'This username has already been 
          registered. Please try another.';
      } else {  //  One or both may be taken.
        //$row = mysqli_fetch_array($r, MYSQLI_NUM);
        $row = $stmt->fetch(PDO::FETCH_NUM);
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