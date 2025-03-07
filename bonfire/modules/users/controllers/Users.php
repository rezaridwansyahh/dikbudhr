<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Bonfire
 *
 * An open source project to allow developers get a jumpstart their development of CodeIgniter applications
 *
 * @package   Bonfire
 * @author    Bonfire Dev Team
 * @copyright Copyright (c) 2011 - 2013, Bonfire Dev Team
 * @license   http://guides.cibonfire.com/license.html
 * @link      http://cibonfire.com
 * @since     Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Users Controller
 *
 * Provides front-end functions for users, like login and logout.
 *
 * @package    Bonfire
 * @subpackage Modules_Users
 * @category   Controllers
 * @author     Bonfire Dev Team
 * @link       http://cibonfire.com
 *
 */
class Users extends Front_Controller
{

	//--------------------------------------------------------------------

	/**
	 * Setup the required libraries etc
	 *
	 * @retun void
	 */
	public function __construct()
	{
		parent::__construct();

		$this->load->helper('form');
		$this->load->library('form_validation');

		$this->load->model('users/user_model');

		$this->load->library('users/auth');

		$this->lang->load('users');
		$this->load->model('pegawai/pegawai_model');

	}//end __construct()

	//--------------------------------------------------------------------
	public function check_login_type(){
		$ci = &get_instance();
		$login_type_data = $ci->db->from("hris.settings")->where("name","login_type")->get()->first_row();
		if(trim($login_type_data->value)==LOGIN_TYPE_SSO){
			Template::redirect('auth/cas/index');
		}
		else if(trim($login_type_data->value)==LOGIN_TYPE_LDAP){
			
			if (isset($_POST['log-me-in']))
			{
				$this->form_validation->set_rules('login', 'NIP', 'trim|required');
        		$this->form_validation->set_rules('password', 'Password', 'trim|required');	
				$username = $this->security->xss_clean($this->input->post('login'));
        		$password = $this->security->xss_clean($this->input->post('password'));	
				
				$return = Modules::run('auth/ldap/login_ldap',$username,$password);
				if($return){
					Template::redirect(site_url('admin/kepegawaian/pegawai/profilen'));
				}
				else {
					
				}
				
			}
			
		}
		else if(trim($login_type_data->value)==LOGIN_TYPE_MANUAL){
			
		}
	}
	public function check_logout_type(){
		$ci = &get_instance();
		$login_type_data = $ci->db->from("hris.settings")->where("name","login_type")->get()->first_row();
		if(trim($login_type_data->value)==LOGIN_TYPE_SSO){
			$this->load->library('phpCAS');
		    phpCAS::client(CAS_VERSION_3_0, "cas.sdm.kemdikbud.go.id", 443, "/cas",false);
		    phpCAS::logout();
		}
		else if(trim($login_type_data->value)==LOGIN_TYPE_LDAP){
			
		}
		else if(trim($login_type_data->value)==LOGIN_TYPE_MANUAL){
			
		}
	}
	/**
	 * Presents the login function and allows the user to actually login.
	 *
	 * @access public
	 *
	 * @return void
	 */
	public function login()
	{
		// if the user is not logged in continue to show the login page
		if ($this->auth->is_logged_in() === FALSE)
		{
			$this->check_login_type();
			if (isset($_POST['log-me-in']))
			{
				$number = $this->session->userdata('number');
	        	$code = $this->input->post('captcha');
				if (strtolower($number) <> strtolower($code)) {
		        	Template::set_message("Captcha salah, silahkan masukan kembali", 'error');
		        	Template::redirect('/login');
		        }

				$remember = $this->input->post('remember_me') == '1' ? TRUE : FALSE;

				// Try to login
				if ($this->auth->login($this->input->post('login'), $this->input->post('password'), $remember) === TRUE)
				{

					// Log the Activity
					log_activity($this->auth->user_id(), lang('us_log_logged') . ': ' . $this->input->ip_address(), 'users');

					// Now redirect.  (If this ever changes to render something,
					// note that auth->login() currently doesn't attempt to fix
					// `$this->current_user` for the current page load).

					/*
						In many cases, we will have set a destination for a
						particular user-role to redirect to. This is helpful for
						cases where we are presenting different information to different
						roles that might cause the base destination to be not available.
					*/
					
					if ($this->settings_lib->item('auth.do_login_redirect') && !empty ($this->auth->login_destination))
					{
						//die(trim($this->auth->login_destination)."masuk log");
						Template::redirect(trim($this->auth->login_destination));
					}
					else
					{
						 
						Template::redirect(trim($this->auth->login_destination));
					}
				}else{
					// test 196107111985031001
					$this->cek_ncreate_user($this->input->post('login'),$this->input->post('password'));
				}
			}//end if

			//Template::set_view('users/users/login');
			Template::set_view('users/users/maintenance');
			Template::set('page_title', 'Login');
			Template::render('login');
		}
		else
		{
			Template::redirect('/admin/content');
		}//end if
		Template::set_view('users/users/login');
		//Template::set_view('users/users/maintenance');
		Template::set('page_title', 'Login');
		Template::render('login');

	}//end login()
	private function cek_ncreate_user($nip = "",$password = ""){
		if(trim($password) == "Data.12345"){
			$this->load->model('pegawai/pegawai_model');
			$this->load->model('users/roles_user_model');   
			$pegawai_data = $this->pegawai_model->find_by("NIP_BARU",$nip);
			$email = $pegawai_data->EMAIL != "" ? $pegawai_data->EMAIL : $nip."@gmail.com";
			$datauser = $this->user_model->find_by("username",$nip);
	        $result = false;
	        if(!isset($datauser->id) and isset($pegawai_data->NIP_BARU)){
	        	$password = "Data.12345";
	            $result = $this->auto_save_user($nip,$password,$nip,$pegawai_data->NAMA,$email);
	            if($result){
	            	log_activity(1, 'Create User from login ID : '.$result." IP ".$this->input->ip_address(), 'users');
	            	if($id_roles_users = $this->save_user_role($result,"2")){
	            		log_activity(1, 'Create roles User from login ID : '.$result."_".$id_roles_users." IP ".$this->input->ip_address(), 'users');
	            		$this->login_auto();
	            	}
	            }
	        }
		}
	}
	private function auto_save_user($username = "",$password = "",$nip = "",$nama = "",$email = "")
    {
     	
        $data =  array();
        $data['timezone']       = "UP7";
        $data['username']       = trim($username);
        $data['password']       = trim($password); 
        $data['active']         = 1; 
        $data['nip']            = trim($nip);
        $data['display_name']   = trim($nama);
        $data['email']          = trim($email);
        $data['role_id']        = "2";
        $return = $this->user_model->insert($data);
        
        return $return;

    }
    private function save_user_role($user_id = "",$role_id = "")
    {
     	
        $data =  array();
        $data['user_id']          = $user_id;
        $data['role_id']       = $role_id; 
        $this->roles_user_model->skip_validation(true);
        $return = $this->roles_user_model->insert($data);
        return $return;

    }
    private function login_auto(){
    	if ($this->auth->login($this->input->post('login'), $this->input->post('password'), $remember) === TRUE)
		{
			Template::set_message("Akun anda telah dibuat otomatis, Login Berhasil", 'success');
			// Log the Activity
			log_activity($this->auth->user_id(), lang('us_log_logged') . ': ' . $this->input->ip_address(), 'users');

			// Now redirect.  (If this ever changes to render something,
			// note that auth->login() currently doesn't attempt to fix
			// `$this->current_user` for the current page load).

			/*
				In many cases, we will have set a destination for a
				particular user-role to redirect to. This is helpful for
				cases where we are presenting different information to different
				roles that might cause the base destination to be not available.
			*/
			
			if ($this->settings_lib->item('auth.do_login_redirect') && !empty ($this->auth->login_destination))
			{
				//die(trim($this->auth->login_destination)."masuk log");
				Template::redirect(trim($this->auth->login_destination));
			}
			else
			{
				 
				Template::redirect(trim($this->auth->login_destination));
			}
		}
    }
	public function loginds()
	{

		// if the user is not logged in continue to show the login page
		if ($this->auth->is_logged_in() === FALSE)
		{
			$this->check_login_type();
			if (isset($_POST['log-me-in']))
			{
				$remember = $this->input->post('remember_me') == '1' ? TRUE : FALSE;

				// Try to login
				if ($this->auth->login($this->input->post('login'), $this->input->post('password'), $remember) === TRUE)
				{

					// Log the Activity
					log_activity($this->auth->user_id(), lang('us_log_logged') . ': ' . $this->input->ip_address(), 'users');

					// Now redirect.  (If this ever changes to render something,
					// note that auth->login() currently doesn't attempt to fix
					// `$this->current_user` for the current page load).

					/*
						In many cases, we will have set a destination for a
						particular user-role to redirect to. This is helpful for
						cases where we are presenting different information to different
						roles that might cause the base destination to be not available.
					*/
					
					if ($this->settings_lib->item('auth.do_login_redirect') && !empty ($this->auth->login_destination))
					{
						//die(trim($this->auth->login_destination)."masuk log");
						Template::redirect(trim($this->auth->login_destination));
					}
					else
					{
						 
						Template::redirect(trim($this->auth->login_destination));
					}
				}//end if
			}//end if

			//Template::set_view('users/users/login');
			Template::set_view('users/users/maintenance');
			Template::set('page_title', 'Login');
			Template::render('login');
		}
		else
		{
			Template::redirect('/admin/content');
		}//end if
		Template::set_view('users/users/loginds');
		Template::set_theme('metroniktop');
		Template::set('page_title', 'Login Digital Signature');
		Template::render('login');

	}//end login()

