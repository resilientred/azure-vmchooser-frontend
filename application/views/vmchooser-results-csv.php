<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>


<?php


if (isset($results)) { 
	?>
	
	<div class="page-header">
	  <h1 id="navbar">CSV Parser Results</h1>
	</div>
	
	<?php

	$CI =& get_instance();
	$CI->load->library('table');

	$template = array(
			'table_open' => '<table class="table table-striped table-hover">'
	);
	$CI->table->set_template($template);

	$header = "";
	$seperator = "";
	foreach($results as $key => $value) {
		$header .= $seperator."'".$key."'";
		$seperator = ",";
	}
	$CI->table->set_heading($header);		

	echo $CI->table->generate($results);

}

?>
