<?php

//action.php

$servername = "localhost";
$username = "root";
$password = "";
$database = "test";

// Create connection
$conn = new mysqli($servername, $username, $password,$database);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$received_data = json_decode(file_get_contents("php://input"));

$data = array();
if($received_data->action == 'getList')
{
 $query = $conn->query("SELECT  A.id,B.category,A.name
                          FROM documents A
                          LEFT JOIN categories B ON B.id=A.category_id
                          WHERE A.category_id = '".$received_data->id."'");
   
  while($row = $query->fetch_assoc())
  {
    $data[] = $row;
  }
  echo json_encode($data);
 
}
if($received_data->action == 'fetchall')
{
  $query = $conn->query("SELECT  A.id,B.category,A.name
                          FROM documents A
                          LEFT JOIN categories B ON B.id=A.category_id");
   
  while($row = $query->fetch_assoc())
  {
    $data[] = $row;
  }

  $query1 = $conn->query("SELECT * FROM categories");
   
  while($row1 = $query1->fetch_assoc())
  {
    $data1[] = $row1;
  }
  // echo json_encode($data);
  echo json_encode(array("data"=>$data,
                        "data1"=>$data1));
}

if($received_data->action == 'fetchcategory')
{
  // $query = $conn->query("SELECT  A.id as docId,B.id as CategoryId,B.category
  //                        FROM documents A
  //                        LEFT JOIN categories B ON B.id=A.category_id");
  $query = $conn->query("SELECT * FROM categories");
   
  while($row = $query->fetch_assoc())
  {
    $data[] = $row;
  }
  echo json_encode($data);
}

if($received_data->action == 'insert')

{
  
 $data = array(
  'category' => $received_data->Category,
  'name' => $received_data->Document
  );

  $check="SELECT * FROM documents WHERE category_id = '".$received_data->Category."' AND name='".$received_data->Document."'";
//exit;
  $rs = mysqli_query($conn,$check);
  $res = mysqli_fetch_array($rs, MYSQLI_NUM);
  //print_r($res);exit;
  if($res->num_rows > 0) {
      $output = array(
      'message' => "Document Already in Exists<br/>"
    );
  }

  else
  {
    $query = $conn->query("INSERT INTO documents (category_id,name) VALUES ($received_data->Category,'$received_data->Document')");


    $output = array(
      'message' => 'Data Inserted'
    );
  } 

 echo json_encode($output);
}

if($received_data->action == 'fetchSingle')
{
 $query = $conn->query("SELECT * FROM documents WHERE id = '".$received_data->id."'");
 
 //$result = $mysqli->query($query);

 //$result1 = mysqli_fetch_row($result);
//print_r($result1);
 //foreach($result as $row)
 //{

while ($row = $query->fetch_assoc()) {
  //var_dump($row);exit;
  $data['id'] = $row['id'];
  $data['category_id'] = $row['category_id'];
  $data['document'] = $row['name'];
}
  
 //}


 $query1 = $conn->query("SELECT * FROM categories");
   
  while($row1 = $query1->fetch_assoc())
  {
    $data1[] = $row1;
  }
  //echo json_encode($data);
 echo json_encode(array("data"=>$data,
                        "data1"=>$data1));
}
if($received_data->action == 'update')
{
 $data = array(
  'category_id' => $received_data->Category,
  'document' => $received_data->Document,
  'id'   => $received_data->hiddenId
 );

 $query = $conn->query("UPDATE documents SET category_id=$received_data->Category, name = '$received_data->Document' WHERE id = $received_data->hiddenId");

 

 $output = array(
  'message' => 'Data Updated'
 );

 echo json_encode($output);
}

if($received_data->action == 'delete')
{
 $query = $conn->query("DELETE FROM documents WHERE id = '".$received_data->id."'");

 // $statement = $connect->prepare($query);

 // $statement->execute();

 $output = array(
  'message' => 'Data Deleted'
 );

 echo json_encode($output);
}

?>