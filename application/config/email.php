<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

$config['protocol'] = 'smtp'; // mail, sendmail, or smtp
$config['newline'] = "\r\n";
$config['crlf'] = "\r\n";

// only needed if sendmail was chosen
$config['mailpath'] = '/usr/sbin/sendmail';

// only needed if smtp was chosen
$config['smtp_host'] = ''; // ssl://smtp.googlemail.com to use Gmail
$config['smtp_user'] = '';
$config['smtp_pass'] = '';
$config['smtp_port'] = 25; // 465 for SSL

/* End of file email.php */
/* Location: ./application/config/email.php */