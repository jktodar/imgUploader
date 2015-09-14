<?php
session_start();
$submitted = isset($_REQUEST);
$message = null;
if ($submitted) {
  $value = isset($_REQUEST['upload']) ? $_REQUEST['upload'] : NULL;
  $success = $value === 'true';
  if ($success) {
    $message = 'The file was uploaded!';
  } elseif($value === 'false'){
    $message = 'There was an error during the upload.';    
  }
}

if (!isset($_SESSION['username'])) {
  $redirect_page = 'login.php';
  header('Location: '.$redirect_page);
}else{
  $dbUser = $_SESSION['username'];
  echo "Hello ". $dbUser."<br>";
  require_once 'uploadFile.php';
    }
  require_once 'header_logged_in.php';
  require_once 'classes.php';
?>

<div class="container">
  <div class="row">
    <div class="col-md-6">
      <div class="row">
        <div class="col-md-11">
          <?php
            $maxSize = 2097152;
            $result = array();
            ?>
            <?php
if ($submitted) {
  echo '<div class="alert alert-error"><p>' . $message . '</p></div>';
} else {
  echo '<div class="alert alert-success"><p>'. $message . '</p></div>';
}
?>
          <form class="uploader" action="fileHandler.php" method="POST" enctype="multipart/form-data">
            <select name="ImageCategory">
            <option disabled selected> Select Image Category </option>
            <?php
              $newConn = new DropMenu;
              $newConn->dropdown();
            ?>
            </select>
               <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $maxSize; ?>">
                <input type="file" name="imagePost" multiple>
                <input type="submit" name"submit" value="Submit image">
          </form>
        </div>
      </div>

    </div>
    <div class="col-md-6">
    <form class="poster" action="upload_dropdown2.php" method="POST">
     Name: <br><input type="text" name="text"><br><br>
     Paragraph Title:  <br><input type="text" name="post_title"><br><br>

     Post here:<br><textarea name="Tbox" cols="50" rows="5"></textarea><br><br>
    <input type="submit" name="submit" value="Submit">
    </form>

    </div>
    <?php
    $Para = new Paragraph;
    $Para->InsertText();
    echo $Para->Tbox."<br>";
    echo $Para->text_sql."<br";
    $q;
    ?>
  </div>
</div>
<?php require_once 'footer_logout.php'; ?>