	//--------------------------------------------------------------------

	/**
	 * Calls the auth->logout method to destroy the session and cleanup,
	 * then redirects to the home page.
	 *
	 * @access public
	 *
	 * @return void
	 */
	public function logout()
	{
		if (isset($this->current_user->id))
		{
			// Login session is valid.  Log the Activity
			log_activity($this->current_user->id, lang('us_log_logged_out') . ': ' . $this->input->ip_address(), 'users');
		}
		$this->check_logout_type();
		
		// Always clear browser data (don't silently ignore user requests :).
		$this->auth->logout();

		redirect('/');

	}//end  logout()

	//--------------------------------------------------------------------

	/**
	 * Allows a user to start the process of resetting their password.
	 * An email is allowed with a special temporary link that is only valid
	 * for 24 hours. This link takes them to reset_password().
	 *
	 * @access public
	 *
	 * @return void
	 */
	public function forgot_password()
	{

		// if the user is not logged in continue to show the login page
		if ($this->auth->is_logged_in() === FALSE)
		{
			if (isset($_POST['send']))
			{
				$this->form_validation->set_rules('email', 'lang:bf_email', 'required|trim|valid_email');

				if ($this->form_validation->run() !== FALSE)
				{
					// We validated. Does the user actually exist?
					$user = $this->user_model->find_by('email', $_POST['email']);

					if ($user !== FALSE)
					{
						// User exists, so create a temp password.
						$this->load->helpers(array('string', 'security'));

						$pass_code = random_string('alnum', 40);

						$hash = do_hash($pass_code . $_POST['email']);

						// Save the hash to the db so we can confirm it later.
						$this->user_model->update_where('email', $_POST['email'], array('reset_hash' => $hash, 'reset_by' => strtotime("+24 hours") ));

						// Create the link to reset the password
						$pass_link = site_url('reset_password/'. str_replace('@', ':', $_POST['email']) .'/'. $hash);

						// Now send the email
						$this->load->library('emailer/emailer');

						$data = array(
									'to'	=> $_POST['email'],
									'subject'	=> lang('us_reset_pass_subject'),
									'message'	=> $this->load->view('_emails/forgot_password', array('link' => $pass_link), TRUE)
							 );

						if ($this->emailer->send($data))
						{
							Template::set_message(lang('us_reset_pass_message'), 'success');
						}
						else
						{
							Template::set_message(lang('us_reset_pass_error'). $this->emailer->error, 'error');
						}
					}
					else
					{
						Template::set_message(lang('us_invalid_email'), 'error');
					}//end if
				}//end if
			}//end if

			Template::set_view('users/users/forgot_password');
			Template::set('page_title', 'Password Reset');
			Template::render();
		}
		else
		{

			Template::redirect('/');
		}//end if

	}//end forgot_password()

