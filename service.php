<?php
require 'lib/nusoap.php' ;

include_once("data.php");

$server = new nusoap_server(); // Create a instance for nusoap server

$server->configureWSDL("Get Data","urn:getdata"); // Configure WSDL file

$server->register(
	"getLoaiSanPham",
	array(),
	array("return"=>"xsd:string")
);

$server->register(
	"getNhaSanXuat",
	array(),
	array("return"=>"xsd:string")
);

$server->register(
	"getHTTT",
	array(),
	array("return"=>"xsd:string")
);

/*$server->register(
	"getUsersById", // name of function
	array("id"=>"xsd:integer"),  // inputs
	array("name"=>"xsd:string"),  // inputs
	array("return"=>"xsd:string")   // outputs
);*/


$server->service(file_get_contents("php://input"));

?>