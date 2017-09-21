<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>


<?php

if (isset($results)) { 
	
	$CI =& get_instance();

	$header = array();
	foreach($results[0] as $key => $value) {
		$header[] = $key;
	}

	print_r($results);

}

?>

