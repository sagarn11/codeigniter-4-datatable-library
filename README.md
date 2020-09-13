# Codeigniter 4 Datatables Library

This repository is for codeigniter 4 datatables


## Instructions

Store Datatables.php file in Root_folder/app/Libraries Folder


## How to use

### Controller
Using Codeigniter's provided Query Builder.
DO NOT CALL Query Builders `get()` before `generate_datatable()` function
If necessary call `get()` function with arguments as `get(NULL, 0 , false)`
```
<?php
namespace App\Controllers;
use App\Libraries\Datatables;

class User extends BaseController
{
	private $_db;
	private $_builder;
	public function __construct()
	{
		$this->_db = \Config\Database::connect();
	}
	public function get_data()
	{
		$datatables = new Datatables;
		$where = [
			'username' => 'admin'
		];
		$varible = 'value';
		$editColumn = [
			'status' => ['helper_function' => 'get_status_button', 'args' => [['status'],[$varible, false]]],
			'role_id' => ['helper_function' => 'get_role_name', 'args' => [['role_id']]],
		];
		$this->_builder = $this->_db->table('pf_user_master s'); 
		$this->_builder->join('pf_role_master r', 'r.role_id = s.role_id');
		$this->_builder->select('username, first_name, last_name, mobile, email, r.role_id, s.status');
		if (is_array($where) && !empty($where)) {
			$this->_builder->where($where);
		}
		$output = $datatables->generate_datatable($this->_builder,$editColumn);
		echo $output;
	}
}
```
#### `$editColumn` variable
`$editColumn` variable is NOT mandatory.
`$editColumn` should be an assosiative array.
##### `$editColumn` key
`$editColumn` key must be the column name we would like to change.
##### `$editColumn` value 
`$editColumn` value should be an assosiative array.
keys `helper_function` and `args` are MANDATORY. while `helper_function` value should be the helper function without `()`
The value stored in key `args` should be and Array of Array. With inner array's first value is column name from selected columns and second value is NOT mandatory as it requires to be set as `false` only if first value is not column name and needs to a value stored in variable or `NULL`
