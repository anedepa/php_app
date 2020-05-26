<!-- create table kome(
  id int not null auto_increment primary key,
  kiji int(8),
  com text
); -->

<!-- insert into kome (kiji,com) values (1,'a'); -->

<?php
// require_once( 'User.php');
define('DB_HOST','localhost');
define('DB_USER','root');
define('DB_PASSWORD','000000');
define('DB_NAME','dsp');

function db_connect(){
  $dsn = 'mysql:dbname=dsp;host=localhost;charset=utf8';
  $user = 'root';
  $password = '000000';
  $dbh = new PDO($dsn,$user,$password);
  $dbh->query('SET NAMES utf8');
  $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);
  return $dbh;
}

error_reporting(E_ALL & ~E_NOTICE);
try{
  $dbh = new PDO
  ('mysql:host='.DB_HOST.';dbname='.DB_NAME,DB_USER,DB_PASSWORD);
}catch(PDOException $e){
  echo $e->getMessage();
  exit;
}

require_once( 'User.php');
class Indete {


    protected function isLoggedIn() {
      // $_SESSION['me']がセットされていて空じゃなかったら
      return isset($_SESSION['me']) && !empty($_SESSION['me']);
    }

    public function me() {
      return $this->isLoggedIn() ? $_SESSION['me'] : null;
    }


}

session_start();

$app = new Indete();


// $pg = $_GET['id'];
// $sql = "select * from comments WHERE id = $pg";
// $comments = array();
// foreach($dbh->query($sql)as$row){
//   array_push($comments,$row);
// }

// 投稿
$errors=array();
if(isset($_POST['pd'])){
// それぞれのフォームに投稿されたものを関数に入れる

  // $created=date("Y/m/d H:i:s");
  $kiji=$_GET['id'];
  $com=$_POST['pa'];

// それぞれの関数を文字列にする
  $kiji=htmlspecialchars($kiji,ENT_QUOTES);
  $com=htmlspecialchars($com,ENT_QUOTES);

if(count($errors)===0){
// $dbh=db_connect();

  $dbh = new PDO('mysql:dbname=dsp;host=localhost;charset=utf8','root','000000');
  $dbh->query('SET NAMES utf8');
  $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);

  $sqll='INSERT INTO kome (kiji,com)VALUES(?,?)';
  //値が、?になっています。ユーザーから入力される値を、直接SQLにはいれません。

  $stmt=$dbh->prepare($sqll);

// この数字の順番で入る
  $stmt->bindValue(1,$kiji,PDO::PARAM_STR);
  $stmt->bindValue(2,$com,PDO::PARAM_STR);

  $stmt->execute();
  $dbh=null;//データベースを切断します。
// header ('$_SERVER["REQUEST_URI"]');
}
}


// $atai=$_SERVER['QUERY_STRING'];

//
// // 削除処理
// if(isset($_POST['method'])&&($_POST['method']==='put')){
//   // if{この記事投稿者が今のログイン者なら}
//   // ログイン者ID　
//   if ($app->me()->id=$task["done"]) {
//
//   $id=$_POST["id"];
//   $id=htmlspecialchars($id,ENT_QUOTES);
//   $id=(int)$id;
//   $dbh=db_connect();
//
//   // $sqlll = "DELETE FROM kome WHERE id = $id";
//   $sqlll = "delete * from kome WHERE id = $id";
//
//   $stmt=$dbh->prepare($sqlll);
//   // $stmt->bindValue(1,$id,PDO::PARAM_INT);
//
//   $stmt->execute();
//   $dbh=null;
// }else{
//   echo "no";
// }
// }

$dbh = new PDO('mysql:dbname=dsp;host=localhost;charset=utf8','root','000000');
$dbh->query('SET NAMES utf8');
$dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);
$pg = $_GET['id'];
$sql = "select * from comments WHERE id = $pg";
$comments = array();
foreach($dbh->query($sql)as$row){
  array_push($comments,$row);
}
var_dump($task["done"]);
?>
<!DOCTYPE html>
<html lang="ja" dir="ltr">
  <head>
    <style media="screen">
    body{
      background-color:gray;
      }
.red{
  color:red;
}
.blue{
  color:blue;
}
.kome{
  border:solid;
  border-color:white;
}

