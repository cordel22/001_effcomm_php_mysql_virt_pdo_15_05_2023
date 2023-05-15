<?php
require('./includes/config.inc.php');
$page_title = 'Forgot Your Password?';
include('./includes/header.html');
require(MYSQL);

$pass_errors = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    /* $q = 'SELECT id FROM users WHERE email="' .
      mysqli_real_escape_string($dbc, $_POST['email']) . '"'; */
    $q = "SELECT id FROM users WHERE email = :xyz";
    //  $r = mysqli_query($dbc, $q);
    $stmt = $pdo->prepare($q);
    $stmt->execute(array(":xyz" =>  $_POST['email']));
    //  if (mysqli_num_rows($r) == 1) { //  Retrieve the user ID.
    $row_count = $stmt->rowCount();
    if ($row_count == 1) {
      //  list($uid) = mysqli_fetch_array($r, MYSQLI_NUM);
      list($uid) = $stmt->fetch(PDO::FETCH_NUM);
    } else {  //  No database match made.
      $pass_errors['email'] = 'The submitted email address does not match those on file!';
    }
  } else {  //  No valid address submitted.
    $pass_errors['email'] = 'Please enter a valid email addess!';
  } //  End of $_POST['email'] IF.
  if (empty($pass_errors)) {  //  if everything's OK.
    $p = substr(md5(uniqid(rand(), true)), 10, 15);
    /* 
    $q = "UPDATE users SET pass='" . get_password_hash($p) . "' WHERE id=$uid LIMIT 1";
    $r = mysqli_query($dbc, $q);
 */
    $sql = "UPDATE users SET pass = :pass WHERE id= :id LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
      ':pass' => $_POST['name'],
      ':id' => $uid
    ));

    //  if (mysqli_affected_rows($dbc) == 1) {  //  If it ran OK.
    if ($stmt->rowCount() == 1) {
      $body = "Your password to log into<whatever site> has been
          temporarily changed to '$p'. Please log in using that password and this
          email address. Then you may change your password to something more familiar.";
      mail($_POST['email'], 'Your temporary password.', $body, 'From: cordelfenevall@gmail.com');
      echo '<h3>Your password has been changed.</h3><p>You will receive
          the new, temporary password via email. Once you have logged in 
          with this new password, you may change it by clicking on the "Change
          Pssword" link.</p>';
      include('./includes/footer.html');
      exit();
    } else {  //  If it did not run OK.
      trigger_error('Your password could not be changed due to a system
          error. We apologize for ny inconvenience.');
    }
  } //  End of $uid IF.
}   //  End of the main $ubmit conditional.

require('./includes/form_functions.inc.php');
?>
<h3>Reset Your Password</h3>
<p>
  Enter your email addres below to reset your password.
</p>
<form action="forgot_password.php" method="post" accept-charset="utf-8">
  <p>
    <label for="email">
      <strong>
        Email Addess
      </strong>
    </label>
    <br />
    <?php
    create_form_input('email', 'text', $pass_errors);
    ?>
  </p>
  <input type="submit" name="submit_button" value="Reset &rarr;" id="submit_button" class="formbutton" />
</form>
<?php
include('./includes/footer.html');
?>