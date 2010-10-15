<?php

class Template {
	function __construct ()
	{
		$this->head = file_get_contents("./templ/head.tpl");
		$this->body = "";
		$this->nav = ""; //file_get_contents("./templ/nav.tpl");
		$this->foot = file_get_contents("./templ/foot.tpl");
	}

	function load($template)
	{
		$this->body .= file_get_contents("./templ/".$template.".tpl");
	}
	
	function add($content)
	{
		$this->body .= $content;
	}
	
	function replace($search, $replace)
	{
		$this->head = str_replace("MY".$search."REPLACE", $replace, $this->head);
		$this->nav = str_replace("MY".$search."REPLACE", $replace, $this->nav);
		$this->body = str_replace("MY".$search."REPLACE", $replace, $this->body);
		$this->foot = str_replace("MY".$search."REPLACE", $replace, $this->foot);
	}
	
	function write()
	{
		echo $this->head;
		echo $this->nav;
		echo $this->body;
		echo $this->foot;
	}
}

?>