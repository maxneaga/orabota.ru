<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Recommend extends CI_Controller {

    function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->library('email');
        $this->lang->load('recommend', $this->language->get());

		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
        $this->user = $this->ion_auth->user()->row();
		
		$this->data['title'] = 'orabota - '.lang('page_title');
        $this->data['site_description'] = lang('site_description_recommend');
	}
	
	
	
	public function index()
	{
        $this->data['first_name'] = '';
        $this->data['last_name'] = '';
        $this->data['birth_year'] = '';
        $this->data['birth_month'] = '';
        $this->data['birth_day'] = '';

        if ($this->ion_auth->logged_in())
		{
            $this->data['first_name'] = $this->user->first_name;
            $this->data['last_name'] = $this->user->last_name;
            $birth_date = explode('-', $this->user->birth_date);
            $this->data['birth_year'] = $birth_date[0];
            if ($birth_date[0] == '0000') $this->data['birth_year'] = '';
            $this->data['birth_month'] = $birth_date[1];
            if ($birth_date[1] == '00') $this->data['birth_month'] = '';
            $this->data['birth_day'] = $birth_date[2];
            if ($birth_date[2] == '00') $this->data['birth_day'] = '';
		}

		$this->load->view('global_header.php', $this->data);
		$this->load->view('recommend.php', $this->data);
		$this->load->view('global_footer.php');
	}
	
}

/* End of file recommend.php */
/* Location: ./application/controllers/recommend.php */
