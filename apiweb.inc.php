<?php

class APIweb {
	private $command;
	protected $db;

	/* HTML Vars Table
		c = command:
				show_table
				new_entry
				search
				show_entry
				edit_entry
				delete_entry
	
	
	
		INTERNAL CONTROL VARs
		$this->state -> view, do -> $_SESSION['state']
	
	*/

	function __construct($db)
  {
  	$this->db = $db;
  	$this->exit = false;
  	$this->content = new Template();
  	$this->error_output = "";
  	session_start();
  	$this->state = $_SESSION['state'];
  }

	function __destruct()
	{
		$this->content->replace("ERROR", $this->error_output);
		$this->content->write();
	}
  
	function get_command()
	{
		if ($this->exit)
		{
			return "exit";
		}
		else
		{
			import_request_variables("gp", "webreq_");
			return $webreq_c;
		}
	}


	function print_error($text)
	{
		$this->error_output .= "<h3>ERROR: ".$text."</h3>";
	}

	function show_table()
	{
		$this->content->load("show_table");
		$content  = "<table>";
		$content .= "<tr><th>Firstname</th><th>Surname</th><th>Firm</th><th>Email</th><th>Mobile Phone</th><th>Work Phone</th><th>Private Phone</th><th>&nbsp;</th><th>&nbsp;</th></tr>";
		$card = new Card($this->db);
		foreach ($this->db->get_card_ids() as $cardIds)
		{
			$card->load_by_id($cardIds[rowid]);
			$content .= "<tr>";

			$content .= "<form action='?' method='POST'>";
			$content .= "<input type='hidden' name='c' value='edit_entry'>";
			$content .= "<input type='hidden' name='id' value='".$card->id."'>";
			$content .= "<td><input type='text' name='firstname' value='".$card->firstname."' size='15'></td>";
			$content .= "<td><input type='text' name='surname' value='".$card->surname."' size='15'></td>";
			$content .= "<td><input type='text' name='firm' value='".$card->firm."' size='15'></td>";
			$content .= "<td><input type='text' name='email' value='".$card->email."' size='15'></td>";
			$content .= "<td><input type='text' name='mobilep' value='".$card->mobilep."' size='15'></td>";
			$content .= "<td><input type='text' name='workp' value='".$card->workp."' size='15'></td>";
			$content .= "<td><input type='text' name='privatep' value='".$card->privatep."' size='15'></td>";
			$content .= "<td><input type='submit' name='save' value='save' /></td>";
			$content .= "</form>";

			$content .= "<form action='?' method='POST'>";
			$content .= "<input type='hidden' name='c' value='delete_entry'>";
			$content .= "<input type='hidden' name='id' value='".$card->id."'>";
			$content .= "<td><input type='submit' name='delete' value='delete' /></td>";
			$content .= "</form>";

			$content .= "</tr>";
		}
		$content .= "<tr>";
		$content .= "<form action='?' method='POST'>";
		$content .= "<input type='hidden' name='c' value='new_entry'>";
		$content .= "<td><input type='text' name='firstname' value='' size='15'></td>";
		$content .= "<td><input type='text' name='surname' value='' size='15'></td>";
		$content .= "<td><input type='text' name='firm' value='' size='15'></td>";
		$content .= "<td><input type='text' name='email' value='' size='15'></td>";
		$content .= "<td><input type='text' name='mobilep' value='' size='15'></td>";
		$content .= "<td><input type='text' name='workp' value='' size='15'></td>";
		$content .= "<td><input type='text' name='privatep' value='' size='15'></td>";
		$content .= "<td><input type='submit' name='new' value='new' /></td>";
		$content .= "<td>&nbsp;</td>";
		$content .= "</tr>";
		$content .= "</form>";
		$content .= "</table>";
	
		$this->content->add($content);
		$this->exit = true;
	}

	
	function search()
	{
		$this->show_table();
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
		$card = new Card($db);
		$card->load_by_id((integer) $webreq_id);
		$card->delete();
		unset ($card);
		$this->show_table();
	}
  
  function edit_entry ()
  {
		$card = new Card($this->db);
		$card->load_by_id((integer) $webreq_id);
		
		if ($card->firstname != $webreq_firstname)
			$this->db->card_update($webreq_id , "firstname", $webreq_firstname);
			
		if ($card->surname != $webreq_surname)
			$this->db->card_update($webreq_id , "surname", $webreq_surname);

		if ($card->firm != $webreq_firm)
			$this->db->card_update($webreq_id , "firm", $webreq_firm);

		if ($card->mobilep != $webreq_mobilep)
			$this->db->card_update($webreq_id , "mobilep", $webreq_mobilep);

		if ($card->workp != $webreq_workp)
			$this->db->card_update($webreq_id , "workp", $webreq_workp);

		if ($card->privatep != $webreq_privatep)
			$this->db->card_update($webreq_id , "privatep", $webreq_privatep);

		if ($card->email != $webreq_email)
			$this->db->card_update($webreq_id , "email", $webreq_email);
		
		$this->show_table();
  }
}


?>