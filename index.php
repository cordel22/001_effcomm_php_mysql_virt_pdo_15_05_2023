<?php
require('./includes/config.inc.php');

//  to test the logged in
/* $_SESSION['user_id'] = 1; */

//  logged in admin
/* $_SESSION['user_type'] = 'admin'; */

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  /* include('./includes/login.php'); */
  include('./includes/login_form.inc.php');
}

include('./includes/header.html');

require(MYSQL);

?>

<h3>Welcome</h3>
<p>Welcome to Knowledge is Power, a site dedicated to keeping you up
  to date on the Web security and programming information you need
  to know. Blh, blah, blah. Yadda, yadda, yadda.
</p>

<?php
include('./includes/footer.html');
?>