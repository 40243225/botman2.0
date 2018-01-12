<?php
namespace BotMan\BotMan\H_F_Command;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\MySQL\connetdb;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;

class LinkEInVoice extends Conversation
{
    protected $No;
    protected $api;
    protected $password;

    protected $e_api="https://api.einvoice.nat.gov.tw/";
    protected $startDate="2017/11/01";
    protected $endDate="2017/12/31";
    protected $appID="EINV2201711304894";
    public function askCardNo()
    {
        $this->ask('你的手機條碼為?? (Example:/FAECZQK)', function(Answer $answer) {
            // Save result
            $this->No = $answer->getText();

            $this->checkNo();
        });
    }
    public function checkNo()
    {
        $question = Question::create('手機條碼確認為:'.$this->No.'?')
        ->fallback('註冊失敗!')
        ->callbackId('askNo')
        ->addButtons([
            Button::create('是')->value('yes'),
            Button::create('重新輸入')->value('no'),
        ]);

        $this->ask($question, function (Answer $answer) {
            // Detect if button was clicked:
            if ($answer->isInteractiveMessageReply()) {
                $selectedValue = $answer->getValue(); // will be either 'yes' or 'no'
                if(!strcmp($selectedValue,'yes'))
                    $this->askPassword();
                else
                {
                    $this->say("請重新輸入");
                    $this->askNo();
                }
            }
            else
            {
                $this->say("請案是或否");
                $this->checkNo();
            }
        });

    }
    public function askPassword()
    {
        $this->ask('驗證碼(密碼)為??', function(Answer $answer) {
            // Save result
            $this->password = $answer->getText();

            $this->checkPassword();
        });
    }
    public function checkPassword()
    {
        $this->ask('請再輸入一次驗證碼(密碼)', function(Answer $answer) {
            // Save result
            $checkpass=$answer->getText();
            if(!strcmp($checkpass,$this->password))
                $this->Verfication();
            else
            {
                 $this->say("驗證碼(密碼)輸入不一致，請重新輸入一次");
                 $this->askPassword();
            }
        });
    }
    public function Verfication()
    {
        $this->say("驗證中...");
        $this->api=$this->e_api."/PB2CAPIVAN/invServ/InvServ?version=0.3&cardType=3J0002&cardNo=".$this->No."&expTimeStamp=2147483647&action=carrierInvChk&timeStamp=2147483647&startDate=".$this->startDate."&endDate=".$this->endDate."&onlyWinningInv=N&uuid=". $this->getUserID() ."&appID=".$this->appID."&cardEncrypt=".$this->password;
        $handle = fopen((string)$this->api,"rb");
        while (!feof($handle)) 
        {
            $content.= fread($handle, 100000);
        }
        fclose($handle);
        $data=json_decode($content);
        if( $data->{'code'}==200)
          $this->SaveToSQL();
       else
          $this->say("驗證失敗，可能輸入的資料有誤唷");
    }
    public function SaveToSQL()
    {
        $this->say("驗證成功!!");
        $id=$this->getUserID();
        $db=new connetdb();
        $mysql=$db->SQL();
        $sql  = "INSERT INTO `e_invoice_carrier` (`MemberID`, `cardNo`, `cardEncrypt`) VALUES ('$id', '$this->No', '$this->password')";
        if($mysql->query($sql))
            $this->say($this->name."恭喜您連結成功~~");
        else
            $this->say("資料庫錯誤");
    }

    public function run()
    {
        // This will be called immediately
        $this->askCardNo();
    }
}

