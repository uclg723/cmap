<?php

class Page_block extends CI_Controller {

	// used for file_browser & file_upload
	var $base_url = 'http://localhost:8088/cmaphk';
	
	public function __construct(){
		parent::__construct();
		$this->base_url = 'http://localhost/';
		$this->load->model('page_block_model');
		$this->load->model('page_model');
	
	} // __construct()

	public function view($page = 'list')
	{
		// load the data
		$data['page_list'] = $this->page_model->get_option_list();
		$data['page_blocks'] = $this->page_block_model->get();

	
		if ( ! file_exists(APPPATH.'/views/page_block/'.$page.'.php'))
		{
			// Whoops, we don't have a page for that!
			show_404();
		}

		$data['title'] = ucfirst($page); // Capitalize the first letter

		$this->load->view('templates/header', $data);
		$this->load->view('page_block/'.$page, $data);
		$this->load->view('templates/footer', $data);

	} // view
	
	public function index()
	{
		$this->view('list');		



	} // index
	
	public function handle_upload_file($upload_file='block_image'){
		$config['upload_path'] = '../uploads/page_block/'; // './uploads/';
		$config['allowed_types'] = 'gif|jpg|jpeg|png';
		$config['max_size'] = '30000'; // KB, (30 MB)
		$config['max_width'] = '5000';
		$config['max_height'] = '5000';
		
		$this->load->library('upload', $config);
		$error = array();
		$data = array();
		if (!$this->upload->do_upload($upload_file)){
			$up_err = $this->upload->display_errors();
			$error = array('error' => $up_err);			
		}else{
			$upload_data = $this->upload->data();
			$data = array('upload_data' => $upload_data);
		}
		return array(
			'error'=> $error,
			'data' => $data,
		);
		
	} // function handle_upload_file
	
	/** Dummy function for debug
	function handle_upload_file(){
		return array(
			'error'=> array(),
			'data' => array(),
		);
	}*/
	
	public function create(){
		$this->load->helper('form');
		$this->load->library('form_validation');

		$data['title'] = 'Create a Page Block';

		$this->form_validation->set_rules('is_visible', 'Visible', 'required');
		$this->form_validation->set_rules('display_order', 'Display Order', 'required');
		if (count($_POST)>0){
			// has post data
			$upload_result = $this->handle_upload_file();
		}else{
			$upload_result = array('error'=>array());
		}
		if ($this->form_validation->run() === FALSE ||
			(count($upload_result['error'])>0)
			)
		{		
		
			$data['upload_error'] = $upload_result['error'];
			$data['page_list'] = $this->page_model->get_option_list();
			
			$this->load->view('templates/header', $data);
			$this->load->view('page_block/create');
			$this->load->view('templates/footer');

		}
		else
		{
			// $upload_data['data']['upload_data']
			// 'image_width' 'image_height', 'is_image', 'file_name'
			// 'upload_data'
			$field_list = array(
				'parent_page_id',
				'display_order',
				'is_visible',
				'div_class',
				'content_en',
				'content_tc',
				'content_sc',
				'block_image',
			);
			$post_data = array();
			foreach ($field_list as $cur_field){
				$post_data[$cur_field] = $this->input->post($cur_field);
			}
			// special case of block_image			
			$post_data['block_image'] = $upload_result['data']['upload_data']['file_name'];
			$data['page_block'] = $this->page_block_model->insert($post_data);
//			$this->load->view('templates/header', $data);
//			$this->load->view('page/detail');
//			$this->load->view('templates/footer');
			
			$this->view('list');
		}
	} // function create()
	
	
	public function detail($page_block_id){
		$data['page_block'] = $this->page_block_model->get($page_block_id);

		$page = 'detail';
	
		if ( ! file_exists(APPPATH.'/views/page_block/'.$page.'.php'))
		{
			// Whoops, we don't have a page for that!
			show_404();
		}

		$data['title'] = ucfirst($page); // Capitalize the first letter

		$this->load->view('templates/header', $data);
		$this->load->view('page_block/'.$page, $data);
		$this->load->view('templates/footer', $data);

	} // detail
	public function edit($page_block_id){
		$data['page_block'] = $this->page_block_model->get($page_block_id);

		$page = 'edit';

		$this->load->helper('form');
		$this->load->library('form_validation');

		$data['title'] = 'Edit a Block';

		$this->form_validation->set_rules('parent_page_id', 'Parent Page', 'required');
		$this->form_validation->set_rules('display_order', 'Page Order', 'required');

		if ($this->form_validation->run() === FALSE)
		{
			$this->load->view('templates/header', $data);
			$this->load->view('page_block/edit');
			$this->load->view('templates/footer');

		}
		else
		{
			$data['page_block'] = $this->page_block_model->update($page_id);
			$this->view('list');
//			$this->load->view('templates/header', $data);
//			$this->load->view('page/detail');
//			$this->load->view('templates/footer');
			
		}	
	} // function edit
	
	public function delete($page_block_id){
		$this->page_block_model->delete($page_block_id);
		$this->view('list');
	}
	
	//file_browser page used by CKEditor for file browsing
	public function file_browser(){
		// Required: anonymous function reference number as explained above.
		$funcNum = $_REQUEST['CKEditorFuncNum'];
		// Optional: instance name (might be used to load a specific configuration file or anything else).
		$CKEditor = $_REQUEST['CKEditor'];
		// Optional: might be used to provide localized messages.
		$langCode = $_REQUEST['langCode'];
		$data['funcNum'] = $funcNum;
		$data['CKEditor'] = $CKEditor;
		$data['langCode'] = $langCode;
		
		$admin_upload_folder = __DIR__ . '/../../../cmaphk/admin/uploads/page_block';
		$file_list = array();
		if ($handle = opendir($admin_upload_folder)) {
			while (false !== ($entry = readdir($handle))) {
				if ($entry != "." && $entry != "..") {
					//echo "$entry\n";
					$file_list[] = $entry;
				}
			}			
			closedir($handle);
		}
		$data['file_list'] = $file_list;
		$data['base_url'] = $this->base_url;
		$this->load->view('page_block/file_browser', $data);
	}
	
	
	public function file_upload(){
	    // Required: anonymous function reference number as explained above.
		
		$result = $this->handle_upload_file('upload');
		if (count($result['data'])>0){
			$file_name = $result['data']['upload_data']['file_name'];
		}
		$funcNum = $_REQUEST['CKEditorFuncNum'] ;
		// Optional: instance name (might be used to load a specific configuration file or anything else).
		$CKEditor = $_REQUEST['CKEditor'] ;
		// Optional: might be used to provide localized messages.
		$langCode = $_REQUEST['langCode'] ;

		// Check the $_FILES array and save the file. Assign the correct path to a variable ($url).
		$url = $this->base_url . '/admin/upload/page_block/' . $file_name;
		// Usually you will only assign something here if the file could not be uploaded.
		$message = 'Ok';
		echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($funcNum, '$url', '$message');</script>";
	}
	
	
} // class Pages
?>