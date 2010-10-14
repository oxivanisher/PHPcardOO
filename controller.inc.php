<?php

class Controller {
	protected $api;
	protected $db;
	
	function __construct ($db)
	{
		$this->db = $db;	
	
		#cli
		if (defined('STDIN'))
		{
			$this->api = new APIcli($db);
		}
		if (empty($this->api))
			die("Not supported api.");
	}

	function loop ()
	{
		$loop = true;
		while ($loop)
		{ 
			$input = $this->api->get_command();
			if ($input == "exit")
				$loop = false;
			elseif ($input == "show_table")
			{
				$this->api->show_table();
			}
			elseif ($input == "new_entry")
			{
				$this->api->new_entry();
			}
			elseif ($input == "search")
			{
				$this->api->search();
			}
			elseif ($input == "show_entry")
			{
				$this->api->show_detail();
			}
			elseif ($input == "edit_entry")
			{
				$this->api->edit_entry();
			}
			elseif ($input == "delete_entry")
			{
				$this->api->delete_entry();
			}
			else
				$this->api->print_error("Invalid selecion: ".$input."\n");	
		}
	}
}

?>