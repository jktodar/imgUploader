<?php
// require_once 'header_logged_in.php';
require_once 'Connector.php';
require_once 'classes.php';

//  Cut following two conditionals once troublshooting is complete..
// if (isset($_POST['ImageCategory']) && isset($_FILES['imagePost'])) {
//   if (!empty($_POST['ImageCategory']) && !empty($_FILES['imagePost'])) {

//     $file = current($_FILES);
//     $catID = current($_POST);

//     echo "<br>";

//     $upload = new UploadFile($catID, $file);
//     echo "<br>";
//     $upload->uploadFile($file);

    


//   }

// }
// $imageCategory = isset($_POST['ImageCategory']) && !empty($_POST['ImageCategory']) ? $_POST['ImageCategory'] : null;
// $imagePost = isset($_FILES['imagePost']) && !empty($_FILES['imagePost']) ? $_FILES['imagePost'] : null;




class UploadFile {
  public $maxSize = 2097152;
  public $DBcon;
  public $messages = array();
  public $AllowedTypes = array(
          'image/jpeg',
          'image/png',
          'image/gif'
    );
  public $file;
  public $uploadFolder;
  public $post;
  public $catNumber;
  public $catName;
  public $fileName;
  public $newName;
  public $newLocation;
  public $sqlInsert;
  public $copy;
  public $movedCopy;
  public $copyName;
  public $type;
  public $ext;
  public $currentName;
  public $dateTime = "Now()";

  public function __construct($catID){
    $this->DBcon = new Connector;
    $this->catID = $catID;
  }//__construct()

  public function getCategoryNumber($catID){
    $catNumber = $this->catID;
  }//getCategoryNumber()

  public function setCategoryName(){
    $id = $this->catID;
    $result = $this->DBcon->connection->query("SELECT * FROM photocat WHERE id = $id");
    $row = $result->fetch_assoc();
    $this->catName = $row['category'];
  }//setCategoryName()

  public function uploadFile($file){
    $this->file = $file;
    $success = false;
    $this->setCategoryName();
    $this->getName();

    $this->setFolder();
    
    if ($this->checkFile()) {
      if ($this->moveFile()) {
        if ($this->moveToDB($this->newName,$this->newLocation)) {
          if($this->resize()){
            $this->moveToDB($this->currentName, $this->destinationFilename);
            if ($this->makeThumb()) {
              $this->moveToDB($this->currentName, $this->destinationFilename);
            }else{
            echo "Problem moving thumbnail to DB";
            return false;
            }            
          }
        }
      }
      
      $success = true;
    }
    // $success = true;
    $this->getMessages();
    return $success;

  }//uploadFile($file)

  public function getName(){
    $this->fileName = $this->file['name'] ;
  }//getName()

  public function changeName(){
    $this->newName = NULL;

    if ($nospaces = strpos(trim($this->fileName) , ' ')) {
      $nospaces =str_replace(' ', '_', trim($this->fileName));
    } else{
      $nospaces = $this->fileName;
    }

    $nameparts = pathinfo($nospaces);
    $cabinet = scandir($this->uploadFolder.$this->type);
    $this->newName = $nameparts['filename'] . '.' . $nameparts['extension'];

    if (in_array($this->newName, $cabinet)) {
      $i = 1;
      do {
          $this->fileName = $nameparts['filename'] . '_'. $i++;
          $this->newName = $this->fileName . '.' .$nameparts['extension'];
        }
        while (in_array($this->newName, $cabinet));
    }

  }//changeName()

  public function checkTypes(){
    if (in_array($this->file['type'], $this->AllowedTypes)) {
      return true;
    } else{
      $this->messages[] = $this->file['name']."This is not an allowed type of file";
      return false;
    }

  }//checkTypes()

  public function getErrorMessages(){
    switch ($this->file['error']) {
      case 1:
      case 2:
        $this->messages[] = $this->file['name']. ' was bigger than: ' . $this->maxSize;
        break;
      case 3:
        $this->messages[] = $this->file['name']. ' was only partially uploaded';
        break;
      case 4:
        $this->messages[] = 'No file was selected';
        break;
      default:
        $this->messages[] = 'There was a problem uploading '. $this->file['name'];
        break;
    }
  }//getErrorMessages()



  public function checkFile(){
    if ($this->file['error']!=0) {
      $this->getErrorMessages();
      return false;
    }
    if (!$this->checkSize()) {

      return false;
    }
    if (!$this->checkTypes()) {

      return false;
    }
      $this->changeName();
    return true;
  }//checkFile()

  public function checkSize(){
    if ($this->file['size'] < $this->maxSize) {
      return true;
    }
    $this->messages[] = $this->file['name']. ' was bigger than: ' . $this->maxSize;
    return false;
  }//checkSize()


  public function setFolder(){
    $this->type = 'original';
    // $this->uploadFolder = "C:/xampp/htdocs/MonicaNova/ImgUploads/" . $this->catName . '/';
    echo $this->uploadFolder = dirname(__FILE__ ). "/ImgUploads/". $this->catName . '/';
    echo "<br>";
    echo "From SetFolder";

  }//setFolder()

  public function getMessages(){
    ?><ul><?php
      foreach ($this->messages as $message) { ?>
        <li><?php echo $message;?></li>
        <?php
      }
  }//getMessages()

  

  

