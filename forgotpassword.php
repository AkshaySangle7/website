<?php

include("connection.php");
include("functions.php");

/*if($_SERVER['REQUEST_METHOD'] == "POST")
	{
        $ans = $_POST['ans'];

        if(!empty($ans) && !is_numeric($ans))
        {
            $query = "select password FROM users where user_name = '$ans' limit 1";
            $result = mysqli_query($con, $query);
  
            while ($row = $result->fetch_assoc())
            {
                echo "Your password is:<br>".$row['password'];
            }
            
        }
    }
*/

if($_SERVER['REQUEST_METHOD'] == "POST")
{
    $email = $_POST['email'];

    if(!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL))
    {
        $query = "select password FROM users where email = '$email' limit 1";
        $result = mysqli_query($con, $query);

        while ($row = $result->fetch_assoc())
        {
        $subject = "Nature's Basket Login Info";
       /* $message = "Your password is :".print_r($row, true);*/
       foreach ($row as $k => $v){

        $message = "Hello user!, your password is : $v";

        mail($email, $subject, $message);
        echo "<script>alert('your password has been email to you')</script>";
        /*echo "your password has been email to you";*/
        }
    }
    }
}

/*if($_SERVER['REQUEST_METHOD'] == "POST")
{
    $email = $_POST['email'];

    if(!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL))
    {
        $email_check=mysqli_query("SELECT password FROM users WHERE email='$email'");
        $count=mysqli_num_rows($email_check);
        $subject="Login Info";
        $message="Your password is .$count";
        $from="From: assangle7@gmail.com";

        mail($email, $subject, $message, $from);
        echo "your password has been email to you";
    }
} */  

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>forgotpassword</title>
    <link rel="stylesheet" href="style.css">
</head>
<body style="background-image: url('https://techcrunch.com/wp-content/uploads/2015/03/groceries-e1554037962210.jpg');">
<div id="box">
		
		<form method="post">
			<div style="font-size: 20px;margin: 10px;color: white;font-family: 'Ubuntu';">forgot password</div>
            <h2 style="margin: 10px;color: white;font-family: 'Ubuntu';">Enter Your Email id</h2>
			<input id="text" type="text" name="email" placeholder="Email id"><br><br>
			<input id="button" type="submit" value="submit"><br><br>
            <a href="login.php" style="font-family: 'Ubuntu';">go to login page</a>
</body>
</html>

