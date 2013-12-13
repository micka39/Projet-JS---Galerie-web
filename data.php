<?php
require ("class/config.php");


$mysqli= new mysqli("localhost", "root", "nzen43", "projetJs");
$query = 'SELECT title FROM image';

 
if(isset($_POST['query'])){
$query .= ' WHERE title LIKE "%'.$_POST['query'].'%"';
}
 
$return = array();

 if($result = $mysqli->query($query)){
// fetch object array
while($obj = $result->fetch_object()) {
$return[] = $obj->title;
}
// free result set
$result->close();
}
 
 $mysqli->close();

 
$json = json_encode($return);
print_r($json);

?>