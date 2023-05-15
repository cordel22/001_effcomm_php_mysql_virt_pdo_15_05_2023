
<?php
require('./includes/config.inc.php');

require(MYSQL);

$page_title = 'PDFs';
include('./includes/header.html');
echo '<h3>PDF Guides</h3>';

if (isset($_SESSION['user_id']) && !isset($_SESSION['user_not_expired'])) {
  echo '<p class="error">Thank you for your interest in this content.
      Unfortunately your account has expired. Please <a href="renew.php">
      renew your account</a>in oder to view any of the PDFs listed below.
      </p>';
} elseif (!isset($_SESSION['user_id'])) {
  echo '<p class="error">Thank you for your interest in this content.
      You must be logged in as a registered user to view any of the PDFs
      listed below.</p>';
}
/* 
$q = 'SELECT tmp_name, title, description, size FROM pdfs ORDER BY date_created DESC';
$r = mysqli_query($dbc, $q);
 */
$q = "SELECT tmp_name, title, description, size 
          FROM pdfs ORDER BY date_created DESC";
$tmnt = $pdo->query($q);

$row_count = $tmnt->rowCount();

// debug


var_dump($tmnt);

echo "<br />";

echo 'tmnt var_dump = '. var_dump($tmnt) . '<br />';


//  end debug

//  if (mysqli_num_rows($r) > 0) {
if ($row_count > 0) {
  //  while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
  while ($row = $tmnt->fetch(PDO::FETCH_NUM)) {
    //  debug view_pdf.php to pdf.php
    /* echo "<div><h4><a href=\"view_pdf.php?id={$row['tmp_name']}\"> */
    echo "<div><h4><a href=\"pdf.php?id={$row['tmp_name']}\">
        {$row['title']}</a>({$row['size']}kb)</h4><p>{$row['description']}
        </p></div>\n";
  } //  END of WHILE loop.
} else {  //  No PDFs!
  echo '<p>There are currently no PDFs available to view.
      Please check back again!</p>';
}
include('./includes/footer.html');
?>

