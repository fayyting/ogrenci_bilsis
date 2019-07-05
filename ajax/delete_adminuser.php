<?php
	//echo "test";
	//error_reporting( E_ALL );
	//session_start();
	include_once("../../includes/header.php");
	check_session("admin");
	$dbc = connect_db();
	// print_r($_POST);
	if (isset($_POST['id'])) { 
		
		$id = $_POST['id'];
		$search = $_POST['search'];
		
		//echo $id , $search;

		$sql = "DELETE FROM admin_users WHERE user_id = $id";

		$delete_row=mysqli_query($dbc, $sql);
		$numberofEffectedRows = mysqli_affected_rows($dbc);
		
		if ($numberofEffectedRows == 1){
			echo "<div class='container text-center p-3 text-success'>Deleted</div>";
		} else  { 
				echo "<div class='container text-center p-3 text-danger'>Not Deleted</div>"; 
				}


	}
	
?>