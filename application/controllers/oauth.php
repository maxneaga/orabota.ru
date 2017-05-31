<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Oauth extends CI_Controller {

    function __construct()
	{
		parent::__construct();
        $this->lang->load('global', $this->language->get());
	}
	

	public function index()
	{
	    if (!isset($_GET['access_token'])) {
            $this->session->set_flashdata('success_msg', lang('allow_view_email'));
	        echo '<script type="text/javascript">
                    if (window.location.hash.substring(1) == \'error=access_denied\') {
                        window.location.href = \''.$this->config->item('base_url').'\';
                    }
                    else {
                        window.location.href = \''.$this->config->item('base_url').'oauth?\'+window.location.hash.substring(1);
                    }
                </script>';
	        return;
	    }
	    
	    $token = $_GET['access_token'];
	    $json = file_get_contents('https://www.googleapis.com/oauth2/v1/userinfo?access_token='.$token);
	    $oauth = json_decode($json);

        $userdata = array(
            'first_name' => '',
            'last_name' => '',
            'birth_date' => ''
        );

        if (property_exists($oauth, 'given_name'))
            $userdata['first_name'] = $oauth->given_name;

        if (property_exists($oauth, 'family_name'))
            $userdata['last_name'] = $oauth->family_name;

        if (property_exists($oauth, 'birthday'))
            $userdata['birth_date'] = $oauth->birthday;

	    if (!$this->ion_auth->login($oauth->email, '', false, true))
        { 

            if($this->ion_auth->register($oauth->email, '', $userdata)) 
            {
                // User Created, Log in
                $this->ion_auth->login($oauth->email, '', false, true);
                redirect('/home', 'refresh');
            }
            else
            {
                echo 'OAuth Error. Please, report.';
                exit;
            }
        }

        // else login successful
        $user = $this->ion_auth->user()->row();
        // Check if no firts & last names & birth_date => get them from Google
        if ($user->first_name == '' && $user->last_name == '' && $user->birth_date == '0000-00-00')
        {
            $this->ion_auth->update($user->id, $userdata);
        }
        redirect('/home', 'refresh');
	}
}

	
/* End of file oauth.php */
/* Location: ./application/controllers/oauth.php */