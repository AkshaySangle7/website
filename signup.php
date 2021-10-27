<?php 
session_start();

	include("connection.php");
	include("functions.php");


	if($_SERVER['REQUEST_METHOD'] == "POST")
	{
		//something was posted
		$user_name = $_POST['user_name'];
		$password = $_POST['password'];
		$email = $_POST['email'];
        $address = $_POST['address'];
		$pincode = $_POST['pincode'];
		$mobile = $_POST['mobile'];

		if(!empty($user_name) && !empty($password) && !is_numeric($user_name) && !empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($address) && !empty($pincode) && !empty($mobile)) 
		{

			//save to database
			$user_id = random_num(10);
			$query = "insert into users (user_id,user_name,password,email,address,pincode,mobile) values ('$user_id','$user_name','$password','$email','$address','$pincode','$mobile')";

			mysqli_query($con, $query);

			header("Location: login.php");
			die;
		}else
		{
			echo "Please enter some valid information!";
		}
	}
?>


<!DOCTYPE html>
<html>
<head>
	<title>Signup</title>
	<link rel="stylesheet" href="style.css">
</head>
<body style="background-image: url('https://techcrunch.com/wp-content/uploads/2015/03/groceries-e1554037962210.jpg');">

	<div id="box">
		
		<form method="post">
			<div style="font-size: 30px;margin: 10px;color: white;font-weight: bold;font-family: 'Ubuntu';">Signup</div>

			<input id="text" type="text" name="user_name" placeholder="username"><br><br>
			<input id="text" type="password" name="password" placeholder="password"><br><br>
			<input id="text" type="text" required name="email" placeholder="email id"><br><br>
			<input id="text" type="text" name="address" placeholder="Full address"><br><br>
			<input id="text" type="text" name="pincode" placeholder="pincode"><br><br>
			<input id="text" type="text" name="mobile" placeholder="mobile number"><br><br>
            

			<input id="button" type="submit" value="Signup"><br><br>
            <div style="font-family: 'Ubuntu';">
			<p>Already registered?<a href="login.php">Click to Login</a></p><br><br>
            </div>
		</form>
	</div>
</body>
</html>