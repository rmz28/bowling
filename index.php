<?php
session_start();

include_once('Calculator.php');
//todo remove
//$_SESSION['scores'] = [];

$calculator = new Calculator();
if(isset($_SESSION['scores'])){
    $calculator->setScores($_SESSION['scores']);
}

if (!empty($_POST)){
	$calculator->setCurrentFrameData($_POST);
}

$_SESSION['scores'] = $calculator->getScores();

include_once('form.php');