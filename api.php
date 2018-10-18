<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require('./src/XLSXReader.php');
require('./src/datasource.php');
require('./src/db_config.php');

class api 
{
    private $post;
    private $db;

    public function __construct( $post, $dbParams )
    {
        $this->post = $post;
        $this->db = new datasource($dbParams['database'],
            $dbParams['user'],
            $dbParams['password'],
            $dbParams['host']);

    }

    public function main( )
    {
        $response = 'ok'; // default value for non legal requests.

        switch ($this->post['method']) {
        case  'getRows' :
            $response = $this->getRows($this->post['limit'], $this->post['offset']);
            break;

        case  'getField' :
            $response = $this->getField($this->post['id'], $this->post['field']);
            break;

        case  'getAmountOfRecords' :
            $response = $this->getAmountOfRecords();
            break;

        case  'delete' :
            $response = $this->delete($this->post['id']);
            break;

        case  'getById' :
            $response = $this->getById($this->post['id']);
            break;

        case  'update' :
            $response = $this->update($this->post['form']);
            break;

        case  'search' :
            $response = $this->search($this->post['form'], $this->post['type']);
            break;

        default:
            break;
        }


        echo $response;
    }

    private function getRows($limit, $offset) 
    {
        $rows = '{}';
        $rows = $this->db->getRows($limit, $offset);

        return json_encode($rows);
    }

    private function getField($id, $field)
    {
        $result = $this->db->findById($id);

        return json_encode($result[0][$field]);
    }

    private function getAmountOfRecords()
    {
           $count = $this->db->getAmountOfRecords();
           return json_encode($count[0]['total']);
    }

    private function delete( $id )
    {
        $result = $this->db->delete($id);
        return json_encode($result);
    }

    private function getById( $id )
    {
        $result = $this->db->findById($id);
        if ($result) {
            return json_encode($result[0]);
        }
        return false;
    }

    private function update( $data )
    {
        parse_str($data, $fields);
        $result = $this->db->update( $fields );
        if ($result) {
            return json_encode($fields);
        }
        return false;
        
    }

    private function search( $data, $type )
    {
        parse_str($data, $fields);
        $result = $this->db->search( $fields, $type );
        if ($result) {
            return json_encode($result);
        }
        return false;
        
    }

}

$api = new api($_POST, $dbParams);
$api->main();




