<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>


<?php

if (isset($results)) { 
	
	$CI =& get_instance();
	$CI->load->library('table');

	$template = array(
			'table_open' => '<table class="table table-striped table-hover">'
	);
	$CI->table->set_template($template);

	$header = array();
	foreach($results[0] as $key => $value) {
		$header[] = $key;
	}

	$CI->table->set_heading($header);		

	echo $CI->table->generate($results);

}

?>

