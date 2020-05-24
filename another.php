<!-- create table kome(
  id int not null auto_increment primary key,
  kiji int(8),
  com text
); -->

<!-- insert into kome (kiji,com) values (1,'a'); -->

<?php
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


$dbh = new PDO('mysql:dbname=dsp;host=localhost;charset=utf8','root','000000');
$dbh->query('SET NAMES utf8');
$dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);
$pg = $_GET['id'];
$sql = "select * from comments WHERE id = $pg";
$comments = array();
foreach($dbh->query($sql)as$row){
  array_push($comments,$row);
}

// $atai=$_SERVER['QUERY_STRING'];
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
       
        width:300px;
      }

    </style>
    <meta charset="utf-8">
    <title>個別</title>
  </head>
  <body>
    <h1>個別ページ<?php print($_GET['id']) ?></h1>
<!-- <img src="curry.jpg" alt="ccu">
<img src="../curry.jpg" alt="ccu"> -->
    <!-- <ul> -->
      <?php
      print('<dl>');
        foreach($comments as $task){

   print '<dt class="red";>';
   print "id=".$task["id"];
   print '</dt>';

   print '<dd>';
   print "comment=".$task["comment"];
   print '</dd>';
  

   print '<dd>';
   print '<img src="../'.$task['img']. '"alt="'.$task['img'].'">';
   print '</dd>';

   print '<dd>';
   print "投稿日時：".$task["created"];
   print '</dd>';


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
          print('<dl>');
          foreach($commentsm as $tas){
            print '<dt class="blue">';
            print $tas["kiji"];
            print '</dt>';

            print '<dt class="blue">';
            print $tas["com"];
            print '</dt>';
          }
          print('</dl>');

          ?>
          <!-- </div> -->
          <!-- <a href="/?<?php echo $_SERVER['QUERY_STRING']; ?>">index.php</a> -->
          <a href="/login.php?page=<?php echo $_GET[page];?>&atai1=<?php echo $_GET[atai1];?>">戻る</a>

  </body>
</html>
