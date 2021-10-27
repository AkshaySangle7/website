<?php 

session_start();

	include("connection.php");
	include("functions.php");


	if($_SERVER['REQUEST_METHOD'] == "POST")
	{
		//something was posted
		$user_name = $_POST['user_name'];
		$password = $_POST['password'];

		if(!empty($user_name) && !empty($password) && !is_numeric($user_name))
		{

			//read from database
			$query = "select * from users where user_name = '$user_name' limit 1";
			$result = mysqli_query($con, $query);

			if($result)
			{
				if($result && mysqli_num_rows($result) > 0)
				{

					$user_data = mysqli_fetch_assoc($result);
					
					if($user_data['password'] === $password)
					{

						$_SESSION['user_id'] = $user_data['user_id'];
						header("Location: index.php");
						die;
					}
				}
			}
			
			echo "wrong username or password!";
		}else
		{
			echo "wrong username or password!";
		}
	}

?>


<!DOCTYPE html>
<html>
<head>
	<title>Login</title>
	<link rel="stylesheet" href="style.css">
</head>
<!--<body style="background-color: darkgreen;">-->
<body style="background-image: url('https://techcrunch.com/wp-content/uploads/2015/03/groceries-e1554037962210.jpg');";>

	<div id="box">
		
		<form method="post">
			<div style="font-size: 30px;margin: 10px;color: white;font-weight: bold;font-family: 'Ubuntu';">Login</div>

			<input id="text" type="text" name="user_name" placeholder="username"><br><br>
			<input id="text" type="password" name="password" placeholder="password"><br><br>

			<input id="button" type="submit" value="Login"><br><br>
           <div style="font-family: 'Ubuntu';">
			<p>Not registered?<a href="signup.php">Click to Signup</a><br><br>
			<a href="forgotpassword.php">Forgot password?</a></p>
			</div>
		</form>
	</div>
</body>
</html>