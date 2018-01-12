<?php
namespace BotMan\BotMan\H_F_Command;

use BotMan\BotMan\MySQL\connetdb;
use BotMan\BotMan\H_F_Command\RegisterConversation;
use BotMan\BotMan\H_F_Command\LinkEInVoice;
use BotMan\BotMan\H_F_Command\GetPost;
class MyBotCommands {

    public function handleFoo($bot) {
        $bot->reply('Hello World');
    }
    public function LinkInvoice($bot)
    {
        $db=new connetdb();
        $mysql=$db->SQL();
        $user = $bot->getUser();
        $id=$user->getId();
        $sql  = "SELECT count(MemberID) FROM `e_invoice_carrier` WHERE `MemberID` like '$id'";
        $result = $mysql->query($sql);
        $row = $result->fetch_array(MYSQLI_NUM);
        $result->free();
        $mysql->close();
        if($row[0]>0)
            $bot->reply('親，您已經連結過了唷~~');
        else
        {
            $bot->reply($user->getFirstName(). "你好~~" .'開始連結載具');
         $bot->startConversation(new LinkEInVoice());
        }
    }
    public function getdata($bot)
    {
        $bot->startConversation(new GetPost());
    }
    public function AQI($bot)
    {
        $bot->startConversation(new AQI_Query());
    }
    public function register($bot)
    {
    	$db=new connetdb();
		$mysql=$db->SQL();
		$user = $bot->getUser();
        $id=$user->getId();
		$sql  = "SELECT count(ID) FROM `memeber_info` WHERE `ID` like '$id'";
		$result = $mysql->query($sql);
		$row = $result->fetch_array(MYSQLI_NUM);
        $result->free();
        $mysql->close();
		if($row[0]>0)
    		$bot->reply('親，您已經註冊過了唷~~');
    	else
    	{
    		$bot->reply($user->getFirstName().'開始註冊');
            $bot->startConversation(new RegisterConversation());
    	}
        
    }
}
