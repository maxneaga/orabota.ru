<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Page extends CI_Controller {

    function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->library('email');
        $this->lang->load('pages/tos', $this->language->get());

		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
		
		$this->data['title'] = 'orabota - '.lang('page_title');
	}
	
	
	
	public function index()
	{
        redirect('/', 'refresh');
	}
	

    public function tos()
    {
        $this->data['site_description'] = lang('site_description_tos');

        $this->load->view('global_header.php', $this->data);
		$this->load->view('tos.php', $this->data);
		$this->load->view('global_footer.php');
    }
}

/* End of file page.php */
/* Location: ./application/controllers/page.php */
