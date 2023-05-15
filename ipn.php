<?php
//  page 151 / 168
require('./includes/config.inc.php');

$req = 'cmd=_notify-validate';

foreach ($_POST as $key => $value) {
  $value = urlencode(stripslashes($value));
  $req .= "&$key=$value";
}

$fp = fsockopen('ssl://www.sandbox.paypal.com', 443, $errno, $errstr, 30);  //  Test
//  When the site goes live, the connection will be made to just
//  ssl://www.paypal.com, with the other settings the same:
//  $fp = fsockopen('ssl://www.paypal.com',443,$errno,$errstr,30);

if (!$fp) {
  trigger_error('Could not connect for the IPN!');
} else {
  $header = "POST /cgi-bin/webscr HTTP/1.0\r\n";
  $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
  $header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
  fputs($fp, $header . $req);

  while (!feof($fp)) {
    $res = fgets($fp, 1024);
    if (strcmp($res, "VERIFIED") == 0) {
      if (
        isset($_POST['payment_status'])
        && ($_POST['payment_status'] == 'Completed')
        && ($_POST['receiver_email'] == 'cordelfenevall@gmail.com')
        && ($_POST['mc_gross'] == 10.00)
        && ($_POST['mc_currency'] == 'USD')
        && (!empty($_POST['txn_id']))
      ) {
        require(MYSQL);
        //  $txn_id = mysqli_real_escape_string($dbc, $_POST['txn_id']);
        $txn_id = $_POST['txn_id'];
        /* 
        $q = "SELECT id FROM orders WHERE transaction_id='$txn_id'";
        $r = mysqli_query($dbc, $q);
 */
        $stmt = $pdo->prepare("SELECT id FROM orders WHERE transaction_id = :xyz");
        $stmt->execute(array(":xyz" => $txn_id));

        $row_count = $tmnt->rowCount();

        //  if (mysqli_num_rows($r) == 0) {
        if ($row_count > 0) {
          $uid = (isset($_POST['custom'])) ? (int)$_POST['custom'] : 0;
          $status = mysqli_real_escape_string($dbc, $_POST['payment_status']);
          $amount = (float)$_POST['mc_gross'];
          /* 
          $q = "INSERT INTO orders (user_id, transaction_id, payment_status,
              payment_amount) VALUES ($uid, '$txn_id', '$status', $amount)";
          $r = mysqli_query($dbc, $q);
 */
          $sql = "INSERT INTO orders (user_id, transaction_id, payment_status,
              payment_amount)  VALUES (:user_id, :transaction_id, :payment_status, :payment_amount)";
          $tmnt = $pdo->prepare($sql);
          $tmnt->execute(array(
            ':user_id' => $uid,
            ':transaction_id' => $txn_id,
            ':payment_status' => $status,
            ':payment_amount' => $amount
          ));

          //  if (mysqli_affected_rows($dbc) == 1) {
          if ($tmnt->rowCount() == 1) {
            if ($uid > 0) {
              /* 
              $q = "UPDATE users SET date_expires = IF(date_expires > NOW(),
                ADDDATE(date_expires, INTERVAL 1 YEAR), ADDDATE(NOW(),
                INTERVAL 1 YEAR)), date_modified=NOW() WHERE id=$uid";
              $r = mysqli_query($dbc, $q);
 */
              $sql = "UPDATE users SET date_expires = :date_expires,
            date_modified = :date_modified WHERE id = :id";
              $stmt = $pdo->prepare($sql);
              $stmt->execute(array(
                ':date_expires' => 'IF(date_expires > NOW(),
        ADDDATE(date_expires, INTERVAL 1 YEAR), ADDDATE(NOW(),
        INTERVAL 1 YEAR))',
                ':date_modified' => 'NOW()',
                ':id' => $uid
              ));


              //  if (mysqli_affected_rows($dbc) != 1) {
              if ($tmnt->rowCount() !== 1) {
                trigger_error('The user\'s expiration date could not be updated!');
              }
            }
          } else {  //  Problem inserting the order!
            trigger_error('The transaction could not be stored in the orders table!');
          }
        } //  The order has alreaady been stored!
      } //  The right values don't exist in $_POST!
    } elseif (strcmp($res, "INVALID") == 0) {
      //  Log for further investigation.
    }
  } //  End of the WHILE loop.
  fclose($fp);
} //  End of $fp IF-ELSE.
