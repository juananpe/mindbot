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
        public function getQuestionsResults($quiz){
		$sql = "
			SELECT sum(case when result in ('right') then 1 else 0 end) respOK,
				sum(case when result in ('wrong') then 1 else 0 end) respKO,
				question, quiz 
			FROM answerhistory
			where quiz = %d
			group by question
			";
                $results = $this->conn->_multipleSelect($sql, $quiz);
                // error_log(print_r($row, 1), 3, "/tmp/error.log");
		return $results;
	}

	public function getQuizTitle($quiz){
		$sql = " select description from quiz where id = %d";
		$result = $this->conn->_singleSelect($sql, $quiz);
		return $result->description;
	}

}


class Node implements JsonSerializable{

 private $title = "";
 private $id = "";
 private $ideas = [];

 public function __construct($title, $id) {
	$this->title = $title;
	$this->id = $id;
	$this->ideas = [];
 }

 public function add($nodo){
	$this->ideas[] = $nodo;
 }

    // function called when encoded with json_encode
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

}

$index = 1;

$root = new Node("DAWE", $index++);
$root->formatVersion = 2;
$root->attr = json_decode('{ "measurements-config": [ "respOK", "respKO" ], "style": {} }');

$nodo = new Node("canvas", $index++);
$nodo->add(new Node("geo", $index++)); // añadir hijo geo al nodo canvas


$db = new DB();
$results = $db->getQuestionsResults(1); // for quiz 1

$title = $db->getQuizTitle(1);

// echo "Quiz: " . $title . "\n";

$nodo = new Node($title, $index++);

foreach($results as $result){
	$questionNode = new Node("question" . $result->question, $index++);
	$questionNode->attr = json_decode('{ "measurements" : { "ejerciciosOK": "' . $result->respOK  . '", "ejerciciosKO": "' . $result->respKO . '"} , "style":{} } ');
	$nodo->add($questionNode);
}

$root->add($nodo);

print_r(json_encode($root) . "\n");



