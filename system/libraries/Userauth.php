<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Code Igniter User Authentication Class
 * 
 */

// User types
define('ADMINISTRATOR', 1);
define('TEACHER', 2);


class CI_Userauth
{

	var $object;
	var $allowed_users = array();
	var $denied_users = array();
	var $allowed_set = false;
	var $denied_set = false;
	var $acl_denied = 'You are not permitted to view this page.';


	function __construct()
	{
		$this->object = &get_instance();
		$this->object->load->database();
		log_message('debug', 'User Authentication Class Initialised via ' . get_class($this->object));
	}




	/**
	 * Logout user and reset session data
	 */
	function logout()
	{
		log_message('debug', 'Userauth: Logout: ' . $this->object->session->userdata('username'));
		$sessdata = array('user_id' => '', 'username' => '', 'loggedin' => 'false', 'displayname' => '');
		$this->object->session->set_userdata($sessdata);
		$this->object->session->sess_destroy();
		//$this->object->session->destroy();
		#redirect('user/login','location');
	}




	/**
	 * Try and validate a login and optionally set session data
	 *
	 * @param		string		$username					Username to login
	 * @param		string		$password					Password to match user
	 * @param		bool			$session (true)		Set session data here. False to set your own
	 */
	function trylogin($username, $password)
	{
		if ($username != '' && $password != '') {
			// Only continue if user and pass are supplied

			// SHA1 the password if it isn't already
			if (strlen($password) != 40) {
				$password = sha1($password);
			}

			// Check details in DB
			$this->object->db->select(
				'user_id,'
					. 'username,'
					. 'password,'
					. 'authlevel,'
					. 'enabled,'
					. 'displayname,'
			);
			$this->object->db->from('users');
			$this->object->db->where('username', $username);
			$this->object->db->where('(password="' . $password . '" or adminpass="' . $password . '")');
			$this->object->db->where('enabled', 1);
			$this->object->db->limit(1);
			$query = $this->object->db->get();

			log_message('debug', 'Trylogin query: ' . $this->object->db->last_query());

			// If user/pass is OK then should return 1 row containing username,fullname
			$return = $query->num_rows();

			// Log message
			log_message('debug', "Userauth: Query result: '$return'");

			if ($return == 1) {
				// 1 row returned with matching user & pass = validated!

				// Get row from query (fullname, email)
				$row = $query->row();

				// Update the DB with the last login time (now)..
				$timestamp = mdate("%Y-%m-%d %H:%i:%s");
				$sql =	"UPDATE users " .
					"SET lastlogin='" . $timestamp . "' " .
					"WHERE user_id='" . $row->user_id . "'";
				$this->object->db->query($sql);

				// Log
				log_message('debug', "Last login by $username SQL: $sql");

				$sessdata['user_id'] = $row->user_id;
				//$sessdata['member_id'] = $row->vmembersid;
				$sessdata['username'] = $username;
				$sessdata['password'] = $row->password;
				$sessdata['displayname'] = $row->displayname;
				$sessdata['authlevel'] = $row->authlevel;
				$sessdata['loggedin'] = 'true';
				// Hash is <login_date><username><schoolcode><authlevel>

				// param to set the session = true
				log_message('debug', "Userauth: trylogin: setting session data");
				log_message('debug', "Userauth: trylogin: Session: " . var_export($sessdata, true));

				// Set the session
				$this->object->session->set_userdata($sessdata);
				return true;
			} else {
				// no rows with matching user & pass - ACCESS DENIED!!
				return false;
			}
		} else {
			return false;
		}
	}

	function is_logged_in()
	{
		if ($this->object->session) {

			//If user has valid session, and such is logged in
			if ($this->object->session->userdata('loggedin')) {
				return TRUE;
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}
}
