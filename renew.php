<?php
require('./includes/config.inc.php');
redirect_invalid_user();
$page_title = 'Renew Your Account';
include('./includes/header.html');
require(MYSQL);
?>
<h3>
  Thanks!
</h3>
<p>
  Thank you for your interest in renewing your
  account! To complete the process, please now click the button below
  so that you may pay for your renewal via PayPal. The cost is $10(US)
  per year.
</p>

<form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post">
  <input type="hidden" name="cmd" value="_s-xclick">
  <input type="hidden" name="custom" value="
    <?php
    echo $_SESSION['user_id'];
    ?>">
  <input type="hidden" name="hosted_button_id" value="8YW8FZDELF296">
  <input type=" submit" name="submit_button" value="Renew &rarr;" id="submit_button" class="formbutton" />
</form>
<?php include('./includes/footer.html'); ?>