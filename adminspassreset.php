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
				
			<form method="post">
				<div class="form-row align-items-center">
					<div class="col-sm-3 my-1">
						<label class="sr-only" for="inlineFormInputName">Search</label>
						<input type="text" class="form-control" id="search" name="search" placeholder="Name / Surname / NI">
					</div>

					<div class="col-auto my-1">
						<button type="submit" class="btn btn-primary">Search</button>
					</div>
				</div>
			</form>
			
			<div class="table table-responsive">
				<table class="table table-hover">
					<thead>
						<tr>
							<!-- <th scope="col">Status</th> -->
							<th scope="col">Full Name</th>
							<th class="text-center" scope="col">Reset Password</th>
							<th class="text-center" scope="col">Delete</th>
						</tr>
					</thead>
					<tbody>
						<?php
							$dbc = connect_db();
						
							$search = 'none';
							if ($_SERVER['REQUEST_METHOD'] == 'POST') {$search = $_POST['search'];} 
							
							if ( ($_SERVER['REQUEST_METHOD'] == 'GET') and isset($_GET['search']) ) {$search = $_GET['search'];}
						
							$select_users = "SELECT admin_users.user_id, admin_users.first_name, admin_users.last_name FROM admin_users WHERE first_name LIKE '%$search%' or last_name like '%$search%' or ni_no like '%$search%' ";
							
							$rows = mysqli_query($dbc, $select_users);
							while($r = mysqli_fetch_assoc($rows)){
								
								$fullname = $r['first_name'] . ' ' . $r['last_name'] ;
								$userid = $r['user_id'];
								echo '<tr>'; 
									echo '<td><i class="<i class="fas fa-user pdf-icon-1"></i>'.$fullname.'</td>';
									echo "<td class='text-center'><a class='Edit' href='adminpassreset.php?id=$userid&search=$search'><i class='fas fa-key icon-black'></i></a></td>";
									echo "<td class='text-center'><a class='Edit' id='$search' onClick='deleteUser($userid , this.id);'><i class='far fa-trash-alt icon-red'></i></a></td>";

								echo '</tr>';
							}
							mysqli_free_result($rows);
						?>
					</tbody>
				</table>
			</div>
		
			<div id="results"></div>
			<!--End of table div responsive -->		
		</div>	
			
		<!--		Content Above----------------------------------------------- -->
		<?php include '../includes/footer.php'; ?>	

		<script>
			$(document).ready(function(e){

			});
				
			function deleteUser(userid,search){	
				var deleteuser = confirm("Are you sure you want to delete the user?");
				if (deleteuser == true)
				{

					$.ajax({
						url: 'ajax/delete_adminuser.php', 
						type: "POST",
						data: {id:userid , search:search },
						success: function(data){

							$("#results").html(data);
								setTimeout(function() {window.location = "adminspassreset.php?search=" + search ;}, 1500);
						} // end of success
					}); // end of ajax   
					
				} // end of if  delete user is true
						
			} // end of delete user function
			
		</script>
		
	</body>
</html>

