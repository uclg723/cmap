<?php
if (!class_exists('ListView')){
	//include('config.php');
	//include('module/View/View.php');
	
	include(__DIR__ .'/../../config.php');
	include(__DIR__ .'/../../module/View/View.php');
	
	class ListView extends View{
		protected $css=array();
		protected $displayColumns = null;
		
		public function addCSS($file_path){
			$this->css[] = $file_path;
		}
		
		public function __construct(){
			$cur_path = realpath(__DIR__);
			$default_css = $cur_path . '/css/ListView.css';
			$this->addCSS ($default_css);
		}
		
		public function setDisplayColumns($display){
			$this->displayColumns = $display;
		}
		
		public function display($return_str=false){
			if ($return_str){
				return "List View not supported return string yet.";
			}
			parent::display($return_str);
		
			global $list; // change the form into global, so that the template can take it
			
			$list = $this->setupList();
			
			// display the css first
			global $css;
			$css = $this->css;
			
			include(__DIR__ . '/template/list_view_content.php');
		
		}
		
		protected function setupList(){
			$list_structure = array(
				'elementType'=>'div',
				'attribute' => array(
					'class'=>'list-view',
				),
				'childNode' => null,	
			);
			// setup the header
			$model = &$this->model;
			
			if ($model == false){
				// no model was loaded
				$list_structure['childNode'] =array(						
					array(
					  'elementType' =>'span',
					  'attribute'=>array(
					  	'class'=>'list-view-content-cell ',
					  ),
					  'childNode' => null,
						  'textNode' => "No Records.",
					),
				);
				return $list_structure;
			}
			
			if ($model instanceof Model){
				$desc = $model->getDescription();	
				$first = $model;			
			}else if (is_array($model)){
				// array
				if (sizeof($model)>0){
					$k = array_keys($model);
					$first = $model[$k[0]];
					$desc = $first->getDescription();
				}else{
					$first = null;
					$desc = array();
				} 
			}else{
				$first = null;
				$desc = array();
			}
			$head_line = array(
				'elementType'=>'div',
				'attribute' => array(
						'class'=>'list-view-header ',
				),
				'childNode' => null,			
			);	
			if (is_null(($first))){
				throw new Exception('ListView::setupList - the model seems corrupted.');
			}
			$head_line_child = array();	
			
			$display_desc = array();
			foreach ($desc as $field=>$cur_desc){
				if ((is_null($this->displayColumns)) ||
					 in_array($field,$this->displayColumns)){
					$display_desc [$field] = $cur_desc; // copy if display
				}
					
			}
			
			$relate_list = array(); // relate list for handling display of related module.
			foreach ($display_desc as $field=>$cur_desc){
				
				
				$label = $first->translateLabel($cur_desc['label']);
				$head_element = array(
					'elementType'=>'span',
					'attribute' => array(
							'class'=>'list-view-header-cell field-' . $field,
					),
					'childNode' => null,
					'textNode' => $label,
				);
				$head_line_child [] = $head_element;		
	
				// Load the RelateList if required.
				if (isset($cur_desc['relate_list'])){
	
					$target_class = $cur_desc['relate_list'];
					self::loadClass($target_class);
					$temp_obj = new $target_class();
					$relate_list_name = (isset($cur_desc['relate_list_name'])?$cur_desc['relate_list_name']:null);
					$relate_list[$target_class] = $temp_obj->getRelateList($relate_list_name);
				}
				
			}
			$head_line['childNode'] = $head_line_child;
			$list_structure['childNode'][] = $head_line;
			
			// 
			$model_list = ($model instanceof Model)?array($model):$model;
			foreach ($model_list as $cur_model){
				$content_line = array(
					'elementType'=>'div',
					'attribute'=> array(
						'class'=>'list-view-content list-view-content-row',
					),
					'childNode'=> null,
				);
				$content_line_child = array();
				foreach ($display_desc as $field=>$cur_desc){
					$content_cell = array(
						'elementType' => 'span',
						'attribute' => array(
							'class'=>'list-view-content-cell field-' . $field, 
						),
						'textNode'=> $cur_model->$field,
					);
					
					if (isset($cur_desc['relate_list'])){
						// is related to another module
						$target_class = $cur_desc['relate_list'];
						$diplay_str = $relate_list[$target_class][$cur_model->$field];
						$content_cell['textNode'] = $diplay_str;
					}
					
					$content_line_child[] = $content_cell;
				}
				$content_line['childNode'] = $content_line_child;
				$list_structure['childNode'][] = $content_line;
			}		
			
			
			return $list_structure;
		}
	}  //class
} //if