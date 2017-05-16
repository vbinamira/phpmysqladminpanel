<?php 
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/class.dbconnect.php');

class Email{
	private $conn;

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
	public function getCampaign() 
	{
		// USE CURL FOR API CONNECTIONS
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://us13.api.mailchimp.com/3.0/campaigns?count=15&status=save",
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
		$column = $decoded->campaigns;
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
	// Only For Testing Purposes
	public function getScheduledCampaign() 
	{
		// USE CURL FOR API CONNECTIONS
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://us13.api.mailchimp.com/3.0/campaigns?count=15&status=schedule",
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
		$column = $decoded->campaigns;
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
	public function getChecklistbyCampaign($cmpgnid)
	{
		// USE CURL FOR API CONNECTIONS
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://us13.api.mailchimp.com/3.0/campaigns/$cmpgnid/send-checklist",
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
	public function createCampaign($subject,$replyto,$fromname,$title,$segmentid)
	{
		// UPDATE LIST ID ONCE WE HAVE CONTACTS
		// USE CURL FOR API CONNECTIONS
		$curl = curl_init();
		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://us13.api.mailchimp.com/3.0/campaigns",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_SSL_VERIFYPEER => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "POST",
		  CURLOPT_POSTFIELDS => "{\"recipients\":{\"list_id\":\"fddbe1854a\",\"segment_opts\":{\"saved_segment_id\":$segmentid}},\"type\":\"regular\",\"settings\":{\"subject_line\":\"$subject\",\"reply_to\":\"$replyto\",\"title\":\"$title\",\"from_name\":\"$fromname\"}}",
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
	public function updateContent($id,$htmlcode)
	{
		// UPDATE LIST ID ONCE WE HAVE CONTACTS
		// USE CURL FOR API CONNECTIONS
		$curl = curl_init();
		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://us13.api.mailchimp.com/3.0/campaigns/$id/content",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_SSL_VERIFYPEER => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "PUT",
		  CURLOPT_POSTFIELDS => "{\"html\":$htmlcode}",
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
	public function deleteCampaign($id)
	{
		// DELETE A SINGLE CAMPAIGN
		// USE CURL FOR API CONNECTIONS
		$curl = curl_init();
		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://us13.api.mailchimp.com/3.0/campaigns/$id",
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
	public function sendTest($id,$email)
	{
		// DELETE A SINGLE CAMPAIGN
		// USE CURL FOR API CONNECTIONS
		$curl = curl_init();
		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://us13.api.mailchimp.com/3.0/campaigns/$id/actions/test",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_SSL_VERIFYPEER => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "POST",
		  CURLOPT_POSTFIELDS => "{\"test_emails\":[\"$email\"],\"send_type\":\"html\"}",
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
	public function scheduleCampaign($id,$scheduletime)
	{
		// DELETE A SINGLE CAMPAIGN
		// USE CURL FOR API CONNECTIONS
		$curl = curl_init();
		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://us13.api.mailchimp.com/3.0/campaigns/$id/actions/schedule",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_SSL_VERIFYPEER => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "POST",
		  CURLOPT_POSTFIELDS => "{\"schedule_time\":\"$scheduletime\"}",
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
	public function unscheduleCampaign($id)
	{
		// DELETE A SINGLE CAMPAIGN
		// USE CURL FOR API CONNECTIONS
		$curl = curl_init();
		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://us13.api.mailchimp.com/3.0/campaigns/$id/actions/unschedule",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_SSL_VERIFYPEER => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "POST",
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
}
?>