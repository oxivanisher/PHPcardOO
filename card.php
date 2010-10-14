#!/usr/bin/php
<?php

include("db.inc.php");
include("card.inc.php");
include("apicli.inc.php");
include("controller.inc.php");

function main()
{
  $db = new DB("card.db");

	$controller = new Controller($db);
	$controller->loop();
	/* 
		$card = new Card($db);
		$card->name = "Simon";
		$card->surname = "Kallweit";
		$card->show();
		$id = $card->create();

		$card = new Card($db);
		$card->load_by_id($id);
		$card->show();
  	$card2 = new Card($db);
  	$card2->show();
	*/	
}

main();

?>