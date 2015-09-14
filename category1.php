<?php
error_reporting(0);
session_start();
require_once 'Connector.php';

if (!isset($_SESSION['username'])) {
  session_destroy();
  require_once 'header.php';
}else{
  require_once 'header_logged_in.php';
}
?>


  <div class="container">
    <div class="row">
      <div class="gallery col-md-12">

          <?php
          $SQL = "SELECT * FROM thumbnails WHERE category = 'Category1' ORDER BY id DESC";

          $searchFolder = "ImgUploads/";

          $DBCon = new Connector;

          if ($results = $DBCon->connection->query($SQL)) {
            while ($rows = $results->fetch_assoc()) {
               $currentLocation =  $searchFolder . $rows['category']. "/thumbnails/". $rows['image_name'];
               $originalFile = $searchFolder . $rows['category']. "/original/". $rows['image_name'];
              ?>
              <div class="col-md-4 catBox">

                <a  href="<?php echo $originalFile;?>"><img class="thumbnail galImg" src="<?php echo $currentLocation; ?>"></a>
               
                <div class="fb-like" data-href="http://localhost/practUpload/php echo $rows[&#039;category&#039;]. &quot;/&quot;. $rows[&#039;image_name&#039;]?&gt;" data-width="70px" data-layout="standard" data-action="like" data-show-faces="true" data-share="true"></div>

              </div>

            <?php }

          }else{
            echo "no results found";
          }
        ?>

      </div>
    </div>
  </div>
<?php require_once 'footer.php'; ?>
