<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Bootstrap 101 Template</title>

	<link rel="stylesheet" href="./css/bootstrap.min.css">
   
  </head>
  <body>
  <div class="container">
  <form id="" action="" method="POST">
   
    <?php
		include 'header.html';
        include 'db_connection.php';
        $conn = OpenCon();
		$qty_arr = range(0, 10);
		$cus_number = htmlentities($_POST['customer_number']);
		$list_items = $_POST["items"];
		

		print '<h1>Add a new Transaction</h1>';
		print '<h5>Customer Id: '.$cus_number.'</h5>';
		print '<h6>Choose Quantity</h6>';
        foreach($list_items as $item) {
			$item_id =  strval($item);
			print '<div class="form-group col-md-4">';
			print '<input name="items_list[]" type="hidden" value='.$item_id.'>';
			print '<input name="c_no" type="hidden" value='.$cus_number.'>';
			print '<label for="item_'.$item_id.'">Item '.$item_id.'</label>';
			print'<select name="qty_'.$item_id.'" class="form-control" style="width:40%">';
			foreach($qty_arr as $num) {
				print "<option value=\"$num\">$num</option>";
			}
			print '</select>';
			print '</div>';
		}
		
		#method to calculate discount code
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
		
		
		function readDiscountCode($conn,$cus_number) {
			$DC = 0;
			$DC_query = "select discount_code from CUSTOMER where c_id = '{$cus_number}';";
			$DC_result = mysqli_query($conn, $DC_query);
			if (!$DC_result) print("ERROR: ".mysqli_error($conn));	
			$cust_row = mysqli_fetch_row($DC_result);
			return $cust_row[0];
			
		}
		
		// isset Check whether a variable is empty.
	if(isset($_POST['submit2']) ) {
		#total during insert
		$sum = 0.0;
		$total_price = 0.0;
		$items_list = $_POST['items_list'];
		$c_no = $_POST['c_no'];
		
		//get each item's quantity and calculate total price
        foreach($items_list as $item) {
			$list_item_price_query = 'SELECT price FROM ITEM where _id = '.$item.'';
			$list_item_price_result = mysqli_query($conn, $list_item_price_query);
			$item_row = mysqli_fetch_row($list_item_price_result);
			$sum += ((int)$_POST['qty_'.$item] * $item_row[0]);
		}
		
		
		#get current discount code
		$discount_code = readDiscountCode($conn,$c_no);
		
		/*
		
			curr_totail = get sum(total price) from txn where cid=?  - D
			figure out DC - D
			calculate the new total price and insert in  transaction table - D
			insert txn items - D
			update discoutn code to customer table
		
		*/
		
		#get total price based on DC
		$total_price = (floatval($sum))*(1-2.5*$discount_code/100);
		
		#insert into transaction table
		$insert_txn = 'INSERT INTO CustTransaction(txn_date,c_id,total_purchase_price) VALUES (NOW(),'.intval($c_no).','.floatval($total_price).');';
        $insert_txn_result = mysqli_query($conn,$insert_txn);
		$items_txn_num = mysqli_insert_id($conn);
		if (!$insert_txn_result) {
			print("ERROR: ".mysqli_error($conn));
		}
		
		#insert into txn_items table
		foreach($items_list as $item) {
			$insert_txn_item_query = "INSERT INTO TXN_ITEMS(txn_num, item_id,quantity) VALUES (".$items_txn_num.",".$item.",".intval($_POST['qty_'.$item]).")";
			$insert_txn_item_result = mysqli_query($conn,$insert_txn_item_query);
			if(!$insert_txn_item_result) {
				print("ERROR: ".mysqli_error($conn));
			}
		}
		
		#calculate new discount code
		$d_code = getCurrentDiscountCode($conn,$c_no);
		
		#update customer's discount code
		$update_DC_query = 'UPDATE CUSTOMER SET discount_code= '.$d_code.' WHERE c_id = '.intval($c_no).'';
		$update_DC_result = mysqli_query($conn, $update_DC_query);
		if(!$update_DC_result) {
			print("Error: ".mysqli_error($conn));
		} else {
			echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
									Transaction Added!
									<button type="button" class="close" data-dismiss="alert" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>';
		}

	}
		
		
		
				
        CloseCon($conn);

    ?>
	
   
  <div class="row">
  <button type="submit" name="submit2" class="btn btn-primary">Submit</button>
  </div>
</form>
</div>   

<script src="./js/jquery.min.js"></script>
<script src="./js/bootstrap.min.js"></script>
  </body>
</html>