 <?php
 //error_reporting(0);
 require_once 'Connector.php';

class DropMenu  {
  public $DBcon;
  
  public function __construct(){
    $this->DBcon = new Connector;
  }

  public function dropdown(){
    if ($result = $this->DBcon->connection->query($this->DBcon->qSelCat)) {
    if ($result->num_rows) {
      $existing = array();
     while ($row = $result->fetch_object()) {
      //print_r($row);
      //die();
        if ($row->category && !in_array($row->category, $existing)) {
         echo "<option value=\"$row->id\">  $row->category  </option>" ;
         array_push($existing, $row->category);
          }

        }
      }
    }
  }

}// End of DropMenu

class SQLclass{
 
  public $SQLall = "SELECT * FROM original ORDER BY time_posted DESC LIMIT 4";
  public $imgArr = array();
  public $catArr = array();
  public $multiArr = array();
  public $DBcon;
  public $name = 'name';
  public $category;


  public function __construct(){
    $this->DBcon = new Connector;
  }

  public function imageQuery(){
    // $imgArr = [];
    $searchFolder = "ImgUploads/";
    if ($results = $this->DBcon->connection->query($this->SQLall)) {
                  
      while ($rows = $results->fetch_assoc()) {
        $currentLocation =  $searchFolder . $rows['category']. '/original/'.$rows['image_name'];
        array_push($this->imgArr, $currentLocation);
        array_push($this->catArr, $rows['category']);
        array_push($this->multiArr,[$this->catArr,$this->imgArr]);
        // array_push(array, var)
      }
    }

    $this->getCategory();

    return $this->imgArr;
  }



  public function getCategory(){

    return $this->catArr;
  }


} // End of SQLclass

class Paragraph {
  public $DBcon;
  public $text_sql;
  public $update_sql;
  public $text;
  public $post_title;
  public $Tbox;
  public $qIns;
  public $qSelTitle;
  public $qSelPara;
  public $result;

  public function __construct(){
    $this->DBcon = new Connector;
  }

  public function InsertText(){
    if (isset($_POST) && isset($_POST['text']) && isset($_POST['Tbox'])
      && isset($_POST['post_title'])) {
      $text = mysqli_real_escape_string($this->DBcon->connection,trim($_POST['text']));
      $post_title = mysqli_real_escape_string($this->DBcon->connection,trim($_POST['post_title']));
      $Tbox = mysqli_real_escape_string($this->DBcon->connection,trim($_POST['Tbox']));
      $this->text = $text;
      $this->Tbox = $Tbox;
      $this->post_title = $post_title;
      $this->update_sql = sprintf("UPDATE `text_post` SET  name = '%s', post_title = '%s',
                                  posting = '%s' WHERE id = 1 ",
                        $this->text, $this->post_title, $this->Tbox);
      $this->qIns = $this->DBcon->connection->query($this->update_sql);

        if (!empty($this->text) && !empty($this->Tbox)) {
          echo "Attempting to submit post<br><br>";
          $this->qIns;
          if ($this->qIns) {
            echo "great<br>";
          }
      } else echo "Please be sure to fill out both fields.";
    }

  }//InsertText

  public function SelectTitle(){

    $this->qSelTitle = "SELECT `post_title` FROM `text_post` WHERE id =1";
    if ($result = $this->DBcon->connection->query($this->qSelTitle) ) {
      $rows = $result->fetch_assoc();;
      foreach ($rows as $row) {
        echo $row;
      }
    }

  }//SelectTitle()

  public function SelectPara(){
    $this->qSelPara = "SELECT `posting` FROM `text_post` WHERE id =1";
    if ($result = $this->DBcon->connection->query($this->qSelPara)) {
      $rows = $result->fetch_assoc();
      foreach ($rows as $row) {
        echo $row;
      }
    }
  }//SelectPara()

}//Paragraph




