<?php
	//error_reporting( E_ALL );
	//session_start();
	//print_r($_SESSION);

	include_once("../includes/header.php");
	check_session("admin");
?>

<!doctype html>
<html>
	<head>
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
	<body>	
		<?php 
			include '../includes/admin/topmenu.php';	
			$dbc = connect_db();	
			$search = $_GET['search'];
		?>	
		
		<section id="breadcrumb">
			<div class="container">
				<ol class="breadcrumb">
					<li class="active">Dashborad </li> <span class="seperator">/</span> <a class="breadcrumb-link" href="index.php"><li> Home </li></a>
				</ol>	
			</div>
		</section>
		
		<!--		Content Below----------------------------------------------- -->
		<div class="container">
			<a href = "adminspassreset.php?search=<?php echo $search ?>" class="btn btn-primary"><i class="fas fa-chevron-left"></i> Back</a>
				
			<?php 
				if (isset($_GET['id'])) { $user_id = $_GET['id'] ; }	else {$user_id = 0; echo "<div class='container text-center p-3 text-danger'>No User Found</div>";}	

				$dbc = connect_db();
				$select_users = "SELECT user_id, first_name, last_name from admin_users where user_id = $user_id ";
				$rows = mysqli_query($dbc, $select_users);
				$numberofUsers = mysqli_num_rows($rows);
				if ($numberofUsers == 1)
				{
					$r = mysqli_fetch_assoc($rows);
					$fullname = $r['first_name'] . ' ' . $r['last_name'] ;
					echo "<div class='container text-center p-3'>Reseting password for $fullname</div>";
					echo '		
					<form method = "post">
					<div class="form-group col-4">
						<label for="password">Password</label>
						<input type="text" class="form-control" id="pass1" name="pass1" placeholder="Enter Password" required>
					</div>
					
					<div class="form-group col-4">
						<label for="password2">Password2</label>
						<input type="text" class="form-control" id="pass2" name="pass2" placeholder="Confirm Password" required>
					</div>

					<button type="submit" class="btn btn-primary">Apply</button>
					</form> ';
				} 
				else { echo "<div class='container text-center p-3 text-danger'>No User Found</div>";}

				mysqli_free_result($rows);

				if (isset($_POST['pass1']) and isset($_POST['pass2']))
				{
					
					$pass1 = SHA1($_POST['pass1']); 
					$pass2 = SHA1($_POST['pass2']);
					
					
					if ($pass1 == $pass2){
						
						$upadepassword = "update admin_users set password = '$pass1' where user_id = $user_id" ;
						$runUpdatePassword = mysqli_query($dbc , $upadepassword);
						$numberofEffectedRows = mysqli_affected_rows($dbc);
						
						// echo "Updated Rows = $numberofEffectedRows";

						// check if database table updated 
						if ($numberofEffectedRows == 1)
							// if updated echo updated
							{
							echo "<div class='container text-center p-3 text-success'>Password Updated</div>";
						} 
						// if passwords match but still not updated echo error
						else {echo "<div class='container text-center p-3 text-danger'>Opps. Same as last password !!! Try a different password.</div>";}
						
					} // end of if pass1 = pass2 
					
					else
					// if password do not match echo do not match
					{
						echo "<div class='container text-center p-3 text-danger'>Passwords do not match. Please try again.</div>";
					}
					
					
				} // end of if pass1 and pass2 is set
						
			?>
        </div>	
				
		<!--		Content Above----------------------------------------------- -->
		<?php include '../includes/footer.php'; ?>	

		<script>
			$(document).ready(function(e){

			});
		</script>
	</body>
</html>

