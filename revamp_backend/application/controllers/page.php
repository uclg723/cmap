<?php

class Page extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('page_model');
	
	} // __construct()

	public function view($page = 'list', $data=null)
	{
		// load the data
		if (is_null($data)){
			$data['page'] = $this->page_model->get_page();
		}

	
		if ( ! file_exists(APPPATH.'/views/page/'.$page.'.php'))
		{
			// Whoops, we don't have a page for that!
			show_404();
		}

		$data['title'] = ucfirst($page); // Capitalize the first letter

		$this->load->view('templates/header', $data);
		$this->load->view('page/'.$page, $data);
		$this->load->view('templates/footer', $data);

	} // view
	
	public function index()
	{
//		$this->view('list');		

		// load the data
		$data['page'] = $this->page_model->get_page();

		$page = 'list';
		if ( ! file_exists(APPPATH.'/views/page/'.$page.'.php'))
		{
			// Whoops, we don't have a page for that!
			show_404();
		}

		$data['title'] = ucfirst($page); // Capitalize the first letter

		$this->load->view('templates/header', $data);
		$this->load->view('page/'.$page, $data);
		$this->load->view('templates/footer', $data);



	} // index
	
	public function create(){
		$this->load->helper('form');
		$this->load->library('form_validation');

		$data['title'] = 'Create a Page';

		$this->form_validation->set_rules('page_name', 'Page Name', 'required');
		$this->form_validation->set_rules('page_description', 'Page Description', 'required');

		if ($this->form_validation->run() === FALSE)
		{
			$this->load->view('templates/header', $data);
			$this->load->view('page/create');
			$this->load->view('templates/footer');

		}
		else
		{
			$data['page'] = $this->page_model->set_page();
//			$this->load->view('templates/header', $data);
//			$this->load->view('page/detail');
//			$this->load->view('templates/footer');
			
			$this->view('list');
		}
	} // function create()
	
	public function detail($page_id){
		$data['page'] = $this->page_model->get_page($page_id);

		$page = 'detail';
	
		if ( ! file_exists(APPPATH.'/views/page/'.$page.'.php'))
		{
			// Whoops, we don't have a page for that!
			show_404();
		}

		$data['title'] = ucfirst($page); // Capitalize the first letter

		$this->load->view('templates/header', $data);
		$this->load->view('page/'.$page, $data);
		$this->load->view('templates/footer', $data);

	} // detail
	public function edit($page_id){
		$data['page'] = $this->page_model->get_page($page_id);
		$data['page_type_list'] = $this->page_model->get_page_type_list();

		$page = 'edit';

		$this->load->helper('form');
		$this->load->library('form_validation');

		$data['title'] = 'Edit a Page';

		$this->form_validation->set_rules('page_name', 'Page Name', 'required');
		$this->form_validation->set_rules('page_description', 'Page Description', 'required');

		if ($this->form_validation->run() === FALSE)
		{
			$this->load->view('templates/header', $data);
			$this->load->view('page/edit');
			$this->load->view('templates/footer');

		}
		else
		{
			$result = $this->page_model->update_page($page_id);
			$data['page'] = $this->page_model->get_page($page_id);
			$this->view('update_success', $data);
			
		}	
	} // function edit
	
	public function delete($page_id){
		$this->page_model->delete_page($page_id);
		$this->view('list');
	}
} // class Pages
?>