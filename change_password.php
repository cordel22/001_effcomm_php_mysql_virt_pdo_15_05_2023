<?php
require('./includes/config.inc.php');
require('./includes/form_functions.inc.php'); //  book missed this

redirect_invalid_user();
$page_title = 'Change Your Password';
include('./includes/header.html');
require(MYSQL);

$pass_errors = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (!empty($_POST['current'])) {
    //  $current = mysqli_real_escape_string($dbc, $_POST['current']);
    $current = $_POST['current'];
  } else {
    $pass_errors['current'] = 'Please enter your current password!';
  }
  if (preg_match('/^(\w*(?=\w*\d)(?=\w*[a-z])(?=\w*[A-Z])\w*){6,20}$/', $_POST['pass1'])) {
    if ($_POST['pass1'] == $_POST['pass2']) {
      //  $p = mysqli_real_escape_string($dbc, $_POST['pass1']);
      $p = $_POST['pass1'];
    } else {
      $pass_errors['pass2'] = 'Your password did not match the
          confirmed password!';
    }
  } else {
    $pass_errors['pass1'] = 'Plese enter a valid password!';
  }

  if (empty($pass_errors)) {  //  If everything's OK.
    /*  
    $q = "SELECT id FROM users WHERE pass='" . get_password_hash($current)
      . "' AND id={$_SESSION['user_id']}";
    $r = mysqli_query($dbc, $q);
 */
    $q = "SELECT id FROM users WHERE pass = :pass AND id=:id";
    //  $r = mysqli_query($dbc, $q);
    $stmt = $pdo->prepare($q);
    $stmt->execute(array(":pass" =>  $current, ":id" => $_SESSION['user_id']));

    //  if (mysqli_num_rows($r) == 1) { //  Correct
    $row_count = $stmt->rowCount();
    if ($row_count == 1) {
      /* 
      $q = "UPDATE users SET pass='" . get_password_hash($p) . "' WHERE
        id = {$_SESSION['user_id']} LIMIT 1";
 */
      $sql = "UPDATE users SET pass = :pass WHERE id = :id LIMIT 1";
      $stmt = $pdo->prepare($sql);
      $stmt->execute(array(
        ':pass' => $p,
        ':id' => $_SESSION['user_id']
      ));

      //  if ($r = mysqli_query($dbc, $q)) {  //  If it ran OK.
      if ($stmt->execute(array(
        ':pass' => $p,
        ':id' => $_SESSION['user_id']
      ))) {  //  If it ran OK.
        echo '<h3>Your password has been changed.</h3>';
        include('./includes/footer.html');
        exit();
      } else {  //  If it did not run OK.
        trigger_error('Your password could not be changed due to a system
          error. We apologize for any inconvenience.');
      }
    } else {
      $pass_errors['current'] = 'Your current password is incorrect!';
    } //  End of current password ELSE.
  } //  End of $p IF.
} //  End of the form submission conditional.

require('./includes/form_functions.inc.php');
?>
<h3>Change Your Pssword</h3>
<p>
  Use the form below to change your pssword.
</p>
<form action="change_password.php" method="post" accept-charset="utf-8">
  <p>
    <label for="pass1">
      <strong>
        Current Pssword
      </strong>
    </label>
    <br />
    <?php
    create_form_input('current', 'password', $pass_errors);
    ?>
  </p>
  <p>
    <label for="pass1">
      <strong>
        New Password
      </strong>
    </label>
    <br />
    <?php
    create_form_input('pass1', 'password', $pass_errors);
    ?>
    <small>
      Must be between 6 and 20 characters loong, with at least one
      lowercase letter, one uppercase letter, and one number.
    </small>
  </p>
  <p>
    <label for="pass2">
      <strong>
        Confirm New Password
      </strong>
    </label>
    <br />
    <?php
    create_form_input('pass2', 'password', $pass_errors);
    ?>
  </p>
  <input type="submit" name="submit_button" value="Change &rarr;" id="submit_button" class="formbutton" />
</form>

<?php
include('./includes/footer.html');
?>