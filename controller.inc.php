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
			$this->api->show_get_command_text();
			$input = $this->api->get_command();
			if ($input == "exit")
				$loop = false;
			elseif ($input == "show_table")
			{
				$this->api->print_output("-> Show Table:\n");
				$this->api->show_table();
				$this->api->print_output("\n");
			}
			elseif ($input == "new_entry")
			{
				$this->api->print_output("-> New Entry:\n");
				$this->api->new_entry();
				$this->api->print_output("\n");
			}
			elseif ($input == "search")
			{
				$this->api->print_output("-> Search:\n");
				$this->api->search();
				$this->api->print_output("\n");
			}
			elseif ($input == "show_entry")
			{
				$this->api->print_output("-> Show Entry:\n");
				$this->api->show_detail();
				$this->api->print_output("\n");
			}
			elseif ($input == "delete_entry")
			{
				$this->api->print_output("-> Delete Entry:\n");
				$this->api->delete_entry();
				$this->api->print_output("\n");
			}
			else
				$this->api->print_output("Invalid selecion: ".$input."\n");	
		}
	}
}

?>

