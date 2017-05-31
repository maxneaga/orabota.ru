<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class People extends CI_Controller {

    function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model('Employer_model', 'employer');
		$this->load->model('Employee_model', 'employee');
        $this->lang->load('people', $this->language->get());

		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
	    $this->user = $this->ion_auth->user()->row();
	    
	    $this->data['title'] = 'orabota - '.lang('people');
        $this->data['site_description'] = lang('site_description_people');
    }
	
	
	public function index()
	{
        redirect('/people/search/all', 'refresh');
	}
	
	
	
	public function view($userid)
	{
	    $this->data['user'] = $this->employee->get($userid);

        if (!$this->data['user'])
	    {
	        $this->load->view('global_header.php', $this->data);
		    $this->load->view('user_not_exists.php', $this->data);
		    $this->load->view('global_footer.php');
		    return;
	    }

	    $this->data['user']->birth_date = age($this->data['user']->birth_date);
	    $this->data['comments'] = $this->employee->get_comments($userid);

        // Check if comments for no birthdate exist
        $nodob_userid = $this->employee->get_id($this->data['user']->first_name, $this->data['user']->last_name, $this->data['user']->first_name_alt, $this->data['user']->last_name_alt, '0000-00-00');

        // Display those comments only if record found
        if ($nodob_userid && $this->data['user']->birth_date !== '-')
        {
            $this->data['comments_nodob'] = $this->employee->get_comments($nodob_userid);
        }

        $this->data['title'] = $this->data['user']->first_name.' '.$this->data['user']->last_name;
        $this->data['site_description'] = $this->data['user']->first_name.' '.$this->data['user']->last_name.'. ';
        
        if (sizeof($this->data['comments']))
        {
            $this->data['site_description'] .= mb_substr($this->data['comments'][0]->comment, 0, 100);
        }

	    $this->load->view('global_header.php', $this->data);
		$this->load->view('user.php', $this->data);
		$this->load->view('global_footer.php');
	}
	
	
	
	public function search($searchstr='', $page=1)
	{
	    if ($this->input->post('searchstr') != '')
        {
            $searchstr = $this->input->post('searchstr');
        }   
        elseif  ($searchstr == '' && $this->input->post('searchstr') == '')
	    {
	        redirect('/people', 'refresh');
	    }

        $this->data['title'] = lang('people').': '.ucwords($searchstr);
	    
	    $config['per_page'] = 8;
	    
	    // Build Pagination
	    $this->load->library('pagination');
        $config['base_url'] = site_url("people/search/$searchstr/");
        $config["uri_segment"] = 4;
        $config['total_rows'] = $this->employee->search($searchstr, 0, 0, TRUE);
        $config['use_page_numbers'] = TRUE;
        $config['num_links'] = 4;
        $this->pagination->initialize($config); 
        $this->data['pagination'] = $this->pagination->create_links();

        $result = $this->employee->search($searchstr, ($page-1)*$config['per_page'], $config['per_page']);
	    
	    
	    $this->data['message'] = '';
        $this->data['searchstr'] = strip_tags(ucwords(urldecode($searchstr)));
	    $this->data['results'] = array();
	    
	    if ($result === false)
	    {
	        $this->data['message'] = lang('please_at_least_two_search_params');
	    }
	    else if (empty($result))
	    {
	        $this->data['message'] = lang('no_results');
	    }
	    else
	    {
	        $this->data['results'] = $result;
        }

        if ($this->data['searchstr'] == 'All')
        {
            $this->data['searchstr'] = lang('people');
            $this->data['title'] = 'orabota - '.lang('people');
            $this->data['site_description'] = $page.' - '.lang('site_description_people');
        }
	    
	    $this->load->view('global_header.php', $this->data);
		$this->load->view('search.php', $this->data);
		$this->load->view('global_footer.php');
	}

}

/* End of file people.php */
/* Location: ./application/controllers/people.php */