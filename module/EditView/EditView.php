<?php

if (!class_exists('EditView')){

//include(__DIR__ .'/config.php');
//include(__DIR__ .'/module/View/View.php');

include(__DIR__ .'/../../config.php');
include(__DIR__ .'/../../module/View/View.php');

class EditView extends View{
	
	protected $method='post';
	protected $action=null;
	
	protected $error_queue=array();
	
	
	function __construct(){
		parent::__construct();
		
	}
	
	public function pushError($msg){
		$this->error_queue [] = $msg;
	}
	
	public function setAction($url){
		$this->action = $url;
	}
	
	public function display($return_str=false){
		if ($return_str){
			return "Edit View not supported return string yet.";
		}
		parent::display($return_str);
		
		global $form; // change the form into global, so that the template can take it
		$form = $this->setupForm();
		
		global $error_queue;
		$erorr_queue = $this->error_queue;
		
		include('template/edit_view_content.php');
		
	}
	
	protected function setupForm(){
		$form = array(); // form is the structural object for rendering the actual form
		$form['elementType'] = 'form';
		$form['attribute'] = array(
				'method' => $this->method,
				'action' => $this->action,
		);
//		$form['method'] = $this->method;
//		$form['action'] = $this->action;
		$model = &$this->model; //alias
		$desc = $model->getDescription();
		foreach ($desc as $field=>$description){
			$div = array(
					'elementType'=>'div',
					'attribute' => array(
							'name'	=> 'div_' . $field,
							'class' => 'div_' . $field . ' form-group',
					),
					'childNode' => array(),
			);
			
			// Label Node
			$label_text = $model->translateLabel($description['label']);
			$labelNode = array(
					'elementType'=>'label',
					'attribute'=>array(
						'class' => $field . '_label',
					),
					'textNode'=> $label_text,
			);
			if (!(isset($description['user_input']) && 
				($description['user_input']==false))){
				// user input not defined or set to true
				$div['childNode'][$field . '_label'] = $labelNode;
			}
			
			// INPUT NODE
			$inputNode = array(
					'elementType'=>'input',
					'attribute'=>array(
							'name'	=> $field,
							'value' => $model->$field,
							'type'  => 'text',
							'size'  => $decription['length'],
							'class' => 'form-control',
					),
					'textNode'=>'', // textNode inside the tag
//					'elementName'=> $field,
			);
				
			if (($description['data_type'] =='text') ||
				($description['data_type'] =='longtext'))
			{
					$inputNode['elementType'] = 'textarea';		
					$inputNode['attribute']['value'] = null;
					$inputNode['textNode'] = $model->$field;		
			}
			
			if (($description['is_primary_key']==true)){
				$inputNode['elementType'] = 'input';
				$inputNode['attribute']['type'] = 'hidden';
				
			}else if (($description['user_input']==false)){
				$inputNode = null;
			}
			
			
			$div['childNode'][$field] = $inputNode;
			
			// attach to form
			$form['childNode']['div_' . $field] = $div;
		}
		
		$submit_button = array(
			'elementType' => 'button',
			'attribute'=>array(
				'class'=>'btn btn-default',
				'name' =>'submit',
				'type' => 'submit',	
			),
			'textNode' => 'Submit',					
		);
		$form['childNode']['submit_btn'] = $submit_button;
		
		return $form;
	}
	
}  //class
} //if
