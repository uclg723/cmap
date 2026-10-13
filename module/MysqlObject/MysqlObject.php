<?php
/**
 * MysqlObject:
 * 		simple object to provide Create, Retrieve, Update, and Delete 
 */

if (!class_exists('MysqlObject')){

//include_once('include/common.php');
//include_once('config/db_config.php');
//include_once('module/Model/Model.php');

include_once(__DIR__ . '/../../include/common.php');
include_once(__DIR__ . '/../../config/db_config.php');
include_once(__DIR__ . '/../../module/Model/Model.php');

class MysqlObject extends Model{
	//protected $_cache_description;
	protected $_description;	
	protected $_table_name;
	protected $_primary_key = array(); // most table use the field `id` as ID

	static protected $dbh = null;
	// PUBLIC FUNCTIONS
	public function __toString(){
		
	}
	
	public function getPrimaryKey(){
		if (sizeof($this->_primary_key)>0){
			return $this->_primary_key;
		}else{
			return false;
		}
	}
	
	public function __construct(){
//echo "Mysqlobject::connect_to_pdo();<br>";		
		$this->connect_to_pdo(); // get ready of the PDO connection first
//echo "Mysqlobject::get_class();<br>";		
		$class_name = get_class($this);
		$this->_table_name = strtolower($class_name);
//echo "Mysqlobject::describe();<br>";
		$this->describe(); // ask the mysql to describe the table and record in
//echo "Mysqlobject::_load_field_description();<br>";		
		$this->_load_field_description(); 

	} // function __construct
	
	public function __destruct(){
		//$this->dbh = null; // disconnect;
	}
	
	// DB managemange
	protected function connect_to_pdo(){
		global $db_config;
		include_once(__DIR__ . '/../../config/db_config.php');
//echo "class PDO:" . class_exists("PDO") . "<br>";
//echo "\$db_config:[" . sizeof($db_config) . "]<br>";
		if (self::$dbh==null){
			// shared dbh		
			self::$dbh = new PDO(
							'mysql:host=' . $db_config['hostname'] . ';dbname=' . $db_config['database'], 
							$db_config['username'], 
							$db_config['password'],
							array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8')
						);
		}
	}
	protected function get_dbh(){
		return self::$dbh;
	}
	
	
	public function create(){
		// create the model and return the object.
	}
	
	public function retrieve($id){
		// check if the $id is valid
		if (!$this->_is_valid_id($id)){
			return false; // if $id is not valid, don't attempt to retrieve it
		}
	
		$id_column = implode(',' ,$this->_get_id_column_name());
		//$link = $this->_check_mysql_connect();
		
		//$query = mysql_query($sql, $link);
		//if($query){
		//	$nor = mysql_num_rows($query);
		//	if($nor>0){
		//		$row = mysql_fetch_assoc($query);
				
		// Avoid Code injection, use prepare and execute
		// $sql = "SELECT * FROM {$this->_table_name} WHERE {$id_column} = {$id} ; ";		
		//$stmt = $this->dbh->query($sql);
		$sql = "SELECT * FROM {$this->_table_name} WHERE {$id_column} = :{$id_column} ; ";
		$dbh = $this->get_dbh();
		$stmt = $dbh->prepare($sql);
		$stmt->bindValue(":{$id_column}", $id);
		$stmt->execute();
		
//		if ($stmt->rowCount<=0){
//			throw new Exception("Cannot retrieve object of class " . get_class($this) . " with id = " . $id);
//		}
		$row = $stmt->fetch(); // only process the first one.
		if ($row==false){
			return false;
		}
		foreach ($this->_description as $field_name=>$field_desc){
			$this->$field_name = $row[$field_name];
		}
		
		$stmt = null; //destruct
		$row = null;
		//	}
		//}
		return $this;
	} // function retrieve
	
//	public function fromArray($arr_src){
		// copy the data from $arr_src into the object
		
//	}
	public function setField($fieldName, $fieldValue){
		// validate the field value (later)
		$this->$fieldName = $fieldValue;
	}
	
	public function save(){
		$sql = '';

		$pk = $this->getPrimaryKey();
		if (isset($this->$pk[0])){
			// if we have setup the primary key
			$res = $this->updateDB();
		}else{
			$res = $this->insertDB();
		}
		return $res;		
	} // function save
	
	
	public function delete(){
		
	} // function delete
	

	public function getDescription(){
		return $this->_description;
	}
	
	
	// retrieve by filter
	// $filter is an array with field=>value
	public function retrieveByFilter($filter, $is_update_this=false){
		//
		if (!$this->_is_valid_id($id)){
			return false; // if $id is not valid, don't attempt to retrieve it
		}

		$dbh = $this->get_dbh(); //&$this->dbh;
		
		$sql = "SELECT * FROM {$this->_table_name} " ;

		$where_clause = array();

		$field_list = array_keys($this->getDescription());
		
		foreach ($filter as $field=>$value){

			if (in_array($field, $field_list)){
				if (is_array($value) 
					&&
					in_array(strtolower($value['operator']), array('=','>','<','<=','>=','like'))
					){
					// valid operator, use it								
					// if it is array, we expect to have
					// ['operator'] and ['value']
					// other wise, treat it as value
					$op = $value['operator'];
					$where_clause[] = "({$field} {$op} :{$field})";
					
				}else{
					// default use =
					$where_clause[] = "({$field} = :{$field})";
				}

			}
		}
		if (sizeof($where_clause)>0){
			$sql .= " WHERE " . implode (" AND ", $where_clause);
		}
		$sql .= ";";
		

		$stmt = $dbh->prepare($sql);
		foreach ($filter as $field=>$value){
			if (in_array($field, $field_list)){
				if (is_array($value)){
					// if array, expect to use ['value'];
					$val = $value['value'];
					$stmt->bindValue(":{$field}", $val);
				}else{
					$stmt->bindValue(":{$field}", $value);
				}				
			}
		}
		
		
		try{
			$is_done = $stmt->execute();
		}catch (Exception $e){
			echo $e->getMessage();
		}
		//		if ($stmt->rowCount<=0){
		//			throw new Exception("Cannot retrieve object of class " . get_class($this) . " with id = " . $id);
		//		}

		$return_set = array();
		$class_name = get_class($this);

		$pk_list = $this->getPrimaryKey();
		if (sizeof($pk_list)>0){
			$pk = $pk_list[0];
		} 

		while($row = $stmt->fetch()){
			$cur_obj = new $class_name(); // just copy, faster
			foreach ($this->_description as $field_name=>$field_desc){
				$cur_obj->$field_name = $row[$field_name];
			}
			if (isset($pk)){
				$cur_id = $cur_obj->$pk;
				$return_set [$cur_id] = $cur_obj;
			}else{
				$return_set [] = $cur_obj;
			}
		}
		
		$stmt = null;
		$row = null;
				
		if (sizeof($return_set)<=0){
			return false;
		}

		if ($is_update_this){
			// update the object itself as well
			$key_list = array_keys($return_set);
			$first_obj = $return_set[$key_list[0]];
			foreach ($this->_description as $field_name=>$field_desc){
				$this->$field_name = $first_obj->$field_name;
			}
		} // if $is_update_this
			
		//$this = $return_set[0]; // assign this object to be the first return element.
		
		return $return_set;
		
	}
	
	
	protected $_cached_relate_list = null;
	public function getRelateList($relate_list_name_str= null){
		if ($this->_cached_relate_list!=null){
			return $this->_cached_relate_list; // Save Query time
		}
		// load the relate_list from db
		$pk_list = $this->getPrimaryKey();
		if (sizeof($pk_list)<=0){
			return array();
		}
		$pk = $pk_list[0];
		$format_str = null;
		if (is_null($relate_list_name_str)){
			$relate_list_name_str = "{\${$pk}}";
		}else{
		}
		// preprocess the relate_list_name_str into format_str
		$pattern = '/{\$(.*?)}/';
		$is_match = preg_match_all($pattern, $relate_list_name_str, $matches);
		// what is in the matches?

		$search = array();
		if ($is_match>=1){
			$search = $matches[0];
		}
		
		$relate_list = array();
		$obj_list = $this->retrieveByFilter(array()); // get all
		foreach ($obj_list as $id=>$cur_obj){
			$replace = array();
			foreach ($matches[1] as $field_name){
				$replace[] = $cur_obj->$field_name;
			}
			//@TO-DO: get the related list
			$relate_list[$id] = str_replace($search,$replace, $relate_list_name_str);
		}
	
		return $relate_list;
	} // getRelateList
	
	
	//----------------------------------
	// Protected functions
	//----------------------------------
	protected function _check_mysql_connect(){		
		global $mysql_link;
		if ($mysql_link == null){
			connect(); // then, $mysql_link should be ready to use.
		}
		return $mysql_link;		
	}
		
	protected function describe(){
		// call the mysql describe
//		$link = $this->_check_mysql_connect();
		
		// Load the description from the database
		$sql = "DESCRIBE {$this->_table_name};";
//		$res = mysql_query($sql, $link);
//		if ($res){
//			while ($row = mysql_fetch_assoc($res)){

		$dbh = $this->get_dbh();
		$stmt = $dbh->query($sql);
		if ($stmt){
			while ($row = $stmt->fetch()){
		
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
			}
		}
		$stmt = null; // destroy the statment object
		return $this->_description;
	}

	
	protected function _load_field_description(){
		// load the field description from file
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
	protected function insertDB(){
	
		$dbh = $this->get_dbh();
	
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
	
	
		$dbh = $this->get_dbh();
	
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
	
	
	
} // class MysqlObject

} // if not defined class

?>