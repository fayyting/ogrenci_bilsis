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
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
		<!--Jquery for Bootstrap-->
		<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
	</head>
	<body>	
		<?php include '../includes/admin/topmenu.php'; ?>	
		
		<section id="breadcrumb">
			<div class="container">
				<ol class="breadcrumb">
					<li class="active">Dashborad</li>  <span class="seperator">/</span> <a class="breadcrumb-link" href="adminusers.php"><li> Admin Users </li></a>
				</ol>
			</div>
		</section>
			
		<!--		Content Below----------------------------------------------- -->
		<div class="container">	
			<div class="container text-center mt-3 mb-3"><h5>Edit Admin User</h5></div>	
			
			<a href = "adminusers.php" class="btn btn-primary"><i class="fas fa-chevron-left"></i> Back</a>
			
			<?php 
				$dbc = connect_db();
				if (isset($_GET['id'])){$user_id = $_GET['id'] ; }
				$select_users = "SELECT user_id, first_name, last_name , ni_no  from admin_users where user_id = $user_id ";
				$rows = mysqli_query($dbc, $select_users);
				while($r = mysqli_fetch_assoc($rows))
				{
				$firstname = $r['first_name'] ;
				$lastname = $r['last_name'] ;
				$nino = $r['ni_no'] ;
				
				}
						
				if ($_SERVER['REQUEST_METHOD']=='POST')
				{
					echo "<div class=''>";
					$firstname = mysqli_real_escape_string($dbc, $_POST['first_name']);
					$lastname = mysqli_real_escape_string($dbc, $_POST['last_name']);
					$nino = mysqli_real_escape_string($dbc, $_POST['ni_no']);
				

					
					//	echo $firstname , $lastname , $nino , $department , $password , $password2 ;

						$updateUser = "update admin_users set 
						first_name = '$firstname' , 
						last_name = '$lastname', 
						ni_no = '$nino'
						
						where user_id = $user_id
						";
					
					
						$runAddUser = mysqli_query($dbc , $updateUser);
						$rowsUpdated = mysqli_affected_rows($dbc) ;
						if ($rowsUpdated > 0 ){
							echo "<span class='text-success mb-3'>User updated</span>";
						}	else { echo "<span class='text-danger mb-3'>Not updated</span>"; }
					
					echo '</div>';
				}	
			?>
			
			<form name="editUser" id="editUser" action="#" method="post">
				<div class="form-row mt-3">
					<div class="form-group col-md-6">
						<label for="inputEmail4">First Name</label>
						<input type="text" class="form-control" id="first_name" name="first_name" value="<?php if(isset($firstname)) {echo $firstname ;} ?>" placeholder="First Name" required>
					</div>   
				
					<div class="form-group col-md-6">
						<label for="inputEmail4">Last Name</label>
						<input type="text" class="form-control" id="last_name" name="last_name" value="<?php if(isset($lastname)) {echo $lastname ;} ?>" placeholder="Last Name" required>
					</div>
				</div>  
				
				<div class="form-row">
					<div class="form-group col-md-6">
						<label for="inputEmail4">NI No</label>
						<input type="text" class="form-control" id="ni_no" name="ni_no" value="<?php if(isset($nino)) {echo $nino ;} ?>" placeholder="NI No" required>
					</div>   
				
				</div>  


				<button type="submit" class="btn btn-primary">Update</button>
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

