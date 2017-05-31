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
$lang['account_creation_successful'] 	  	 = 'Учетная запись успешно создана';
$lang['account_creation_unsuccessful'] 	 	 = 'Ошибка: невозможно создать учетную запись'; // DISABLED
$lang['account_creation_duplicate_email'] 	 = 'Неверный пароль';
$lang['account_creation_duplicate_username'] = 'E-mail либо занят либо не верен';

// Password
$lang['password_change_successful'] 	 	 = 'Пароль успешно изменен';
$lang['password_change_unsuccessful'] 	  	 = 'Ошибка: невозможно изменить пароль';
$lang['forgot_password_successful'] 	 	 = 'E-mail с инструкцией по сбросу пароля отправлен.';
$lang['forgot_password_unsuccessful'] 	 	 = 'Ошибка: невозможно сбросить пароль. Пожалуйста, Свяжитесь с нами.';

// Activation
$lang['activate_successful'] 		  	     = 'Учетная запись успешно активирована';
$lang['activate_unsuccessful'] 		 	     = 'Ошибка: невозможно активировать учетную запись. Пожалуйста, Свяжитесь с нами.';
$lang['deactivate_successful'] 		  	     = 'Учетная запись деактивирована.';
$lang['deactivate_unsuccessful'] 	  	     = 'Ошибка: невозможно деактивировать учетную запись. Пожалуйста, Свяжитесь с нами.';
$lang['activation_email_successful'] 	  	 = 'Инструкция по активации вашей учетной записи отправлена Вам на e-mail';
$lang['activation_email_unsuccessful']   	 = 'Ошибка: невозможно отправить e-mail для активации учетной записи. Пожалуйста, Свяжитесь с нами.';

// Login / Logout
$lang['login_successful'] 		  	         = 'Вы успешно вошли в систему';
$lang['login_unsuccessful'] 		  	     = 'Неверный e-mail'; // DISABLED
$lang['login_unsuccessful_not_active'] 		 = 'Учетная запись не активна. Пожалуйста, Свяжитесь с нами.';
$lang['login_timeout']                       = 'Превышен лимит попыток входа. Пожалуйста, попробуйте позже.';
$lang['logout_successful'] 		 	         = 'Вы успешно вышли из системы.';

// Account Changes
$lang['update_successful'] 		 	         = 'Данные учетной записи успешно обновлены';
$lang['update_unsuccessful'] 		 	     = 'Ошибка: не возможно обновить данные учетной записи. Пожалуйста, Свяжитесь с нами.';
$lang['delete_successful'] 		 	         = 'Пользователь удален';
$lang['delete_unsuccessful'] 		 	     = 'Ошибка: не возможно удалить пользователя. Пожалуйста, Свяжитесь с нами.';

// Email Subjects
$lang['email_forgotten_password_subject']    = 'Восстановление пароля';
$lang['email_new_password_subject']          = 'Новый пароль';
$lang['email_activation_subject']            = 'Активация учетной записи';