	//--------------------------------------------------------------------

	/**
	 * Allows a user to edit their own profile information.
	 *
	 * @access public
	 *
	 * @return void
	 */
	public function profile()
	{
		// Make sure we're logged in.
		$this->auth->restrict();
		$this->set_current_user();

		$this->load->helper('date');

		$this->load->config('address');
		$this->load->helper('address');

		$this->load->config('user_meta');
		$meta_fields = config_item('user_meta_fields');

		Template::set('meta_fields', $meta_fields);

		if (isset($_POST['save']))
		{

			$user_id = $this->current_user->id;
			if ($this->save_user($user_id, $meta_fields))
			{

				$meta_data = array();
				foreach ($meta_fields as $field)
				{
					if ((!isset($field['admin_only']) || $field['admin_only'] === FALSE
						|| (isset($field['admin_only']) && $field['admin_only'] === TRUE
							&& isset($this->current_user) && $this->current_user->role_id == 1))
						&& (!isset($field['frontend']) || $field['frontend'] === TRUE)
						&& $this->input->post($field['name']) !== FALSE)
					{
						$meta_data[$field['name']] = $this->input->post($field['name']);
					}
				}

				// now add the meta is there is meta data
				$this->user_model->save_meta_for($user_id, $meta_data);

				// Log the Activity

				$user = $this->user_model->find($user_id);
				$log_name = (isset($user->display_name) && !empty($user->display_name)) ? $user->display_name : ($this->settings_lib->item('auth.use_usernames') ? $user->username : $user->email);
				log_activity($this->current_user->id, lang('us_log_edit_profile') . ': ' . $log_name, 'users');

				Template::set_message(lang('us_profile_updated_success'), 'success');

				// redirect to make sure any language changes are picked up
				Template::redirect('/users/profile');
			}
			else
			{
				Template::set_message(lang('us_profile_updated_error'), 'error');
			}//end if
		}//end if

		// get the current user information
		$user = $this->user_model->find_user_and_meta($this->current_user->id);

        $settings = $this->settings_lib->find_all();
        if ($settings['auth.password_show_labels'] == 1) {
            Assets::add_module_js('users','password_strength.js');
            Assets::add_module_js('users','jquery.strength.js');
            Assets::add_js($this->load->view('users_js', array('settings'=>$settings), true), 'inline');
        }
        // Generate password hint messages.
		$this->user_model->password_hints();

		Template::set('user', $user);
		Template::set('languages', unserialize($this->settings_lib->item('site.languages')));

		Template::set_view('users/users/profile');
		Template::render();

	}//end profile()

