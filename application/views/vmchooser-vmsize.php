<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>


<?php

echo "results";
print_r($results);

if (isset($results)) { 
  echo "start";
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

	foreach ($results as $result) {
		$data = array();
		print_r($result);
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
