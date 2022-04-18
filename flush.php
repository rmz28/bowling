<?php
session_start();
unset($_SESSION['scores']);

header('Location: index.php');