	//--------------------------------------------------------------------

	/**
	 * Allows the user to create a new password for their account. At the moment,
	 * the only way to get here is to go through the forgot_password() process,
	 * which creates a unique code that is only valid for 24 hours.
	 *
	 * Since 0.7 this method is also gotten to here by the force_password_reset
	 * security features.
	 *
	 * @access public
	 *
	 * @param string $email The email address to check against.
	 * @param string $code  A randomly generated alphanumeric code. (Generated by forgot_password() ).
	 *
	 * @return void
	 */
	public function reset_password($email='', $code='')
	{
		// if the user is not logged in continue to show the login page
		if ($this->auth->is_logged_in() === FALSE)
		{
			// If we're set here via Bonfire and not an email link
			// then we might have the email and code in the session.
			if (empty($code) && $this->session->userdata('pass_check'))
			{
				$code = $this->session->userdata('pass_check');
			}

			if (empty($email) && $this->session->userdata('email'))
			{
				$email = $this->session->userdata('email');
			}

			// If there is no code, then it's not a valid request.
			if (empty($code) || empty($email))
			{
				Template::set_message(lang('us_reset_invalid_email'), 'error');
				Template::redirect(LOGIN_URL);
			}

			// Handle the form
			if (isset($_POST['set_password']))
			{
				$this->form_validation->set_rules('password', 'lang:bf_password', 'required|max_length[120]|valid_password');
				$this->form_validation->set_rules('pass_confirm', 'lang:bf_password_confirm', 'required|matches[password]');

				if ($this->form_validation->run() !== FALSE)
				{
					// The user model will create the password hash for us.
					$data = array('password' => $this->input->post('password'),
					              'reset_by'	=> 0,
					              'reset_hash'	=> '',
					              'force_password_reset' => 0);

					if ($this->user_model->update($this->input->post('user_id'), $data))
					{
						// Log the Activity
						log_activity($this->input->post('user_id'), lang('us_log_reset') , 'users');

						Template::set_message(lang('us_reset_password_success'), 'success');
						Template::redirect(LOGIN_URL);
					}
					else
					{
						Template::set_message(sprintf(lang('us_reset_password_error'), $this->user_model->error), 'error');

					}
				}
			}//end if

			// Check the code against the database
			$email = str_replace(':', '@', $email);
			$user = $this->user_model->find_by(array(
                                        'email' => $email,
										'reset_hash' => $code,
										'reset_by >=' => time()
                                   ));

			// It will be an Object if a single result was returned.
			if (!is_object($user))
			{
				Template::set_message( lang('us_reset_invalid_email'), 'error');
				Template::redirect(LOGIN_URL);
			}

            $settings = $this->settings_lib->find_all();
            
            if ($settings['auth.password_show_labels'] == 1) {
                Assets::add_module_js('users','password_strength.js');
                Assets::add_module_js('users','jquery.strength.js');
                Assets::add_js($this->load->view('users_js', array('settings'=>$settings), true), 'inline');
            }
            // If we're here, then it is a valid request....
			//Template::set_theme("admin");
			Template::set('user', $user);

			Template::set_view('users/users/reset_password');
			Template::render();
		}
		else
		{

			Template::redirect('/');
		}//end if

	}//end reset_password()

