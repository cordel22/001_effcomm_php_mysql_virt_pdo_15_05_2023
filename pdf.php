<?php
//  possibly view_pdf.bhp p.  132 / 149
require('./includes/config.inc.php');
require(MYSQL);

$valid = false;

if (
  isset($_GET['id']) && (strlen($_GET['id']) == 40) &&
  (substr($_GET['id'], 0, 1) != '.')
) {
  $file = PDFS_DIR . $_GET['id'];
  if (file_exists($file) && (is_file($file))) {
    /* 
    $q = 'SELECT title,description,file_name FROM pdfs WHERE
          tmp_name="' . mysqli_real_escape_string($dbc, $_GET['id']) . '"';
    $r = mysqli_query($dbc, $q);
 */
    $sql = 'SELECT title,description,file_name FROM pdfs WHERE
          tmp_name = :tmp_name';

    $tmnt = $pdo->prepare($sql);
    $tmnt->execute(array(
      ':tmp_name' => $_GET['id']
    ));

    $row_count = $tmnt->rowCount();

    //  if (mysqli_num_rows($r) == 1) { //  OK!
    if ($row_count == 1) {
      //  $row = mysqli_fetch_array($r, MYSQLI_ASSOC);
      $row = $tmnt->fetch(PDO::FETCH_NUM);
      $valid = true;

      if (isset($_SESSION['user_not_expired'])) {
        header('Content-type:application/pdf');
        header('Content-Disposition:inline;filename="' . $row['file_name'] . '"');
        $fs = filesize($file);
        header("Content-Length:$fs\n");
        readfile($file);
        exit();
      } else {  //  Inactive account!
        $page_title = $row['title'];
        include('./includes/header.html');
        echo "<h3>$page_title</h3>";
        if (isset($_SESSION['user_id'])) {
          echo '<p class="error">Thank you for your interest in this
            content. Unfortunately your account has expired. Please
            <a href="renew.php">renew your account</a>in order to
            access this file.</p>';
        } else {
          echo '<p class="error">Thank you for you interest in this
            content. You must be logged in as a registered user 
            to view this file.</p>';
        }
        echo "<div>{$row['description']}</div>";
        include('./includes/footer.html');
      } //  End of user IF-ELSE.
    } //  End of mysqli_num_rows() IF.
  } //  End of file_exists() IF.
} //  End of $_GET['id'] IF.

if (!$valid) {
  $page_title = 'Error!';
  include('./includes/header.html');
  echo '<p class="error">This page has been accessed in error.</p>';
  include('./includes/footer.html');
}
