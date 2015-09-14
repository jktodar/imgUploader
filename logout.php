<?php
require_once 'header.php';
require_once 'Connector.php';

session_start();
unset($_SESSION['username']);
$redirect_page = 'index.php';
header('Location: '.$redirect_page);

?>
