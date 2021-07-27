<html>
<head>
<title>
	Add New Transaction
</title>
<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Bootstrap 101 Template</title>

	<link rel="stylesheet" href="./css/bootstrap.min.css">
	<link rel="stylesheet" type = "text/css" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/css/select2.min.css">
</head>
<body>

<div class="container">
<form action="chooseQuantity.php" method="post">
	<?php
	include 'header.html';
	include 'db_connection.php';
	print '<h1>Add a new transaction</h1>';
    $conn = OpenCon();
	
	
	if (!$conn) die("Couldn't connect to MySQL");

	$cust_list = mysqli_query($conn, "select c_id, fname from CUSTOMER");

	if (!$cust_list) print("ERROR: ".mysqli_error($conn));
	else {
	    getCustomers($cust_list); 
	}

	#Select Items
	$items_list = mysqli_query($conn, "select _id, price from ITEM");

	if (!$items_list) print("ERROR: ".mysqli_error($conn));
	else {
	    getItems($items_list);
	}
		
	  
	#list of customer
	function getCustomers($cust_list){
		print '<div class="form-group col-md-6">';
		print '<label for="fname">Customer</label>';
		print "<select name='customer_number' class='form-control' style='width:40%'>";
		print '<option value="">Choose...</option>';
		while ($a_row = mysqli_fetch_row($cust_list)) {
			print "<option value=\"$a_row[0]\">$a_row[0]-$a_row[1]</option>";
		}
		print "</select>";
		print "</div>";
	}

	#list of items
	function getItems($items_list){
		print '<div class="form-group col-md-4">';
		print '<label for="fname">Items</label></br>';
		print "<select name='items[]' id='item' multiple='multiple' class='form-control' style='width:80%'>";
		while ($a_row = mysqli_fetch_row($items_list)) {
            print "<option value=\"$a_row[0]\">$a_row[0] - $$a_row[1]</option>";
		}
		print "</select>";
		print "</div>";
	}
	
	CloseCon($conn);
	?>
		
	<div class="row">
		<button type="submit" name="submit" class="btn btn-primary">Select Quantity</button>
	</div>
	</form>
<script src="./js/jquery.min.js"></script>
<script src="./js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/select2.full.min.js"></script>
</div>
</body>
<script>
$(function () {
  $('#item').select2();
});

</script>
</html>
