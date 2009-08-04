<?php 
if (eregi('crypt.php', $_SERVER['PHP_SELF']))
{
	die ("You can't access this file directly...");
}
?>

<?php 
	function en_crypt($data, $key)
	{
		$td = mcrypt_module_open('rijndael-256', '', 'ecb', '');
		$iv = mcrypt_create_iv (mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
		mcrypt_generic_init($td, $key, $iv);
		$data = mcrypt_generic($td, $data);
		mcrypt_generic_deinit($td);
		mcrypt_module_close($td);
		
		return $data;
	}
	
	function de_crypt($data, $key)
	{
		$td = mcrypt_module_open('rijndael-256', '', 'ecb', '');
		$iv = mcrypt_create_iv (mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
		mcrypt_generic_init($td, $key, $iv);
		$data = trim(mdecrypt_generic($td, $data));
		mcrypt_generic_deinit($td);
		mcrypt_module_close($td);
		
		return $data;
	}
?>
