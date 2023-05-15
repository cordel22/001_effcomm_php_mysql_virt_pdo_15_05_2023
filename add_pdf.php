<?php
require_once('./includes/config.inc.php');
//  debug maybe evn form_function higher
//  redirect_invalid_user('user_admin');

$page_title = 'Add a PDF';
include('./includes/header.html');

require(MYSQL);
$add_pdf_errors = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (!empty($_POST['title'])) {
    //  $t = mysqli_real_escape_string($dbc, strip_tags($_POST['title']));
    $t = strip_tags($_POST['title']);
  } else {
    $add_pdf_errors['title'] = 'Plese enter the  title!';
  }
  if (!empty($_POST['description'])) {
    // $d = mysqli_real_escape_string($dbc, strip_tags(
    $d = strip_tags(
      $_POST['description']
    );
  } else {
    $add_pdf_errors['description'] = 'Plese enter the description!';
  }
  if (
    is_uploaded_file($_FILES['pdf']['tmp_name'])
    && ($_FILES['pdf']['error'] == UPLOAD_ERR_OK)
  ) {
    $file = $_FILES['pdf'];

    $size = ROUND($file['size'] / 1024);
    if ($size > 1024) {
      $add_pdf_errors['pdf'] = 'The uploaded file was too large.';
    }
    if (($file['type'] != 'application/pdf') && (substr($file['name'], -4) != '.pdf')) {
      $add_pdf_errors['pdf'] = 'The uploaded file was not a PDF.';
    }
    if (!array_key_exists('pdf', $add_pdf_errors)) {
      $tmp_name = sha1($file['name']  . uniqid('', true));
      $dest = PDFS_DIR . $tmp_name  . '_tmp';

      if (move_uploaded_file($file['tmp_name'], $dest)) {
        $_SESSION['pdf']['tmp_name'] = $tmp_name;
        $_SESSION['pdf']['size'] = $size;
        $_SESSION['pdf']['file_name'] = $file['name'];
        echo '<h4>The file has been uploaded!</h4>';
      } else {
        trigger_error('The file could not be moved.');
        unlink($file['tmp_name']);
      }
    } //  End of array_key_exists() IF.
  } else {  //  No uploaded file.
    switch ($_FILES['pdf']['error']) {
      case 1:
      case 2:
        $add_pdf_errors['pdf'] = 'The uploaaded file was too lrge.';
        break;
      case 3:
        $add_pdf_errors['pdf'] = 'The file was only parially uploaded.';
        break;
      case 6:
      case 7:
      case 8:
        $add_pdf_errors['pdf'] = 'The file could not be uploaded due to a system error.';
        break;
      case 4:
      default:
        $add_pdf_errors['pdf'] = "No file was uploaded.";
        break;
    } //  End of SWITCH.
  }   //  End of $_FILES IF-ELSEIF-ELSE.

  if (empty($add_pdf_errors)) { //  If everything's OK.
    //  $fn = mysqli_real_escape_string($dbc, $_SESSION['pdf']['file_name']);
    $fn = $_SESSION['pdf']['file_name'];
    //  $tmp_name = mysqli_real_escape_string($dbc, $_SESSION['pdf']['tmp_name']);
    $tmp_name = $_SESSION['pdf']['tmp_name'];
    $size = (int) $_SESSION['pdf']['size'];
    /* 
    $q = "INSERT INTO pdfs (tmp_name, title, description, file_name, size)
      VALUES ('$tmp_name', '$t', '$d', '$fn', $size)";
    $r = mysqli_query($dbc, $q);
 */
    $sql = "INSERT INTO pdfs (tmp_name, title, description, file_name, size) 
      VALUES (:tmp_name, :title, :description, :file_name, :size)";
    $tmnt = $pdo->prepare($sql);
    $tmnt->execute(array(
      ':tmp_name' => $tmp_name,
      ':title' => $t,
      ':description' => $d,
      ':file_name' => $fn,
      ':size' => $size
    ));

    //  if (mysqli_affected_rows($dbc) == 1) {  //  If it ran OK.
    if ($tmnt->rowCount() == 1) {
      $original = PDFS_DIR . $_SESSION['pdf']['tmp_name'] . '_tmp';
      $dest = PDFS_DIR . $_SESSION['pdf']['tmp_name'];
      rename($original, $dest);

      echo '<h4>The PDF has been added!</h4>';
      $_POST = array();
      $_FILES = array();
      unset($file, $_SESSION['pdf']);
    } else {  //  If it did not run OK.
      trigger_error('The PDF could not be dded due to a system error.
        We apologize for any inconvenience.');
      unlink($dest);
    }
  } //  End of $errors IF.
} else {  //  Clear out the session on a GET request:
  unset($_SESSION['pdf']);
} //  End of the submission IF.

require('includes/form_functions.inc.php');
?>
<h3>Add a PDF</h3>
<form enctype="multipart/form-data" action="add_pdf.php" method="post" accept-charset="utf-8">
  <input type="hidden" name="MAX_FILE_SIZE" value="1048576" />
  <fieldset>
    <legend>Fill out the form to dd a PDF to the site:</legend>
    <p><label for="title"><strong>Title</strong></label><br />
      <?php create_form_input('title', 'text', $add_pdf_errors); ?></p>
    <p><label for="description"><strong>Description</strong>
      </label><br /><?php create_form_input('description', 'textarea', $add_pdf_errors); ?>
    </p>

    <p><label for="pdf"><strong>PDF</strong></label><br />
      <?php
      echo '<input type="file" name="pdf" id="pdf"';

      if (array_key_exists('pdf', $add_pdf_errors)) {
        echo ' class="error" /><span class="error">' . $add_pdf_errors['pdf'] . '</span>';
      } else {  //  No error.
        echo ' />';

        if (isset($_SESSION['pdf'])) {
          echo "Currently '{$_SESSION['pdf']['file_name']}'";
        }
      } //  end of errors IF-ELSE.
      ?><small>PDF only,1MB Limit</small></p>
    <p><input type="submit" name="submit_button" value="Add this PDf" id="submit_button" class="formbutton" />
    </p>
  </fieldset>
</form>
<?php
include('./includes/footer.html');
?>