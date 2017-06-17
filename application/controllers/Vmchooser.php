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
		$this->form_validation->set_rules('inputCores', 'inputCores', 'numeric');
		$this->form_validation->set_rules('inputMemory', 'inputMemory', 'numeric');
		$this->form_validation->set_rules('inputNics', 'inputNics', 'numeric');
		$this->form_validation->set_rules('inputData', 'inputData', 'numeric');
		$this->form_validation->set_rules('inputIops', 'inputIops', 'numeric');
		$this->form_validation->set_rules('inputThroughput', 'inputThroughput', 'numeric');
		$this->form_validation->set_rules('inputTemp', 'inputTemp', 'numeric');

		if ($this->form_validation->run() == FALSE)
		{
				// NOK
				$this->load->view('vmchooser-form');
		}
		else
		{
				// OK
				$this->load->view('vmchooser-form');
		}
}
}
