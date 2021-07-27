<html>
<head>
<title>
	Cancel Transaction
</title>
<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Bootstrap 101 Template</title>
	<link rel="stylesheet" href="./css/bootstrap.min.css">
</head>
<body>
<div class="container">
<?php

	include 'header.html';
	include 'db_connection.php';
    $conn = OpenCon();
	if (!$conn) die("Couldn't connect to MySQL");
	
	
	function getCurrentDiscountCode($conn,$cus_number) {
			$DC = 0;
			$curr_total_price_query = "select SUM(total_purchase_price) as total from CustTransaction where c_id = '{$cus_number}' AND txn_date between  DATE(NOW() - INTERVAL 5 YEAR) and NOW();";
			$curr_total_price_result = mysqli_query($conn, $curr_total_price_query);
			if (!$curr_total_price_result) print("ERROR: ".mysqli_error($conn));	
			$row = mysqli_fetch_assoc($curr_total_price_result); 
			$sum = $row['total'];
			if($sum){
				if($sum > 500){
					$DC = 5;
				}
				else {
					$DC = ($sum - 1)/100;
				}
			}
			else {
				//$txn_row[0] = 0;
				$DC = 0;
			}			
			return intval($DC);
		}
	
	
	if(isset($_POST['submit']) ){

		$trxn_number = $_POST["trxn_number"];

		$query = mysqli_query($conn,"SELECT txn_date, c_id FROM CustTransaction WHERE txn_num = '$trxn_number'");

	if (!$query)
		print("ERROR1: ".mysqli_error($conn));
	else {
		if(mysqli_num_rows($query) == 0) 
			echo "No such transaction exists";
		else{
		
		$output = mysqli_fetch_row($query); 
		
		
		foreach($output[0] as $date)
		{	
			
			$current =date('y-m-d',time());
			$your_date_time = strtotime($date);
			$your_date = date('y-m-d',$your_date_time);
			$days = round(abs(strtotime($current)-strtotime($your_date))/86400);
			
			if($days <= 30){
				$delete1 = mysqli_query($conn, "DELETE FROM TXN_ITEMS where txn_num = '$trxn_number'");
					if (!$delete1) print("ERROR: ".mysqli_error($conn));
					else {
							$delete2 = mysqli_query($conn, "DELETE FROM CustTransaction where txn_num = '$trxn_number'");
							
							if (!$delete2) print("ERROR2: ".mysqli_error($conn));
							else {
								$d_code = getCurrentDiscountCode($conn,$cus_number);
								$update_DC_query = 'UPDATE CUSTOMER SET discount_code= '.$d_code.' WHERE c_id = '.intval($output[0]).'';
								$update_DC_result = mysqli_query($conn, $update_DC_query);
								if(!$update_DC_result) {
									print("Error: ".mysqli_error($conn));
								} else {
									echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
															Transaction deleted successfully!
															<button type="button" class="close" data-dismiss="alert" aria-label="Close">
																<span aria-hidden="true">&times;</span>
															</button>
														</div>';
								}
								
								/*echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
									Transaction deleted successfully!
									<button type="button" class="close" data-dismiss="alert" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
							</div>'*/
							}
						}
			}
			else{
				
				echo '<div class="alert alert-danger alert-dismissible fade show" role="alert"> <strong> Error deleting transaction!
									</strong>Transaction is '.$days.' days old. Transaction older than 30 days cannot be deleted. <button type="button" class="close" data-dismiss="alert" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>';
			}
		}
	}
}
CloseCon($conn);
}
?>

<h5>Enter transaction number to cancel:</h5>
<form name="cancel transaction" action="" method="POST">
	<div class="form-group col-md-4">
		<input type="text" class="form-control" name="trxn_number" placeholder="Transaction number">
	</div>
	<div class="row">
		<button type="submit" name="submit" class="btn btn-primary">Submit</button>
	</div>
</form>

<script src="./js/jquery.min.js"></script>
<script src="./js/bootstrap.min.js"></script>
</div>
</body>
</html>
