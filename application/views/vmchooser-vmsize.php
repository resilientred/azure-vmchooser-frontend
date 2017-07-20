<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>


<?php

if (isset($results)) { 

	?>
	
	<div class="page-header">
	  <h1 id="navbar">Results</h1>
	</div>
	
	<?php

	$CI =& get_instance();
	$CI->load->library('table');

	$template = array(
			'table_open' => '<table class="table table-striped table-hover">'
	);
	$CI->table->set_template($template);

	$first = true;

  print_r($results);

	foreach ($results as $result) {
		$data = array();
		foreach($result as $key => $value) {
			$header[] = str_replace("'", "", $key);;
			$data[] = $value;
		}
		if ($first) {
			$CI->table->set_heading($header);
		}
		$CI->table->add_row($data);
		$first = false;
	}
	echo $CI->table->generate();

}

?>
