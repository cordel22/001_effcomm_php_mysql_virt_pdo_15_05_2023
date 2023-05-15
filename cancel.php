<?php
require('./includes/config.inc.php');
require(MYSQL);
$page_title = 'Oops!';
include('./includes/header.html');
?><h3>Oops!</h3>
<p>
  The payment through PayPal was not completed. You have a valid
  membership at this site, but you will not be able to view any content
  until you complete the PayPal transaction. You cn do so by clicking on
  the Renew link after logging in.
</p>
<?php
include('./includes/footer.html');
?>