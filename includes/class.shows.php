<?php 
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/class.dbconnect.php');

class SHOW {
	private $conn;
	// DATABASE CONNECTION
	public function __construct() 
	{
		$database = new database();
  		$db = $database->dbconnect();
  		$this->conn = $db;
	}
	// CUSTOM QUERY
	public function runQuery($sql)
	{
	 $stmt = $this->conn->prepare($sql);
	 return $stmt;
	}
	//GET LAST ID USED
	public function lastID()
	{
	  $stmt = $this->conn->insert_id;
	  return $stmt;
	}
	// GET ALL SHOWS
	public function getShows() 
	{
	    $stmt = $this->conn->prepare("SELECT * FROM shows");
	    $stmt->execute();
	    // CREATE AN ARRAY TO STORE VARIABLES
	    $newarray = array();
	    while ($result = $stmt->get_result()) {
	    	foreach($result as $r) 
	    	{
	    		$newarray[] = array('data'=>$r);
	    	}
	    }
	    // ALWAYS ECHO RESULTS AND ENCODE TO JSON
	   	echo json_encode($newarray);
	   	// CLOSE
	    $stmt->close();
	}
	//Create Show
	public function createShow($shname,$shtime,$shdate) {
		try 
		{
			$stmt = $this->conn->prepare("INSERT INTO shows(show_name,show_times,show_date,created_at,updated_at) VALUES(?,?,?,now(),now())");
			$stmt->bind_param('sss', $shname, $shtime, $shdate);
			$stmt->execute();
			$stmt->close();
			return $stmt;
		} 
		catch (Exception $e) 
		{
			echo $e->getMessage();
		}
	}
	// Create Total Tickets
	public function createTotalTicket($showid,$ticketleft,$tickets) 
	{
		try 
		{
			$stmt = $this->conn->prepare("INSERT INTO tickets(show_id,tickets_left,total_tickets,created_at,updated_at) VALUES(?,?,?,now(),now())");
			$stmt->bind_param('iii', $showid, $ticketleft, $tickets);
			$stmt->execute();
			$stmt->close();
			return $stmt;
		} 
		catch (Exception $e) 
		{
			echo $e->getMessage();
		}
	}
	// DELETE A SHOW
	public function deleteShow($id) 
	{
		try 
		{
		 	$stmt = $this->conn->prepare("DELETE FROM shows WHERE id = ? LIMIT 1");
		 	$stmt->bind_param("i",$id); 
		 	$stmt->execute();
		 	$stmt->close();
		} 
		catch (Exception $e) 
		{
		 	echo $ex->getMessage();
		} 
	} 
	// GET ALL SHOW TICKETS
	public function getshowtickets() {
      try 
      {
        $stmt = $this->conn->prepare("SELECT shows.id, shows.show_name, shows.show_times, shows.show_date, tickets.ticket_id, tickets.show_id, tickets.tickets_left , tickets.total_tickets FROM shows , tickets WHERE shows.id = tickets.show_id");
        $stmt->execute();
        // CREATE AN ARRAY TO STORE VARIABLES
        $newarray = array();
        while ($result = $stmt->get_result()) {
          foreach($result as $r) 
          {
            $newarray[] = array('data'=>$r);
          }
        }
        // ALWAYS ECHO RESULTS AND ENCODE TO JSON
        echo json_encode($newarray);
        // CLOSE
        $stmt->close();
      } 
      catch (Exception $e) 
      {
        echo $ex->getMessage();
      }
    }
    public function deleteTickets($ticketid) 
    {
    	try 
		{
		 	$stmt = $this->conn->prepare("DELETE FROM tickets WHERE ticket_id = ? LIMIT 1");
		 	$stmt->bind_param("i",$ticketid); 
		 	$stmt->execute();
		 	$stmt->close();
		} 
		catch (Exception $e) 
		{
		 	echo $ex->getMessage();
		} 
    }
}