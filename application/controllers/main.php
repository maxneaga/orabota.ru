<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main extends CI_Controller {

    function __construct()
	{
		parent::__construct();
		
		$this->load->library('form_validation');
		$this->lang->load('main', $this->language->get());
		
		$this->data['title'] = lang('site_title_main');
        $this->data['site_description'] = lang('site_description_main');
	}
	
	
	
	public function index()
	{
		if ($this->ion_auth->logged_in())
		{   // User logged in
            redirect('/home', 'refresh');
		}
		else
		{   // Display Langing Page

            // Generate "Sign In with Google" link
            $ourl = 'https://accounts.google.com/o/oauth2/auth?';
            $ourl .= 'scope=https%3A%2F%2Fwww.googleapis.com%2Fauth%2Fuserinfo.profile%20https%3A%2F%2Fwww.googleapis.com%2Fauth%2Fuserinfo.email&';
            $ourl .= 'redirect_uri='.$this->config->item('base_url').'oauth&';
            $ourl .= 'response_type=token&';
            $ourl .= 'client_id='.$this->config->item('oauth_clientid');
            $this->data['oauth_link'] = $ourl;

            $this->load->view('global_header.php', $this->data);
			$this->load->view('main.php', $this->data);
			$this->load->view('global_footer.php');
		}
	}
	
	
	
	function forgot_password()
	{
        $this->config->set_item('language', $this->language->get());
		$this->form_validation->set_rules('email', 'Email', 'required');

        $this->data['title'] = lang('forgot_password').' - orabota';
        $this->data['site_description'] = lang('forgot_password_desc');
		
		if ($this->form_validation->run() == false)
		{
			//set any errors and display the form
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->load->view('global_header.php', $this->data);
			$this->load->view('forgot_password', $this->data);
			$this->load->view('global_footer.php');
		}
		else
		{
			//run the forgotten password method to email an activation code to the user
			$forgotten = $this->ion_auth->forgotten_password($this->input->post('email'));
			
			$this->session->set_flashdata('message', $this->ion_auth->messages().$this->ion_auth->errors());
			redirect("/main/forgot_password", 'refresh');
		}
	}
	
	
	/**
     * Reset the user's password using reset code
     **/
	public function reset_password($code = NULL)
	{
		if (!$code)
		{
			show_404();
		}

        $this->config->set_item('language', $this->language->get());
		$user = $this->ion_auth->forgotten_password_check($code);

		if ($user)
		{  
			//if the code is valid then display the password reset form
			$this->form_validation->set_rules('new_password', '<strong>'.lang('new_password').'</strong>', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[new_password_confirm]');
			$this->form_validation->set_rules('new_password_confirm', '<strong>'.lang('confirm_new_pass').'</strong>', 'required');

			if ($this->form_validation->run() == false)
			{   //display the form

				//set the flash data error message if there is one
				$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

				$this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
				$this->data['new_password'] = array(
					'name' => 'new_password',
					'id'   => 'new_password',
				    'type' => 'password'
				);
				$this->data['new_password_confirm'] = array(
					'name' => 'new_password_confirm',
					'id'   => 'new_password_confirm',
					'type' => 'password'
				);
				$this->data['user_id'] = array(
					'name'  => 'user_id',
					'id'    => 'user_id',
					'type'  => 'hidden',
					'value' => $user->id,
				);

				$this->data['code'] = $code;

				//render
			    $this->load->view('global_header.php', $this->data);
				$this->load->view('reset_password', $this->data);
				$this->load->view('global_footer.php');
			}
			else
			{
				// finally change the password
				$identity = $user->{$this->config->item('identity', 'ion_auth')};

				$change = $this->ion_auth->reset_password($identity, $this->input->post('new_password'));

				if ($change)
				{ 
					//if the password was successfully changed
					$this->session->set_flashdata('message', $this->ion_auth->messages());
					redirect('/', 'refresh');
				}
				else
				{
					$this->session->set_flashdata('message', $this->ion_auth->errors());
					redirect('auth/reset_password/' . $code, 'refresh');
				}
			}
		}
		else
		{
			//if the code is invalid then send them back to the forgot password page
			$this->session->set_flashdata('message', $this->ion_auth->errors());
			redirect("/main/forgot_password", 'refresh');
		}
	}
	
	
	/**
     * Set the user's language preference
     *
     * @param       $lang       shorthang language preference (ex: 'en')
     **/
	function lang($lang = 'en')
	{
	    $this->language->set($lang);
	    redirect('/', 'refresh');
	}
	
	
	/**
     * Log the user out
     **/
	function logout()
	{
		$logout = $this->ion_auth->logout();

		//redirect them to the login page
		$this->session->set_flashdata('message', $this->ion_auth->messages());
		redirect('/', 'refresh');
	}


    /**
     * Unsubscribe
     **/
	function unsubscribe($email)
    {
        $email = str_replace('%40', '@', $email);

        $data = array(
            'unsubscribed' => 1
        );

        $this->db->where('email', $email);
        $this->db->update('employers', $data);

        $data = array(
            'unsubscribed' => 1
        );

        $this->db->where('email', $email);
        $this->db->update('employer_ads', $data);

        $this->session->set_flashdata('success_msg', 'You have successfully unsubscribed!');

        if ($this->ion_auth->logged_in())
		{   // User logged in
            redirect('/home', 'refresh');
		}

        redirect('/', 'refresh');
    }


    /***********************
     *  Processing Functions
     ***********************/
	
    /**
     * Activated user via activation code
     */
	function activate($id, $code=false)
	{
        $this->ion_auth->activate($id, $code);
		$this->session->set_flashdata('message', $this->ion_auth->messages());
		redirect("/", 'refresh');
	}
	
}

/* End of file main.php */
/* Location: ./application/controllers/main.php */
