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

	$header = array();
	foreach($results[0] as $key => $value) {
		$header[] = $key;
	}

	$CI->table->set_heading($header);		

	echo $CI->table->generate($results);

}

?>

<div class="alert alert-dismissible alert-info">
  <button type="button" class="close" data-dismiss="alert">&times;</button>
  <strong>Export : </strong> Download as <a href="<?php echo base_url()."/vmchooser/downloadcsv/".$csvfile; ?>" target="_blank" class="alert-link">CSV</a> .</br>
</div>
