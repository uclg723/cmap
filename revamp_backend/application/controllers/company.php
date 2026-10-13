<?php

class Company extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('company_model');
	
	} // __construct()

	public function view($page = 'list')
	{
		// load the data
		$data['company'] = $this->company_model->get();

	
		if ( ! file_exists(APPPATH.'/views/company/'.$page.'.php'))
		{
			// Whoops, we don't have a page for that!
			show_404();
		}

		$data['title'] = ucfirst($page); // Capitalize the first letter

		$this->load->view('templates/header', $data);
		$this->load->view('company/'.$page, $data);
		$this->load->view('templates/footer', $data);

	} // view
	
	public function index()
	{
		$this->view('list');		
	} // index
	
	public function create(){
		$this->load->helper('form');
		$this->load->library('form_validation');

		$data['title'] = 'Create a Company';

		$this->form_validation->set_rules('company_name_en', 'Company Name', 'required');
		$this->form_validation->set_rules('company_address_en', 'Company Address', 'required');


		if ($this->form_validation->run() === FALSE)
		{
			$this->load->view('templates/header', $data);
			$this->load->view('company/create');
			$this->load->view('templates/footer');

		}
		else
		{
			$data['company'] = $this->company_model->insert();
//			$this->load->view('templates/header', $data);
//			$this->load->view('page/detail');
//			$this->load->view('templates/footer');
			
			$this->view('list');
		}
	} // function create()
	
	public function detail($company_id){
		$data['company'] = $this->company_model->get($company_id);

		$page = 'detail';
	
		if ( ! file_exists(APPPATH.'/views/company/'.$page.'.php'))
		{
			// Whoops, we don't have a page for that!
			show_404();
		}

		$data['title'] = ucfirst($page); // Capitalize the first letter

		$this->load->view('templates/header', $data);
		$this->load->view('company/'.$page, $data);
		$this->load->view('templates/footer', $data);

	} // detail
	public function edit($company_id){
		$data['company'] = $this->company_model->get($company_id);

		$page = 'edit';

		$this->load->helper('form');
		$this->load->library('form_validation');

		$data['title'] = 'Edit Company';

		$this->form_validation->set_rules('company_name_en', 'Company Name', 'required');
		$this->form_validation->set_rules('company_address_en', 'Company Address', 'required');

		if ($this->form_validation->run() === FALSE)
		{
			$this->load->view('templates/header', $data);
			$this->load->view('company/edit');
			$this->load->view('templates/footer');

		}
		else
		{
			$data['page'] = $this->company_model->update($company_id);
			$this->view('list');
//			$this->load->view('templates/header', $data);
//			$this->load->view('page/detail');
//			$this->load->view('templates/footer');
			
		}	
	} // function edit
	
	public function delete($company_id){
		$this->company_model->delete($company_id);
		$this->view('list');
	}
} // class Pages
?>