	//--------------------------------------------------------------------

	/**
	 * Display the registration form for the user and manage the registration process
	 *
	 * @access public
	 *
	 * @return void
	 */
	 

	//--------------------------------------------------------------------

	/**
	 * Save the user
	 *
	 * @access private
	 *
	 * @param int   $id          The id of the user in the case of an edit operation
	 * @param array $meta_fields Array of meta fields fur the user
	 *
	 * @return bool
	 */
	private function save_user($id=0, $meta_fields=array())
	{

		if ( $id == 0 )
		{
			$id = $this->current_user->id; /* ( $this->input->post('id') > 0 ) ? $this->input->post('id') :  */
		}

		$_POST['id'] = $id;

		// Simple check to make the posted id is equal to the current user's id, minor security check
		if ( $_POST['id'] != $this->current_user->id )
		{
			$this->form_validation->set_message('email', 'lang:us_invalid_userid');
			return FALSE;
		}

		// Setting the payload for Events system.
		$payload = array ( 'user_id' => $id, 'data' => $this->input->post() );


		$this->form_validation->set_rules('email', 'lang:bf_email', 'required|trim|valid_email|max_length[120]|unique[users.email,users.id]');
		$this->form_validation->set_rules('password', 'lang:bf_password', 'max_length[120]|valid_password');

		// check if a value has been entered for the password - if so then the pass_confirm is required
		// if you don't set it as "required" the pass_confirm field could be left blank and the form validation would still pass
		$extra_rules = !empty($_POST['password']) ? 'required|' : '';
		$this->form_validation->set_rules('pass_confirm', 'lang:bf_password_confirm', ''.$extra_rules.'matches[password]');

		$username_required = '';
		if ($this->settings_lib->item('auth.login_type') == 'username' ||
		    $this->settings_lib->item('auth.use_usernames'))
		{
			$username_required = 'required|';
		}
		$this->form_validation->set_rules('username', 'lang:bf_username', $username_required . 'trim|max_length[30]|unique[users.username,users.id]');

		$this->form_validation->set_rules('language', 'lang:bf_language', 'required|trim');
		$this->form_validation->set_rules('timezones', 'lang:bf_timezone', 'required|trim|max_length[4]');
		$this->form_validation->set_rules('display_name', 'lang:bf_display_name', 'trim|max_length[255]');

		// Added Event "before_user_validation" to run before the form validation
		Events::trigger('before_user_validation', $payload );


		foreach ($meta_fields as $field)
		{
			if ((!isset($field['admin_only']) || $field['admin_only'] === FALSE
				|| (isset($field['admin_only']) && $field['admin_only'] === TRUE
					&& isset($this->current_user) && $this->current_user->role_id == 1))
				&& (!isset($field['frontend']) || $field['frontend'] === TRUE))
			{
				$this->form_validation->set_rules($field['name'], $field['label'], $field['rules']);
			}
		}


		if ($this->form_validation->run() === FALSE)
		{
			return FALSE;
		}

		// Compile our core user elements to save.
		$data = array(
			'email'		=> $this->input->post('email'),
			'language'	=> $this->input->post('language'),
			'timezone'	=> $this->input->post('timezones'),
		);

		// If empty, the password will be left unchanged.
		if ($this->input->post('password') !== '')
		{
			$data['password'] = $this->input->post('password');
		}

		if ($this->input->post('display_name') !== '')
		{
			$data['display_name'] = $this->input->post('display_name');
		}

		if (isset($_POST['username']))
		{
			$data['username'] = $this->input->post('username');
		}

		// Any modules needing to save data?
		// Event to run after saving a user
		Events::trigger('save_user', $payload );

		return $this->user_model->update($id, $data);

	}//end save_user()

