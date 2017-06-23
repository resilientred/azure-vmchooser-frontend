<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vmchooser extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		
		$this->load->helper(array('form', 'url'));

		$this->load->library('form_validation');
		$this->form_validation->set_rules('inputCores', 'Number of Cores', 'numeric');
		$this->form_validation->set_rules('inputMemory', 'Amount of Memory', 'numeric');
		$this->form_validation->set_rules('inputNics', 'Number of NICs', 'numeric');
		$this->form_validation->set_rules('inputData', 'Minimum disk size', 'numeric');
		$this->form_validation->set_rules('inputIops', 'IOPS', 'numeric');
		$this->form_validation->set_rules('inputThroughput', 'Throughput', 'numeric');
		$this->form_validation->set_rules('inputTemp', 'Minimum temp disk size', 'numeric');
		$this->form_validation->set_rules('inputAvgcpupeak', 'Peak CPU Usage', 'numeric');
		$this->form_validation->set_rules('inputAvgmempeak', 'Peak Memory Usage', 'numeric');
		$this->form_validation->set_rules('inputSaps2tier', 'SAPS 2-Tier', 'numeric');
		$this->form_validation->set_rules('inputSaps3tier', 'SAPS 3-Tier', 'numeric');

		if ($this->form_validation->run() == FALSE)
		{
				// NOK
				$this->load->view('vmchooser-form');
		}
		else
		{
			// Generate Query
			$this->load->helper('security');
			$ssd = $this->security->xss_clean($_POST["ssd"]);
			switch ($ssd) {
				case "Yes":
					break;
				case "No":
					break;
				case "All":
					break;
				default:
				   echo "Something went wrong :-(";
			}
			$inputCores = $this->security->xss_clean($_POST["inputCores"]);
			$inputMemory = $this->security->xss_clean($_POST["inputMemory"]);
			$inputNics = $this->security->xss_clean($_POST["inputNics"]);
			$inputData = $this->security->xss_clean($_POST["inputData"]);
			$inputIops = $this->security->xss_clean($_POST["inputIops"]);
			$inputThroughput = $this->security->xss_clean($_POST["inputThroughput"]);
			$inputTemp = $this->security->xss_clean($_POST["inputTemp"]);
			$inputAvgcpupeak = $this->security->xss_clean($_POST["inputAvgcpupeak"]);
			$inputAvgmempeak = $this->security->xss_clean($_POST["inputAvgmempeak"]);
			$hana = $this->security->xss_clean($_POST["hana"]);
			switch ($hana) {
				case "Yes":
					break;
				case "All":
					break;
				default:
				   echo "Something went wrong :-(";
			}
			$inputSaps2tier = $this->security->xss_clean($_POST["inputSaps2tier"]);
			$inputSaps3tier = $this->security->xss_clean($_POST["inputSaps3tier"]);
			$querysuffix = "&cores=$inputCores&memory=$inputMemory&iops=$inputIops&data=$inputData&temp=$inputTemp&throughput=$inputThroughput&nics=$inputNics&ssd=$ssd&avgcpupeak=$inputAvgcpupeak&avgmempeak=$inputAvgmempeak";
			
			if ((!empty($inputSaps2tier) AND !empty(inputSaps3tier)) OR ($hana == Yes)) {
				$sapsuffix = "&saps2t=$inputSaps2tier&saps3t=$inputMemory&iops=$inputSaps3tier&hana=$hana";
			}
			
			// Do API Call
			$this->load->library('guzzle');
			if ($sapsuffix <> "") { $api_url = getenv('SAPCHOOSERAPI') . $querysuffix . $sapsuffix; } else { $api_url = getenv('VMCHOOSERAPI') . $querysuffix; }
			$client     = new GuzzleHttp\Client();
			
			try {
				$response = $client->request( 'POST', 
											   $api_url, 
											  [ 'form_params' 
													=> [ 'processId' => '2' ] 
											  ]
											);
				$json =  $response->getBody()->getContents();
			} catch (GuzzleHttp\Exception\BadResponseException $e) {
				$response = $e->getResponse();
				$responseBodyAsString = $response->getBody()->getContents();
				//print_r($responseBodyAsString);
				echo "Something went wrong :-(";
			}
			
			// Prep Results
			$array = json_decode($json);
			$i=0;
			foreach ($array as $result) {
			  $temp = (array) $result;
			  $results[$i] = $temp;
			  $i++;
			}
		
			// OK
			$data['results'] = $results;
			$this->load->view('vmchooser-form',$data);
				
				
		}
	}
	
	public function csv() 
	{
		$this->load->helper(array('form', 'url'));
		$this->load->library('guzzle');
		
		print_r($_FILES);
		
		$allowed =  array('csv');
		$filename = strtolower($_FILES['csvfile']['name']);

		$ext = pathinfo($filename, PATHINFO_EXTENSION);
		if(!in_array($ext,$allowed) ) {
			echo "NOK";
			$this->load->view('vmchooser-form-csv');
		}
		else
		{
			echo "OK";
			$validator = new PhpCsvValidator();
			$tmpfile = $_FILES['csvfile']['tmp_name'];
			$csvschema = "tests/files/example-scheme2.json";
			
			echo "validator";
			$validator->loadSchemeFromFile($csvschema);

			echo "isvalid";
			if($validator->isValidFile($tmpfile)) {
				echo "File is Valid";
			} else {
				echo "File is Invalid!";
			}
			
			$this->load->view('vmchooser-form-csv');
		}
	}
	
}
