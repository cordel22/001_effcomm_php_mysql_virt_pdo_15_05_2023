<?php
require('./includes/config.inc.php');
//  p 158 / 175
redirect_invalid_user('reg_user_id');
require(MYSQL);
$page_title = 'Thanks!';
include('./includes/header.html');
/* 
$q = "UPDATE users SET date_expires = ADDDATE(date_expires,
    INTERVAL 1 YEAR) WHERE id={$_SESSION['reg_user_id']}";
$r = mysqli_query($dbc, $q);
 */
$sql = "UPDATE users SET date_expires = :date_expires 
  WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute(array(
  ':date_expires' => 'ADDDATE(date_expires, INTERVAL 1 YEAR)',
  ':id' => $_SESSION['reg_user_id']
));

unset($_SESSION['reg_user_id']);
?><h3>Thank You!</h3>
<p>Thank you for your payment! You may now access all of the site's
  content for the next year!<strong>Note: Your access to the site will
    automatically be renewed via PayPal Profile page.</strong></p>
<?php
include('./includes/footer.html');
?>