<?php
require_once 'header.php';
require_once 'classes.php';
require_once 'uploadFile.php';

$redirect_page = 'upload_dropdown2.php';


if (isset($_POST['ImageCategory']) && isset($_FILES['imagePost'])) {
  if (!empty($_POST['ImageCategory']) && !empty($_FILES['imagePost'])) {

    $file = current($_FILES);
    $catID = current($_POST);

    echo "<br>";

    $upload = new UploadFile($catID, $file);
    echo "<br>";
    if ($upload->uploadFile($file)) {
     $redirect_page .= "?upload=true";
    } else{
      $redirect_page .= "?upload=false";
    }

    header('Location: '.$redirect_page);



  }

}else{

  $redirect_page = 'upload_dropdown2.php?upload=false';

  header('Location: '.$redirect_page);
  exit();

}


$imageCategory = isset($_POST['ImageCategory']) && !empty($_POST['ImageCategory']) ? $_POST['ImageCategory'] : null;
$imagePost = isset($_FILES['imagePost']) && !empty($_FILES['imagePost']) ? $_FILES['imagePost'] : null;


if ($imageCategory && $imagePost) {
  // We have BOTH a category and a file
  die('uploaded file');
} else {
  // We are missing either a category or an image OR both...

  $redirectPage = 'upload_dropdown2.php?upload=unknown';
 
  header('Location: ', $redirectPage);
  exit();
}

require_once 'footer.php';
