<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Name:  Ion Auth Lang - English
*
* Author: Ben Edmunds
* 		  ben.edmunds@gmail.com
*         @benedmunds
*
* Location: http://github.com/benedmunds/ion_auth/
*
* Created:  03.14.2010
*
* Description:  English language file for Ion Auth messages and errors
*
*/

// Account Creation
$lang['account_creation_successful'] 	  	 = 'Inregistrarea reușită';
$lang['account_creation_unsuccessful'] 	 	 = 'Unable to Create Account'; // DISABLED
$lang['account_creation_duplicate_email'] 	 = 'Parola greșită';
$lang['account_creation_duplicate_username'] = 'E-mail deja inregistrat sau greșit';

// Password
$lang['password_change_successful'] 	 	 = 'Parola a fost schimbată';
$lang['password_change_unsuccessful'] 	  	 = 'Parola nu poate fi schimbată';
$lang['forgot_password_successful'] 	 	 = 'E-mail cu instrucțiunile pentru a reseta parola a fost trimis.';
$lang['forgot_password_unsuccessful'] 	 	 = 'Parola nu poate fi resetă';

// Activation
$lang['activate_successful'] 		  	     = 'Account a fost activat';
$lang['activate_unsuccessful'] 		 	     = 'Account nu poate fi activat';
$lang['deactivate_successful'] 		  	     = 'Account a fost deactivat';
$lang['deactivate_unsuccessful'] 	  	     = 'Account nu poate fi deactivat';
$lang['activation_email_successful'] 	  	 = 'Email cu activare a fost trimis';
$lang['activation_email_unsuccessful']   	 = 'Email cu activare nu poate fi trimis';

// Login / Logout
$lang['login_successful'] 		  	         = 'Intrare reușită';
$lang['login_unsuccessful'] 		  	     = 'Incorrect Login'; // DISABLED
$lang['login_unsuccessful_not_active'] 		 = 'Account este neactivat';
$lang['login_timeout']                       = 'Temporar blocat. Va rugam sa incercați mai tirziu';
$lang['logout_successful'] 		 	         = 'Iesirea reușită';

// Account Changes
$lang['update_successful'] 		 	         = 'Datele personale au fost actualizate';
$lang['update_unsuccessful'] 		 	     = 'Datele personale nu pot fi actualizate';
$lang['delete_successful'] 		 	         = 'Utilizatorul a fost elimitat';
$lang['delete_unsuccessful'] 		 	     = 'Utilizatorul nu poate fi eliminat';

// Email Subjects
$lang['email_forgotten_password_subject']    = 'Parola uitată';
$lang['email_new_password_subject']          = 'Parola nouă';
$lang['email_activation_subject']            = 'Activarea account-ului';
