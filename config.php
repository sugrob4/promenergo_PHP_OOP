<?php
defined('PROM') or exit('Access denied');

define('CONTROLLER','core/controller');

define('MODEL','core/model');

define('VIEW','template/default/');

define('LIB','lib');

define('SITE_URL','/promenergo/');

define('QUANTITY',3);

define('QUANTITY_LINKS',3);

define('UPLOAD_DIR','images/');

define('HOST','localhost');

define('USER','Aleksey');

define('PASSWORD','12345');

define('DB_NAME','promenergo');

define('IMG_WIDTH',116);

define('NOIMAGE','rpodukt.jpg');

define ('FEALT',1);

/////////////////////////////////////
define("VERSION",'113');
define("KEY","GDSHG4385743HGSDHdkfgjdfk4653475JSGHDJSDSKJDF476354");
define("EXPIRATION",600);
define("VARNING_TIME",300);
/////////////////////////////////////

$conf = array(
			'styles' => array(
						'style.css',
						),
			'scripts' => array(
						'JS/jquery-1.7.2.min.js',
						'JS/jquery-ui-1.8.20.custom.min.js',
						'JS/jquery.cookie.js',
						'JS/js.js',
						'JS/script.js',
						),
			'styles_admin' => array(
						'style.css'
						),
			'scripts_admin' => array(
						'JS/tiny_mce/tiny_mce.js',
						'JS/tiny_script.js',
						),						
);
?>
