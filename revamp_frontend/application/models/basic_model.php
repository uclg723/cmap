<?php
// Basic_model: gather the common CRUD functions together,
//   - under the code-igniter model
class Basic_model extends CI_Model {
	protected $_description = null;	
	protected $_table_name = '';
	protected $_primary_key = array(); // most table use the field `id` as ID

	protected $_field_list = array(); // to be automated by 'describe' later on.
	// Constructor
	public function __construct()
	{
		$this->load->database();
		$class_name = get_class($this);
		
		$class_name = strtolower(get_class($this));
		$table_name = str_replace('_model','',$class_name);
		if (is_null($this->_table_name) || empty($this->_table_name)){
			$this->_table_name = $table_name;
		} // change default table name if it is null
//echo "Mysqlobject::describe();<br>";
		$this->describe(); // ask the mysql to describe the table and record in
//echo "Mysqlobject::_load_field_description();<br>";		
		$this->_load_field_description(); 

	} // __construct
	// Destructor
	public function __destruct(){
		//$this->dbh = null; // disconnect;
	} // __destruct
	
	// Helper functions (public)
	public function getDescription(){
		return $this->_description;
	}

	public function get_id_field(){ 
		return $this->getPrimaryKey(); 
	}
	
 	public function getPrimaryKey(){
		if (sizeof($this->_primary_key)>0){
			return $this->_primary_key;
		}else{
			return false;
		}
	}

	// Create
	public function create(){
	
	} // create
	
	//
	// Taken from revamp_backend/application/models/basic_model.php
	public function retrieve($id = FALSE)
	{
		$table_name = $this->_table_name;
		$id_field = $this->get_id_field();

		if ($id === FALSE)
		{
			$query = $this->db->get($table_name);
			return $query->result_array();
		}

		if (is_array($id_field)){ 
			$id_field = $id_field[0]; // only use first field
		}
		$query = $this->db->get_where($table_name, array($id_field => $id));
		return $query->row_array();
	} // function get_page
	
	public function insert($data=null)
	{
//		$this->load->helper('url');

		if (is_null($data)){
			$data = array();
		}
		foreach ($this->_field_list as $cur_field=>$field_content){
		
			if (isset($_POST[$cur_field])){
				$cur_data = $this->input->post($cur_field);
				if (isset($cur_data)){
					$data[$cur_field] = $cur_data;
				}
			} // is it post
		}

		$is_success =  $this->db->insert($this->_table_name, $data);
		if (!$is_success){ return false;}
		$last_insert_id = $this->db->insert_id();
		return $last_insert_id; // either return id, or return false.
	} // set_page
	
	
	public function update($id, $req_data=null)
	{
//		$this->load->helper('url');
		if (is_null($req_data)){
			// default, use post
			$req_data= $_POST;
		}

		$data = array();
		foreach ($this->_field_list as $cur_field=>$field_content){
			if (isset($req_data[$cur_field])){
				$cur_data = $req_data[$cur_field]; // old: $this->input->post($cur_field);
				if (isset($cur_data)){
					$data[$cur_field] = $cur_data;
				}
			}
		}
		$table_name = $this->_table_name;
		$id_field = $this->get_id_field();
		
		if (is_array($id_field)){ 
			$id_field = $id_field[0]; // only use first field
		}

		$this->db->where($id_field, $id);
		return $this->db->update($table_name, $data);
	} // set_page
	
	public function delete($id){
		if (empty($id)){
			return;
		}
		$table_name = $this->_table_name;
		$id_field = $this->get_id_field();
		$this->db->where($id_field, $id);
		$this->db->delete($table_name); 
	}	
	
	public function save(){
	} // save()
	 
