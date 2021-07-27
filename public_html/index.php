<!DOCTYPE html>
<html lang="en">
  <head>
  <link rel="stylesheet" href="./css/bootstrap.min.css">

  </head>
  <body>
    <div class="container">
    <h1>Welcome to HSL</h1>
    <form id="getData" action="getTableData.php" method="POST">
    <?php
	include 'header.html';
        include 'db_connection.php';
        $conn = OpenCon();

	#Show tables query
	$show_table_query = "show tables";

	function showTables($table) {
		print "<select name='tables' class='form-control' style='width:40%'>";
	        print "<option value=\"\">Choose...</option>";
			while ($row = mysqli_fetch_row($table)) {
				foreach ($row as $column) print "<option value=\"$column\">$column</option>";
			}
		print "</select>";
	}

	$show_table_query_result = mysqli_query($conn, $show_table_query);
	if (!$show_table_query_result) {
		print("ERROR: ".mysqli_error($conn));
	}
	else {
		$rows = mysqli_num_rows($show_table_query_result);
		showTables($show_table_query_result);
	}

        CloseCon($conn);

    ?>
	
		<button style="margin:20px;" type="submit" name="submit" class="btn btn-primary">Show Table data</button>
  </form>
</div>
<script src="./js/jquery.min.js"></script>
<script src="./js/bootstrap.min.js"></script>
</body>
<style>
select {
	margin-left:20px;
	height:27px;
}


</style>
<script>
$('#getData').submit(function(e) {
		if($("select[name='tables']").val() == ""){
			alert('Choose a table!');
			e.preventDefault();
		}
});

</script>
</html>