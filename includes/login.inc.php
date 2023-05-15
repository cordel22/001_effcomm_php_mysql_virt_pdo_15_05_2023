<?php
$login_errors = array();
require('./config.inc.php');  //  watch out, you are in includes!!!
require(MYSQL);

if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
  //  $e = mysqli_real_escape_string($dbc, $_POST['email']);
  $e = $_POST['email'];
} else {
  $login_errors['email'] = 'Please enter a valid email address!';
}

if (!empty($_POST['pass'])) {
  //  $p = mysqli_real_escape_string($dbc, $_POST['pass']);
  $p = $_POST['pass'];
} else {
  $login_errors['pass'] = 'Please enter your password!';
}

if (empty($login_errors)) {
  /* 
  $q = "SELECT id, username, type, IF(date_expires >= NOW(), true, false)
      FROM users WHERE (email='$e' AND pass='" . get_password_hash($p) . "')";
  $r = mysqli_query($dbc, $q);
 */

  //  debug
  echo '<br />';
  echo '":xyz" = ' .  $e;
  echo '<br />';
  echo  '":word" = ' . $p;
  echo '<br />';

  //  end debug

  // $q = "SELECT id, username, type, IF(date_expires >= NOW(), true, false)
  // FROM users WHERE (email=:xyz AND pass=:word)";   //  todo: osetri tento call
  //  debug
  $stmt = $pdo->prepare("SELECT id, username, type, IF(date_expires >= NOW(), true, false) FROM users where email = :xyz AND pass=:word");
  $stmt->execute(array(
    ":xyz" => $e,
    ":word" => $p
  ));
  //  end debug
  /* 
$stmt = $pdo->prepare($q);
  $stmt->execute(array(":xyz" =>  $e, ":word" => $p));  //  preco tu nebolo MYSSQL..? pozi do knihy..!*/
  $row_count = $stmt->rowCount();
  //  debug
  echo '<br />';
  echo 'tu sme!';
  echo '<br />';
  var_dump($stmt);
  //  end debug

  //  if (mysqli_num_rows($r) == 1) {
  if ($row_count == 1) {

    //  $row = mysqli_fetch_array($r, MYSQLI_NUM);
    $row = $stmt->fetch(PDO::FETCH_NUM);
    /* $_SESSION['user_id'] = $row[0];
    $_SESSION['username'] = $row[1]; */




    if ($row[2] == 'admin') {
      session_regenerate_id(true);
      $_SESSION['user_admin'] = true;
    }
    $_SESSION['user_id'] = $row[0];
    $_SESSION['username'] = $row[1];
    if ($row[3] == 1) $_SESSION['user_not_expired'] = true;

    //  debug
    echo '<br />';
    echo 'so we r in ryte?!';
    echo '<br />';
    //  end debug

    header('Location: ../index.php');
    return;
  } else {
    $login_errors['login'] = 'The email address and password do not match those on file.';
  }
} //  End of $login_errors IF.
