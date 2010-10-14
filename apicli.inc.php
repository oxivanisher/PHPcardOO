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
		$this->show_get_command_text();
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

			case "5":
				return "edit_entry";
			break;

			case "9":
				return "delete_entry";
			break;

			case "0":
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
		echo "[1 Table][2 New][3 Search][4 Show][5 Edit][9 Delete][0 Exit]: ";
	}
	
	function print_output($text)
	{
		echo $text;
	}
	
	function print_error($text)
	{
		$this->print_output("ERROR: ".$text);
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
				$this->card_show($cardIds[rowid]);
				$count++;
			}
			$this->print_output("Results found: ".$count."\n");
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
			$this->card_show($cardIds[rowid]);
		}
	}

	function show_detail()
	{
		$this->print_output("Please enter ID: ");
		$id = (integer) $this->read_input();

		$card = new Card($this->db);
		$card->load_by_id($id);

		$this->print_output("\n");
		$this->print_output("Firstname:     ".$card->firstname."\n");
		$this->print_output("Surname:       ".$card->surname."\n");
		$this->print_output("Firm:          ".$card->firm."\n");
		$this->print_output("Mobile Phone:  ".$card->mobilep."\n");
		$this->print_output("Work Phone:    ".$card->workp."\n");
		$this->print_output("Private Phone: ".$card->privatep."\n");
		$this->print_output("Email:         ".$card->email."\n");
		$this->print_output("\n");

		$this->print_output("\n");
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
		$this->print_output("Entry removed.\n");
	}
	
	function card_show($id)
  {
		$card = new Card($this->db);
		$card->load_by_id($id);
    echo $card->id.":\t".$card->surname." ".$card->firstname;
		if ($card->firm)
			echo ", ".$card->firm;
		  if ($card->email)
			  echo ", ".$card->email;
		if (($card->mobilep) OR ($card->workp) OR ($card->privatep))
		  	echo "\n\t";
    if ($card->mobilep)
	    echo "M:".$card->mobilep." ";
    if ($card->workp)
	    echo "W:".$card->workp." ";
    if ($card->privatep)
	    echo "P:".$card->privatep." ";
    echo "\n";
  }
  
  function edit_entry ()
  {
		$this->print_output("Please enter ID: ");
  	$id = (integer) $this->read_input();

		$card = new Card($this->db);
		$card->load_by_id($id);
		
		$this->draw_line($id, "Firstname", "firstname", $card->firstname);
		$this->draw_line($id, "Surname", "surname", $card->surname);
		$this->draw_line($id, "Firm", "firm", $card->firm);
		$this->draw_line($id, "Mobile Phone", "mobilep", $card->mobilep);
		$this->draw_line($id, "Work Phone", "workp", $card->workp);
		$this->draw_line($id, "Private Phone", "privatep", $card->privatep);
		$this->draw_line($id, "Email", "email", $card->email);
	
		$this->print_output("\nNew entry:\n");
		$this->card_show($id);
  }

 	function draw_line($id, $text, $fieldname, $content)
 	{
		$this->print_output($text." (".$content."): ");
		$ret = $this->read_input();
		if ($ret == "-")
			$this->db->card_update($id , $fieldname, "");
		elseif (! empty($ret))
			$this->db->card_update($id , $fieldname, $ret);
 	
 	}
}


?>