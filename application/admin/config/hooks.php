<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	https://codeigniter.com/user_guide/general/hooks.html
|
*/

/* 登陆验证 */
$hook['post_controller_constructor'] = array(
    'class'    => 'Vertify',
    'function' => 'auth_vertify',
    'filename' => 'Vertify.php',
    'filepath' => 'hooks',
    'params'   => NULL
);