<?php
 
if(isset($_GET['format'])) {
 
//Set our variables
$format = strtolower($_GET['format']);
$num = intval($_GET['num']);

/* Pass any other variables relating to the API query via the url and create variables such as the ones above.
   For example: $type = $_GET['articleType']; via the url http://www.example.com/get.php?format=json&articleType=news
   Alternatively, you can create specific APIs for specific purposes - get.php gets 'all' data from a table, 
   while .getNews.php may just get articles of the type 'news'.
*/
 
//Connect to the Database
$db = new mysqli("localhost", "my_user", "my_password", "myDB");
if ($db->connect_errno) {
  printf("Connect failed: %s\n", $db->connect_error);
}

 
//Run our query, I use '*' here, but ideally you would tell the DB exactly what data to fetch.
if ($result = $db->query("SELECT * FROM myTable LIMIT ".$num)) {
 
  //Preapre our output
  if($format == 'json') {
   
  $myResults = array();
  while($row = $result->fetch_assoc()) {
    $myResults[] = array('post'=>$row);
  }
   
  $output = json_encode(array('posts' => $myResults)); //if the output is JSON, we'll use the 'json_encode' function to parse the JSON, ready for output
  
  } elseif($format == 'xml') {
   
  header('Content-type: text/xml');
  $output = "<?xml version=\"1.0\"?>\n";
  $output .= "<myResults>\n";
   
  for($i = 0; $i < $result->num_rows; $i++){
    $row = $result->fetch_assoc();
    $output .= "<myResult> \n";
    $output .= "<resultID>" . $row['resultID'] . "</resultID> \n";
    $output .= "<resultCol1>" . $row['resultCol1'] . "</resultCol1> \n";
    $output .= "<resultCol2>" . $row['resultCol2'] . "</resultCol2> \n";
    $output .= "<resultCol3>" . $row['resultCol3'] . "</resultCol3> \n";
    $output .= "<resultCol4>" . $row['resultCol4'] . "</resultCol4> \n";
    $output .= "</myResult> \n";
  }
   
  $output .= "</myResults>"; //if the output is XML, we'll build the XML as we would any other HMTL or text element
  
  } else {
  die('Improper response format.');
  }
  
  $db->close();
   
  //Output the output and import into your application.
  echo $output;
   
  }
 
?>
