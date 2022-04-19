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

$_SESSION['scores'] = json_decode(json_encode($calculator->calculateScores()), true);

include_once('form.php');