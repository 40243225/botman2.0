<?php
namespace BotMan\BotMan\MySQL;
use Mysqli;

class connetdb{
	function SQL()
	{	
		$DBNAME = "h_f";
		$DBUSER = "root";
		$DBPASSWD = "00000000";
		$DBHOST = "localhost";
		$mysqli = new Mysqli($DBHOST, $DBUSER, $DBPASSWD, $DBNAME);
			if ($mysqli->connect_errno) {
		    	printf("Connect failed: %s\n", $mysqli->connect_error);
		   	 exit();
		}
	return $mysqli;
	}
}

