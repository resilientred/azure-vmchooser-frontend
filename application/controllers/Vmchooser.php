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
				   //echo "Something went wrong :-(";
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
			
			$vmchooserapikey = getenv('VMCHOOSERAPIKEY');
			$client     = new GuzzleHttp\Client(['headers' => ['Ocp-Apim-Subscription-Key' => $vmchooserapikey]]);
			print_r($client);
			echo $api_url;
			try {
				$response = $client->request( 'POST', $api_url);
				$json =  $response->getBody()->getContents();
				echo "debug";
				print_r($response);
			} catch (GuzzleHttp\Exception\BadResponseException $e) {
				$response = $e->getResponse();
				$responseBodyAsString = $response->getBody()->getContents();
				print_r($responseBodyAsString);
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
			$this->load->view('tpl/header');	
			$this->load->view('vmchooser-form',$data);
			$this->load->view('tpl/footer');	
				
		}
	}
	
	public function results() 
	{
		$this->load->helper('security');
		if ($this->uri->segment(3) === FALSE)
		{
				echo "no file given";
				die();
		}
		else
		{
				$blobName = $this->uri->segment(3);
		}
		$blobName = $this->security->xss_clean($blobName);
		$downloadurl = getenv('VMCHOOSERSTORAGEURL').$blobName;
		
		$this->load->library('guzzle');
		$client = new GuzzleHttp\Client;
		try {
			$client->head($downloadurl);
			header('Location: '.$downloadurl);
			exit;
		} catch (GuzzleHttp\Exception\ClientException $e) {
			$this->load->helper(array('url'));
			$this->load->view('tpl/header');	
			$this->load->view('vmchooser-downloadnotready');
			$this->load->view('tpl/footer');
		}
	
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
