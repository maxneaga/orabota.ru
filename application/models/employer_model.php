<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Employer_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
        $this->user = $this->ion_auth->user()->row();
    }


    /**
	 * Get employer by id
	 **/
    public function get($id = 0)
    {
        if ($id == 0)
            return $this->db->get('employers')->result();

        return $this->db->get_where('employers', array('id' => $id))->row();
    }


    /**
	 * Remove employer and everything associated
	 **/
    public function delete($id)
    {
        // Remove all employer's organizations
        $orgs = $this->db->get_where('employer_organizations', array('employer_id' => $id));

        foreach ($orgs->result() as $org)
        {
            delete_org($org->id);
        }

        // Delete employer
        $this->db->where('id', $id);
        $this->db->delete('employers');
    }


    /**
	 * Adds organization info for employer
	 *
	 * @return id of last inserted row on success or FALSE on failure
	 **/
    public function add_org($org, $country, $region, $title)
    {
        $data = array(
            'employer_id' => $this->user->id,
            'organization' => $org,
            'country' => $country,
            'region' => $region,
            'job_title' => $title
        );

        if ($this->db->insert('employer_organizations', $data))
        {
            return $this->db->insert_id();
        }

        return false;
    }


    /**
	 * Updates rating for review
	 *
	 **/
    public function set_review_rating($comment_id, $rating)
    {
        $org_id = $this->db->get_where('employee_comments', array('id' => $comment_id))->row()->org_id;

        if (!$this->belongs($org_id))
        {
            return FALSE;
        }

        // Check if valid rating
        if ($rating == 1 || $rating == 0 || $rating == -1)
        {
            $this->db->where('id', $comment_id);
            return $this->db->update('employee_comments', array('rating' => $rating));
        }

        return FALSE;
    }


    /**
	 * Updates comment for review
	 *
	 **/
    public function set_review_comment($comment_id, $comment)
    {
        $org_id = $this->db->get_where('employee_comments', array('id' => $comment_id))->row()->org_id;

        if (!$this->belongs($org_id))
        {
            return FALSE;
        }

        $this->db->where('id', $comment_id);
        return $this->db->update('employee_comments', array('comment' => $comment, 'verified' => 0));

        return FALSE;
    }


    /**
	 * Reject employer's organization
	**/
	public function reject_org($orgid)
	{
	    // Check if file exists -> delete it
        $file = $this->db->get_where('employer_organizations', array('id' => $orgid))->row()->file;
        if (file_exists($this->config->item('upload_path').$file) && ! is_dir($this->config->item('upload_path').$file))
        {
            unlink($this->config->item('upload_path').$file);
        }

        $this->db->where('id', $orgid);
        $this->db->set('status', 'rejected');
        $this->db->set('file', '');
        $this->db->update('employer_organizations');
	}


	/**
	 * Remove organization and all employee records associated with it
	**/
	public function delete_org($orgid)
	{
	    // Check if file exists -> delete it
        $file = $this->db->get_where('employer_organizations', array('id' => $orgid))->row()->file;
        if (file_exists($this->config->item('upload_path').$file) && ! is_dir($this->config->item('upload_path').$file))
        {
            unlink($this->config->item('upload_path').$file);
        }

        // Delete organization
        $this->db->where('id', $orgid);
        $this->db->delete('employer_organizations');

        // Delete all comments left by this organization
        $comments = $this->db->get_where('employee_comments', array('org_id' => $orgid));

        foreach($comments->result() as $comment)
        {
            // Check if this is the only comment for the user => delete the user
            $num_comments = $this->db->get_where('employee_comments', array('employee_id' => $comment->employee_id))->num_rows();
            if ($num_comments <= 1)
            {
                // Delete user
                $this->db->where('id', $comment->employee_id);
                $this->db->delete('employees');
            }

            // Delete the comment
            $this->db->where('id', $comment->id);
            $this->db->delete('employee_comments');
        }
	}


	/**
	 * Return database object of organizations
	 *
	 * @param $status       Organizations' status. Use 'all' to get all
	 * @param $limit        Query limit
	 * @param $offset       Query offset
   
	 * @return              Database object of organizations
	 **/
	public function get_orgs($status = 'all', $limit = NULL, $offset = NULL)
	{
	    if ($status == 'all')
	    {
	        return $this->db->get('employer_organizations', $limit, $offset);
	    }
	    if ($status == 'pending')
	    {
            return $this->db->get_where('employer_organizations', 'status = \'pending\' AND last_adm_view < (NOW() - INTERVAL 10 MINUTE)', $limit, $offset);
	    }
        else
        {
            die('error get_orgs');
        }
	}


	/**
	 * Get organization record by id
	**/
	public function get_org($id)
	{
	    return $this->db->get_where('employer_organizations', array('id' => $id))->row();
	}


    /**
	 * Return organization with provided id
	 **/
    public function get_employer_orgs($userid)
    {
        $this->db->order_by('id', 'desc');
        return $this->db->get_where('employer_organizations', array('employer_id' => $userid))->result();
    }



    /**
	 * Return array of employees of an organization
	 **/
    public function get_employees($orgid)
    {
        // Get ids of all employees at this org
        $records = $this->db->get_where('employee_comments', array('org_id' => $orgid))->result();

        if (empty($records)) {
            return array();
        }

        // Build a query to get the all at once
        $uids = array();

        foreach ($records as $record)
        {
            array_push($uids, $record->employee_id);
        }

        $this->db->where_in('id', $uids);

        return $this->db->get('employees')->result();
    }


     /**
	 * Return array of job titles
	 *
	 * @return              FALSE when not enough or too much data is specified
	 *                      array of results on success
	 **/
    public function search_job_titles($searchstr, $page, $per_page)
    {
        $searchstr = str_replace(array('%20', ' '), ' ', $searchstr);
        $words = explode(' ', $searchstr); // break the string into words

        foreach ($words as $word)
        {
            $this->db->or_like('job_title', "{$word}", 'both');
        }

        $this->db->distinct();
        $this->db->select('job_title');

        return $this->db->get('employer_organizations', $per_page, $page)->result();
    }


    /**
	 * Return array of search results of organizations
	 *
	 * @param $searchstr    The search string
	 * @param $page         Results page number (query offset)
	 * @param $per_page     Limit of results per page (query limit)
	 * @param $numonly      if set to TRUE, will return the number of rows only (used by pagination)
	 *
	 * @return              FALSE when not enough or too much data is specified
	 *                      array of results on success
	 **/
    public function search_org($searchstr, $page, $per_page, $numonly = FALSE)
    {
        $searchstr = str_replace(array('%20', ' '), ' ', $searchstr);
        $words = explode(' ', $searchstr); // break the string into words

        foreach ($words as $word)
        {
            $this->db->like('organization', "{$word}", 'both');
        }

         return $this->db->get('employer_organizations', $per_page, $page)->result();
    }


    /**
	 * Return TRUE if organization belongs to user, FALSE if not
	 **/
    public function belongs($orgid)
    {
        if ($this->db->get_where('employer_organizations', array('id' => $orgid, 'employer_id' => $this->user->id))->num_rows() == 0)
        {
            return false;
        }

        return true;
    }


    /**
	 * Returns TRUE if organization is verified and FALSE if not
	 **/
    public function is_org_verified($orgid)
    {
        if ($this->db->get_where('employer_organizations', array('id' => $orgid))->row()->status == 'verified')
        {
            return true;
        }

        return false;
    }



    /**
	 * Returns the value of the rating based on review
	 **/
    public function calculate_review_rating($rating)
    {
        switch ($rating)
        {
            case 'bad':
                return -1;
                break;
            case 'good':
                return 1;
                break;
            default:
                return 0;
        }
    }


    /**
	 * Log user's action
     *
     * @param $action       Name of action / e-mail address of receiver
	 **/
    public function log($action)
    {
        $data = array(
            'ip' => $this->input->ip_address(),
            'action' => $action
        );
        $this->db->set('date', 'CURDATE()', FALSE);
        $this->db->insert('action_log', $data);
    }


    /**
	 * Check for agressive actions. Sets daily limit of an action per ip address
     *
     * @param $action       Name of action / e-mail address of receiver
     * @param $limit        Maximum # of daily occurrences
     *
     * @return              TRUE if daily limit exceeded
	 **/
    public function limit_exceeded($action, $limit)
    {
        $this->db->where("date = CURDATE()", NULL, FALSE);
        $this->db->where('ip', $this->input->ip_address());
        $this->db->where('action', $action);
        $count = $this->db->get('action_log')->num_rows();

        if ($count >= $limit)
        {
            return TRUE;
        }

        return FALSE;
    }
}
