<?php
	//error_reporting( E_ALL );
	//session_start();
	//print_r($_SESSION);

	include_once("../includes/header.php");
	//check_session("admin");
?>

<!doctype html>
<html>
	<head>
		<?php 
			include_once('../includes/head.php');
			include_once('../includes/head_admin.php');
		?>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
		<!--Jquery for Bootstrap-->
		<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
	</head>
	<body>	
		<?php 
			include '../includes/admin/topmenu.php'; 
			$dbc = connect_db();
		?>	

		
		<section id="breadcrumb">
			<div class="container">
				<ol class="breadcrumb">
					<li class="active">Dashborad</li>
				</ol>
			</div>
		</section>
			
		<!--		Content Below----------------------------------------------- -->
		<div class="container">	
			<div class="container text-center mt-3 mb-3"><h5>Add Admin User</h5></div>	
			
			<?php 
				
						
				if ($_SERVER['REQUEST_METHOD']=='POST')
				{
					echo "<div class=''>";
					$firstname = mysqli_real_escape_string($dbc, $_POST['first_name']);
					$lastname = mysqli_real_escape_string($dbc, $_POST['last_name']);
					$nino = mysqli_real_escape_string($dbc, $_POST['ni_no']);
					
					$password = mysqli_real_escape_string($dbc, $_POST['password']);
					$password2 = mysqli_real_escape_string($dbc, $_POST['password2']);
					
					//	echo $firstname , $lastname , $nino , $department , $password , $password2 ;
					if ($password !== $password2 ){
						echo "<span class='text-danger  mb-3'>Password do not match</span>";
					} else {	
						$addUser = "insert into admin_users (first_name , last_name , ni_no , password) 
						VALUES ('$firstname' , '$lastname' , '$nino' , SHA1('$password') )";
				
						$runAddUser = mysqli_query($dbc , $addUser);
						$rowsInserted = mysqli_affected_rows($dbc) ;
						if ($rowsInserted > 0 ){
							echo "<span class='text-success mb-3'>User added</span>";
						}	
					}
					echo '</div>';
				}	
			?>
			
			<form name="addUser" id="addUser" action="#" method="post">
				<div class="form-row mt-3">
					<div class="form-group col-md-4">
						<label for="inputEmail4">First Name</label>
						<input type="text" class="form-control" id="first_name" name="first_name" placeholder="First Name" required>
					</div>   
				
					<div class="form-group col-md-4">
						<label for="inputEmail4">Last Name</label>
						<input type="text" class="form-control" id="last_name" name="last_name" placeholder="Last Name" required>
					</div>
					
					<div class="form-group col-md-4">
						<label for="inputEmail4">Login Name</label>
						<input type="text" class="form-control" id="ni_no" name="ni_no" placeholder="Login name" required>
					</div>  
					
				</div>  

				
				<div class="form-row">
					<div class="form-group col-md-6">
						<label for="password">Password</label>
						<input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
					</div>   
					
					<div class="form-group col-md-6">
						<label for="password2">Confirm Password</label>
						<input type="password" class="form-control" id="password2" name="password2" placeholder="Confirm Password" required>
					</div>
				</div>

				<button type="submit" class="btn btn-primary">Register</button>
			</form>	
		</div>		
		<!--		Content Above----------------------------------------------- -->	
			
<?php include '../includes/footer.php'; ?>	

		<script>
		$(document).ready(function(e){
			
		});
		</script>
	
	</body>
</html>

