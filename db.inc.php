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
    if ($this->db = sqlite_popen($dbname, 0777, $sqliteerror))
    {
    	if ($newfile) 
				$this->query('CREATE TABLE card (firstname varchar(100), surname varchar(100), firm varchar(100), mobilep varchar(20), workp varchar(20), privatep varchar(20), email varchar(65));');
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

	function get_card_ids ()
	{
		return $this->query("SELECT rowid FROM card WHERE 1 ORDER BY surname ASC, firstname ASC;");
	}
	
	function search_card($needle)
	{
		return $this->query("SELECT rowid FROM card WHERE
					firstname LIKE '%".$needle."%' OR 
					surname LIKE '%".$needle."%' OR 
					firm LIKE '%".$needle."%' OR 
					mobilep LIKE '%".$needle."%' OR 
					workp LIKE '%".$needle."%' OR 
					privatep LIKE '%".$needle."%' OR 
					email LIKE '%".$needle."%';");
	}
	
	function card_update($id, $field, $newvalue)
	{
		$this->query("UPDATE card SET ".$field."='".$newvalue."' WHERE rowid='".$id."';");
	}
}


?>