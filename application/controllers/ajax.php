<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ajax extends CI_Controller {

    function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
        $this->load->model('Employer_model', 'employer');
		$this->load->model('Employee_model', 'employee');
        $this->config->set_item('language', $this->language->get());
        $this->lang->load('global');
        $this->lang->load('home');

		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
	    $this->user = $this->ion_auth->user()->row();
    }


	/**
     * Logs user in / Creates a new user via AJAX
     */
	public function login_ajax()
	{
        $this->data['errors'] = ''; // Needs to be initialized for ajax request

	    // Validate form inputs
	    $this->form_validation->set_rules('email', '<strong>E-mail</strong>', 'required|valid_email|max_length[25]');
	    $this->form_validation->set_rules('password', '<strong>'.lang('password').'</strong>', 'required|min_length[3]|max_length[20]');
	    
	    
	    if ($this->form_validation->run() == true)
	    {   // Form submitted, validation passed
	    
            // Check for "remember me"
            $remember = (bool) $this->input->post('remember');

            if (!$this->ion_auth->login($this->input->post('email'), $this->input->post('password'), $remember))
            { 
                // Create a new user account
                $email = $this->input->post('email');
                $password = $this->input->post('password');

                // Check if daily signup limit per ip not exceeded
                if ($this->employer->limit_exceeded('signup', 5))
                {
                    $this->data['errors'] = lang('spam_activity_suspected');
                    echo json_encode($this->data);
                    return FALSE;
                }

                if($this->ion_auth->register($email, $password, array())) 
                {
                    // Add record to db
                    $this->employer->log('signup');

                    // User Created, Log in
                    $this->ion_auth->login($this->input->post('email'), $this->input->post('password'), $remember);
                }
                else
                {   // User exists
                    $this->data['errors'] = $this->ion_auth->errors();
                }
            }

	    }
	    else
	    {
		    $this->data['errors'] = validation_errors();
	    }
        
        echo json_encode($this->data);
	}


    /**
     * Send recommendation request via AJAX
     */
	public function recommend_me_ajax()
	{
        $this->data['errors'] = ''; // Needs to be initialized for ajax request

        $this->lang->load('email/recommend_email');

	    // Validate form inputs
	    $this->form_validation->set_rules('email', '<strong>E-mail</strong>', 'required|valid_email|max_length[25]');
	    $this->form_validation->set_rules('first_name', '<strong>'.lang('first_name').'</strong>', 'required');
        $this->form_validation->set_rules('last_name', '<strong>'.lang('last_name').'</strong>', 'required');
        $this->form_validation->set_rules('bd_year', '<strong>'.lang('birth_year').'</strong>', 'required|integer|greater_than[1930]|less_than[2000]');
        $this->form_validation->set_rules('bd_month', '<strong>'.lang('birth_month').'</strong>', 'required|integer|greater_than[0]|less_than[13]');
        $this->form_validation->set_rules('bd_day', '<strong>'.lang('birth_day').'</strong>', 'required|integer|greater_than[0]|less_than[32]');
	    
	    
	    if ($this->form_validation->run() == true)
	    {   // Form submitted, validation passed

            // Check for valid date
            if  (!checkdate($this->input->post('bd_month'), $this->input->post('bd_day'), $this->input->post('bd_year')))
            {
                $this->data['errors'] = lang('invalid_date');
                echo json_encode($this->data);
                return FALSE;
            }

            // Update user's data
            $userdata = array(
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name'),
                'birth_date' => $this->input->post('bd_year').'-'.$this->input->post('bd_month').'-'.$this->input->post('bd_day')
            );
            $this->ion_auth->update($this->user->id, $userdata);
	    
            $this->data['first_name'] = $this->input->post('first_name');
            $this->data['last_name'] = $this->input->post('last_name');
            $this->data['bd_year'] = $this->input->post('bd_year');
            $this->data['bd_month'] = $this->input->post('bd_month');
            $this->data['bd_day'] = $this->input->post('bd_day');
            
            // Check if daily limit per ip not exceeded
            if ($this->employer->limit_exceeded('recommend_'.$this->input->post('email'), 2))
            {
                $this->data['errors'] = lang('recommend_limit_exceeded');
                echo json_encode($this->data);
                return FALSE;
            }

	        // Build & send the e-mail
            $subject = lang('request_from').' '.$this->data['first_name'].' '.$this->data['last_name'];
            $message = $this->load->view('email/recommend.php', $this->data, true);
            email($this->input->post('email'), $subject, $message);
            
            // Add record to db
            $this->employer->log('recommend_'.$this->input->post('email'));

            // Set success message & redirect
            $this->session->set_flashdata('success_msg', lang('recommend_request_sent'));
	    }
	    else
	    {
		    $this->data['errors'] = validation_errors();
	    }
        
        echo json_encode($this->data);
	}


    /**
     * Creates a new user organization via AJAX
     */
	public function new_org_ajax()
	{
        $this->data['errors'] = ''; // Needs to be initialized for ajax request

	    // Validate form inputs
	    $this->form_validation->set_rules('organization', '<strong>'.lang('company').'</strong>', 'required|min_length[3]');
	    $this->form_validation->set_rules('country', '<strong>'.lang('country').'</strong>', 'required');
	    $this->form_validation->set_rules('job_title', '<strong>'.lang('job_title').'</strong>', 'required');
        $this->form_validation->set_rules('region', '<strong>'.lang('region').'</strong>', 'required');
	    
	    
	    if ($this->form_validation->run() == true)
	    {   // Form submitted, validation passed
	    
	        // check if valid country
	        if ($this->db->get_where('countries', array('iso' => $this->input->post('country')))->num_rows() !== 0)
	        {
	            // Add it to DB (return id of the inserted row on success or false on failure)
	            $result = $this->employer->add_org($this->input->post('organization'), $this->input->post('country'), $this->input->post('region'), $this->input->post('job_title'));
	            $this->session->set_flashdata('new_org_id', $result);

	            if (!$result)
	            {
	                $this->data['errors'] = 'Sorry, something went wrong. Please, contact us and try again later.';
	            }
	        }
	        else
	        {
	            $this->data['errors'] = 'Invalid country';
	        }
	    }
	    else
	    {
		    $this->data['errors'] = validation_errors();
	    }
        
        echo json_encode($this->data);
	}

    
    /**
     * Validate new employee review via AJAX
     *
     * Creates employee, if doesnt exist
     * Updates its rating if org is verified
     * Adds review comment
     *
     */
    public function employee_review_ajax()
    {
        $this->data['errors'] = '';

        // Check for "birth date unknown"
        $nodob = (bool) $this->input->post('nodob');
        
        $this->form_validation->set_rules('first_name', '<strong>'.lang('first_name').'</strong>', 'required');
        $this->form_validation->set_rules('last_name', '<strong>'.lang('last_name').'</strong>', 'required');
        if (!$nodob)
        {
            $this->form_validation->set_rules('bd_year', '<strong>'.lang('birth_year').'</strong>', 'required|integer|greater_than[1930]|less_than[2000]');
            $this->form_validation->set_rules('bd_month', '<strong>'.lang('birth_month').'</strong>', 'required|integer|greater_than[0]|less_than[13]');
            $this->form_validation->set_rules('bd_day', '<strong>'.lang('birth_day').'</strong>', 'required|integer|greater_than[0]|less_than[32]');
        }
        $this->form_validation->set_rules('job_title', '<strong>'.lang('job_title').'</strong>', 'required');
        $this->form_validation->set_rules('rating', '<strong>Rating</strong>', 'required');
        $this->form_validation->set_rules('tos_agree', '<strong>'.lang('tos').'</strong>', 'required');
        
        // Run validation
        if (!$this->form_validation->run())
        {
            $this->data['errors'] = validation_errors();
        }
        
        elseif  (!checkdate($this->input->post('bd_month'), $this->input->post('bd_day'), $this->input->post('bd_year')) && !$nodob)
        {
            $this->data['errors'] = lang('invalid_date');
        }

        else {
            // check if the organization really belongs to employer
	        if (!$this->employer->belongs($this->input->post('orgid')))
	        { 
	            return;
	        }
	    
	        // format birth date
	        $birth_date = $this->input->post('bd_year').'-'.$this->input->post('bd_month').'-'.$this->input->post('bd_day');
            if ($nodob)
            {
                $birth_date = '0000-00-00';
            }
	        // add employee to DB, if not exists
	        $employee_id = $this->employee->add($this->input->post('first_name'), $this->input->post('last_name'), $birth_date, $this->input->post('first_name_alt'), $this->input->post('last_name_alt'));
	        // Check if this employee already has a comment from this user
            if ($this->employee->reviewed_by($employee_id, $this->input->post('orgid')))
            {
                $this->data['errors'] = lang('user_already_reviewed');
                echo json_encode($this->data);
                return;
            }

            // calculate the rating
	        $rating = $this->employer->calculate_review_rating($this->input->post('rating'));
	        // check if org verified -> update employee's rating
	        if ($this->employer->is_org_verified($this->input->post('orgid')))
	        {
	            $this->employee->update_rating($employee_id, $rating);
	        }
	        // add comment
	        $this->employee->add_comment($employee_id, $this->input->post('orgid'), $this->input->post('job_title'), $this->input->post('comment'), $rating);

            // Set thank you message
            $this->session->set_flashdata('success_msg', lang('thank_you_for_review'));
        }
        
        echo json_encode($this->data);
    }
    
    
    /**
     * Update user's password via AJAX
     */
    public function update_password_ajax()
    {
        $this->data['errors'] = '';
        
        $this->form_validation->set_rules('new_password', '<strong>'.lang('password').'</strong>', 'required|min_length[3]|max_length[20]');
        
        if (!$this->form_validation->run())
        {
            $this->data['errors'] = validation_errors();
        }
        else
        {
            // Update password
            $this->ion_auth->user()->update($this->user->id, array('password' => $this->input->post('new_password')));
        }
        echo json_encode($this->data);
    }


    /**
     * Toggle private messages acceptance via AJAX
     */
    public function set_accept_pms_ajax($accept)
    {
        if ($accept == 'true')
        {
            $this->ion_auth->user()->update($this->user->id, array('accept_pms' => 1));
        }
        else
        {
            $this->ion_auth->user()->update($this->user->id, array('accept_pms' => 0));
        }
    }


    /**
     * Contact us form
     */
    public function contact_us_ajax()
    {
        $this->data['errors'] = '';
        
        $this->form_validation->set_rules('email', '<strong>E-mail</strong>', 'required|valid_email');
        $this->form_validation->set_rules('message', '<strong>'.lang('contact_message').'</strong>', 'required');
        
        if (!$this->form_validation->run())
        {
            $this->data['errors'] = validation_errors();
        }
        else
        {
            // Check if daily limit per ip not exceeded
            if ($this->employer->limit_exceeded('contact_form_mail', 3))
            {
                $this->data['errors'] = lang('spam_activity_suspected');
                echo json_encode($this->data);
                return FALSE;
            }

            // Build & send the e-mail
            $message = $this->input->post('email').'<br>'.$this->input->post('message');
            email('max@maxsites.net', 'orabota Contact Form message', $message);

            // Add record to db
            $this->employer->log('contact_form_mail');
        }
        
        echo json_encode($this->data);
    }


    /**
     * Contact review author form
     */
    public function contact_review_author_ajax()
    {
        $this->data['errors'] = '';

        // Make sure the user is logged in
        if (!$this->ion_auth->logged_in())
        {
            $this->data['errors'] = lang('spam_activity_suspected');
            echo json_encode($this->data);
            return FALSE;
        }
        
        // Validation
        $this->form_validation->set_rules('message', '<strong>'.lang('contact_message').'</strong>', 'required');
        
        if (!$this->form_validation->run())
        {
            $this->data['errors'] = validation_errors();
        }
        else
        {
            // Check if valid orgid provided
            if (! ($org = $this->employer->get_org($this->input->post('orgid'))))
            {
                $this->data['errors'] = 'Error: bad orgid.';
                echo json_encode($this->data);
                return FALSE;
            }

            // Check if user's private messages are enabled
            if (! $this->employer->get($org->employer_id)->accept_pms)
            {
                $this->data['errors'] = 'This user does not accept private messages';
                echo json_encode($this->data);
                return FALSE;
            }

            // Get recipient's e-mail
            $recipient_email = $this->employer->get($org->employer_id)->email;

            // Check if daily limit per ip not exceeded
            if ($this->employer->limit_exceeded('contact_'.$recipient_email, 2) || $this->employer->limit_exceeded('contact_from_'.$this->user->email, 4))
            {
                $this->data['errors'] = lang('spam_activity_suspected');
                echo json_encode($this->data);
                return FALSE;
            }

            // Build & send the e-mail
            $message = lang('new_pm_disclaimer');
            $message .= $this->input->post('message');
            $message .= lang('new_pm_avoid');
            if (!email($recipient_email, lang('new_private_message_regarding').' '.$this->input->post('subject'), $message, $this->user->email))
            {
                $this->employer->log('err_contact_'.$recipient_email);
                $this->data['errors'] = lang('could_not_send');
                echo json_encode($this->data);
                return FALSE;
            }

            // Add record to db
            $this->employer->log('contact_'.$recipient_email);
            $this->employer->log('contact_from_'.$this->user->email);
        }
        
        echo json_encode($this->data);
    }


    /**
     * Get employee review details
     */
    public function get_review_details_ajax()
    {
        $this->data['errors'] = '';
        
        $comments = $this->employee->get_comments($this->input->post('employee_id'), $this->input->post('org_id'));

        if ($comments)
        {
            $this->data['comment'] = $comments[0];
        }
        else
        {
            $this->data['errors'] = 'crap data';
        }

        echo json_encode($this->data);
    }


    /**
     * Set employee review rating
     */
    public function set_employee_rating()
    {
        $this->employer->set_review_rating($this->input->post('comment_id'), $this->input->post('rating'));
    }

    /**
     * Set employee review comment
     */
    public function set_employee_comment()
    {
        $this->employer->set_review_comment($this->input->post('comment_id'), $this->input->post('comment'));
    }


   /***********************
    *
    *  Autocomplete
    *
    ***********************/

    /**
    * Organizations
    */
    public function autocomplete_orgs()
    {
        $orgs = $this->employer->search_org($this->input->post('organization'), 0, 5);
        
        $this->data['org_names'] = array();
        
        foreach ($orgs as $org)
            array_push($this->data['org_names'], $org->organization);
            
        echo json_encode($this->data);
    }


    /**
    * Job Titles
    */
    public function autocomplete_job_titles()
    {
        $job_titles = $this->employee->search_job_titles($this->input->post('job_title'), 0, 5);
        
        $this->data['job_titles'] = array();
        
        foreach ($job_titles as $title)
            array_push($this->data['job_titles'], $title->job_title);
            
        echo json_encode($this->data);
    }


    /**
    * Employers' Job Titles
    */
    public function autocomplete_job_titles_employers()
    {
        $job_titles = $this->employer->search_job_titles($this->input->post('job_title'), 0, 5);
        
        $this->data['job_titles'] = array();
        
        foreach ($job_titles as $title)
            array_push($this->data['job_titles'], $title->job_title);
            
        echo json_encode($this->data);
    }


    /**
    * Cities
    */
    public function autocomplete_cities()
    {
        $input_city = $this->input->post('region');
        $this->db->like('city', "$input_city", 'both');
        $cities = $this->db->get('cities', 5, 0)->result();
        
        $this->data['cities'] = array();
        
        foreach ($cities as $city)
            array_push($this->data['cities'], $city->city);
            
        echo json_encode($this->data);
    }
	
}

/* End of file ajax.php */
/* Location: ./application/controllers/ajax.php */