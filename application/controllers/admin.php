<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {

    function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->library('mailer');
		
		$this->load->model('Employer_model', 'employer');
		$this->load->model('Employee_model', 'employee');
        $this->lang->load('admin', $this->language->get());

        // Check if logged in and is admin
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
		{
            redirect('/home', 'refresh');
		}

        $this->data['title'] = 'orabota';
	}
	
	
	
	public function index()
	{
        $this->data['total_activity'] = $this->db->get_where('employee_comments', array('verified' => '0'))->num_rows();
        $this->data['total_orgs'] = $this->employer->get_orgs('all')->num_rows();
        $this->data['total_people'] = $this->db->get('employees')->num_rows();

        $this->load->view('global_header.php', $this->data);
		$this->load->view('admin/main.php', $this->data);
		$this->load->view('global_footer.php');
	}


    public function activity()
    {
        $this->db->order_by("date", "asc"); 
        $this->data['comments'] = $this->db->get_where('employee_comments', array('verified' => '0'))->result();
        
        $this->load->view('global_header.php', $this->data);
	    $this->load->view('admin/activity.php', $this->data);
	    $this->load->view('global_footer.php');
    }


    public function adm_mail($mode = 'users')
    {
		$mail = $this->mailer;
		$mail->CharSet = 'utf-8';

		$mail->IsSMTP();                                      // Set mailer to use SMTP
		$mail->Host = 'email-smtp.us-east-1.amazonaws.com';  // Specify main and backup server
		$mail->SMTPAuth = true;                               // Enable SMTP authentication
		$mail->Username = 'AKIAJAYMMD3JDSACPVKA';                            // SMTP username
		$mail->Password = 'AtqryFwgmE2NXTpwho6ibODiH9unnlEj53Q++jITYP/M';                           // SMTP password
		$mail->SMTPSecure = 'tls';                            // Enable encryption, 'ssl' also accepted
		$mail->Port = 587;

		$mail->From = 'noreply@orabota.ru';
		$mail->FromName = 'orabota.ru';

		$mail->WordWrap = 50;                                 // Set word wrap to 50 characters
		$mail->IsHTML(true);                                  // Set email format to HTML

		$mail->Subject = 'Отзывы о ваших сотрудниках';
		
		// For users
		//$query = $this->db->get_where('employers', array('mailed' => 0))->result();
		// For ads
		if ($mode == 'ads')
			$query = $this->db->get_where('employer_ads', array('mailed' => 0))->result();
		
        $i = 0;
		foreach ($query as $emp)
		{
            $emstr = '';
            if ($emp->unsubscribed == 1)
                continue;
				
			// If ads -> get the targeting data
			if ($mode == 'ads')
			{
				$company = $emp->company;
				$job_title = $emp->title;
                		$site = $emp->site;
                		$lang = $emp->lang;
			}
            
            $em = $emp->email;

            $emstr .= $em.'<br>';
            //continue;

            $mail->AddAddress($em);
            $em = urlencode($em);

            $mail->Body    = '
<table marginheight="0" marginwidth="0" width="100%" height="100%" style="border-spacing:0px;border-collapse:collapse;border-top:none;border-right:none;border-bottom:none;border-left:none;font-family:Helvetica,Arial,sans-serif;min-height:100%;margin:0px;padding-top:0px;padding-right:0px;padding-bottom:0px;padding-left:0px;background-image:url(http://medleyweb.com/wp-content/uploads/2012/06/Seamless-Web-Background-Patterns-for-Your-Site-13.jpg);background-color:rgb(255,255,255)!important;width:100%!important;background-repeat:repeat;table-layout:auto">
	<tbody>	
		<tr style="border-spacing:0px;border-collapse:collapse">
			<td style="border-spacing:0px;border-collapse:collapse;margin-top:0;margin-right:0;margin-bottom:0;margin-left:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0">
				
				<div style="max-width:600px;display:block;margin-top:0;margin-right:auto;margin-bottom:0;margin-left:auto;padding-top:0px;padding-right:10px;padding-bottom:0px;padding-left:10px">
					<h1><a href="http://orabota.ru" style="text-decoration:none"><span style="color:#ffa834">о,</span> <span style="color:#757575">работа</span></a></h1>
				</div>
				
				<div style="max-width:600px;display:block;margin-top:0;margin-right:auto;margin-bottom:0;margin-left:auto;padding-top:0px;padding-right:10px;padding-bottom:0px;padding-left:10px;border-top-left-radius:5px;border-top-right-radius:5px;border-bottom-right-radius:5px;border-bottom-left-radius:5px;background-color:rgb(255,255,255);">
					<h2 style="padding-top:20px;">Отзывы о ваших сотрудниках</h2>
					<p>
					<font style="color:rgb(89,101,115);font-size:17px;line-height:23px">
						Здравствуйте,<br>
						<br>
						Вы получаете данное сообщение, так как оставили объявление <em>'.$job_title.'</em> на сайте <em>'.$site.'</em><br>
						<br>
						У нас для вас отличные новости! Теперь вы можете оставлять отзывы о своих сотрудниках на нашем проекте orabota.ru<br>
						Оставляя отзывы мы помогаем друг другу нанимать только лучших и самых надежных работников.<br>
						Наша цель - сделать найм сотрудников максимально прозрачным и предсказуемым.<br>
						<br>
						Просим вас уделить минутку и рассказать что вы думаете о ваших сотрудниках на orabota.ru<br>
						<br>
						<a href="http://orabota.ru" style="text-decoration:none"><span style="color:#08c">Перейти на сайт</span></a>
					</font>
					<br><hr style="border: 0;height: 0;border-top: 1px solid rgba(0, 0, 0, 0.1);border-bottom: 1px solid rgba(255, 255, 255, 0.3);" /><br>
					<font style="color:rgb(89,101,115);font-size:17px;line-height:23px">
						С Уважением,<br>
						Команда orabota 
					</font>
					</p>
					<br>
				</div>
				
				<div style="max-width:600px;display:block;margin-top:0;margin-right:auto;margin-bottom:0;margin-left:auto;padding-top:0px;padding-right:10px;padding-bottom:0px;padding-left:10px">
					<p style="color:#999"><small>Если вы не желаете получать обновлеия от orabota, вы можете <a href="http://orabota.ru/main/unsubscribe/'.$em.'">отписаться</a>.</small></p>
				</div>
			</td>
		</tr>
	</tbody>
</table>
';


			$mail->AltBody = '
Здравствуйте,

Вы получаете данное сообщение, так как оставили объявление '.$job_title.' на сайте '.$site.'

У нас для вас отличные новости! Теперь вы можете оставлять отзывы о своих сотрудниках на нашем проекте orabota.ru
Оставляя отзывы мы помогаем друг другу нанимать только лучших и самых надежных работников.
Наша цель - сделать найм сотрудников максимально прозрачным и предсказуемым.

Просим вас уделить минутку и рассказать что вы думаете о ваших сотрудниках на http://orabota.ru

Искренне благодарим вас за внимание.

С Уважением,
Команда orabota

Если вы не желаете получать обновления от orabota, вы можете отписаться: http://orabota.ru/main/unsubscribe/'.$em;



			// mark as mailed
			$record_id = $emp->id;
            if ($mode == 'ads') {
			    $this->db->update('employer_ads', array('mailed' => 1), "id = $record_id");
            } else {
                $this->db->update('employers', array('mailed' => 1), "id = $record_id");
            }
			
            if(!$mail->Send()) {
                $page = 'http://dev.orabota.ru/admin/adm_mail/ads';
                $sec = "5";
                header("Refresh: $sec; url=$page");
		       echo 'Message could not be sent.';
		       echo 'Mailer Error: ' . $mail->ErrorInfo;
		       break;
		    }

            $mail->ClearAddresses();

            $i++;
            if ($i == 25) 
            {
                $secs = rand(5, 25);
                echo '<meta http-equiv="refresh" content="'.$secs.'">';
                echo $emstr;
                break;
            }
            echo $emstr;
        }
    }


    public function orgs($which = 'all', $page=0)
    {
        $this->load->library('pagination');
        $config['per_page'] = 10;

        $this->data['orgs'] = $this->employer->get_orgs('all', $config['per_page'], $page)->result();
        $config['total_rows'] = $this->employer->get_orgs('all')->num_rows();
        
        
        // Build Pagination
        $config['base_url'] = $this->config->item('base_url')."admin/orgs/$which/";
        $config["uri_segment"] = 4;
        $this->pagination->initialize($config); 
        $this->data['pagination'] = $this->pagination->create_links();

        $this->load->view('global_header.php', $this->data);
		$this->load->view('admin/orgs.php', $this->data);
		$this->load->view('global_footer.php');
    }

    
    public function people($page=0)
    {
        // Build Pagination
        $this->load->library('pagination');
        $config['per_page'] = 10;
        $config['total_rows'] = $this->db->get('employees')->num_rows();
        $config['base_url'] = $this->config->item('base_url')."admin/people/";

        $this->pagination->initialize($config); 
        $this->data['pagination'] = $this->pagination->create_links();
        
        $this->data['people'] = $this->db->get('employees', $config['per_page'], $page)->result();
        
        $this->load->view('global_header.php', $this->data);
	    $this->load->view('admin/people.php', $this->data);
	    $this->load->view('global_footer.php');
    }
    
    
    
    /***********************
     *  Processing Functions
     ***********************/
     
    // Search organizations
	public function search_org()
	{
	    $this->data['orgs'] = $this->employer->search_org($this->input->post('search_text'), 0, 10);
	    $this->data['pagination'] = '';
	    
	    // Check if no results
	    if (!$this->data['orgs'])
	    {
	        redirect('/admin/orgs', 'refresh');
	        return;
	    }
	    
	    $this->load->view('global_header.php', $this->data);
		$this->load->view('admin/orgs.php', $this->data);
		$this->load->view('global_footer.php');
	}
	
	
	// Search people
	public function search_people()
	{
	    $this->data['people'] = $this->employee->search($this->input->post('search_text'), 0, 10, FALSE, TRUE);
	    $this->data['pagination'] = '';
	    
	    // Check if no results
	    if (!$this->data['people'])
	    {
	        redirect('/admin/people', 'refresh');
	        return;
	    }
	    
	    $this->load->view('global_header.php', $this->data);
	    $this->load->view('admin/people.php', $this->data);
	    $this->load->view('global_footer.php');
	}
    
    // Approve organization
    public function approve_org($orgid)
    {
        $this->employer->approve_org($orgid);
        redirect('/admin/pool', 'refresh');
    }
    
    
    // Reject organization
    public function reject_org($orgid)
    {
        $this->employer->reject_org($orgid);
        redirect('/admin/pool', 'refresh');
    }
    

    // Approve comment
    public function approve_comment($id)
    {
        $this->employee->approve_comment($id);
        redirect('/admin/activity', 'refresh');
    }


    // Delete comment
    public function delete_comment($id)
    {
        $this->employee->delete_comment($id);
        redirect('/admin/activity', 'refresh');
    }
    
    // Mass reject of organizations
    public function mass_reject_org()
	{
	    $orgids = $this->input->post('orgid');
	
	    if (!empty($orgids))
	    {
	        foreach ($orgids as $orgid)
	        {
	            $this->employer->reject_org($orgid);
	        }
        }
	    
	    redirect('/admin/orgs/verify', 'refresh');
	}
	
	
	// Mass removal of people
	public function mass_remove_people()
	{
	    $uids = $this->input->post('uid');
	    
	    if (!empty($uids))
	    {
	        foreach ($uids as $uid)
	        {
	            $this->employee->delete($uid);
	        }
        }
        
        redirect('/admin/people', 'refresh');
	}
}

/* End of file admin.php */
/* Location: ./application/controllers/admin.php */