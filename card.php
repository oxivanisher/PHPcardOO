#!/usr/bin/php
<?php

class DB {
  function __construct($dbname)
  {

    if (!file_exists($dbname))
    {
			$newfile = true;    
    } else {
    	$newfile = false;
    }
    if ($this->db = sqlite_popen($dbname, 0666, $sqliteerror))
    {
    	if ($newfile) 
				$this->query('CREATE TABLE card (firstname varchar(100), surname varchar(100), firm varchar(100), mobile varchar(20), work varchar(20), private varchar(20), email varchar(65));');
    } else {
      die($sqliteerror);
    }
  }

  function __destruct()
  {
    sqlite_close($this->db);
  }

  function query($query)
  {
    //return sqlite_array_query($this->db, $query);
    $res = sqlite_query($this->db, $query);
    if ($res == FALSE) {
      echo(sprintf("query: %s\nsqlite error: %s\n", $query, sqlite_error_string(sqlite_last_error($this->db))));
      return NULL;
    }
    return sqlite_fetch_all($res);
  }

  function last_rowid()
  {
    return sqlite_last_insert_rowid($this->db);
  }

	function get_ids ()
	{
		return $this->query("SELECT rowid FROM card WHERE 1 ORDER BY surname, name ASC;");
	}

}

class Card {
	/*
	firstname varchar(100)
	surname varchar(100)
	firm varchar(100)
	mobile varchar(20)
	work varchar(20)
	private varchar(20)
	email varchar(65)
	*/

  public $id;
  public $name;
  public $surname;
  public $firm;
  public $mobilep;
  public $workp;
  public $privatep;
  public $email;
  protected $db;

  function __construct($db)
  {
    $this->db = $db;
    $this->id = -1;
    $this->name = "";
    $this->surname = "";
    $this->firm = "";
    $this->mobilep = "";
    $this->workp = "";
    $this->privatep = "";
    $this->email = "";
  }

  function show()
  {
    echo $this->id.":\t".$this->surname.", ".$this->surname.", ".$this->firm."\n";
    echo "\t-> ";
    if ($this->mobilep)
	    echo "M: ".$this->mobilep." ";
    if ($this->workp)
	    echo "W: ".$this->workp." ";
    if ($this->privatep)
	    echo "P: ".$this->privatep." ";
    if ($this->email)
	    echo "E: ".$this->email." ";
    echo "\n";
  }

  function create()
  {
    $this->db->query(sprintf("INSERT INTO card (firstname, surname, firm, mobilep, workp, privatep, email) VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s' );",
    								 $this->name, $this->surname, $this->firm, $this->mobilep, $this->workp, $this->privatep, $this->email));
    return $this->db->last_rowid();
  }

  function load_by_id($id)
  {
    $result = $this->db->query(sprintf("SELECT firstname, surname, firm, mobilep, workp, privatep, email FROM card WHERE rowid='%d';", $id));
    $this->id = $id;
    $this->name = $result[0]['firstname'];
    $this->surname = $result[0]['surname'];
    $this->firm = $result[0]['firm'];
    $this->mobilep = $result[0]['mobilep'];
    $this->workp = $result[0]['workp'];
    $this->privatep = $result[0]['privatep'];
    $this->email = $result[0]['email'];
  }

}

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
	
	function search($needle)
	{
		
	}
	
	function show_table()
	{
		$card = new Card($this->db);
		print_r($this->db);
		foreach ($this->db->get_ids() as $id)
		{
			$card->load_by_id($id);
			$card->show();
		}
	}
}

class Controller {
	protected $api;
	protected $db;
	
	function __construct ()
	{
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
				$this->api->print_output("\n-> Show Table:\n");
				$this->api->show_table();
				true;
			}
			elseif ($input == "new_entry")
			{
				$this->api->print_output("\n-> New Entry:\n");
				true;
			}
			elseif ($input == "search")
			{
				$this->api->print_output("\n-> Search:\n");
				true;
			}
			elseif ($input == "show_entry")
			{
				$this->api->print_output("\n-> Show Entry:\n");
				true;
			}
			elseif ($input == "delete_entry")
			{
				$this->api->print_output("\n-> Delete Entry:\n");
				true;
			}
			else
				$this->api->print_output("Invalid selecion: ".$input."\n");
				
			
		}
	}
}

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

/*
To detect if run from CLI:

if (defined('STDIN'))

or:

if (isset($argc))
*/


}

main();

?>

