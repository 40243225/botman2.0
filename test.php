<?php
require_once 'BassicSeetingHearing.php';
$id=$_GET['id'];
$text=$_GET['text'];
$botstart= new BassicSeetingHearing;
$botman=$botstart->setting();
if($text==1)
{
	$botstart->Sending($botman,"請點選以下網址來重新驗證!!",$id);
	$botstart->Sending($botman,'http://cloudsoftwarelab404.info/H_F/action/FB_action/ReverifyAccessToken.php?id='.$id."_60132_Verify",$id);
}
else
{
	$botstart->Sending($botman,"載入成功!!",$id);
	
}


//$botstart->hears($botman);


