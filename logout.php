<?php
require_once 'config.php';
require_once 'auth.php';

session_destroy();
redirect('login.php');
?>