<?php
session_start();

include_once('Calculator.php');

$calculator = new Calculator();
if(isset($_SESSION['scores'])){
    $calculator->loadScores($_SESSION['scores']);
}

if (!empty($_POST)){
	$calculator->setCurrentFrameData($_POST);
}

$_SESSION['scores'] = $calculator->calculateScores();

include_once('form.php');