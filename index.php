
<!-- create table comments(
  id int not null auto_increment primary key,
  comment text,
  created datetime,
  img varchar(255),
  done tinyint
); -->

<!-- php -S 192.168.33.10:8000 -t public_html/ -->


<?php


function h($s) {return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');}
  ini_set('display_errors', 1);
  define('DSN', 'mysql:dbhost=localhost;dbname=dsp');
  define('DB_USERNAME', 'root');
  define('DB_PASSWORD', '000000');
  define('SITE_URL', 'http://' . $_SERVER['HTTP_HOST']);


function db_connect(){
  $dsn = 'mysql:dbname=dsp;host=localhost;charset=utf8';
  $user = 'root';
  $password = '000000';
  $dbh = new PDO($dsn,$user,$password);
  $dbh->query('SET NAMES utf8');
  $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);
  return $dbh;
}

require_once( 'User.php');

class Index {

    private $_errors;
    private $_values;

    public function __construct() {
      if (!isset($_SESSION['token'])) {
        $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(16));
      }
      $this->_errors = new \stdClass();
      $this->_values = new \stdClass();
    }

    protected function setValues($key, $value) {
      $this->_values->$key = $value;
    }

    public function getValues() {
      return $this->_values;
    }

    protected function setErrors($key, $error) {
      $this->_errors->$key = $error;
    }
    public function getErrors($key) {
      return isset($this->_errors->$key) ?  $this->_errors->$key : '';
    }

    protected function hasError() {
      return !empty(get_object_vars($this->_errors));
    }

    protected function isLoggedIn() {

      return isset($_SESSION['me']) && !empty($_SESSION['me']);
    }
    public function me() {
      return $this->isLoggedIn() ? $_SESSION['me'] : null;
    }
    public function run() {
      if (!$this->isLoggedIn()) {
      header('Location:'.SITE_URL.'/login.php');
      exit;
}
}
}

    session_start();

    $app = new Index();
    $app->run();

 
    $errors=array();

    //if(isset($_POST['pd'])){
    if(isset($_POST['pd'])&&(isset($_POST['pd']))){
    // それぞれのフォームに投稿されたものを関数に入れる
    $comment=$_POST['pa'];
    $created=date("Y/m/d H:i:s");

    // それぞれの関数を文字列にする
    $comment=htmlspecialchars($comment,ENT_QUOTES);
    $created=htmlspecialchars($created,ENT_QUOTES);

    // 画像の保存
    $uploads_dir = 'img';
    $name = $_FILES['pc']['name'];
    $tmp_name = $_FILES['pc']['tmp_name'];
    //move_uploaded_file($_FILES['pc']['tmp_name'],$_FILES['pc']['name']);
    move_uploaded_file($tmp_name, "$uploads_dir/$name");
    // 画像のデータを関数に入れる
    $image=$_FILES['pc']['name'];
    // 文字列であることを明記する
    $image=htmlspecialchars($image,ENT_QUOTES);
    $done=$app->me()->id;

    if(count($errors)===0){
    $dbh=db_connect();

    $sql='INSERT INTO comments(comment,created,img,done)VALUES(?,?,?,?)';
    //値が、?になっている。ユーザーから入力される値を、直接SQLには入れない。

    $stmt=$dbh->prepare($sql);
    // prepareメソッドは、PDOStatement クラスのインスタンスを返します。
    // $stmt変数には、PDOStatement クラスのインスタンスへの参照値が代入されます。
    $stmt->bindValue(1,$comment,PDO::PARAM_STR);
    $stmt->bindValue(2,$created,PDO::PARAM_STR);
    $stmt->bindValue(3,$image,PDO::PARAM_STR);
    $stmt->bindValue(4,$done,PDO::PARAM_STR);
    $stmt->execute();
    $dbh=null;
    }
   }
   // $done=$app->me()->id;
   // var_dump($done);
   // // 消した瞬間にページが無くなる場合、その前のページに飛ばす
   // if($total%PER_PAGE===1){
   // // if($total%$pg===1){
   //   $page=$_POST["pp"]-1;
   // }else{
  //   $page=$_POST["pp"];
  // }
  // $page=htmlspecialchars($page,ENT_QUOTES);
  // $page=(int)$page;
  //   header("Location:?page=$page");
  // exit;
  // }


// $_GET['page']は、URLの後ろの?マーク以降に列記されたpage=の部分に相当
// 最初１ページだけだとpage=1が無いのでエラーが出る
// if (preg_match('/^[1-9][0-9]*$/',$_GET['page'])){
//   $page = (int)$_GET['page'];
// }else{
//   $page=1;
// }

   ?>
<!DOCTYPE html>
   <html lang="ja" dir="ltr">
    <head>
     <meta charset="utf-8">
     <title>コメント+画像+ページング</title>
     <link rel="stylesheet" href="styles.css">
    </head>
    <body>
      <h1>投稿フォーム</h1>
      <form action="" method="post" enctype="multipart/form-data">
        <ul>
          <li><textarea name="pa" rows="4" cols="40">本文を記入してください。</textarea></li>
          <li><span>画像</span><input type="file"name="pc">
          <li><input type="submit" name="pd"></li>
        </ul>
      </form>

     <?php  require_once( 'incont.php');?>

    <div id="container">
     <form action="logout.php" method="post" id="logout">
      <p>ログイン者情報<br>ID:<?= h($app->me()->id); ?><br>登録メールアドレス:<?= h($app->me()->email); ?></p>
      <input type="submit" value="Log Out">
      <input type="hidden" name="token" value="<?= h($_SESSION['token']); ?>">
     </form>
    </div>
   </body>
  </html>
