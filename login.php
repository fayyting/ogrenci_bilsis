<?php 
	session_start();
	session_destroy();
	include_once('../includes/header.php');
?>

<!doctype html>
<html>
	<head>
		<title>housingbritain Ltd Login</title>
		<?php 
			include_once('../includes/head.php');
			include_once('../includes/head_admin.php');
		?>
		<!--Bootstrap-->
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
		<!--Jquery for Bootstrap-->
		<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>

	</head>

	<body class="loginbackground">
		<div class="container"><img class="main-logo" src="../logo/logo3.png"></div>
		<div class="loginBox">
			<div class="container text-center"><i class="far fa-user user-login-icon"></i></div>
			<div class="container text-center text-white"><h3>Manager Login</h3></div>
			<div class="container text-white pr-5 pl-5 my-font-size mt-4">
				<form action="login.php" method="post">
					<div class="form-group">
						<label for="username">User Name</label>
						<input type="text" class="form-control" id="username" name="username" aria-describedby="emailHelp" placeholder="User name" required>
					
					</div>
					<div class="form-group">
						<label for="password">Password</label>
						<input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
					</div>
					<button type="submit" name="loginBtn" class="btn btn-block login-btn-green">Login</button>
				</form>	
			</div>
				
			<div class="container text-white pr-5 pl-5 my-font-size-sm mt-4"><a class="text-white" href="reset.php">Forgot Password?</a></div>
			<div class="container text-white text-center">	
				<?php
				if(($_SERVER['REQUEST_METHOD'] == 'POST') && (!empty($_POST)) && (isset($_POST['username']) && (!empty($_POST['username'])) ))
				{
					//require_once("../../connect_db.php");
					$dbc = connect_db();
					
					$username = mysqli_real_escape_string($dbc, $_POST['username']);
					$password = mysqli_real_escape_string($dbc, $_POST['password']);

					//echo $username , $password ;
					//$password = sha1($password)	;
					//echo "<br>";
					//echo $password;
					
					$checkuser = "Select user_id, first_name, last_name FROM admin_users WHERE ni_no = '$username' AND password = SHA1('$password') LIMIT 1";
					$runcheckuser = mysqli_query($dbc, $checkuser);
						
					if(mysqli_num_rows($runcheckuser) > 0)
					{
						$row=mysqli_fetch_array ($runcheckuser , MYSQLI_ASSOC);
						
						$_SESSION['sessionid'] = session_id();
						$_SESSION['user_id'] = $row['user_id'];
						$_SESSION['first_name'] = $row['first_name'];
						$_SESSION['last_name'] = $row['last_name'];
						$_SESSION['user_type'] = "admin"; 
						
					//	echo "Welcome " .$_SESSION['first_name'] .' '. $_SESSION['last_name'] ;
						if (isset($_SESSION['sessionid'])){echo "<script> window.location ='index.php'; </script>"; } else { echo "Not Logged in";}
					}
					else
					{
						echo "<div class='text-danger error-message p-2'>Username or password wrong !!!</div>";
					}			

				}
					
				?>
			</div> <!--php div end-->	
		</div>
	</body>
</html>