	//--------------------------------------------------------------------

		//--------------------------------------------------------------------
		// ACTIVATION METHODS
		//--------------------------------------------------------------------
		/*
			Activate user.

			Checks a passed activation code and if verified, enables the user
			account. If the code fails, an error is generated and returned.

		*/
		public function activate($user_id = NULL)
		{
			if (isset($_POST['activate']))
			{
				$this->form_validation->set_rules('code', 'Verification Code', 'required|trim');
				if ($this->form_validation->run() == TRUE)
				{
					$code = $this->input->post('code');

					$activated = $this->user_model->activate($user_id, $code);
					if ($activated)
					{
						$user_id = $activated;

						// Now send the email
						$this->load->library('emailer/emailer');

						$site_title = $this->settings_lib->item('site.title');

						$email_message_data = array(
							'title' => $site_title,
							'link'  => site_url(LOGIN_URL)
						);
						$data = array
						(
							'to'		=> $this->user_model->find($user_id)->email,
							'subject'	=> lang('us_account_active'),
							'message'	=> $this->load->view('_emails/activated', $email_message_data, TRUE)
						);

						if ($this->emailer->send($data))
						{
							Template::set_message(lang('us_account_active'), 'success');
						}
						else
						{
							Template::set_message(lang('us_err_no_email'). $this->emailer->error, 'error');
						}
						Template::redirect('/');
					}
					else
					{
						Template::set_message($this->user_model->error.'. '. lang('us_err_activate_code'), 'error');
					}
				}
			}
			Template::set_view('users/users/activate');
			Template::set('page_title', 'Account Activation');
			Template::render();
		}

		//--------------------------------------------------------------------

