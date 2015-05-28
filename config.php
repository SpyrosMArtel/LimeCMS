<?php
/*
Copyright (c) Spyridon Marinis Artelaris, All rights reserved.

This file is part of LimeCMS.

LimeCMS is free software; you can redistribute it and/or
modify it under the terms of the GNU Lesser General Public
License as published by the Free Software Foundation; either
version 3.0 of the License, or (at your option) any later version.

LimeCMS is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
Lesser General Public License for more details.

You should have received a copy of the GNU Lesser General Public
License along with LimeCMS.
*/
// http://www.php.net/manual/en/timezones.php
ini_set('display_errors', true);
date_default_timezone_set('Europe/Stockholm');
define('DB_DSN', 'mysql:host=localhost;dbname=limecms;charset=utf8');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'root');
define('CLASS_PATH', 'classes');
define('TEMPLATE_PATH', 'templates');
define('HOMEPAGE_NUM_ARTICLES', 5);
// let's see
ini_set('upload_tmp_dir', TEMPLATE_PATH . '/admin/tmp');
define('UPLOAD_FOLDER', TEMPLATE_PATH . '/content/images');
define('WEBSITE_FOLDER', TEMPLATE_PATH . '/website');

require (CLASS_PATH . '/Category.php');
require (CLASS_PATH . '/Article.php');
require (CLASS_PATH . '/User.php');

function handleException($exception) {
	echo 'Sorry, a problem occurred. Please try later.';
	error_log($exception -> getMessage());
}

set_exception_handler('handleException');
?>