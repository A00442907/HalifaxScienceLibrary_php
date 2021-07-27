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
</head>
<body>

<div class="container">
<form action="insertTransaction.php" method="post">
	<?php
	include 'header.html';
	include 'db_connection.php';
    $conn = OpenCon();
	
	
	if (!$conn) die("Couldn't connect to MySQL");

	/*mysqli_select_db($conn, $db)
		or die("Couldn't open $db: ".mysqli_error($conn));*/
		
		/*insert in  txn (txn_num,cid, now, purchase price=0) - txn_num
insert in txn_items(txn_num, itemid, qty) 
update txn set column purchase price
update discount code in Customer table*/

	$result = mysqli_query($conn, "select c_id, fname from CUSTOMER");

	if (!$result) print("ERROR: ".mysqli_error($conn));
	else {
	    $num_rows = mysqli_num_rows($result);    
	}

	#Select Articles
	$result_items = mysqli_query($conn, "select _id, price from ITEM");

	if (!$result) print("ERROR: ".mysqli_error($conn));
	else {
	    $num_rows = mysqli_num_rows($result);
	}
	
	function printcustomers($result){
		print "<select name='customer_number' class='form-control' style='width:40%'>";
		while ($a_row = mysqli_fetch_row($result)) {
			print "<option value=\"$a_row[0]\">$a_row[0]-$a_row[1]</option>";
		}
		print "</select>";
	}

	function printMagazine($result_items){
		print "<select name='magazine' multiple width:auto;>\n";
		while ($a_row = mysqli_fetch_row($result_items)) {
			// print "<option value=\"$a_row[0]\">$a_row[1] - vol $a_row[2] - price  $a_row[3]</option>";
            print "<option value=\"$a_row[0]\">$a_row[0] - price$a_row[1]</option>";
		}
		print "</select>";
	}
	
	printcustomers($result);
	#print "</td><br><br><td>Magazine: </td><td>";
	print "<p>I: </p>";
	printMagazine($result_items);
	print "</br>";
	
	
    // isset Check whether a variable is empty.
	if( isset($_POST['submit']) ) {
		
		$res2 = mysqli_query($conn,"SELECT MAX(TxnNum) + 1 from CustTransaction;");
            $ID = 0;
            while ($b_row = mysqli_fetch_row($res2)) {
                $ID = $b_row[0] + 1;
            }
            mysqli_query($conn,"INSERT INTO CustTransaction VALUES ($ID, NOW(), $cus_number, $total);");

            foreach ($magazines as $option) {
                mysqli_query($conn, "INSERT INTO TXN_ITEMS VALUES ($ID, $option.$value,1);");
                
            }
		
            // $_POST is a PHP super global variable which is used to collect form data
            //make sure to clean variables, htmlentities - Convert characters to HTML entities:
            $cus_number = htmlentities($_POST['customer_number']);
            $magazines = $_POST['magazine'];
		// mysqli_query Perform query against a database:
        $result = mysqli_query($conn, "select CustTransaction.c_id, (sum(ITEM.price * TXN_ITEMS.quantity * 1 - 2.5 * CUSTOMER.discount_code / 100)) as total  from CustTransaction,CUSTOMER,TXN_ITEMS,ITEM where ((Date between  DATE(NOW() - INTERVAL 5 YEAR) and NOW()) and CustTransaction.TxnNum = TXN_ITEMS.txn_num and ITEM._id = TXN_ITEMS.item_id and CustTransaction.c_id = $cus_number) Group by CustTransaction.c_id;");
        $total = 0;
        // mysqli_fetch_row -  Fetch rows from a result-set:
        while ($b_row = mysqli_fetch_row($result)) {
                $total = $b_row[1];
        }
            $DC = 0;
            if($total > 500){
                $DC = 5;
            } else if ($total == 0){
                $DC = 0;
            } else if ($total > 100 && $total <= 200){
                $DC = 1;
            } else if ($total > 200 && $total <= 300){
                $DC = 2;
            } else if ($total > 300 && $total <= 400){
                $DC = 3;
            } else if ($total > 400 && $total <= 500){
                $DC = 4;
            }
            
            
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
									Transaction added successfully!
									<button type="button" class="close" data-dismiss="alert" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>';
	}
	
	CloseCon($conn);
	?>
		

	<input type="submit" name="submit">
	</form>
	<script src="./js/jquery.min.js"></script>
<script src="./js/bootstrap.min.js"></script>
</div>
</body>

</html>
