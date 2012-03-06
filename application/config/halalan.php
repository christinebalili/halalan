<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

// don't change if you already entered some data
$config['halalan']['pin'] = TRUE;
$config['halalan']['password_pin_generation'] = "web";
$config['halalan']['password_pin_characters'] = "alnum";
$config['halalan']['password_length'] = 4;
$config['halalan']['pin_length'] = 4;
$config['halalan']['captcha'] = TRUE;
$config['halalan']['captcha_length'] = 4;
$config['halalan']['show_candidate_details'] = TRUE;
$config['halalan']['generate_image_trail'] = TRUE;
$config['halalan']['image_trail_path'] = "/home/waldemar/public_html/htwo/trails/";

$config['base_url'] = "http://localhost/~waldemar/htwo/";
$config['encryption_key'] = "oAnn954IRRetz3xDtPOp3ePdLdLNGHh4";

?>