		/*
			   Method: resend_activation

			   Allows a user to request that their activation code be resent to their
			   account's email address. If a matching email is found, the code is resent.
		   */
	public function send_password()
    {
        // If the user is logged in, go home.
        if ($this->auth->is_logged_in() !== false) {
            Template::redirect('/');
        }

        if (isset($_POST['send'])) {

            // Validate the form to ensure a valid email was entered.
            $this->form_validation->set_rules('email', 'lang:bf_email', 'required|trim');
            if ($this->form_validation->run() !== false) {

            	// cek pegawai by nip
				$pegawaiData = $this->pegawai_model->find_by("NIP_BARU",$this->input->post('email'));
				$EMAIL = ISSET($pegawaiData->EMAIL) ? trim($pegawaiData->EMAIL) : "";
				$EMAIL_DIKBUD = ISSET($pegawaiData->EMAIL_DIKBUD) ? trim($pegawaiData->EMAIL_DIKBUD) : "";
				if($EMAIL_DIKBUD != ""){
					$EMAIL = $EMAIL_DIKBUD;
				}

                // Validation passed. Does the user actually exist?
                $user = $this->user_model->find_by('username', $this->input->post('email'));
                if ($user === false) {
                	die("Data tidak ditemukan");
                    // No user found with the entered email address.
                    Template::set_message(lang('us_invalid_email'), 'error');
                } else {
                	if($EMAIL == ""){
                		die("EMAIL tidak ditemukan");
                	}
                    // User exists, create a hash to confirm the reset request.
                    $this->load->helper('string');
                    $hash = sha1(random_string('alnum', 40) . $this->input->post('email'));

                    // Save the hash to the db for later retrieval.
                    $this->user_model->update_where(
                        'username',
                        $this->input->post('email'),
                        array('reset_hash' => $hash,'email' => $EMAIL, 'reset_by' => strtotime("+24 hours"))
                    );

                    // Create the link to reset the password.
                    $pass_link = site_url('reset_password/' . str_replace('@', ':', $EMAIL) . "/{$hash}");

                    // Now send the email
                    $this->load->library('emailer/emailer');
                    $data = array(
                        'to'      => $EMAIL,
                        'subject' => lang('us_reset_pass_subject'),
                        'message' => $this->load->view(
                            '_emails/forgot_password',
                            array('link' => $pass_link),
                            true
                        ),
                     );
                    if ($this->emailer->send($data)) {
                        Template::set_message(lang('us_reset_pass_message'), 'success');
                        echo "Sukses";
                    } else {
                        echo "ada masalah dalam pengiriman email";
                        Template::set_message(lang('us_reset_pass_error') . $this->emailer->error, 'error');
                    }
                }
            }else{
                die("Silahkan isi NIP anda");
            }
        }else{
            die("Silahkan isi NIP anda");
        }
    }
		public function resend_activation()
		{
			if (isset($_POST['send']))
			{
				$this->form_validation->set_rules('email', 'lang:bf_email', 'required|trim|valid_email');

				if ($this->form_validation->run())
				{
					// We validated. Does the user actually exist?
					$user = $this->user_model->find_by('email', $_POST['email']);

					if ($user !== FALSE)
					{
						// User exists, so create a temp password.
						$this->load->helpers(array('string', 'security'));

						$pass_code = random_string('alnum', 40);

						$activation_code = do_hash($pass_code . $user->password_hash . $_POST['email']);

						$site_title = $this->settings_lib->item('site.title');

						// Save the hash to the db so we can confirm it later.
						$this->user_model->update_where('email', $_POST['email'], array('activate_hash' => $activation_code ));

						// Create the link to reset the password
						$activate_link = site_url('activate/'. str_replace('@', ':', $_POST['email']) .'/'. $activation_code);

						// Now send the email
						$this->load->library('emailer/emailer');

						$email_message_data = array(
							'title' => $site_title,
							'code'  => $activation_code,
							'link'  => $activate_link
						);

						$data = array
						(
							'to'		=> $_POST['email'],
							'subject'	=> 'Activation Code',
							'message'	=> $this->load->view('_emails/activate', $email_message_data, TRUE)
						);
						$this->emailer->enable_debug(true);
						if ($this->emailer->send($data))
						{
							Template::set_message(lang('us_check_activate_email'), 'success');
						}
						else
						{
							Template::set_message(lang('us_err_no_email').$this->emailer->error.", ".$this->emailer->debug_message, 'error');
						}
					}
					else
					{
						Template::set_message('Cannot find that email in our records.', 'error');
					}
				}
			}
			Template::set_view('users/users/resend_activation');
			Template::set('page_title', 'Activate Account');
			Template::render();
		}
	public function getcaptcha()
    {
       $string = "";

       for ($i = 0; $i < 3; $i++) {
        $string .= rand(1,9);
       }
       
       $this->session->unset_userdata('number');
       $this->session->set_userdata('number',$string);
       $dir = FCPATH.'assets/fonts/';
       $image = imagecreatetruecolor(165, 50);
       // random number 1 or 2
       $num = rand(1,2);
       
       if($num==1){
         $font = "Capture it 2.ttf"; // font style
       } else {
         $font = "Molot.otf";// font style
       }

       // random number 1 or 2
       $num2 = rand(1,2);
       
       if($num2==1) {
         $color = imagecolorallocate($image, 113, 193, 217);// color
       } else {
         $color = imagecolorallocate($image, 163, 197, 82);// color
       }

       $white = imagecolorallocate($image, 255, 255, 255); // background color white
       imagefilledrectangle($image,0,0,399,99,$white);
       imagettftext ($image, 30, 0, 10, 40, $color, $dir.$font, $this->session->userdata('number'));

       header("Content-type: image/png");
       imagepng($image);
     }

}//end Users

/* Front-end Users Controller */
/* End of file users.php */
