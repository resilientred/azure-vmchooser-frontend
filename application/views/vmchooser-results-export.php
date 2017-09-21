<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function arrayToCsv( array &$fields, $delimiter = ';', $enclosure = '"', $encloseAll = false, $nullToMysqlNull = false ) {
    $delimiter_esc = preg_quote($delimiter, '/');
    $enclosure_esc = preg_quote($enclosure, '/');

    $output = array();
    foreach ( $fields as $field ) {
        if ($field === null && $nullToMysqlNull) {
            $output[] = 'NULL';
            continue;
        }

        // Enclose fields containing $delimiter, $enclosure or whitespace
        if ( $encloseAll || preg_match( "/(?:${delimiter_esc}|${enclosure_esc}|\s)/", $field ) ) {
            $output[] = $enclosure . str_replace($enclosure, $enclosure . $enclosure, $field) . $enclosure;
        }
        else {
            $output[] = $field;
        }
    }

    return implode( $delimiter, $output );
}

if (isset($results)) { 

	$header = "array()";
	foreach($results[0] as $key => $value) {
		$header[] = $key;
	}

	print_r($results);

}

?>

