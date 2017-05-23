<?php 
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/class.dbconnect.php');

class USER
{ 
  private $conn;
  //CONNECT TO A DATABASE
  public function __construct()
  {
    $database = new database();
    $db = $database->dbconnect();
    $this->conn = $db;
  }
  //CUSTOM QUERY
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
  //REGISTER USER
  public function register($uname,$email,$upass,$code)
  {
    try
    {       
      $upass = md5($upass); //HASH PASSWORD
      $stmt = $this->conn->prepare("INSERT INTO users( name, email, password, email_verification_code, created_at, updated_at) VALUES(?, ?, ?, ?, now(), now())"); //PREPARE STATEMENT FOR SANITATION
      $stmt->bind_param('ssss', $uname, $email, $upass, $code); //INSERT RESULTS INTO VARIABLES
      $stmt->execute(); //EXECUTE QUERY
      return $stmt; //GET VALUES SO THAT IT CAN BE REUSED
    }
    catch(Exception $ex)
    {
      echo $ex->getMessage();
    }
  }
  //LOGIN USER
  public function login($email,$password)
  {
    try
    {
     $stmt = $this->conn->prepare("SELECT id, name, password, email_verified FROM users WHERE email = ? ");
     $stmt->bind_param('s', $email);
     $stmt->execute();
     $stmt->bind_result($id, $name, $upass, $emailverify);
     $stmt->fetch();
     $stmt->close();
      if ($emailverify == 1)
      {
       if($upass == md5($password)) //CHECKS IF PASSWORDS MATCH
        {
          $_SESSION['userSession'] = $id;
          $_SESSION['loggedin'] = $name;
          return true;
        }
        else
        {
          header("Location: index.php?error");
          exit;
        }
      }
      else
      { 
        header("Location: index.php?inactive");
        exit;
      } 
    }
    catch(Exception $ex)
    {
      echo $ex->getMessage();
    }
  }
  //IF USER IS LOGIN
  public function is_logged_in()
  {
    if(isset($_SESSION['userSession']) && isset($_SESSION['loggedin']))// CHECKS TO SEE IF SESSION VARIABLES ARE SET
    {
      return true;
    }
  }
  //REDIRECT  URL
  public function redirect($url)
  {
    header("Location: $url"); // REDIRECT TO WHATEVER URL CALLS THE FUNCTION
  }
  //LOG OUT
  public function logout()
  {
    session_destroy(); // DESTROY SESSION AND SET VARIABLES TO FALSE
    $_SESSION['userSession'] = false;
    $_SESSION['loggedin'] = false;
  }
  /*===============
   READ
  ================*/
  //GET ALL USERS 
  public function getUsers() 
  {
      $stmt = $this->conn->prepare("SELECT * FROM users");
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
  //GET A USER'S ROLE BY ID
  public function getUserRolebyID($id) {
    try {
     $stmt = $this->conn->prepare("SELECT t1.role_id, t2.name FROM role_user as t1 JOIN roles as t2 ON t1.role_id = t2.id WHERE t1.user_id = ? ");
     $stmt->bind_param('i', $id);
     $stmt->execute();
     $stmt->bind_result($roleid, $rname);
     $row = $stmt->fetch(); // INSERT RESULT IN AN ARRAY
     if (!empty($row)) {
       echo "<ul class=\"list-group\">";
       echo " <a href=\"edit-user-role.php?id=$id\" class=\"list-group-item\">$rname</a>";
       echo "</ul>";
     } else {
       echo "<a class=\"btn btn-default\" href=\"add-user-role.php?id=$id\" role=\"button\">Add</a>";
     }
      $stmt->close();
      return $stmt;
    } 
    catch (Exception $ex) 
    {
      echo $ex->getMessage();
    }
    
  }
  //GET A USER'S PRIORITY BY ID
  public function getUserPrioritybyID($id) 
  {
    try {
      $stmt = $this->conn->prepare("SELECT t1.usrpr_id, t2.name FROM user_priority_list as t1 JOIN user_priorities as t2 ON t1.usrpr_id = t2.usrpr_id WHERE t1.user_id = ? ");
      $stmt->bind_param('i', $id);
      $stmt->execute();
      $stmt->bind_result($prtyid, $prtyname);
      $row = $stmt->fetch();
        if (!empty($row)) {
            echo "<ul class=\"list-group\">";
            echo " <a href=\"edit-user-priority.php?id=$id\" class=\"list-group-item\">$prtyname</a>";
            echo "</ul>";
        } 
        else 
        {
            echo "<a class=\"btn btn-default\" href=\"add-user-priority.php?id=$id\" role=\"button\">Add</a>";
        }
      $stmt->close();
      return $stmt;
    } 
    catch (Exception $ex) 
    {
      echo $ex->getMessage();
    }
  }
  //GET ALL PRIORITIES
  public function getUserPriority() 
  {
    try {
      $stmt = $this->conn->prepare("SELECT usrpr_id, name FROM user_priorities");
      $stmt->execute();
      $stmt->bind_result($prtyid, $prtyname);
      while($stmt->fetch()) { // LOOP THROUGH RESULTS
        echo "<div class=\"radio\">";
        echo "<label>";
        echo "<input type=\"radio\" name=priority[] id=role_no.$prtyid value=$prtyid>$prtyname";
        echo "</label>";
        echo "</div>";
      }
      $stmt->close();
      return $stmt;
    } 
    catch (Exception $ex) 
    {
      echo $ex->getMessage();
    }
  }
  //GET ALL ROLES
  public function getAllRoles() 
  {
       $stmt = $this->conn->prepare("SELECT * FROM roles");
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
  //GET ALL PERMISSIONS
  public function getAllPerm() 
  {
    $stmt = $this->conn->prepare("SELECT * FROM permissions");
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
  //GET ALL PRIORITIES
  public function getAllPrty() 
  {
     $stmt = $this->conn->prepare("SELECT * FROM user_priorities");
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
  // GET USER SHOWS BY ID
  public function getContactShowsbyID($id) 
  {
    try 
    {
      $stmt = $this->conn->prepare("SELECT t1.show_id, t1.ticket_allocated, t2.show_name, t2.show_times, t2.show_date, t1.contact_id FROM contact_shows as t1 JOIN shows as t2 JOIN contacts as t3 ON t1.show_id = t2.id AND t1.contact_id = t3.id WHERE t3.contact_id = ?");
      $stmt->bind_param('i', $id);
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
    catch (Exception $ex) 
    {
      echo $ex->getMessage();
    }
  }
  // GET USER INFO
  public function getUserInfo() 
  {
    $stmt = $this->conn->prepare("SELECT * FROM user_infos");
    $stmt->execute();
    // CREATE AN ARRAY TO STORE VARIABLES
    $newarray = array();
    while ($result = $stmt->get_result()) 
    {
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
  // GET CONTACTS FROM MAILCHIMP
  public function getContacts()
  {
    // USE CURL FOR API CONNECTIONS
    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://us13.api.mailchimp.com/3.0/lists/fddbe1854a/segments/905189/members",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_SSL_VERIFYPEER => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "GET",
      CURLOPT_HTTPHEADER => array(
        "authorization: Basic <insert_api_key_here>",
        "cache-control: no-cache",
        "postman-token: fa2b0b3f-bafe-7bca-0798-6357bead9885"
      ),
    ));
    // create variables
    $response = curl_exec($curl);
    $err = curl_error($curl);
    $decoded = json_decode($response);
    $column = $decoded->members;

    // close statement
    curl_close($curl);
    // if there's an error show error if not show json
    if ($err) 
    {
      echo "cURL Error #:" . $err;
    } 
    else 
    {
      echo json_encode($column);
    }
  }
  // GET A CONTACT'S INFO
  public function getContactsByID($hash)
  {
    // USE CURL FOR API CONNECTIONS
    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://us13.api.mailchimp.com/3.0/lists/fddbe1854a/members/$hash",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_SSL_VERIFYPEER => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "GET",
      CURLOPT_HTTPHEADER => array(
        "authorization: Basic <insert_api_key_here>",
        "cache-control: no-cache",
        "postman-token: fa2b0b3f-bafe-7bca-0798-6357bead9885"
      ),
    ));
    // create variables
    $response = curl_exec($curl);
    $err = curl_error($curl);
    $decoded = json_decode($response);
    $column = $decoded->merge_fields;
    // close statement
    curl_close($curl);
    // if there's an error show error if not show json
    if ($err) 
    {
      echo "cURL Error #:" . $err;
    } 
    else 
    {
      echo json_encode($decoded);
    }
  }  
  public function getContactfromDB($contactid)
  {
    try 
    {
      $stmt = $this->conn->prepare("SELECT id FROM contacts WHERE contact_id = ?");
      $stmt->bind_param('s', $contactid);
      $stmt->execute();
      while ($result = $stmt->get_result()) 
      {
        foreach($result as $r) 
        {
          $value = $r;
        }
      }
      // ALWAYS ECHO RESULTS AND ENCODE TO JSON
      echo json_encode($value);
      // CLOSE
      $stmt->close();
    } 
    catch (Exception $ex) 
    {
      echo $ex->getMessage();
    }
  }
  public function getGroups()
  {
    // USE CURL FOR API CONNECTIONS
    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://us13.api.mailchimp.com/3.0/lists/fddbe1854a/segments",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_SSL_VERIFYPEER => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "GET",
      CURLOPT_HTTPHEADER => array(
        "authorization: Basic <insert_api_key_here>",
        "cache-control: no-cache",
        "postman-token: fa2b0b3f-bafe-7bca-0798-6357bead9885"
      ),
    ));
    // create variables
    $response = curl_exec($curl);
    $err = curl_error($curl);
    $decoded = json_decode($response);
    $column = $decoded->segments;
    // close statement
    curl_close($curl);
    // if there's an error show error if not show json
    if ($err) 
    {
      echo "cURL Error #:" . $err;
    } 
    else 
    {
      echo json_encode($column);
    }
  }
  public function getGroupbyID($groupid)
  {
    // USE CURL FOR API CONNECTIONS
    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://us13.api.mailchimp.com/3.0/lists/fddbe1854a/segments/$groupid",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_SSL_VERIFYPEER => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "GET",
      CURLOPT_HTTPHEADER => array(
        "authorization: Basic <insert_api_key_here>",
        "cache-control: no-cache",
        "postman-token: fa2b0b3f-bafe-7bca-0798-6357bead9885"
      ),
    ));
    // create variables
    $response = curl_exec($curl);
    $err = curl_error($curl);
    $decoded = json_decode($response);
    $column = $decoded->members;
    // close statement
    curl_close($curl);
    // if there's an error show error if not show json
    if ($err) 
    {
      echo "cURL Error #:" . $err;
    } 
    else 
    {
      echo json_encode($column);
    }
  }
  /*===============
    CREATE
   ================*/
  //ADD ADDITIONAL INFO FOR USER
  public function createUserinfo ($id,$cname,$title,$desc) 
  {
    try {
      $stmt = $this->conn->prepare("INSERT INTO user_infos (user_id ,company_name, title, description, created_at, updated_at) VALUES (?,?,?,?,now(),now())");
      $stmt->bind_param('isss',$id, $cname,$title,$desc);
      $stmt->execute();
      $stmt->close();

    } catch (Exception $ex) {
      echo $ex->getMessage();
    }
  }
  // ADD A SHOW TO A USER
  public function addShowtoContact($contactid, $showid) 
  {
    try 
    {
      $stmt = $this->conn->prepare("INSERT INTO contact_shows (show_id,contact_id, created_at, updated_at) VALUES (?,?,now(),now())");
      $stmt->bind_param('ii',$showid, $contactid);
      $stmt->execute();
      $stmt->close();
    } 
    catch (Exception $ex) 
    {
      echo $ex->getMessage();
    }
  }
  // ADD COMPS TO A USER
  public function addComps($contactid,$showid,$alctd_tickets)  
  {
    try 
    {
      $stmt = $this->conn->prepare("UPDATE contact_shows SET ticket_allocated = ?, updated_at=now() WHERE contact_id=? AND show_id=?");
      $stmt->bind_param('iii',$alctd_tickets, $contactid, $showid);
      $stmt->execute();
      $stmt->close();
    } 
    catch (Exception $ex) 
    {
      echo $ex->getMessage();
    }
  }
  // ADD A CONTACT TO MAILCHIMP
  public function addContact($email,$fname,$lname,$prty)
  {
    // UPDATE LIST ID ONCE WE HAVE CONTACTS
    // USE CURL FOR API CONNECTIONS
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://us13.api.mailchimp.com/3.0/lists/fddbe1854a/members",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_SSL_VERIFYPEER => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => "{\"email_address\":\"$email\",\"status\":\"subscribed\",\"merge_fields\":{\"FNAME\":\"$fname\",\"LNAME\":\"$lname\",\"PRTY\":\"$prty\"}}",
      CURLOPT_HTTPHEADER => array(
        "authorization: Basic <insert_api_key_here>",
        "cache-control: no-cache",
        "postman-token: fa2b0b3f-bafe-7bca-0798-6357bead9885"
      ),
    ));
    // create variables
    $response = curl_exec($curl);
    $err = curl_error($curl);
    $decoded = json_decode($response);
    // close statement
    curl_close($curl);
    // if there's an error show error if not show json
    if ($err) 
    {
      echo "cURL Error #:" . $err;
    } 
    else 
    {
      echo $response;
    }
  } 
  public function addContactDB($fname,$lname,$email,$prty,$contactid)
  {
    try {
      $stmt = $this->conn->prepare("INSERT INTO contacts (contact_id, first_name, last_name, email, priority, created_at, updated_at) VALUES (?,?,?,?,?,now(),now())");
      $stmt->bind_param('sssss',$contactid,$fname,$lname,$email,$prty);
      $stmt->execute();
      $stmt->close();
    } catch (Exception $ex) {
      echo $ex->getMessage();
    }
  }
  // ADD A NEW GROUP IN MAILCHIMP
  public function addGroup($groupname,$value)
  {
    // UPDATE LIST ID ONCE WE HAVE CONTACTS
    // USE CURL FOR API CONNECTIONS
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://us13.api.mailchimp.com/3.0/lists/fddbe1854a/segments",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_SSL_VERIFYPEER => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => "{\"name\":\"$groupname\",\"options\":{\"conditions\":[{\"condition_type\":\"TextMerge\",\"field\":\"PRTY\",\"op\":\"is\",\"value\":\"$value\"}]}}",
      CURLOPT_HTTPHEADER => array(
        "authorization: Basic <insert_api_key_here>",
        "cache-control: no-cache",
        "postman-token: fa2b0b3f-bafe-7bca-0798-6357bead9885"
      ),
    ));
    // create variables
    $response = curl_exec($curl);
    $err = curl_error($curl);
    // close statement
    curl_close($curl);
    // if there's an error show error if not show json
    if ($err) 
    {
      echo "cURL Error #:" . $err;
    } 
    else 
    {
      echo $response;
    }
  }
  /*===============
    UPDATE
  ================*/
  // CHANGE CONTACT INFO IN MAILCHIMP
  public function updateContact($hash,$email,$fname,$lname,$prty)
  {
    // UPDATE LIST ID ONCE WE HAVE CONTACTS
    // USE CURL FOR API CONNECTIONS
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://us13.api.mailchimp.com/3.0/lists/fddbe1854a/members/$hash",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_SSL_VERIFYPEER => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "PATCH",
      CURLOPT_POSTFIELDS => "{\"email_address\":\"$email\",\"status\":\"subscribed\",\"merge_fields\":{\"FNAME\":\"$fname\",\"LNAME\":\"$lname\",\"PRTY\":\"$prty\"}}",
      CURLOPT_HTTPHEADER => array(
        "authorization: Basic <insert_api_key_here>",
        "cache-control: no-cache",
        "postman-token: fa2b0b3f-bafe-7bca-0798-6357bead9885"
      ),
    ));
    // create variables
    $response = curl_exec($curl);
    $err = curl_error($curl);
    // close statement
    curl_close($curl);
    // if there's an error show error if not show json
    if ($err) 
    {
      echo "cURL Error #:" . $err;
    } 
    else 
    {
      echo $response;
    }
  }
  public function updateGroup($id)
  {
    // UPDATE LIST ID ONCE WE HAVE CONTACTS
    // USE CURL FOR API CONNECTIONS
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://us13.api.mailchimp.com/3.0/lists/fddbe1854a/members/$hash",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_SSL_VERIFYPEER => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "PATCH",
      CURLOPT_POSTFIELDS => "{\"merge_fields\": {\"FNAME\":\"\",\"LNAME\":\"\"}}",
      CURLOPT_HTTPHEADER => array(
        "authorization: Basic <insert_api_key_here>",
        "cache-control: no-cache",
        "postman-token: fa2b0b3f-bafe-7bca-0798-6357bead9885"
      ),
    ));
    // create variables
    $response = curl_exec($curl);
    $err = curl_error($curl);
    // close statement
    curl_close($curl);
    // if there's an error show error if not show json
    if ($err) 
    {
      echo "cURL Error #:" . $err;
    } 
    else 
    {
      echo $response;
    }
  }
  /*===============
    DELETE
   ================*/
  // DELETE USER
  public function deleteUser($id) 
  {
    try 
    {
      $stmt = $this->conn->prepare("DELETE FROM users WHERE id = ? LIMIT 1");
      $stmt->bind_param("i",$id); // USE ID FROM URL
      $stmt->execute();
      $stmt->close();
    }
    catch (Exception $ex) 
    {
      echo $ex->getMessage();
    }
  } 
  // REMOVE SHOW FROM A USER
  public function removeContactShows($showid,$contactid) 
  {
    $stmt = $this->conn->prepare("DELETE FROM contact_shows WHERE show_id = ? AND contact_id = ?");
    $stmt->bind_param("ii",$showid,$contactid);
    $stmt->execute();
    $stmt->close();
  }
  // DELETE CONTACT FROM MAILCHIMP
  public function deleteContact($hash)
  {
    // DELETE A SINGLE CAMPAIGN
    // USE CURL FOR API CONNECTIONS
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://us13.api.mailchimp.com/3.0/lists/fddbe1854a/members/$hash",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_SSL_VERIFYPEER => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "DELETE",
      CURLOPT_HTTPHEADER => array(
        "authorization: Basic <insert_api_key_here>",
        "cache-control: no-cache",
        "postman-token: fa2b0b3f-bafe-7bca-0798-6357bead9885"
      ),
    ));
    // create variables
    $response = curl_exec($curl);
    $err = curl_error($curl);
    // close statement
    curl_close($curl);
    // if there's an error show error if not show json
    if ($err) 
    {
      echo "cURL Error #:" . $err;
    } 
    else 
    {
      echo $response;
    }
  }
  public function deleteGroup($id)
  {
    // DELETE A SINGLE CAMPAIGN
    // USE CURL FOR API CONNECTIONS
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://us13.api.mailchimp.com/3.0lists/fddbe1854a/members/$hash",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_SSL_VERIFYPEER => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "DELETE",
      CURLOPT_HTTPHEADER => array(
        "authorization: Basic <insert_api_key_here>",
        "cache-control: no-cache",
        "postman-token: fa2b0b3f-bafe-7bca-0798-6357bead9885"
      ),
    ));
    // create variables
    $response = curl_exec($curl);
    $err = curl_error($curl);
    // close statement
    curl_close($curl);
    // if there's an error show error if not show json
    if ($err) 
    {
      echo "cURL Error #:" . $err;
    } 
    else 
    {
      echo $response;
    }
  }
  // DELETE A ROLE
  public function deleteRole($id) 
  {
    try 
    {
      $stmt = $this->conn->prepare("DELETE FROM roles WHERE id = ? LIMIT 1");
      $stmt->bind_param("i",$id); // USE ID FROM URL
      $stmt->execute();
      $stmt->close();
    }
    catch (Exception $ex) 
    {
      echo $ex->getMessage();
    }
  }
  // DELETE A PERMISSION
  public function deletePermission($id) 
  {
    try 
    {
      $stmt = $this->conn->prepare("DELETE FROM permissions WHERE id = ? LIMIT 1");
      $stmt->bind_param("i",$id); // USE ID FROM URL
      $stmt->execute();
      $stmt->close();
    }
    catch (Exception $ex) 
    {
      echo $ex->getMessage();
    }
  }
  // DELETE A PRIORITY
  public function deletePriority($id) 
  {
    try 
    {
      $stmt = $this->conn->prepare("DELETE FROM user_priorities WHERE usrpr_id = ? LIMIT 1");
      $stmt->bind_param("i",$id); // USE ID FROM URL
      $stmt->execute();
      $stmt->close();
    }
    catch (Exception $ex) 
    {
      echo $ex->getMessage();
    }
  }
  // CONFIRMATION SIGNUP EMAIL
  function send_mail($email,$message,$subject)
  {      
    require_once($_SERVER['DOCUMENT_ROOT'].'/bower_components/phpmailer/PHPMailerAutoload.php');
    $mail = new PHPMailer(); // NEW MAILER CHANGE HOST, PORT ONCE IN PROD
    $mail->isSMTP(); 
    $mail->SMTPDebug = 0;
    $mail->Debugoutput = 'html'; 
    $mail->Host = "<insert mail server here>";     
    $mail->Port = 2525;                      
    $mail->SMTPAuth   = true;                                           
    $mail->addAddress($email);
    $mail->Username="username";  
    $mail->Password="password";            
    $mail->setFrom('admin@example.com','Admin');
    $mail->AddReplyTo('replyto@example.com','Admin');
    $mail->Subject    = $subject;
    $mail->MsgHTML($message);
    //send the message, check for errors get rid once in production
    if (!$mail->send()) 
    {
      echo 'Message could not be sent.';
      echo "Mailer Error: " . $mail->ErrorInfo;
    } 
    else 
    {
      echo "Message sent!";
    }
  }
  // SUBTRACT NUMBER FROM TOTAL
  public function subtractTotalComps($alctd_tickets,$showid) 
  {
    try 
    {
      $stmt = $this->conn->prepare("UPDATE tickets SET tickets_left = tickets_left - ? WHERE show_id = ?");
      $stmt->bind_param('ii', $alctd_tickets,$showid);
      $stmt->execute();
      $stmt->close();
    } 
    catch (Exception $e) 
    {
      echo $ex->getMessage();
    }
  }
  // ADD NUMBER FROM TOTAL
  public function addTotalComps($alctd_tickets,$showid) 
  {
    try 
    {
      $stmt = $this->conn->prepare("UPDATE tickets SET tickets_left = tickets_left + ? WHERE show_id = ?");
      $stmt->bind_param('ii', $alctd_tickets,$showid);
      $stmt->execute();
      $stmt->close();
    } 
    catch (Exception $e) 
    {
      echo $ex->getMessage();
    }
  }
}
