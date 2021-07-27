<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Bootstrap 101 Template</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="./css/bootstrap.min.css">

   
  </head>
  <body>
	<div class="container">
    
    <?php
	include 'header.html';
        include 'db_connection.php';
        $conn = OpenCon();

    #Required query
	$table_name = $_POST["tables"];
	$select_query = "select * from $table_name";
	$column_names = "select column_name from information_schema.columns where table_name='$table_name' ORDER BY ordinal_position";

	#Function that prints content in table format
	function printTable($headers,$table) {
		
		print "<table border='1' class='table'><thead>";
		print "<tr>";
		while($header = mysqli_fetch_row($headers)) {
            		foreach ($header as $field) print "<th>$field</th>";
        	}
		print "</tr></thead><tbody>";

		while ($a_row = mysqli_fetch_row($table)) {
			print "<tr>";
			foreach ($a_row as $field) print "<td>$field</td>";
			print "</tr>";
		}
		print "</tbody></table>";
	}

	$result = mysqli_query($conn, $select_query);
	$colHeaders = mysqli_query($conn, $column_names);
	if (!$result || !$colHeaders) {
		print("ERROR: ".mysqli_error($connection));
	}
	else {
		$num_rows = mysqli_num_rows($result);
		print "<h1><b>$table_name</b></h1>";
		print "Total Records: $num_rows";
		printTable($colHeaders,$result);
		
	}


        CloseCon($conn);

    ?>
    </div>
	<script src="./js/jquery.min.js"></script>
<script src="./js/bootstrap.min.js"></script>
  </body>
</html>