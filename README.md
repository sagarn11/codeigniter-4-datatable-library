# Codeigniter 4 Datatables Library

This repository is for codeigniter 4 datatables


## Instructions

Store 


## How to use

### Controller

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
			// 'username' => 'admin'
		];
		$varible = 'value';
		$editColumn = [
			'status' => ['helper_function' => 'numbs', 'args' => [['status'],[$varible, false]]],
			'role_id' => ['helper_function' => 'role', 'args' => [['role_id']]],
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