	protected $_cached_module_label = null;	
	function translateLabel($label, $lang='english',$force_reload=false){
		
		if ($forced_reload || (is_null($this->_cached_module_label))){
			$class_name = get_class($this);
			//global $CMAP_FILE_BASE;
			$module_label_file =  __DIR__ . '/../../module/' . $class_name . '/lang/module_label.php'; 
			if (file_exists($module_label_file)){
				include($module_label_file);
				// $module_label defined.
				$this->_cached_module_label = $module_label;
			}
		} // load the _cached_module_label
		$label_str = $this->_cached_module_label[$lang][$label];
		if (empty($label_str)){
			$label_str = $this->_cached_module_label['en'][$label];
		}		
		return $label_str;
	} // function
	protected $_option_list = null;
	function getOptionList($field_name, $lang='en', $force_reload=false){
		if ($forced_reload || (is_null($this->_cached_module_label))){
			$class_name = get_class($this);
			//global $CMAP_FILE_BASE;
			$option_list_file = __DIR__ . '/../../module/' . $class_name . '/lang/option_list.php';
			if (file_exists($option_list_file)){
				include($option_list_file);
				// $module_label defined.
				$this->_option_list = $option_list;
			}
		} // load the _cached_module_label
		$cur_option_list = $this->_option_list[$class_name][$lang][$field_name];
		if (empty($cur_option_list)){
			$cur_option_list = $this->_option_list[$class_name]['en'][$field_name];
		}
		return $cur_option_list;
		
	}
	
	
	//-----------------------------------------------
	//
	//  Protected functions
	//
	//-----------------------------------------------
	
		protected function describe(){
		
		// Load the description from the database
		$sql = "DESCRIBE {$this->_table_name};";

		// alias
		$dbh =& $this->db;
		$stmt = $dbh->query($sql);
		if (($stmt) && $stmt->num_rows()>0){
			$num_rows = $stmt->num_rows;
			
			foreach ($stmt->result_array() as $row){
				$field_name = strtolower($row['Field']);
				
				$this->_cache_description 	= $row;
				$cur_desc['field_name']	 	= $field_name;
				$cur_desc['table_name']		= $this->_table_name;
				$cur_desc['table_column']   = $row['Field'];
				// parse the type
				$is_match = preg_match("/([A-Za-z0-9]+)(\(){0,1}([0-9]*)(\)){0,1}/", $row['Type'], $match);
				if ($is_match){
					// $match[1]: data type
					// $match[3]: length
					$cur_desc['data_type'] = $match[1];
					$cur_desc['length']    = (empty($match[3]))?1:$match[3];
				}else{
					$cur_desc['data_type'] 		= $row['Type'];
					$cur_desc['length']     = 1;
				}
				
				$cur_desc['is_nullable']  	= ($row['Null'] == 'YES');
				$cur_desc['key_type']		= $row['Key'];
				$cur_desc['is_primary_key'] = ($row['Key'] == 'PRI');
				$cur_desc['default_value']  = $row['Default'];
				$cur_desc['extra_description'] = $row['Extra'];
				$this->_description[$field_name] = $cur_desc;
				if ($cur_desc['is_primary_key']){
					$this->_primary_key[] = $field_name;
				}
			} // foreach
		} // if
		$stmt->free_result();
		$stmt = null; // destroy the statment object

		// simply copy the description to field_list ?
		if (is_null($this->_field_list)||
			empty($this->_field_list)){
			$this->_field_list = $this->_description;
		}
		return $this->_description;
	}

	
	protected function _load_field_description(){
		// load the field description from file
		
		return false;
		// will debug
		
		$class_name = get_class($this); // get the actual class name
		//global $base_url;
		//global $CMAP_FILE_BASE;
		$field_path = __DIR__ . "/../../module/{$class_name}/fields";
		// as 
		//if (is_dir($field_path)){
			if ($handle = opendir($field_path)) {
				while (false !== ($entry = readdir($handle))) {
					if ($entry != "." && $entry != "..") {
						// check if the file extension is php
						if (substr_compare($entry, '.php', -4)==0){
							// a php file to load
					
							include($field_path . '/' . $entry);
							// use the description from there

						}						
//						echo "$entry\n";
					} //if
				} // while
				// $description
				// after loading the description files, now merge them into the object variable
				foreach ($description as $field=>$cur_desc){
					foreach ($cur_desc as $cur_desc_name=>$cur_desc_val){
						$this->_description[$field][$cur_desc_name] = $cur_desc_val;
					}
				}
					closedir($handle);
			} // can open
		// } //is dir		
	}
	
