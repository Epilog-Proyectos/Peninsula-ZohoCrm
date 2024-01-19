<?php
$server=getcwd();
include $server ."/environment.php";
include $server."/vendor/autoload.php";
include $server."/includes/mysqli.php";
include $server."/includes/mongo.php";

/*

//contar no nulos
echo $mongoClient->$database->Products->count(["DesarrolloX" => ['$ne' => null]]);
//contar nulos
echo $mongoClient->$database->Products->count([DesarrolloX" => null]);
//eliminar coleccion
$mongoClient->$database->UnidadesConDesarrollo->drop();
//INSERTAR EN COLECCION NUEVA
$productos = $mongoClient->$database->Products->find();
foreach ($productos as $producto) {
  $producto->Desarrollo = getDesarrollo($producto->Desarrollo->id);
  unset($producto->_id);
  $mongoClient->$database->UnidadesConDesarrollo->insertOne($producto);
}
*/
procesarPotentials();

function procesarPotentials(){
  global $mongoClient;
  global $database;


  /*$condition = [
    '$and' => [
      [
        "id" => 5,
        "a" => array('$gt' => 2, '$lt' => 6),
        "b" => array('$gt' => 10, '$lt' => 20)
      ]
    ]
  ];*/
  
  $condition = [
    '$and' => [
      [
        "InmuebleX" => null,
        "LeadX" => null,
        "Contact_NameX" => null
      ]
    ]
  ];


  $items = $mongoClient->$database->Potentials->find();
  //$items = $mongoClient->$database->Potentials->find(['id' => "5153690000031344080"]);
  foreach ($items as $item) {
    $producto = getProducto($item->Inmueble->id);
    $lead = getLead($item->Lead->id);
    $contact = getContact($item->Contact_Name->id);
    $mongoClient->$database->Potentials->updateOne(
      [ 'id' => $item->id ],
      [ '$set' => ['InmuebleX' => $producto, 'LeadX' => $lead, 'Contact_NameX' => $contact ]],
      ["upsert" => true, "multiple" => true]
    );
    echo $item->Potentials->id."<br>";
  }
  /*$items=$mongoClient->$database->Potentials->find(['id' => "5153690000031344080"]);
  foreach ($items as $item) {
    echo json_encode($item);
  }*/


}

function getProducto($id){
  global $mongoClient;
  global $database;
  $items = $mongoClient->$database->Products->find(['id' => $id]);
  foreach ($items as $item) {
    unset($item->_id);
    return $item;
  }
}



function procesarProductos(){
  global $mongoClient;
  global $database;
  $items = $mongoClient->$database->Products->find(["DesarrolloX" => null]);
  foreach ($items as $item) {
    $desarrollo = getDesarrollo($item->Desarrollo->id);
    $mongoClient->$database->Products->updateOne(
      [ 'id' => $item->id ],
      [ '$set' => [ 'DesarrolloX' => $desarrollo ]]
    );
  }
}

function getDesarrollo($id){
  global $mongoClient;
  global $database;
  $items = $mongoClient->$database->Desarrollos->find(['id' => $id]);
  foreach ($items as $item) {
    unset($item->_id);
    return $item;
  }
}

function getLead($id){
  global $mongoClient;
  global $database;
  $items = $mongoClient->$database->Leads->find(['id' => $id]);
  foreach ($items as $item) {
    unset($item->_id);
    return $item;
  }
}

function getContact($id){
  global $mongoClient;
  global $database;
  $items = $mongoClient->$database->Contacts->find(['id' => $id]);
  foreach ($items as $item) {
    unset($item->_id);
    return $item;
  }
}



/*function getUnidad($id){
  global $mongoClient;
  global $database;
  $productos = $mongoClient->$database->Products->find(['id' => $id]);
  foreach ($productos as $producto) {
    unset($producto->_id);
    $producto->Desarrollo = getDesarrollo($producto->Desarrollo->id);
    return $producto;
  }
}*/



/*$collection = $mongoClient->getCollection('Products');
*/


?>
