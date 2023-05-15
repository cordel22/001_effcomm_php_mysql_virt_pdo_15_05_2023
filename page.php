<?php
require('./includes/config.inc.php');

require(MYSQL);
if (isset($_GET['id']) && filter_var($_GET['id'], FILTER_VALIDATE_INT, array('min_range' => 1))) {
  if (isset($_GET['id']) && ((int)$_GET['id'] >= 1)) {
    /* 
    $q = 'SELECT title, description, content FROM pages WHERE id=' . $_GET['id'];
    $r = mysqli_query($dbc, $q);
 *//* 
    $q = 'SELECT title, description, content FROM pages WHERE id=' . $_GET['id'];
    $tmnt = $pdo->query($q); */

    $q = "SELECT title, description, content FROM pages WHERE id = :xyz";
    $tmnt->execute(array(":xyz" => $_GET['id']));

    $row_count = $tmnt->rowCount();

    //  if (mysqli_num_rows($r) != 1) {
    if ($row_count != 1) {
      $page_title = 'Error!';
      include('./includes/header.html');
      echo '<p class="error">This page hs been accessed in error.</p>';
      include('./includes/footer.html');
      exit();
    }
    //  $row = mysqli_fetch_array($r, MYSQLI_ASSOC);
    $row = $tmnt->fetch(PDO::FETCH_NUM);
    $page_title = $row['title'];
    include('includes/header.html');
    echo "<h3>$page_title</h3>";

    if (isset($_SESSION['user_not_expired'])) {
      echo "<div>{$row['content']}</div>";
    } elseif (isset($_SESSION['user_id'])) {  //  Logged in but not current.
      echo '<p class="error">Thank you for your interest in this content, but
            your account is no longer current. Please <a href="renew.php"> renew
            your account</a>in order to view this page in its entirety</p>';
      echo "<div>{$row['description']}</div>";
    } else {  //  Not logged in.
      echo '<p class="error">Thank you for your interest in this content.
            You must be logged in as a registered user to view this page in its
            entirety.</p>';
      echo "<div>{$row['description']}</div>";
    }
  } else {  //  No valid ID.
    $page_title = 'Error!';
    include('includes/header.html');
    echo '<p class="error">This page has been accessed in error.</p>';
  } //  this parntheses is not in book, but seems to belong here p 121 / 138
}   //  End of primary IF.

include('./includes/footer.html');
