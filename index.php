<?php
require_once 'BassicSeetingHearing.php';

$botstart= new BassicSeetingHearing;
$botman=$botstart->setting();
if(isset($_GET['layout']))
	$botstart->layout();
$botstart->hears($botman);


