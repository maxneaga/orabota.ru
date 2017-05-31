<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('age'))
{
    /*
     * Takes y-m-d date string and returns user's age
     */
    function age($date)
    {
        $date = explode("-", $date);
        $age = (date("md", date("U", mktime(0, 0, 0, $date[1], $date[2], $date[0]))) > date("md") ? ((date("Y")-$date[0])-1):(date("Y")-$date[0]));
        if ($age > 2000) {
            $age = '-';
        }
        return $age;
    }
}

/* End of file user_date_helper.php */
/* Location: ./application/helpers/user_date_helper.php */