	protected function getTableName(){
		return $this->_table_name;
	}
	
	protected function _get_id_column_name(){
		return $this->_primary_key;
	}
	
	protected function _is_valid_id($id){
		// return true or false
		// all $id where assume to be number, if not, return false
		// else return true;
		return true; // not to check first.
	}
	
	// insertDB: insert the current object into the database
	
	// Old Style code, keep for compatability
	protected function insertDB(){
	
		$dbh =& $this->db;
	
		$table = $this->getTableName();
		$sql = 'INSERT INTO ' . $table . ' ';
	
		$desc = $this->getDescription();
		$name_list = array();
		$value_list = array();
		foreach ($desc as $field=>$cur_desc){
			$name_list[] = " {$field} ";
			$value_list[] = " :{$field} ";
			// where is using the primary key
		}
		$sql .= " (" . implode(", ", $name_list) . ") ";
				$sql .= " VALUES (" . implode (", ", $value_list) . ") ";
	
				// Insert statement, don't have the primary key
				// $pk = $this->getPrimaryKey();
				// $pk_col = $pk[0];
				// $pk_val = $this->$pk_col;
				//		$sql .= " WHERE {$pk_col} = {$pk_val} ";
	
				$stmt = $dbh->prepare($sql);
				$i = 1;
				$param = array();
				foreach ($desc as $field=>$cur_desc){
						
	
					// Not used, for making up the quote
					// $value = $this->$field;
					// switch ($cur_desc['data_type']){
					// 	case 'int':
					// 		$quoted_value = $value;
					// 		break;
					// 	case 'varchar':
					// 	case 'text':
					// 	case 'longtext':
					// 		$quoted_value = "'{$value}'";
					// 		break;
					// 	case 'datetime':
					// 		$quoted_value = "#{$value}#";
					// 		break;
					// 	default:
					// 		$quoted_value = $value;
					//
					// }
					$stmt->bindParam(":{$field}", $this->$field);
					//		$param [] = $this->$field;
				}
				$res = $stmt->execute();
	
				// should find by the record.
				$stmt = null;
				return $res;
	} // insertDB
	
	protected function updateDB(){
		$sql = '';
	
	
		$dbh =& $this->db;
	
		$table = $this->getTableName();
		$sql = 'UPDATE ' . $table . ' ';
	
		$desc = $this->getDescription();
		$nv_pair = array();
		foreach ($desc as $field=>$cur_desc){
			$name = $field;
	
				
			$value = $this->$field;
	
			switch ($cur_desc['data_type']){
				case 'int':
					$quoted_value = $value;
					break;
				case 'varchar':
				case 'text':
				case 'longtext':
					$quoted_value = "'{$value}'";
					break;
				case 'datetime':
					$quoted_value = "#{$value}#";
					break;
				default:
					$quoted_value = $value;
						
			}
	
			$nv_pair[] = " {$name} = :{$name} ";
				
				
				
			// where is using the primary key
		}
		$sql .= "SET " . implode(", ", $nv_pair);
	
		$pk = $this->getPrimaryKey();
		$pk_col = $pk[0];
		$pk_val = $this->$pk_col;
		$sql .= " WHERE {$pk_col} = {$pk_val} ";
	
		$stmt = $dbh->prepare($sql);
		$i = 1;
		$param = array();
		foreach ($desc as $field=>$cur_desc){
		$stmt->bindParam(":{$field}", $this->$field);
		//		$param [] = $this->$field;
		}
		$res = $stmt->execute();
	
		$stmt = null;
		return $res;
	
	
	} // updateDB
	



} // class Basic_model

?>