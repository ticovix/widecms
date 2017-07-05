<?php

defined('BASEPATH') OR exit('No direct script access allowed');

$lang['recovery_title'] = 'Recover Password';
$lang['recovery_text_about_recovery'] = 'For greater security, system passwords are encrypted, can not be recovered, but can be reset. <br> <strong> Enter your member\'s email address: </ strong>';
$lang['recovery_captcha_field'] = 'Captcha';
$lang['recovery_email_field'] = 'Email';
$lang['recovery_captcha_field'] = 'Enter the text in the image';
$lang['recovery_send_email_btn'] = 'Send reset email';
$lang['recovery_return_btn'] = 'Return';
$lang['recovery_message_success'] = 'If this email is linked to your registration you will receive an email with the data to reset the password, in case you do not receive check the email in the spam box or the trash.';

/* SEND EMAIL */
$lang['recovery_email_title'] = 'Password Reset';
$lang['recovery_email_message'] = 'Did you request a password reset? If yes click on this <a href="%s"> link </a> to reset the password, if you did not, ignore this email.';

/* ERRORS */
$lang['recovery_error_user_blocked'] = 'Account blocked, can not recover password, contact your administrator.';
$lang['recovery_error_invalid_recaptcha'] = 'Recaptcha is invalid. To request a password reset it is necessary to check the recaptcha box.';
$lang['recovery_error_invalid_captcha'] = 'Correctly type what you see in the image.';

