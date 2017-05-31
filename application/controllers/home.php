<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {

    function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model('Employer_model', 'employer');
		$this->load->model('Employee_model', 'employee');
        $this->lang->load('home', $this->language->get());

		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
	    $this->user = $this->ion_auth->user()->row();
	    
	    $this->data['title'] = 'orabota';

        // Redirect if not logged in
        if (!$this->ion_auth->logged_in())
		{
            redirect('/', 'refresh');
		}
    }
	
	
	public function index()
	{
		// Update user's language
        $this->ion_auth->user()->update($this->user->id, array('language' => $this->language->get(TRUE)));
	
		// No organizations related with this user?
        if ($this->db->get_where('employer_organizations', array('employer_id' => $this->user->id))->num_rows() == 0) 
		{
            redirect('/home/new_org', 'refresh');
		}
		
		// Forward to employee review
		/*else if ($this->session->flashdata('new_org_id'))
		{
		    $orgid = $this->session->flashdata('new_org_id');
		    redirect("/home/new_employee_review/$orgid", 'refresh');
		}*/
		
		else
		{   // Display user's profile page
            $this->data['user_name'] = $this->user->email;
            if ($this->user->first_name != '') 
            {
		        $this->data['user_name'] = $this->user->first_name.' '. $this->user->last_name;
            }

            $this->data['orgs'] = $this->employer->get_employer_orgs($this->user->id);

            // Making org statuses more informative
            foreach ($this->data['orgs'] as $org) 
            {
                $org->employees = $this->employer->get_employees($org->id);
                
                // Get review for every employee
                foreach($org->employees as $employee)
                {
                    $employee->comment = $this->employee->get_comments($employee->id, $org->id);
                    $employee->comment = $employee->comment[0];
                }
            }

            $this->load->view('global_header.php', $this->data);
            $this->load->view('profile.php', $this->data);
			$this->load->view('global_footer.php');
		}
	}
	
	
	/*
     *  Display New Org Form
     */
    public function new_org()
    {
        // Get list of countries to populate the dropdown
        $lang = $this->language->get();
	    $this->db->order_by($lang, "asc");
	    foreach ($this->db->get('countries')->result() as $row)
	    {
	        $this->data['countries'][$row->iso] = $row->$lang;
	    }

        $this->data['title'] = 'orabota - '.lang('add_org');

        $this->load->view('global_header.php', $this->data);
        $this->load->view('new_org_form.php', $this->data);
		$this->load->view('global_footer.php');
    }
	
	
    /*
     *  Display "Add Proof" Form
     */
    public function add_proof($orgid)
    {
        $this->data['orgid'] = $orgid;
	    $this->load->view('global_header.php', $this->data);
        $this->load->view('org_proof_form.php', $this->data);
		$this->load->view('global_footer.php');
    }
    
    
    /*
     *  Display review Form
     */
    public function new_employee_review($orgid = '')
    {
        $this->db->order_by('id', 'desc');
        $orgs = $this->db->get_where('employer_organizations', array('employer_id' => $this->user->id))->result();

        foreach ($orgs as $org)
	    {
	        $this->data['orgs'][$org->id] = $org->job_title.' '.lang('at').' '.$org->organization;
	    }

        $this->data['orgs'][0] = '+ '.lang('add_org');
        
        $this->load->view('global_header.php', $this->data);
        $this->load->view('new_employee_review.php', $this->data);
		$this->load->view('global_footer.php');
    }


    /***********************
     *  Processing Functions
     ***********************/

    /**
     * Add new proof to the database, update status to for review
     */
	public function org_proof()
	{
	    if (!$this->employer->belongs($this->input->post('orgid')))
	    {   // Bad ID
	        redirect('/', 'refresh');
	    }
	    
	    // Check if user did not select file
	    if ($_FILES['userfile']['size'] == 0)
	    {
	        $this->employer->add_org_proof($this->input->post('orgid'), $this->input->post('first_name'), $this->input->post('last_name'), $this->input->post('comment'), '');
	        redirect('/', 'refresh');
	    }
	
	    // Upload the file & write the data
	    $config['upload_path'] = $this->config->item('upload_path');
	    $config['allowed_types'] = 'pdf|jpg|gif|png';
	    $config['max_size'] = 5120;
	    
	    $this->load->library('upload', $config);
	    
	    if ($this->upload->do_upload())
	    {   
	        // File uploaded successfully
	        $result = $this->upload->data();
	        $this->employer->add_org_proof($this->input->post('orgid'), $this->input->post('first_name'), $this->input->post('last_name'), $this->input->post('comment'), $result['file_name']);
	        redirect('/', 'refresh');
	    }
	    else // Display form with errors
	    {
	        $this->data['orgid'] = $this->input->post('orgid');
	        $this->data['errors'] =  $this->upload->display_errors();
	        
	        $this->load->view('global_header.php', $this->data);
            $this->load->view('org_proof_form.php', $this->data);
			$this->load->view('global_footer.php');
	    }
	}

}

/* End of file home.php */
/* Location: ./application/controllers/home.php */
