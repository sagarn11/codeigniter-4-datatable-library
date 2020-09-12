<?php
  /**
  * Ignited Datatables
  *
  * This is a wrapper class/library based on the native Datatables server-side implementation by Allan Jardine
  * found at http://datatables.net/examples/data_sources/server_side.html for CodeIgniter
  *
  * @package    CodeIgniter
  * @subpackage libraries
  * @category   library
  * @version    0.7
  * @author     Sagar Nangare <sagar.nangare11@gmail.com>
  *             Akshay Nangare <akshay.nangare05@gmail.com>
  */
namespace App\Libraries;

use \Config\Services;
use \Config\Database;

class Datatables
{
  public function __construct()
  {
    $this->request = Services::request();
    $this->db = Database::connect();
  }

  public function generate_datatable($builder, $editColumn = NULL)
  {
    $aaData = [];

    $iStart = esc($this->request->getPost('iDisplayStart'));
    $iLength =  esc($this->request->getPost('iDisplayLength'));
    
    $dbResponse   = $builder->get(($iLength != '' && $iLength != '-1') ? $iLength : 100, ($iStart) ? $iStart : 0, false)->getResultArray();
    $iTotal = $builder->countAllResults();
    $aaData = $this->create_datatable_data($dbResponse, $editColumn);

    $sOutput = array(
      'sEcho'                => intval($this->request->getPost('sEcho')),
      'iTotalRecords'        => $iTotal,
      'iTotalDisplayRecords' => $iTotal,
      'aaData'               => $aaData,
      // 'sColumns'             => implode(',', $sColumns)
    );
    return json_encode($sOutput);
  }

  public function create_datatable_data($dbResponse = NULL, $editColumn = NULL)
  {
    $aaData = [];
    if (is_array($dbResponse) && !empty($dbResponse)) {
      for ($i = 0; $i < count($dbResponse); $i++) {
        $temp = [];
        if (is_array($editColumn) && !empty($editColumn)) {
          foreach ($editColumn as $key => $function) {
            $args = $this->generate_datatable_helper_args($dbResponse[$i], $function['args']);
            $dbResponse[$i][$key] = call_user_func_array($function['helper_function'], $args);
          }
        }
        $temp = array_merge($temp, array_values($dbResponse[$i]));
        $aaData[] = $temp;
      }
    }
    return $aaData;
  }

  public function generate_datatable_helper_args($array = [], $keys = [])
  {
    $response = [];
    foreach ($keys as $key) {
      $data = NULL;
      if (isset($key[1]) && $key[1]) {
        $data = $key[0];
      } elseif (isset($array[$key[0]])) {
        $data = $array[$key[0]];
      }
      $response[] = $data;
    }
    return $response;
  }
}