  public function resize() {
      // Get the destination directory: /images/{type}
      // This is where the resized image will be moved.

      $this->type = 'resize';
    
      $destinationDir = $this->uploadFolder.$this->type;
      
      // Set the width of the resized image. We will keep the widths
      // at a constant value, and calculate the adjusted height based
      // on the original photo's aspect ratio later.
      
      $width = 400;
      
      
      // "list" will populate $width and $height here with the current
      // dimensions of the *original* file.
      list($originalWidth, $originalHeight, $ext) = getimagesize($this->newLocation);
      $this->ext = $ext;


      // Check the extension and use appropriate imagecreate function.
      if ($this->ext === 1) {
          $sourceImage = imagecreatefromgif($this->newLocation);
        echo "<br>";
        } elseif($this->ext === 3){
          $sourceImage = imagecreatefrompng($this->newLocation);
        } else{
          $sourceImage = imagecreatefromjpeg($this->newLocation);
        }
      
      // Get the aspect ratio of the *original* photo (which we will scale/resize on).
      $ratio = $originalWidth / $originalHeight;
      
      // We ONLY want to resize based on width, since we are displaying
      // the images in a grid. Since we set the width to a constant, the
      // height can be easily adjusted based on the $ratio.
      $height = $width/$ratio;
      
      // Resample the image. Both a destination and source image are required, since
      // the image processor (GD/ImageMagick/etc) will paint a new picture into
      // $destinationImage (a blank canvas, so to speak), from the $sourceImage, which
      // is the original with all the colors/bits/etc.
      
      // Create a new blank image with our calculated dimensions.
      $destinationImage = imagecreatetruecolor($width, $height);
      
      // Load the source (original) image's contents into memory so that it can be used
      // to resample/resize the new image.
      
      
      // This will "paint" the new picture into the $destination image (which was blank before).
      $success = imagecopyresampled($destinationImage, $sourceImage, 0, 0, 0, 0,
          $width, $height, $originalWidth, $originalHeight);


          
      // If the image was successfully resampled, we need to store it (since it's just floating
      // in memory right now).


      $this->currentName = $this->newName;
      if ($success) {
        $this->destinationFilename = $this->uploadFolder.$this->type . '/'. $this->currentName;
        // Attempt to save the newly resized file. If it fails, handle the error.
        

        // Check the extension and use appropriate image function.
        if ($this->ext === 1) {
          imagegif($destinationImage, $this->destinationFilename,80);
        } elseif($this->ext === 3){
          imagepng($destinationImage, $this->destinationFilename,80);
        } else{
          imagejpeg($destinationImage, $this->destinationFilename,80);
        }

        return true;
      } else {
       // Handle error - resizing the image failed.

        echo "problem with destination<br>";
        return false;
      }
      
      return true;

    }//  resize()

  public function makeThumb(){
    
    $targetFileName = $this->uploadFolder.$this->type.'/'.$this->newName;



    $this->type = 'thumbnails';
   

    list($orig_width, $orig_height) = getimagesize($targetFileName);

    


      if ($this->ext === 1) {
        $sourceImage = imagecreatefromgif($targetFileName);
        echo "<br>";
        } elseif($this->ext === 3){
          $sourceImage = imagecreatefrompng($targetFileName);
        } else{
          $sourceImage = imagecreatefromjpeg($targetFileName);
        }

    $thumbWidth = 200;
    $thumbHeight = 200;

    $src_x = ($orig_width/2)-($thumbWidth/2);
    $src_y = ($orig_height/2)-($thumbHeight/2);


    // Create blank image...
    $destinationImage = imagecreatetruecolor($thumbWidth, $thumbHeight);
      
    
    // This will "paint" the new picture into the $destination image (which was blank before).
    $success = imagecopyresampled($destinationImage, $sourceImage, 0, 0, $src_x, $src_y, $thumbWidth, $thumbHeight, $thumbWidth, $thumbHeight);

    $this->currentName = $this->newName;

    // echo $destinationFilename = $this->uploadFolder.$this->type . '/' . $this->currentName;

    if ($success) {
        $this->destinationFilename = $this->uploadFolder.$this->type . '/' . $this->currentName;

        // Attempt to save the newly resized file. If it fails, handle the error.
        

        // Check the extension and use appropriate image function AND store in new destination
        if ($this->ext === 1) {
        imagegif($destinationImage, $this->destinationFilename,80);
        
        } elseif($this->ext === 3){
         imagepng($destinationImage, $this->destinationFilename,80);
        } else{
          imagejpeg($destinationImage, $this->destinationFilename,80);
        }

        return true;
      } else {
       // Handle error - resizing the image failed.

        echo "problem with destination<br>";
        return false;
      }
      
      return true;


  }// makeThumb()

  public function moveFile(){

    echo "Test message From moveFile()";
    echo "<br>";
    
    echo "<br>";
    echo $this->file['tmp_name']. ' is coming from moveFile()';
    $this->messages[] = "Please note the location for this file is: " . $this->uploadFolder . $this->newName;
    echo "<br><br>";

    $this->newLocation = $this->uploadFolder . $this->type.'/'.$this->newName;
    if (move_uploaded_file($this->file['tmp_name'], $this->newLocation)) {
       $this->messages[] = "The file was successfully moved to the: " . $this->catName. " folder.";
       // $this->makeThumb();
      return true;
     } else {$this->messages[] = "There was a problem moving this file"; return false; }
    
  }//moveFile()

  public function moveToDB($filename, $dbPath){

    $this->sqlInsert = "INSERT INTO `{$this->type}` (`id`,`image_name`,`image`,`category`,`time_posted`)
        VALUES ('','{$filename}','{$dbPath}','{$this->catName}',NOW())";

    if ($this->DBcon->connection->query($this->sqlInsert)) {
      $this->messages[] = "Image has been successfully inserted into the database";
      return true;
    }else{
      $this->messages[] ="There has been an error inserting the image".$this->newName."<br>".$this->newLocation."<br>".$this->catName;
      return false;
    }
    

  }//moveToDB()

}//End Class UploadFile
class Messages extends UploadFile{

  public function __construct() {
     $this->getMessages();
    //echo $message;
  }
}


// require_once 'footer.php';
?>
