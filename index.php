<?php
session_start();

require_once 'classes.php';
require_once 'Connector.php';
if (!isset($_SESSION['username'])) {
  session_destroy();
  require_once 'header.php';
}else{
  require_once 'header_logged_in.php';
  $dbUser = $_SESSION['username'];
  
}



$DBCon = new Connector();
?>
  <!-- Begining of site material -->


  <div class='container'>


  	<div class='row'>
  		<div id="FeatImg" class='col-md-12'>
  			<?php
          $ssql = new SQLclass;
          ?>
                               
         

          <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
          <!-- Indicators -->
					  <ol class="carousel-indicators">
					    <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
					    <li data-target="#carousel-example-generic" data-slide-to="1"></li>
					    <li data-target="#carousel-example-generic" data-slide-to="2"></li>
					    <li data-target="#carousel-example-generic" data-slide-to="3"></li>
					  </ol>

				    <!-- Wrapper for slides -->
					  <div id='slider' class="carousel-inner" role="listbox">

              <!--  Slider images will populate here -->
					    
					  </div>
					  <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
					    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
					    <span class="sr-only">Previous</span>
					  </a>
					  <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
					    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
					    <span class="sr-only">Next</span>
					  </a>
				</div>
        
          
          


          <h2></h2>
          <p class="caption"></p>
  			
  		</div>
  		
  	</div>




  	<div id='catRow' class='row'>
  		<div id='catCol' class='col-md-12'>
  			
                <?php
                // Find distinct categories names..
                $SQLcat = "SELECT DISTINCT category FROM thumbnails ORDER BY category";
                  if ($results = $DBCon->connection->query($SQLcat)) {

                    // Create variable $res that holds value of category name
                    foreach ($results as $result) {
                      $res = $result['category'];
                      $SQLres = "SELECT * FROM thumbnails WHERE category = '$res' ORDER BY id DESC LIMIT 1";
                      
                      $searchFolder = "ImgUploads/";
                        if ($results = $DBCon->connection->query($SQLres)) {
                          // echo "Great....results...";
                          
                          while ($rows = $results->fetch_assoc()) {
                            $currentLocation =  $searchFolder . $rows['category']. '/'.'thumbnails/'.$rows['image_name'];
                            ?>
                            
                            <div class="col-md-3 catBox">
                              
															<a href="<?php echo $rows['category']. '.php'; ?>"><img class="thumbnail galImg" id='catImg'src="<?php echo $currentLocation; ?>"></a>
                              <h3><?php echo $result['category']; ?></h3>

                            </div>
                            <div id="fb-root"></div>
                            
                            
                            <?php
                            }
                           }

                    }

                  }
        

                ?>
   
  			
  		</div>
  	</div>
  </div>


  <?php

  // Store PHP class objects to variables
  // Value of getCategory() is set inside of imageQuery() so imageQuery() must be done first
  $images = $ssql->imageQuery();
  $categories = $ssql->getCategory();
  ?>

  <script>
    //  Set up JavaScript arrays to hold PHP variables
    var imagesArr = [];
    var catsArr = [];

    //////////////////////////////

    // Separately loop through each PHP array
  <?php foreach($categories as $category){ ?>
  
    // Use JavaScript .push() to push the value from the PHP array into the JavaScript array
  catsArr.push('<?=$category?>');


  <?php } ?>

    // Same steps as above...
  <?php foreach($images as $image){ ?>
    // Same steps as above...
  imagesArr.push('<?=$image?>');
  
  

  <?php } ?>




  console.log(catsArr);

  console.log(imagesArr[2]);

  </script>



   <!-- End of site material -->
<?php  require_once 'footer.php'; ?>