img{
  max-width: 900px;
}

    </style>
    <meta charset="utf-8">
    <title>個別</title>
  </head>
  <body>
    <h1>個別ページ<?php print($_GET['id']) ?></h1>

    <form action="" method="post">
    <button type="submit"name="kijiks">記事を削除</button>
    </form>

    <form action="" method="post">
    <button type="submit"name="">記事を編集</button>
    </form>
    <!-- <ul> -->
      <?php
      print('<dl>');
        foreach($comments as $task){

   print '<dt class="red">';
   print "id=".$task["id"];
   print '</dt>';

   print '<dd>';
   print "comment=".$task["comment"];
   print '</dd>';

   print '<dd>';
   print "画像名=".$task["img"];
   print '</dd>';

   print '<dd>';
   print "投稿者ID=".$task["done"];
   print '</dd>';

     print '<dd>';
     print '<img src="../img/'.$task['img']. '" class="ind_img">';
     print '</dd>';

   print '<dd>';
   print "投稿日時：".$task["created"];
   print '</dd>';

     // print '<dd>';
     // print '
     // <form action="index.php" method="post">
     //  <input type="hidden" name="method" value="put">
     //  <input type="hidden" name="id" value="'.$task['id']. '">
     //  <button type="submit">消す</button>
     //  </form>
     //  ' ;
     //  print '</dd>';
   }
      print('</dl>');
      ?>
      <!-- </ul> -->
      <?php

      $sqlm = "select * from kome WHERE kiji = $pg";
      $commentsm = array();
      foreach($dbh->query($sqlm)as$roww){
        array_push($commentsm,$roww);
      }
       ?>
          <form action="" method="post" enctype="multipart/form-data">
            <ul>
              <li><span>コメント</span><input type="text"name="pa"</li>
              <li><input type="submit" name="pd"></li>
            </ul>
          </form>
          <!-- <div class="kome"> -->
          <?php
          $qs=$_SERVER['QUERY_STRING'];
          print('<dl>');
          foreach($commentsm as $tas){
            print '<dt class="blue">';
            print $tas["id"];
            print '</dt>';

            print '<dt class="blue">';
            print $tas["kiji"];
            print '</dt>';

            print '<dt class="blue">';
            print $tas["com"];
            print '</dt>';

            print '<dd>';
            print '
            <form action="?'.$qs.'" method="post">
            <input type="hidden" name="method" value="put">
            <input type="hidden" name="id" value="'.$tas['id']. '">
            <button type="submit">消す</button>
            </form>
            ' ;
            print '</dd>';
          }
          print('</dl>');

          ?>
          <!-- </div> -->
          <!-- <a href="/?<?php echo $_SERVER['QUERY_STRING']; ?>">index.php</a> -->
          <a href="/?page=<?php echo $_GET[page];?>&atai1=<?php echo $_GET[atai1];?>">戻る</a>
          <?php
          var_dump($task["done"]);
          var_dump($task["id"]);


          // 削除処理
          if(isset($_POST['method'])&&($_POST['method']==='put')){
            // if{この記事投稿者が今のログイン者なら}
            // ログイン者ID　
            if ($app->me()->id==$task["done"]) {
            $id=$_POST["id"];
            $id=htmlspecialchars($id,ENT_QUOTES);
            $id=(int)$id;
            $dbh=db_connect();
            $sqlll = "DELETE FROM kome WHERE id = $id";
            $stmt=$dbh->prepare($sqlll);
            // $stmt->bindValue(1,$id,PDO::PARAM_INT);
            $stmt->execute();
            $dbh=null;

            $heree=$_SERVER['QUERY_STRING'];
            header("Location:?$heree");

          }else{
            // echo "no";
            print '<h2 class="red";>';
            print "あなたはこの記事の管理者ではありません";
            print '</h2>';
            exit;
          }
          }

          if(isset($_POST['kijiks'])){

            if ($app->me()->id==$task["done"]) {

            $idd=$task["id"];
            $idd=htmlspecialchars($idd,ENT_QUOTES);
            $idd=(int)$idd;

            $ddi=$_GET["id"];
            $ddi=htmlspecialchars($ddi,ENT_QUOTES);
            $ddi=(int)$ddi;

            $dbh=db_connect();
            $sqq = "DELETE FROM comments WHERE id = $idd";
            $stmt=$dbh->prepare($sqq);
            // $stmt->bindValue(1,$id,PDO::PARAM_INT);
            $stmt->execute();


            $ssq = "DELETE FROM kome WHERE kiji = $ddi";
            $stmt=$dbh->prepare($ssq);
            $stmt->execute();

            $dbh=null;
            $ppl=$_GET['page'];
            $ppz=$_GET['atai1'];

            header("Location:/?page=$ppl&atai1=$ppz");
            // header("Location:index.php/?page=1&atai1=5");
          }else{
               print '<h2 class="red";>';
               print "記事を削除できるのは投稿者のみです";
               print '</h2>';
            exit;
          }
          }
          ?>

          <!-- <?php echo $_SERVER['QUERY_STRING']; ?> -->

  </body>
</html>
