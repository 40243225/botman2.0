<?php
require __DIR__ . '/vendor/autoload.php';
use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
use BotMan\Drivers\Facebook\FacebookDriver;
use BotMan\Drivers\Facebook\Extensions\ButtonTemplate;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\MySQL\connetdb;
use BotMan\BotMan\Cache\DoctrineCache;
use Doctrine\Common\Cache\FilesystemCache;
use BotMan\Drivers\Facebook\Extensions\ElementButton;
use BotMan\Drivers\Facebook\Extensions\GenericTemplate;
use BotMan\Drivers\Facebook\Extensions\Element;
use BotMan\Drivers\Facebook\Extensions\ListTemplate;
use BotMan\BotMan\Messages\Attachments\Image;
use BotMan\BotMan\Messages\Outgoing\OutgoingMessage;
use BotMan\BotMan\Messages\Attachments\Video;
class BassicSeetingHearing{
	protected $config;
	protected $doctrineCacheDriver;
	protected $botman;
	protected $token='EAAB6WNYRR9IBABp8CRpl3m2hZCQa9jmnekBp316joHKUk4p3hd1wr71zlJQqJMpZCzrg6OWZCLQWiBvM78l1DFi4DVEoz669huCa1wWQQZA5TqTeSL3ZCuRXxQRltLYmiuxUtnFCTDiZBkYRpICntt8CFl4wvjxjnNcU6dtTV1klfz662ZBV7R9';
	public function setting(){
		$config = [
		    'facebook' => [
		  	'token' => $this->token,
			'app_secret' => '900f15662dd99bc1e3e5ee61036a1963',
		    'verification'=>'H_F_TOKEN',	    
		]
		];
		$doctrineCacheDriver = new FilesystemCache(__DIR__);
		DriverManager::loadDriver(FacebookDriver::class);
		$botman = BotManFactory::create($config, new DoctrineCache($doctrineCacheDriver));
		//$botman->listen();
		//echo 'success';
		return $botman;
		
	}
	public function layout()
	{
		$url="https://graph.facebook.com/v2.6/me/messenger_profile?access_token=".$this->token;
		$ch = curl_init();
		$json=file_get_contents("./messenger.json");
		$payload=json_decode($json);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true); // 啟用POST
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payload)); 
		curl_exec($ch); 
		curl_close($ch);
	}

	public function hears($botman){
		$botman->hears('hello', function (BotMan $bot) {
		$user = $bot->getUser();
		$name=$user->getFirstName().$user->getLastName();
		$id=$user->getId();
		$bot->reply('hi');
		});


		$botman->hears('firstToUse', function (BotMan $bot) {
		$user = $bot->getUser();
		$name=$user->getLastName().$user->getFirstName();
		$id=$user->getId();
		$bot->reply('你好'.$name."，歡迎使用");
		$bot->reply(ButtonTemplate::create('您可以做以下操作')
			->addButton(ElementButton::create('打招呼')->type('postback')->payload('hello'))
			->addButton(ElementButton::create('空氣指標查詢')->type('postback')->payload('AQI'))
			->addButton(ElementButton::create('推薦新聞')->type('postback')->payload('@推薦新聞'))
			);	
		});
		$botman->hears('@推薦新聞',function(BotMan $bot)
		{
			$bot->reply(ListTemplate::create()
			->useCompactView()
			->addGlobalButton(ElementButton::create('view more')->url('http://test.at'))
			->addElement(
				Element::create('新年換新殼 手機周邊買氣 增3成')
					->subtitle('詳全文:新年換新殼 手機周邊買氣 增3成')
					->image('https://images.gamme.com.tw/news2/2015/29/63/p56ZoqOalaWY.png')
					->addButton(ElementButton::create('觀看全文')
					->url('http://www.appledaily.com.tw/appledaily/article/supplement/20171230/37888411/'))
			)
			->addElement(
				Element::create('中南部今擴散條件仍差　高屏地區空品將達「紅害」')
					->subtitle('詳全文：中南部今擴散條件仍差　高屏地區空品將達「紅害」')
					->image('https://images.gamme.com.tw/news2/2015/29/63/p56ZoqOalaWY.png')
					->addButton(ElementButton::create('觀看全文')
						->url('http://www.appledaily.com.tw/realtimenews/article/new/20171230/1268911/')
					)
			)
		);
		});
		$botman->hears('IU',function(BotMan $bot)
		{
			$bot->reply("IU來囉^_^");
			$json=file_get_contents("C:\AppServ\www\IU\IU.json");
		    $url="https://www.youtube.com/watch?v=cHbNaFNoHCY&list=PLvIVEcmVYIMek_72JHqY4K4zJbh_EIFkg";
			$namevideo=array();
			$video=array();
			$p_data=json_decode($json);
			$youtube="https://www.youtube.com/watch?v=";
			$list="&list=PLvIVEcmVYIMek_72JHqY4K4zJbh_EIFkg";
			foreach ($p_data as $key => $value) {
				array_push($video, $youtube.$value->url);
				array_push($namevideo, $value->name);
			}
			$IU1=rand(0,8);
			$IU2=rand(9,18);
			$IU3=rand(18,26);
			$bot->reply(ButtonTemplate::create('好聽的三首歌^_^')
				->addButton(ElementButton::create($namevideo[$IU1])->url($video[$IU1]))
				->addButton(ElementButton::create($namevideo[$IU2])->url($video[$IU2]))
				->addButton(ElementButton::create($namevideo[$IU3])->url($video[$IU3]))
			);
			$IU=rand(1,308);
			$attachment = new Image('http://140.130.35.73/IU/IU%20('.$IU.').jpg');
		    // Build message object
		    $message = OutgoingMessage::create('This is my text')->withAttachment($attachment);
		    // Reply message object
		   	$bot->reply($message);	
		   	$IU=rand(1,308);
			$attachment = new Image('http://140.130.35.73/IU/IU%20('.$IU.').jpg');
		    // Build message object
		    $message = OutgoingMessage::create('This is my text')->withAttachment($attachment);
		    // Reply message object
		   	$bot->reply($message);
		   	$IU=rand(1,308);
			$attachment = new Image('http://140.130.35.73/IU/IU%20('.$IU.').jpg');
		    // Build message object
		    $message = OutgoingMessage::create('This is my text')->withAttachment($attachment);
		    // Reply message object
		   	$bot->reply($message);    
		});
		$botman->hears('bfgakki',function(BotMan $bot)
		{
			$json=file_get_contents("http://140.130.35.73/gakki/2.json");
			$namevideo=array();
			$video=array();
			$p_data=json_decode($json);
			foreach ($p_data as $key => $value) {
				array_push($video, $value->url);
				array_push($namevideo, $value->name);
			}
			$IU1=rand(0,3);
			$IU2=rand(4,7);
			$IU3=rand(8,11);
			$bot->reply(ButtonTemplate::create('老婆來惹^_^')
				->addButton(ElementButton::create($namevideo[$IU1])->url($video[$IU1]))
				->addButton(ElementButton::create($namevideo[$IU2])->url($video[$IU2]))
				->addButton(ElementButton::create($namevideo[$IU3])->url($video[$IU3]))
			);  
		});
		$botman->hears('adgakki',function(BotMan $bot)
		{
			$json=file_get_contents("http://140.130.35.73/gakki/1.json");
			$namevideo=array();
			$video=array();
			$p_data=json_decode($json);
			foreach ($p_data as $key => $value) {
				array_push($video, $value->url);
				array_push($namevideo, $value->name);
			}
			$IU1=rand(0,5);
			$IU2=rand(6,10);
			$IU3=rand(10,15);
			$bot->reply(ButtonTemplate::create('史上最棒的廣告^_^')
				->addButton(ElementButton::create($namevideo[$IU1])->url($video[$IU1]))
				->addButton(ElementButton::create($namevideo[$IU2])->url($video[$IU2]))
				->addButton(ElementButton::create($namevideo[$IU3])->url($video[$IU3]))
			);  
		});
		$botman->hears('gifgakki',function(BotMan $bot)
		{
			$bot->reply("結衣馬上來^_^請等等...");
			$gakki=rand(1,15);
			$attachment = new Image('http://140.130.35.73/gakki/gif/gif%20('.$gakki.').gif');
		    // Build message object
		    $message = OutgoingMessage::create('This is my text')->withAttachment($attachment);
		    // Reply message object
		   	$bot->reply($message);	
		   	$gakki=rand(15,29);
			$attachment = new Image('http://140.130.35.73/gakki/gif/gif%20('.$gakki.').gif');
		    // Build message object
		    $message = OutgoingMessage::create('This is my text')->withAttachment($attachment);
		    // Reply message object
		   	$bot->reply($message);
		});
		$botman->hears('JPGgakki',function(BotMan $bot)
		{
			$bot->reply("結衣馬上來^_^請等等...");
			$gakki=rand(1,99);
			$attachment = new Image('http://140.130.35.73/gakki/gakki/gakki%20('.$gakki.').jpg');
		    // Build message object
		    $message = OutgoingMessage::create('This is my text')->withAttachment($attachment);
		    // Reply message object
		   	$bot->reply($message);	
		   	$gakki=rand(100,189);
			$attachment = new Image('http://140.130.35.73/gakki/gakki/gakki%20('.$gakki.').jpg');
		    // Build message object
		    $message = OutgoingMessage::create('This is my text')->withAttachment($attachment);
		    // Reply message object
		   	$bot->reply($message);
		   	$gakki=rand(189,289);
			$attachment = new Image('http://140.130.35.73/gakki/gakki/gakki%20('.$gakki.').jpg');
		    // Build message object
		    $message = OutgoingMessage::create('This is my text')->withAttachment($attachment);
		    // Reply message object
		   	$bot->reply($message);
		});

		//註冊帳號
		$botman->hears('r','BotMan\BotMan\H_F_Command\Register@register');
		//取得資料
		$botman->hears('g','BotMan\BotMan\H_F_Command\MyBotCommands@getdata');
		//空氣指標AQI
		$botman->hears('AQI','BotMan\BotMan\H_F_Command\MyBotCommands@AQI');
		//連結電子發票
		//$botman->hears('連結電子發票','BotMan\BotMan\H_F_Command\MyBotCommands@LinkInvoice');

		//fall back
		// start listening
		$botman->listen();
	}
	public function Sending($botman,$msg,$id)
	{
		$botman->say($msg, $id, FacebookDriver::class);	
	}
}


