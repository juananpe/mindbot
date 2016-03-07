<?php

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
$nodo->add(new Node("geo", $index++));

$root->add($nodo);

print_r(json_encode($root));

