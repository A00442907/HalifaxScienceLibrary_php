<!DOCTYPE html>
<html lang="en">
  <head>
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
      
      <form id="processArticle.php" action="" method="POST">
        
        <?php
		include 'header.html';
    include 'db_connection.php';
    $years = range(1900, strftime("%Y", time()));
    $conn = OpenCon();
	print '<h1>Add a new article</h1>';
    
    
    if(isset($_POST["submit"])){
      
    $mId = htmlentities($_POST["mId"]);
    $vId = htmlentities($_POST["vId"]);
    $pubYear = htmlentities($_POST["pubYear"]);
    $title = htmlentities($_POST["title"]);
    $pages = htmlentities($_POST["pages"]);
    $authors = $_POST["authors"];
      
   $volume_exist_query = "SELECT * FROM VOLUME where v_id = '$vId' and m_id = '$mId' ";
   $volumeDoesExist = mysqli_query($conn, $volume_exist_query);
   
   
   if (mysqli_num_rows($volumeDoesExist) != 0){
     //	echo 'Exists';
    // Do nothing.     
    } else {
      // echo 'Doesn"t Exists : Adding a volume !! ';
      insertVolume($mId,$vId,$pubYear, $conn);
    }

    
    $articleId = insertArticle($mId,$vId,$title,$pages, $conn);

    if($articleId != -1)
    {
      foreach($authors as $author) {
        
       //push authorId with article id in WRITTENBY
       if(insertWrittenBy($articleId,$author, $conn) == -1)
       {
          return;
       }
       
      }
      
      echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
            WrittenBy Table updated successfully!
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>';
    }
    
  }
  
  
  
  function insertWrittenBy($articleId,$author, $conn) 
  {
      $insert_writtenby_query = "INSERT into WRITTENBY (article_id,author_id) VALUES ('$articleId','$author')";
      
      $result = mysqli_query($conn,$insert_writtenby_query);
      if (!$result) 
      {
        print("Error: ".mysqli_error($conn));
        return -1;
      }
      else{ 
        return 1;
    }
  }
    

    function insertVolume($mId,$vId,$pubYear, $conn) 
    {
      $insert_volume_query = "INSERT into VOLUME (v_id,m_id,year_of_publication) VALUES ('$vId','$mId','$pubYear')";
      
      $result = mysqli_query($conn,$insert_volume_query);
      if (!$result) print("Error: ".mysqli_error($conn));
      else{ 
          echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                Volume ['.$vId.'] did not exist for the selected Magazine, added as a new Volume!
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>';
    }
  }

    function insertArticle($mId,$vId,$title,$pages, $conn) 
    {
      $insert_volume_query = "INSERT into ARTICLE (title, pages, v_id, m_id) VALUES ('$title','$pages','$vId','$mId')";
      
      $result = mysqli_query($conn,$insert_volume_query);
      $articleId = mysqli_insert_id($conn);


      if (!$result) 
      {
        print("Error: ".mysqli_error($conn));
        return -1;
      }
      else{ 
          echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                New Article "'.$title.'" added!
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>';
              return $articleId;
      }
    }	
    
/////////GET MAGAZINEs First DropDown

		$show_mag_query = "select * from MAGAZINE";
		
		$select_mag_result = mysqli_query($conn,$show_mag_query);
		
		if (!$select_mag_result) {
			print("ERROR: ".mysqli_error($conn));
		}
		else {
			print '<div class="form-group col-md-6">';
			print '<label for="Magazine">Select Magazine</label>';
			print "<select name='mId' id='mId' class='form-control' style='width:100%'>";
			
			print "<option value=\"\">Choose...</option>";
			while ($row = mysqli_fetch_row($select_mag_result)) {
				print "<option value=\"$row[0]\">$row[1]</option>";
			}
			print "</select>";
			print '<small style="margin-top: 15px;"><a href="addNewMagazine.php">Add a new Magazine</a></small>';
			print '</div>';
		}


/////////GET AUTHORs First DropDown


		$show_author_query = "select * from AUTHOR";
		
		$select_author_result = mysqli_query($conn,$show_author_query);
		
		if (!$select_author_result) {
			print("ERROR: ".mysqli_error($conn));
		}
		else {
			print '<div class="form-group col-md-10" style="display: grid;">';
			print '<label for="Magazine">Select Authors</label>';
      print "<select name='authors[]' id='AuthorId' multiple='multiple' class='form-control' style='width:80%'>";
			while ($row = mysqli_fetch_row($select_author_result)) {
				print "<option value=\"$row[0]\">$row[1] $row[2]</option>";
			}
			print "</select>";
			print '</div>';
		}






      CloseCon($conn);
        
    ?>
    
    <div class="form-group col-md-2">
      <label for="Volume">Volume</label>
      <input type="text" class="form-control" name="vId" placeholder="Volume number">
      
    </div>
    
    <div class="form-group col-md-4">
      <label for="pubYearLabel">Select Publication Year : </label>
      <select name="pubYear" class='form-control' style='width:50%'>
        <option>Select Year</option>
        <?php foreach($years as $year) : ?>
          <option value="<?php echo $year; ?>"><?php echo $year; ?></option>
          <?php endforeach; ?>
        </select>
      </div>
        
        <div class="form-group col-md-6">
          <label for="Title">Title</label>
          <input type="text" class="form-control" name="title" placeholder="Add Title">
  </div>
  
  <div class="form-group col-md-2">
    <label for="pages">Pages</label>
    <input type="text" class="form-control" name="pages" placeholder="Pages">
  </div>
  
  <div class="row">
  <button type="submit" name="submit" class="btn btn-primary">Submit</button>
  </div>

</form>
</div>   

<script src="./js/jquery.min.js"></script>
<script src="./js/bootstrap.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/select2.full.min.js"></script>




<script>
  $(function () {
  $("#mId").select2();
});

  $(function () {
  $("#AuthorId").select2();
});

</script>

  </body>
</html>