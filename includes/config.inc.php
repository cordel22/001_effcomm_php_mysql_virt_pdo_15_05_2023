<?php
$live = false;
$contact_email = 'cordelfenevall@gmail.com';


//  ddapt the ollowing configuration acording to real loction oftthe files

//  define('BASE_URI', 'C:\Users\Sisi\Desktop\akcia_14_07_21\php-14-07-21\effcomm_php_mysql\001_effcomm_php_mysql_virt_pdo'/* '/path/to/Web/parent/folder/' */);
//  define('BASE_URI', 'C:\wamp\www\001_effcomm_php_mysql_virt_pdo');
define('BASE_URI', 'C:\wamp\www\001_effcomm_php_mysql_virt_pdo_15_05_2023');

//  define('BASE_URL', 'http://localhost:3000/'/* 'www.example.com/' */);
define('BASE_URL', 'http://localhost:3000/'/*'localhost:3000/' 'www.example.com/' */);

//  define('MYSQL', 'C:\Users\Sisi\Desktop\akcia_14_07_21\php-14-07-21\effcomm_php_mysql\001_effcomm_php_mysql_virt_pdo\pdo.php'/* '/path/to/mysql.inc.php' */);
//  define('MYSQL', 'C:\wamp\www\001_effcomm_php_mysql_virt_pdo\pdo.php');
define('MYSQL', 'C:\wamp\www\001_effcomm_php_mysql_virt_pdo_15_05_2023\pdo.php');
define('PDFS_DIR', BASE_URI . '/includes/pdfs/');   //  where is the PDFS_DIR..?

session_start();

function my_error_handler($e_number = null, $e_message = null, $e_file = null, $e_line = null, $e_vars = null)
{
  global $live, $contact_email;


  $message = "An error occured in script '$e_file' on line $e_line: \n$e_message\n";

  $message .= "<pre>" . print_r(debug_backtrace(), 1) . "</pre>\n";

  $message .= "<pre>" . print_r($e_vars, 1) . "</pre>\n";

  if (!$live) {
    echo '<div class="error">' . nl2br($message) . '</div>';
  } else {
    error_log($message, 1, $contact_email, 'From:cordelfenevall@gmail.com');


    if ($e_number != E_NOTICE) {
      echo '<div class="error">A system error occurred. 
        We apologize for the inconvenience.</div>';
    }
  } //  End of $live IF-ELSE.
  return true;
} //  End of my_error_handler() definition.

set_error_handler('my_error_handler');

function redirect_invalid_user($check = 'user_id', $destination = 'index.php', $protocol = 'http://')
{
  if (!isset($session[$check])) {
    $url = $protocol . BASE_URL . $destination;
    header("Location: $url");
    exit();
  }
}
