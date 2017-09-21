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
		$this->load->library('appinsight');
		$telemetryClient = new \ApplicationInsights\Telemetry_Client();
		$telemetryClient->getContext()->setInstrumentationKey(getenv('APPINSIGHTS_INSTRUMENTATIONKEY'));

		$telemetryClient->trackEvent('Index : Validation');

		$this->load->library('form_validation');
		$this->form_validation->set_rules('inputRegion', 'Azure Region', 'alpha_dash');
		$this->form_validation->set_rules('inputTier', 'VM Tier', 'alpha_dash');
		$this->form_validation->set_rules('inputAcu', 'ACU Value', 'numeric');
		$this->form_validation->set_rules('inputHt', 'Hyperthreaded', 'alpha_dash');
		$this->form_validation->set_rules('inputCores', 'Number of Cores', 'numeric');
		$this->form_validation->set_rules('inputPcores', 'Number of pCores', 'numeric');
		$this->form_validation->set_rules('inputMemory', 'Amount of Memory', 'numeric');
		$this->form_validation->set_rules('inputNics', 'Number of NICs', 'numeric');
		$this->form_validation->set_rules('inputData', 'Minimum disk size', 'numeric');
		$this->form_validation->set_rules('inputIops', 'IOPS', 'numeric');
		$this->form_validation->set_rules('inputThroughput', 'Throughput', 'numeric');
		$this->form_validation->set_rules('inputTemp', 'Minimum temp disk size', 'numeric');
		$this->form_validation->set_rules('inputAvgcpupeak', 'Peak CPU Usage', 'less_than_equal_to[100]');
		$this->form_validation->set_rules('inputAvgmempeak', 'Peak Memory Usage', 'less_than_equal_to[100]');
		$this->form_validation->set_rules('inputSaps2tier', 'SAPS 2-Tier', 'numeric');
		$this->form_validation->set_rules('inputSaps3tier', 'SAPS 3-Tier', 'numeric');
		$this->form_validation->set_rules('inputResults', 'Max Results', 'less_than_equal_to[100]');

		if ($this->form_validation->run() == FALSE)
		{
				// NOK
				$this->load->view('tpl/header');	
				$this->load->view('vmchooser-form',$data);
				$this->load->view('tpl/footer');
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
				   //echo "Something went wrong :-(";
			}
			$inputRegion = $this->security->xss_clean($_POST["inputRegion"]);
			$inputTier = $this->security->xss_clean($_POST["inputTier"]);
			$inputAcu = $this->security->xss_clean($_POST["inputAcu"]);
			$inputHt = $this->security->xss_clean($_POST["inputHt"]);
			$inputCores = $this->security->xss_clean($_POST["inputCores"]);
			$inputPcores = $this->security->xss_clean($_POST["inputPcores"]);
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
				   //echo "Something went wrong :-(";
			}
			$inputSaps2tier = $this->security->xss_clean($_POST["inputSaps2tier"]);
			$inputSaps3tier = $this->security->xss_clean($_POST["inputSaps3tier"]);
			$inputResults = $this->security->xss_clean($_POST["inputResults"]);
			$querysuffix = "?maxresults=$inputResults&acu=$inputAcu&ht=$inputHt&tier=$inputTier&region=$inputRegion&pcores=$inputPcores&cores=$inputCores&memory=$inputMemory&iops=$inputIops&data=$inputData&temp=$inputTemp&throughput=$inputThroughput&nics=$inputNics&ssd=$ssd&avgcpupeak=$inputAvgcpupeak&avgmempeak=$inputAvgmempeak";
			
			if ((!empty($inputSaps2tier) AND !empty(inputSaps3tier)) OR ($hana == Yes)) {
				$sapsuffix = "&saps2t=$inputSaps2tier&saps3t=$inputMemory&iops=$inputSaps3tier&hana=$hana";
			}

			$telemetryClient->trackEvent('Index : API Call');
			
			// Do API Call
			$this->load->library('guzzle');
			if ($sapsuffix <> "") { $api_url = getenv('SAPCHOOSERAPI') . $querysuffix . $sapsuffix; } else { $api_url = getenv('VMCHOOSERAPI').$querysuffix; }
			
			$vmchooserapikey = getenv('VMCHOOSERAPIKEY');
			$client     = new GuzzleHttp\Client(['headers' => ['Ocp-Apim-Subscription-Key' => $vmchooserapikey]]);
			try {
				$response = $client->request( 'POST', $api_url);
				$json =  $response->getBody()->getContents();
			} catch (GuzzleHttp\Exception\BadResponseException $e) {
				$response = $e->getResponse();
				$responseBodyAsString = $response->getBody()->getContents();
				print_r($responseBodyAsString);
				echo "Something went wrong :-(";
			}

			$telemetryClient->trackEvent('Index : Prep Data');
			
			// Prep Results
			$array = json_decode($json);
			$i=0;
			foreach ($array as $result) {
			  $temp = (array) $result;
			  $results[$i] = $temp;
			  $i++;
			}

			$telemetryClient->trackEvent('Index : Load Page');
		
			// OK
			$data['results'] = $results;
			$this->load->view('tpl/header');	
			$this->load->view('vmchooser-form',$data);
			$this->load->view('tpl/footer');
			
			$telemetryClient->flush();
		}
	}
	
	public function results() 
	{
		//
		$this->load->helper('security');
		if ($this->uri->segment(3) === FALSE)
		{
				echo "no csv file provided";
				die();
		}
		else
		{
				$csvfile = $this->uri->segment(3);
		}
		$csvfile = $this->security->xss_clean($csvfile);
		if ($csvfile == "") {
			echo "no csv file provided";
			die();
		}
		
		$api_url = getenv('VMCHOOSERCSVRESULTS');
		$api_url = str_replace("{csvfile}", $csvfile, $api_url);
			
		$this->load->library('guzzle');
		$vmchooserapikey = getenv('VMCHOOSERAPIKEY');	
		$client     = new GuzzleHttp\Client(['headers' => ['Ocp-Apim-Subscription-Key' => $vmchooserapikey]]);
		try {
			$response = $client->request( 'POST', $api_url);
			$json =  $response->getBody()->getContents();
		} catch (GuzzleHttp\Exception\BadResponseException $e) {
			$response = $e->getResponse();
			$responseBodyAsString = $response->getBody()->getContents();
			//print_r($responseBodyAsString);
			//echo "Something went wrong :-(";
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
		$data['csvfile'] = $csvfile;
		$data['results'] = $results;
		$this->load->helper(array('url'));
		$this->load->view('tpl/header');	
		$this->load->view('vmchooser-results-csv',$data);
		$this->load->view('tpl/footer');
	}

	public function vmsize() 
	{
		$this->load->helper('security');
		if ($this->uri->segment(3) === FALSE)
		{
				echo "no vmsize given";
				die();
		}
		else
		{
				$vmsize = $this->uri->segment(3);
		}
		$vmsize = $this->security->xss_clean($vmsize);
		
		$querysuffix = "&vmsize=$vmsize";
		$api_url = getenv('VMSIZECHOOSERAPI') . $querysuffix;
			
		$this->load->library('guzzle');
		$vmchooserapikey = getenv('VMCHOOSERAPIKEY');	
		$client     = new GuzzleHttp\Client(['headers' => ['Ocp-Apim-Subscription-Key' => $vmchooserapikey]]);
		try {
			$response = $client->request( 'POST', $api_url);
			$json =  $response->getBody()->getContents();
		} catch (GuzzleHttp\Exception\BadResponseException $e) {
			$response = $e->getResponse();
			$responseBodyAsString = $response->getBody()->getContents();
			//print_r($responseBodyAsString);
			//echo "Something went wrong :-(";
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
		$data['results'] = $results[0];
		$this->load->helper(array('url'));
		$this->load->view('tpl/header');	
		$this->load->view('vmchooser-vmsize',$data);
		$this->load->view('tpl/footer');	
		
	
	}
	
	public function about() 
	{
		$this->load->helper(array('url'));
		$this->load->view('tpl/header');	
		$this->load->view('about');
		$this->load->view('tpl/footer');
	}
	
	public function csv() 
	{
		$this->load->helper(array('form', 'url'));
		$this->load->library('azurestorage');
		
		$allowed =  array('csv');
		$filename = strtolower($_FILES['csvfile']['name']);

		$ext = pathinfo($filename, PATHINFO_EXTENSION);
		if ($filename <> "") {
			if(!in_array($ext,$allowed) ) {
				$data['message'] = "Only files with a .csv extension are accepted.";
				$this->load->view('tpl/header');	
				$this->load->view('vmchooser-form-csv',$data);
				$this->load->view('tpl/footer');
			}
			else
			{		
				$tmpfile = $_FILES['csvfile']['tmp_name'];
			
				$Azurestorage = new Azurestorage;
				$connectionString = $Azurestorage->getConnectionString();
				$blobName = $Azurestorage->uploadCsvFile($connectionString,$tmpfile);
				$data['message'] = 'Uploaded! In a few minutes you can find your output <a href="/vmchooser/results/'. $blobName . '" target="_blank">here</a>.</br>';
				
				$this->load->view('tpl/header');	
				$this->load->view('vmchooser-form-csv',$data);
				$this->load->view('tpl/footer');
			}
		} else {
			$this->load->view('tpl/header');	
			$this->load->view('vmchooser-form-csv',$data);
			$this->load->view('tpl/footer');
		}
	}
	
}
