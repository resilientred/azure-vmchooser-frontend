<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>


<?php


if (isset($results)) { 
	?>
	
	<div class="page-header">
	  <h1 id="navbar">VMsize Details</h1>
	</div>
	
	<?php

	$CI =& get_instance();
	$CI->load->library('table');

	$template = array(
			'table_open' => '<table class="table table-striped table-hover">'
	);
	$CI->table->set_template($template);
  $CI->table->set_heading('Attribute', 'Value');

	
	foreach($results as $key => $value) {
		echo "adding $key & $value";
		$CI->table->add_row($key, $value);
	}
	echo $CI->table->generate();

}

?>
