<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Add customer</title>

	<link rel="stylesheet" href="./css/bootstrap.min.css">
   
  </head>
  <body>
  <div class="container">
   
    <?php
		include 'header.html';
        include 'db_connection.php';
        $conn = OpenCon();
		
		
		if(isset($_POST['submit']) || isset($_POST['confirmYes']) || isset($_POST['confirmNo'])){
			$fname = htmlentities($_POST["fname"]);
			$lname = htmlentities($_POST["lname"]);
			$addr = htmlentities($_POST["addr"]);
			$phone = htmlentities($_POST["phone"]);
						
			if(isset($_POST["submit"]) || isset($_POST['no'])) {
			
				$cust_exist_query = "SELECT * FROM CUSTOMER where fname = '$fname' and lname = '$lname'";
			
				$doesExist = mysqli_query($conn, $cust_exist_query);
				if (!$doesExist) print("ERROR: ".mysqli_error($conn));
				
				if (mysqli_num_rows($doesExist) != 0){
					#existing customer, confirmation form
					echo '<form action="" method="POST">
					<div class="alert alert-warning alert-dismissible fade show" role="alert">Customer already exists! Add new customer with same name?</div>
					<input class="btn btn-warning" type="submit" name="confirmYes" value="Add"> <input class="btn btn-warning" type="submit" name="confirmNo" value="No"> 
					<input type="hidden" name="fname" value="'.$fname.'">
					<input type="hidden" name="lname" value="'.$lname.'">
					<input type="hidden" name="addr" value="'.$addr.'">
	            <input type="hidden" name="phone" value="'.$phone.'"></form>';
				exit;
				
				
				} else {
					#new customer
					insertCustomer($fname,$lname,$addr,$phone,$conn);
				}
			}else if(isset($_POST['confirmYes'])) {
				insertCustomer($fname,$lname,$addr,$phone,$conn);
			}
			else {
				echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
									Customer not added!
									<button type="button" class="close" data-dismiss="alert" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>';
			}
			
		}
		
		#insert customer data
		function insertCustomer($fname,$lname,$addr,$phone,$conn) {
				$insert_cust_query = "INSERT into CUSTOMER (fname,lname,mailing_address,telephone,discount_code) VALUES ('$fname','$lname','$addr','$phone',0)";
				
				$result = mysqli_query($conn,$insert_cust_query);
				if (!$result) print("Error: ".mysqli_error($conn));
				else{ 
						echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
									Customer Added!
									<button type="button" class="close" data-dismiss="alert" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>';
				}
		}

        CloseCon($conn);

    ?>
	<h1>Add a new customer</h1>
    <form id="custData" action="" method="POST">
  <div class="form-group col-md-2">
    <label for="fname">First name</label>
    <input type="text" class="form-control" name="fname" id="fname" placeholder="First name">
  </div>
  <div class="form-group col-md-2">
    <label for="lname">Last Name</label>
    <input type="text" class="form-control" name="lname" id="lname" placeholder="Last name">
  </div>
  <div class="form-group col-md-6">
    <label for="addr">Address</label>
    <input type="text" class="form-control" name="addr" id="addr" placeholder="Address">
  </div>
  <div class="form-group col-md-2">
    <label for="phone">Telephone</label>
    <input type="text" class="form-control" name="phone" id="phone" placeholder="Telephone">
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