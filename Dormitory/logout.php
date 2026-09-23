<?php 
session_start();
$_SESSION = array();


if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 42000, '/');
}
session_destroy();

header("Cache-Control: no-cache, no-store, must-revalidate"); 
header("Pragma: no-no-cache"); 
header("Expires: 0"); 

header("Location: index.php");
exit();
?>