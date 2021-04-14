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
  echo json_encode(array("data"=>$data,
                        "data1"=>$data1));
}

if($received_data->action == 'fetchcategory')
{
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

  $check="SELECT * 
          FROM documents 
          WHERE category_id = '".$received_data->Category."' 
          AND name='".$received_data->Document."'";

  $rs = mysqli_query($conn,$check);
  
  if (mysqli_num_rows($rs) != 0)
  {
    $output = array(
      'message' => "Document Already in Exists<br/>"
    );
  }

  else
  {
    $query = $conn->query("INSERT INTO documents (category_id,name) 
                           VALUES ($received_data->Category,'$received_data->Document')");


    $output = array(
      'message' => 'Data Inserted'
    );
  } 

 echo json_encode($output);
}

if($received_data->action == 'fetchSingle')
{
  $query = $conn->query("SELECT * FROM documents WHERE id = '".$received_data->id."'");
 
  while ($row = $query->fetch_assoc()) {
    $data['id'] = $row['id'];
    $data['category_id'] = $row['category_id'];
    $data['document'] = $row['name'];
  }


  $query1 = $conn->query("SELECT * FROM categories");
   
  while($row1 = $query1->fetch_assoc())
  {
    $data1[] = $row1;
  }
 
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

  $check="SELECT * 
          FROM documents 
          WHERE category_id = '".$received_data->Category."' 
          AND name='".$received_data->Document."' 
          AND id != '".$received_data->hiddenId."'";

  $rs = mysqli_query($conn,$check);
  
  if (mysqli_num_rows($rs) != 0)
  {
    $output = array(
      'message' => "Document Already in Exists<br/>"
    );
  }

  else
  {
    $query = $conn->query("UPDATE documents 
                           SET category_id=$received_data->Category, 
                           name = '$received_data->Document',
                           updated_at=CURRENT_TIMESTAMP() 
                           WHERE id = $received_data->hiddenId");

    $output = array(
      'message' => 'Data Updated'
    );
  }

 echo json_encode($output);
}

if($received_data->action == 'delete')
{
 $query = $conn->query("DELETE FROM documents WHERE id = '".$received_data->id."'");

 

 $output = array(
  'message' => 'Data Deleted'
 );

 echo json_encode($output);
}

?>