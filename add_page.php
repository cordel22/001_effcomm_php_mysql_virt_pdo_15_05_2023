<?php

//  to test the logged in
/* $_SESSION['user_id'] = 1; */

//  logged in admin
// $_SESSION['user_type'] = 'admin';
//  $_SESSION[$check] = 'user_admin';


require_once('./includes/config.inc.php');
//  debug
//  require_once('./includes/form_functions.inc.php');

//  redirect_invalid_user(/* 'user_admin' */);  //  defined in form_functions.inc.php
//  debug


$page_title = 'Add a Site Content Page';
include('./includes/header.html');

require(MYSQL);

$add_page_errors = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (!empty($_POST['title'])) {
    //  $t = mysqli_real_escape_string($dbc, strip_tags($_POST['title']));
    $t = strip_tags($_POST['title']);
  } else {
    $add_page_errors['title'] = 'Please enter the title!';
  }
  //  if(isset($_POST['category']) && ((int)$_POST['category']>=1)) { //  akoze ak tam neni filter
  if (filter_var($_POST['category'], FILTER_VALIDATE_INT, array('min_range' => 1))) {
    $cat = $_POST['category'];
  } else {
    $add_page_errors['category'] = 'Please select a category!';
  }
  if (!empty($_POST['description'])) {
    //  $d = mysqli_real_escape_string($dbc, strip_tags($_POST['description']));
    $d = strip_tags($_POST['description']);
  } else {
    $add_page_errors['description'] = 'Please enter a description!';
  }

  if (!empty($_POST['content'])) {
    $allowed = '<div><p><span><br><a><img><h1><h2><h3><h4><ul><ol><li><blockquote>';
    //  $c = mysqli_real_escape_string($dbc, strip_tags($_POST['content'], $allowed));
    $c = strip_tags($_POST['content'], $allowed);
  } else {
    $add_page_errors['content'] = 'Please enter the content!';
  }

  if (empty($add_page_errors)) {  //  If everything's OK.
    /* 
    $q = "INSERT INTO pages (category_id, title, description, content) VALUES ($cat, '$t', '$d', '$c')";
    $r = mysqli_query($dbc, $q);
 */
    $sql = "INSERT INTO pages (category_id, title, description, content) 
              VALUES (:category_id, :title, :description, :content)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
      ':category_id' => $cat,
      ':title' => $t,
      ':description' => $d,
      ':content' => $c
    ));

    //  if (mysqli_affected_rows($dbc) == 1) {  //  If it ran OK.
    if ($tmnt->rowCount() == 1) {
      echo '<h4>The page has been added!</h4>';
      $_POST = array();
    } else {  //  If it did not run OK.
      trigger_error('The page could not be added due to a system error.
      We apologize for any inconvenience.');
    }
  } //  End od $add_page_errors IF.
} //  End of the main form submission conditional.

require('includes/form_functions.inc.php');
?>

<h3>Add a Site Content Page</h3>
<form action="add_page.php" method="post" accept-charset="utf-8">
  <fieldset>
    <legend>Fill out the form to add a page of content:</legend>
    <p><label for="first_name"><strong>Title</strong>
      </label><br /><?php create_form_input('title', 'text', $add_page_errors); ?>
    </p>
    <p><label for="category"><strong>Category</strong></label><br />
      <select name="category" <?php if (array_key_exists(
                                'category',
                                $add_page_errors
                              )) echo ' class="error"'; ?>>
        <option>Select One</option>
        <?php //  Retrieve ll the categories and add to the pull-down menu:
        /* 
        $q = "SELECT id, category FROM categories ORDER BY category ASC";
        $r = mysqli_query($dbc, $q);
 */
        $q = "SELECT id, category FROM categories ORDER BY category ASC";
        $tmnt = $pdo->query($q);

        //  while ($row = mysqli_fetch_array($r, MYSQLI_NUM)) {
        while ($row = $tmnt->fetch(PDO::FETCH_NUM)) {
          echo "<option value=\"$row[0]\"";
          //  Check for stickyness:
          if (isset($_POST['category']) && ($_POST['category'] == $row[0]))
            echo ' selected="selected"';
          echo ">$row[1]</option>\n";
        }
        ?>
      </select>
      <?php
      if (array_key_exists('category', $add_page_errors))
        echo ' <span class="error">' . $add_page_errors['category'] .
          '</span>';
      ?>
    </p>
    <p>
      <label for="description"><strong>Description</strong></label>
      <br />
      <?php
      create_form_input('description', 'textarea', $add_page_errors)
      ?>
    </p>
    <p>
      <label for="content"><strong>Content</strong></label>
      <br />
      <?php
      create_form_input('content', 'textarea', $add_page_errors)
      ?>
    </p>
    <p>
      <input type="submit" name="submit_button" value="Add This Page" id="submit_button" class="formbutton" />
    </p>
  </fieldset>
</form>

<script type="text/javascript" src="/tiny_mce/tiny_mce.js"></script>

<script type="text/javascript">
  tinyMCE.init({
    //  generl options
    mode: "exact",
    elements: "content",
    theme: "advanced",
    width: 800,
    height: 400,
    //  pipe or I nizsie..?
    plugins: "advlink,advlist,autoresize,autosave,contextmenu,fullscreen,iespell, inlinepopups,media,paste,preview,safari,searchreplace,visualchars,wordcount,xhtmlxtras",
    theme_advanced_buttons1: "cut,copy,paste,pastetext,pasteword,|,undo,redo,removeformat,|,search,replace,|,cleanup,help,code,preview,visualaid,fullscreen",
    theme_advanced_buttons2: "bold,italic,underline,strikethrough,|,justifyleft,|,justifycenter,justifyright,justifyfull,|,formatselect,|,bullist,numlist,|,outdent,indent,blockquote,|,sub,sup,cite,abbr",
    theme_advanced_buttons3: "hr,|,link,unlink,anchor,image,|,charmap,emotions,iespell,media",

    theme_advanced_toolbar_location: "top",
    theme_advanced_toolbar_align: "left",
    theme_advanced_statusbar_location: "bottom",
    theme_advanced_resizing: true,

    content_css: "/css/style.css",
  });
</script>
<?php
include('./includes/footer.html');
?>