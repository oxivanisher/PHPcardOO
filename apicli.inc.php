<?php

class APIcli {
	private $command;
	protected $db;

	function __construct($db)
  {
  	$this->db = $db;
  }
  
	function get_command()
	{
		$command = $this->read_input();
		switch ($command)
		{
			case "1":
				return "show_table";
			break;

			case "2":
				return "new_entry";
			break;

			case "3":
				return "search";
			break;

			case "4":
				return "show_entry";
			break;

			case "8":
				return "delete_entry";
			break;

			case "9":
				return "exit";
			break;
			
			default:
				return $command;
		}
	}

	function read_input()
	{
		return trim(fgets(fopen("php://stdin","r")));
	}
	
	function show_get_command_text ()
	{
		echo "1\tShow Table\n";
		echo "2\tNew Entry\n";
		echo "3\tSearch\n";
		echo "4\tShow Entry\n";
		echo "8\tDelete Entry\n";
		echo "9\tExit\n";
		echo "Your selection: ";
	}
	
	function print_output($text)
	{
		echo $text;
	}
	
	function search()
	{
		$this->print_output("Enter search string: ");
		$needle = $this->read_input();
		if ($needle) {
			$card = new Card($this->db);
			$count = 0;
			foreach ($this->db->search_card($needle) as $cardIds)
			{
				$card->load_by_id($cardIds[rowid]);
				$card->show();
				$count++;
			}
			$this->print_output("Results found: ".$count);
		}
		else
		{
			$this->show_table();
		}	
	}
	
	function show_table()
	{
		$this->print_output("\n");
		$card = new Card($this->db);
		foreach ($this->db->get_card_ids() as $cardIds)
		{
			$card->load_by_id($cardIds[rowid]);
			$card->show();
		}
	}

	function show_detail()
	{
		$this->print_output("Please enter ID: ");
		$id = $this->read_input();
		
		$card = new Card($db);
		$card->load_by_id($id);
		$card->show_detail();
		unset ($card);
	}

	function new_entry()
	{
		$card = new Card($this->db);

		$this->print_output("Firstname: ");
		$card->firstname = $this->read_input();
		
		$this->print_output("Surname: ");
		$card->surname = $this->read_input();
		
		$this->print_output("Firm: ");
		$card->firm = $this->read_input();
		
		$this->print_output("Mobile Phone: ");
		$card->mobilep = $this->read_input();
		
		$this->print_output("Work Phone: ");
		$card->workp = $this->read_input();
		
		$this->print_output("Private Phone: ");
		$card->privatep = $this->read_input();
		
		$this->print_output("Email: ");
		$card->email = $this->read_input();
		
		$card->create();
		
		$this->print_output("Card saved.\n");
	}
	
	function delete_entry ()
	{
		$this->print_output("Please enter ID: ");
		$id = $this->read_input();
		$card = new Card($this->db);
		$card->load_by_id($id);
		$card->delete();
		unset ($card);
		$this->print_output("Entry removed.");
	}
}


?>