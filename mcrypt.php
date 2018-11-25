<?php
$key = "GDSHG4385743HGSDHdkfgjdfk4653475JSGHDJSDSKJDF476354";

if($_SERVER['REQUEST_METHOD'] == "POST") {
	$str = $_POST['str'];
	
	$td = mcrypt_module_open(MCRYPT_BLOWFISH,'',MCRYPT_MODE_CFB,'');
	
	$iv_size = mcrypt_enc_get_iv_size($td);
	$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
	
	mcrypt_generic_init($td,$key,$iv);
	
	$crypt_text = mcrypt_generic($td,$str);
	
	mcrypt_generic_deinit($td);
	
	echo base64_encode($iv.$crypt_text);
	
	$iv_size1 = mcrypt_enc_get_iv_size($td);
	$iv1 = substr($iv.$crypt_text,0,$iv_size1);
	$crypt_text1 = substr($iv.$crypt_text,$iv_size1);
	mcrypt_generic_init($td,$key,$iv1);
	$text = mdecrypt_generic($td,$crypt_text1);
	mcrypt_generic_deinit($td);
	
	echo "<br />".$text;
	
	
	
}
?>
<form method="post">
<input type="text" name="str">

<input type="submit" value="Ok">
</form>
