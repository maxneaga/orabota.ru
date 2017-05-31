<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Employee_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
        $this->user = $this->ion_auth->user()->row();
    }

    /**
	 * Adds employee to the database
	 *
	 * @return id of user, if exists or the id of the last inserted row on success or FALSE on failure
	 **/
    public function add($first_name, $last_name, $birth_date, $first_name_alt = '', $last_name_alt = '')
    {
        $data = array(
            'first_name' => trim(mb_convert_case($first_name, MB_CASE_TITLE, 'UTF-8')),
            'last_name' => trim(mb_convert_case($last_name, MB_CASE_TITLE, 'UTF-8')),
            'birth_date' => $birth_date
        );

        // Check if alternative name provided
        if ($first_name_alt != '')
        {
            // Check for cyrillic - latin and latin - cyrillic combos
            if ((is_cyrillic($first_name) && !is_cyrillic($first_name_alt)) ||
                (!is_cyrillic($first_name) && is_cyrillic($first_name_alt)))
            {
                $data['first_name_alt'] = $first_name_alt;
                $data['last_name_alt'] = $last_name_alt;
            }

            // Else bad input, leave alternative empty
            else
            {
                $data['first_name_alt'] = '';
                $first_name_alt = '';
                $data['last_name_alt'] = '';
                $last_name_alt = '';
            }
        }

        // make sure tha this is not a duplicate
        $this->db->where("birth_date = '$birth_date'", NULL, FALSE);
        $this->db->where("(first_name = '$first_name' AND last_name = '$last_name')", NULL, FALSE);
        $this->db->or_where("(first_name_alt = '$first_name' AND last_name_alt = '$last_name')", NULL, FALSE);
        if ($first_name_alt != '')
        {
            $this->db->or_where("(first_name = '$first_name_alt' AND last_name = '$last_name_alt')", NULL, FALSE);
            $this->db->or_where("(first_name_alt = '$first_name_alt' AND last_name_alt = '$last_name_alt')", NULL, FALSE);
        }

        $employee = $this->db->get_where('employees', $data)->row();
        if (!$employee)
        {
            if ($this->db->insert('employees', $data))
            {
                return $this->db->insert_id();
            }
        }
        else
        {   // Employee found

            // Check for alternative name -> assign it
            if ($employee->first_name_alt == '' && $first_name_alt != '')
            {
                $this->db->where('id', $employee->id);

                if (is_cyrillic($employee->first_name) && !is_cyrillic($first_name))
                {
                    $this->db->update('employees', array('first_name_alt' => $first_name, 'last_name_alt' => $last_name));
                }
                elseif (is_cyrillic($employee->first_name) && !is_cyrillic($first_name_alt))
                {
                    $this->db->update('employees', array('first_name_alt' => $first_name_alt, 'last_name_alt' => $last_name_alt));
                }
                elseif (!is_cyrillic($employee->first_name) && is_cyrillic($first_name))
                {
                    $this->db->update('employees', array('first_name_alt' => $first_name, 'last_name_alt' => $last_name));
                }
                elseif (!is_cyrillic($employee->first_name) && is_cyrillic($first_name_alt))
                {
                    $this->db->update('employees', array('first_name_alt' => $first_name_alt, 'last_name_alt' => $last_name_alt));
                }
            }

            return $employee->id;
        }

        return false;
    }


    /**
	 * Get employee's record from the database
	 **/
    public function get($id)
    {
        return $this->db->get_where('employees', array('id' => $id))->row();
    }


    /**
	 * Get employee id
     *
	 * @param $first_name       First Name
     * @param $last_name        Last Name
     * @param $first_name_alt   Alternate First Name
     * @param $last_name_alt    Alternate Last Name
     * @param $birth_date       Birth date
     *
     * @return                  False if nothing found
     *                          ID of first match if found
	 *
	 **/
    public function get_id($first_name, $last_name, $first_name_alt, $last_name_alt, $birth_date)
    {
        $result = $this->db->query("SELECT id FROM employees WHERE (((first_name = '$first_name' OR first_name = '$first_name_alt') AND (last_name = '$last_name' OR last_name = '$last_name_alt')) OR ((first_name_alt = '$first_name' OR first_name_alt = '$first_name_alt') AND (last_name_alt = '$last_name' OR last_name_alt = '$last_name_alt'))) AND birth_date = '$birth_date' LIMIT 1")->row();

        if (!$result)
            return FALSE;

        return $result->id;
    }


    /**
	 * Removes employee's record from the database
	 **/
    public function delete($id)
    {
        // delete employee record
        $this->db->where('id', $id);
        $this->db->delete('employees');

        // delete all associated comments
        $this->db->where('employee_id', $id);
        $this->db->delete('employee_comments');
    }


    /**
	 * Updates employee's rating with $amount
	 **/
    public function update_rating($userid, $amount)
    {
        $this->db->set('rating', "rating+$amount", FALSE);
        $this->db->where('id', $userid);
        $this->db->update('employees');
    }


    /**
	 * Adds a review record to an employee
	 **/
    public function add_comment($userid, $orgid, $job_title, $comment, $rating)
    {
        // get organization
        $org = $this->db->get_where('employer_organizations', array('id' => $orgid))->row();

        $data = array(
            'employee_id' => $userid,
            'org_id' => $orgid,
            'rating' => $rating,
            'job_title' => trim(mb_strtoupper(mb_substr($job_title, 0, 1)).mb_strtolower(mb_substr($job_title, 1))),
            'comment' => $comment
        );

        $this->db->set('date', 'CURDATE()', FALSE);
        $this->db->insert('employee_comments', $data);
    }


    /**
	 * Returns all comments of an employee
	 *
	 * @param $userid       ID of employee
     * @param $org_id       ID of organization (optional)
	 *
	 * @return              FALSE when not enough or too much data is specified
	 *                      array of results on success
	 **/
    public function get_comments($userid, $org_id = NULL)
    {
        // Get all comments that match the user
        $where = array(
            'employee_id' => $userid
        );

        if ($org_id)
        {
            $where['org_id'] = $org_id;
        }
        $this->db->order_by('id', 'desc');

        $comments = $this->db->get_where('employee_comments', $where)->result();

        // For each comment, get org name and position
        foreach ($comments as $key=>&$comment)
        {
            // get organization record
            $org = $this->db->get_where('employer_organizations', array('id' => $comment->org_id))->row();

            // Check if organization no longer exists => do not display the review
            if ($org)
            {
                $comment->org_id = $org->id;
                $comment->employer_id = $org->employer_id;
                $comment->org_name = $org->organization;
                $comment->employer_job_title = $org->job_title;
                $comment->region = $org->region;

                // Get the country's name
                $country = $this->db->get_where('countries', array('iso' => $org->country))->row();
                if ($country)
                {
                    $lang = $this->language->get();
                    $comment->country = $country->$lang;
                }
                else
                {
                    $comment->country = $org->country;
                }
            }
            else
            {
                unset($comments[$key]);
            }
        }

        return $comments;
    }


    /**
	 * Marks comment as appropriate
	 **/
    public function approve_comment($id)
    {
        $this->db->where('id', $id);
        $this->db->update('employee_comments', array('verified' => 1));
    }


    /**
	 * Deletes a comment
	 **/
    public function delete_comment($id)
    {
        // Check if the only comment => delete the user
        $employee_id = $this->db->get_where('employee_comments', array('id' => $id))->row()->employee_id;

        if ($this->db->get_where('employee_comments', array('employee_id' => $employee_id))->num_rows() == 1)
        {
            $this->delete($employee_id);
            return;
        }

        $this->db->where('id', $id);
        $this->db->delete('employee_comments');
    }


    /**
	 * Check if employee already has a comment from user
     *
     * @param   $userid     ID of employee. If NULL, will check if at least one employee was reviewed.
	 **/
    public function reviewed_by($userid = NULL, $orgid = NULL)
    {
        $where = array(
            'employee_id' => $userid,
            'org_id' => $orgid
        );

        if ($userid == NULL)
        {
            $where = array(
                'org_id' => $orgid
            );
        }

        if ($userid == NULL && $orgid == NULL)
        {
            $where = array();
        }

        return $this->db->get_where('employee_comments', $where)->num_rows();
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

        return $this->db->get('employee_comments', $per_page, $page)->result();
    }


    /**
	 * Returns array of search results among employees
	 *
	 * @param $searchstr    The search string
	 * @param $page         Results page number (query offset)
	 * @param $per_page     Limit of results per page (query limit)
	 * @param $numonly      if set to TRUE, will return the number of rows only (used by pagination)
     * @param $oneparam     if TRUE, the search will accept only one parameter
	 *
	 * @return              FALSE when not enough or too much data is specified
	 *                      array of results on success
	 **/
    public function search($searchstr, $page, $per_page, $numonly = FALSE, $oneparam = FALSE)
    {
        $searchstr = str_replace(array('%20'), ' ', $searchstr);

        // Check if the request comes from people index
        if (strlen($searchstr) <= 2 || $searchstr == 'all')
        {
            $this->db->order_by('id', 'desc');

            if ($numonly)
            {
                return $this->db->get('employees')->num_rows();
            }

            return $this->db->get('employees', $per_page, $page)->result();
        }

        // Extract year from string, if any
        if (preg_match('/\b\d{4}\b/', $searchstr, $year))
        {
            $this->db->where('YEAR(birth_date)', $year[0]);
        }

        $this->db->where("MATCH (first_name, last_name, first_name_alt, last_name_alt) AGAINST ('$searchstr')", NULL, FALSE);

        if ($numonly)
        {
            return $this->db->get('employees')->num_rows();
        }

        return $this->db->get('employees', $per_page, $page)->result();
    }
}
