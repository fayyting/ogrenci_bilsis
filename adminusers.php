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
		<!-- Ajax -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
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
		<div class="table table-responsive">	
			<table class="table table-hover">
				<thead>
					<tr>
						<th scope="col">User Name</th>
						<th class="text-center" scope="col">Edit</th>
						<th class="text-center" scope="col">Delete</th>
					</tr>
				</thead>
				<tbody>
					<?php
						$dbc = connect_db();
						$select_users = ' SELECT user_id, first_name, last_name from admin_users';

						$rows = mysqli_query($dbc, $select_users);
						while($r = mysqli_fetch_assoc($rows)){
							$userid = $r['user_id'];
							$fullname = $r['first_name'] . ' ' . $r['last_name'] ;
							echo "<tr id='$userid'>"; 
								echo '<td><i class="<i class="fas fa-user pdf-icon-1"></i>'.$fullname.'</td>';
								echo '<td class="text-center"><a class="Edit" href="editadmin.php?id='.$r['user_id'].'" rel="facebox"><i class="far fa-edit icon-black "></i></a></td>';
								echo "<td class='text-center'><a class='Edit' onClick='deleteUser($userid);'><i class='far fa-trash-alt icon-red'></i></a></td>";
							echo '</tr>';
						}
						mysqli_free_result($rows);
					?>
				</tbody>
			</table>	
		</div>	<!--End of table div responsive -->		
	</div>		
				
	<!--		Content Above----------------------------------------------- -->
<?php include '../includes/footer.php'; ?>	

	<script>
	$(document).ready(function(e){

	});
		
function deleteUser(userid){

	var rowstodelete = '#'+userid ;

	
	var deleteuser = confirm("Are you sure you want to delete the user?");
 	if (deleteuser == true)
	{


	 $.ajax({
            url: 'ajax/delete_adminuser.php', 
            type: "POST",
            data: {id:userid},
            success: function(data){

				$("#results").html(data);
				$(rowstodelete).animate({ backgroundColor: "#fbc7c7" }, "slow");
				$(rowstodelete).animate({ opacity: "hide" }, "slow");
				//  setTimeout(function() {window.location = "userspassreset.php?search=" + search ;}, 1500);
			} // end of success
        }); // end of ajax   
		
	} // end of if  delete user is true
			
} // end of delete user function
	</script>
		
	</body>
</html>

