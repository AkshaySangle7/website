<?php
/* Attempt MySQL server connection. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
$link = mysqli_connect("localhost", "root", "", "grocery_db");
 
// Check connection
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
 
// Escape user inputs for security
$name = mysqli_real_escape_string($link, $_REQUEST['name']);
$email = mysqli_real_escape_string($link, $_REQUEST['email']);
$Mobile_number = mysqli_real_escape_string($link, $_REQUEST['Mobile_number']);
$subject = mysqli_real_escape_string($link, $_REQUEST['subject']);
$message = mysqli_real_escape_string($link, $_REQUEST['message']);

 
// Attempt insert query execution
$sql = "INSERT INTO contact_us (name, email, Mobile_number, subject, message) VALUES ('$name', '$email', '$Mobile_number','$subject','$message')";
if(!empty($name) && !empty($email) && !empty($Mobile_number) && !empty($subject) && !empty($message)){
if(mysqli_query($link, $sql)){
    echo "<script>alert('Records Added Successfully');</script>";
    echo "<h1 style:'text-align: center;'>Thank You For Contacting Us.</h1> <br>";
    echo "<a href='index.php'>go to main page</a>";
} }else{
    echo "<script>alert('error! please fill all details');</script>";
    echo "<a href='index.php'>go to main page</a>";
    /*echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);*/
}
 
// Close connection
mysqli_close($link);
?>