<?php
namespace BotMan\BotMan\H_F_Command;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\MySQL\connetdb;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;


class GetPost extends Conversation
{    
    public function checkNo()
    {
        $question = Question::create('載入資料要花點時間，確認要載入?')
        ->fallback('失敗!')
        ->callbackId('askNo')
        ->addButtons([
            Button::create('是')->value('yes'),
            Button::create('算了')->value('no'),
        ]);

        $this->ask($question, function (Answer $answer) {
            // Detect if button was clicked:
            if ($answer->isInteractiveMessageReply()) {
                $selectedValue = $answer->getValue(); // will be either 'yes' or 'no'
                if(!strcmp($selectedValue,'yes'))
                {
                    $this->say("載入資料中...完成後會回傳訊息");
                    $this->StartGetPost();
                }
                else
                {
                    $this->say("好吧!");
                }
            }
            else
            {
                $this->say("請案是或否");
                $this->checkNo();
            }
        });

    }
    public function StartGetPost()
    {
        $url = 'http://140.130.35.73:80/H_F/action/FB_action/GetUserData.php?id='.$this->getUserID();
        preg_match('/^(.+:\/\/)([^:\/]+):?(\d*)(\/.+)/', $url, $matches);
        $protocol = $matches[1];
        $host = $matches[2];
        $port = $matches[3];
        $uri = $matches[4];
        echo $protocol."<br>";
        echo $host."<br>";
        echo $port."<br>";
        echo $uri."<br>";
        $fp = fsockopen($host, $port, $errno, $errstr,5); 
        if ($fp) {
            // 設定 header 與 body
            $httpHeadStr  = "POST {$url} HTTP/1.1\r\n";
            $httpHeadStr .= "Content-type: application/xml\r\n";
            $httpHeadStr .= "Host: {$host}:{$port}\r\n";
            $httpHeadStr .= "Content-Length: ".strlen($xml)."\r\n";
            $httpHeadStr .= "Connection: close\r\n";
            $httpHeadStr .= "\r\n";
            $httpBody = $xml."\r\n";
         
            // 呼叫 WebService
            fputs($fp, $httpHeadStr.$httpBody);
            fclose($fp);
        } else {
            die('Error:'.$errno.$errstr);
        }
    }
    public function run()
    {
        // This will be called immediately
        $this->checkNo();
    }
}

