<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Add Magazine</title>

	<link rel="stylesheet" href="./css/bootstrap.min.css">
   
  </head>
  <body>
  <div class="container">
   
    <?php
		include 'header.html';
        include 'db_connection.php';
        $conn = OpenCon();

		if(isset($_POST["submit"])){
			$magazine_name = htmlentities($_POST["magazine_name"]);
			
			
			$mag_exist_query = "SELECT * FROM MAGAZINE where name = '$magazine_name'";
		
			$doesExist = mysqli_query($conn, $mag_exist_query);
			if (!$doesExist) print("ERROR: ".mysqli_error($conn));
			
			if (mysqli_num_rows($doesExist) != 0){
				echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
									Magazine name already exists!
									<button type="button" class="close" data-dismiss="alert" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>';
			} else {
				insertMagazine($magazine_name,$conn);
			}
		}
		
		function insertMagazine($magazine_name,$conn) {
				$insert_mag_query = "INSERT into MAGAZINE (name) VALUES ('$magazine_name')";
				
				$result = mysqli_query($conn,$insert_mag_query);
				if (!$result) print("Error: ".mysqli_error($conn));
				else{ 
						echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
									Magazine Added!
									<button type="button" class="close" data-dismiss="alert" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>';
				}
		}

        CloseCon($conn);

    ?>
	<h1>Add a new Magazine</h1>
    <form id="" action="" method="POST">
		<div class="form-group col-md-6">
			<label for="magazine_name">Magazine</label>
			<input type="text" class="form-control" name="magazine_name"  placeholder="Magazine name...">
		</div>
		<div class="row">
		<button type="submit" name="submit" class="btn btn-primary">Submit</button>
		</div>
	</form>
</div>   

<script src="./js/jquery.min.js"></script>
<script src="./js/bootstrap.min.js"></script>
  </body>
</html>