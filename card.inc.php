<?php

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
  public $firstname;
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
    $this->firstname = "";
    $this->surname = "";
    $this->firm = "";
    $this->mobilep = "";
    $this->workp = "";
    $this->privatep = "";
    $this->email = "";
  }

  function create()
  {
    $this->db->query(sprintf("INSERT INTO card (firstname, surname, firm, mobilep, workp, privatep, email) VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s' );",
    								 $this->firstname, $this->surname, $this->firm, $this->mobilep, $this->workp, $this->privatep, $this->email));
    $this->id = $this->db->last_rowid();
	return $this->id;
  }

  function load_by_id($id)
  {
    $result = $this->db->query(sprintf("SELECT rowid, firstname, surname, firm, mobilep, workp, privatep, email FROM card WHERE rowid='%d';", $id));
    $this->id = $result[0]['rowid'];
    $this->firstname = $result[0]['firstname'];
    $this->surname = $result[0]['surname'];
    $this->firm = $result[0]['firm'];
    $this->mobilep = $result[0]['mobilep'];
    $this->workp = $result[0]['workp'];
    $this->privatep = $result[0]['privatep'];
    $this->email = $result[0]['email'];
  }

	function delete()
	{
		$this->db->query(sprintf("DELETE FROM card WHERE rowid='%d';", $this->id));
	}
}

?>