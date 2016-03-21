<?php

require_once 'utils/Config.php';
require_once 'utils/Datasource.php';

class DB {

	private $conn;

	/**
	 * Constructor function
	 *
	 * @throws Exception
	 * 		Throws an error if the one trying to access this class is not successfully logged in on the system
	 * 		or there was any problem establishing a connection with the database.
	 */
	public function __construct() {
		try {
			$settings = new Config ( );
			$this->conn = new Datasource ( $settings->host, $settings->db_name, $settings->db_username, $settings->db_password );


		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	/**
	 *
	 *        Returns: 
	 *
	 */	
        public function getData($start, $end){
		$sql = "
			SELECT nan, sum(case when result in ('right') then 1 else 0 end) ok,
				sum(case when result in ('wrong') then 1 else 0 end) ko,
				concat('/jsgrid/demos/img/',nan,'.gif') picture,
				u.first_name first_name, u.last_name last_name 
			FROM answerhistory a, users u
			WHERE data > '%s' and data < '%s'
			      and u.id = a.user and u.nan is not null
			group by user
			";
                $results = $this->conn->_multipleSelect($sql, $start, $end);
                // error_log(print_r($row, 1), 3, "/tmp/error.log");
		return $results;
	}

	public function getQuizTitle($quiz){
		$sql = " select description from quiz where id = %d";
		$result = $this->conn->_singleSelect($sql, $quiz);
		return $result->description;
	}

	public function getNumQuizes(){

		$sql = "select count(*) as numQuizes from quiz";
		$result = $this->conn->_singleSelect($sql);
		return $result->numQuizes;
	}
}



$colors = ["red" => "#ff0000", "orange" => "#ff9900", "green" => "#99cc00"];

$db = new DB();

// $numQuizes = $db->getNumQuizes();


	$results = $db->getData('2015-10-01', 'now()'); 

	$data = [];
	foreach($results as $result){
		$obj = new stdClass;
		$obj->user = $result;
		$data[] = $obj;
	}
	// $title = $db->getQuizTitle($quizInd);

header('Content-Type: application/json');
print_r('{ "results" : ' . json_encode($data) .  ', "nationality": "ES" }');

