<?php
echo "loading view";
defined('BASEPATH') OR exit('No direct script access allowed');

$CI =& get_instance();

echo "go";

print_r($results;)

if (isset($results)) {

	$header = array();
	foreach($results[0] as $key => $value) {
		$header[] = $key;
	}
	
	print_r($header);
}

?>
