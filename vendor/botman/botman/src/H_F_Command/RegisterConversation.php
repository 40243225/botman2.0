<?php
namespace BotMan\BotMan\H_F_Command;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\MySQL\connetdb;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;

class RegisterConversation extends Conversation
{
    protected $name;

    protected $gender;

    public function askName()
    {
        $this->ask('你的名字是?', function(Answer $answer) {
            // Save result
            $this->name = $answer->getText();

            $this->checkname();
        });
    }

    public function checkname()
    {
       $question = Question::create('名稱確認為:'.$this->name.'?')
        ->fallback('註冊失敗!')
        ->callbackId('askname')
        ->addButtons([
            Button::create('是')->value('yes'),
            Button::create('重新輸入')->value('no'),
        ]);

        $this->ask($question, function (Answer $answer) {
            // Detect if button was clicked:
            if ($answer->isInteractiveMessageReply()) {
                $selectedValue = $answer->getValue(); // will be either 'yes' or 'no'
                if(!strcmp($selectedValue,'yes'))
                    $this->askgender();
                else
                {
                    $this->say("重新輸入名稱");
                    $this->askName();
                }
            }
            else
            {
                $this->checkname();
            }
        });
    }
    public function askgender()
    {
        $question = Question::create('你的性別為是?')
        ->fallback('註冊失敗!')
        ->callbackId('askgender')
        ->addButtons([
            Button::create('男生')->value('1'),
            Button::create('女生')->value('2'),
            Button::create('不告訴你^^')->value('3'),
        ]);

        $this->ask($question, function (Answer $answer) {
            // Detect if button was clicked:
            if ($answer->isInteractiveMessageReply()) {
                $this->gender = $answer->getValue(); // will be either 'yes' or 'no'
                $this->SaveToSQL();
            }
            else
            {
                $this->say("請選擇一個性別!");
                $this->askgender();
            }
        });
    }
    public function SaveToSQL()
    {
        $id=$this->getUserID();
        $db=new connetdb();
        $mysql=$db->SQL();
        $sql  = "INSERT INTO `memeber_info` (`ID`, `Name`, `gender`) VALUES ('$id', '$this->name', '$this->gender')";
        if($mysql->query($sql))
            $this->say($this->name."恭喜您註冊成功~~");
        else
            $this->say("資料庫錯誤");
    }

    
    public function run()
    {
        // This will be called immediately
        $this->askName();
    }
}

