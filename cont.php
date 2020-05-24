<?php

define('DB_HOST','localhost');
define('DB_USER','root');
// define('DB_PASSWORD','000000');
define('DB_NAME','dsp');
// define('DSN', 'mysql:dbhost=localhost;dbname=dsp');


//  function h($s) {
//   return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
// }

error_reporting(E_ALL & ~E_NOTICE);

try{
  $dbh = new PDO
  ('mysql:host='.DB_HOST.';dbname='.DB_NAME,DB_USER,DB_PASSWORD);
}catch(PDOException $e){
  echo $e->getMessage();
  exit;
}

// 値の制御
if(!isset($_GET['page'])){
  header("Location:?page=1&atai1=5");
  // header("Location:?page=1&atai1=5");
}else{
  if(preg_match('/^[1-9][0-9]*$/',$_GET['page'])){
    // ^	論理行頭  $論理行末
     $page = (int)$_GET['page'];
   }else{
     $page = 1;
   }
}


$PER_PAGE=$_GET['atai1'];
// $PER_PAGE=5;
// var_dump($page);

$paged=$page-1;
$pageu=$page+1;

$offset = $PER_PAGE*($page-1);
// var_dump($offset);
$pgg = $PER_PAGE;
// $sql = "select * from comments WHERE id = 1";
$sqlin = "select * from comments limit $offset,$pgg";
// $dbh=db_connect();
$comments = array();
// まず空の配列を作る
foreach($dbh->query($sqlin)as$row){
  // foreachで１つずつとりだしてrowという変数に突っ込む
  array_push($comments,$row);
  // pushでrowを配列に追加
}


function url_param_change($par=Array(),$op=0){
    $url = parse_url($_SERVER["REQUEST_URI"]);
    if(isset($url["query"])) parse_str($url["query"],$query);
    else $query = Array();
    foreach($par as $key => $value){
        if($key && is_null($value)) unset($query[$key]);
        else $query[$key] = $value;
    }
    $query = str_replace("=&", "&", http_build_query($query));
    $query = preg_replace("/=$/", "", $query);
    return $query ? (!$op ? "?" : "").htmlspecialchars($query, ENT_QUOTES) : "";
}

$total = $dbh->query("select count(*)from comments")->fetchColumn();
$totalPages = ceil($total/$PER_PAGE);
$from=$offset+1;
$to=($offset+$PER_PAGE)<$total ?($offset+$PER_PAGE):$total;



?>


    <h1>値保持+２つのテーブル+ページング</h1>

    <?php $url_param = url_param_change(Array("atai1"=>"5")); ?>
    <a href="<?php echo $url_param; ?>">5ずつ表示</a>

    <?php $url_param = url_param_change(Array("atai1"=>"3")); ?>
    <a href="<?php echo $url_param; ?>">3ずつ表示</a>



    <a href="another.php/?<?php echo $_SERVER['QUERY_STRING'];  ?>">link</a>
    <ul>
      <?php

      $stmt=$dbh->prepare($sqlin);
      $stmt->execute();
      $atai=$_SERVER['QUERY_STRING'];

      print('<dl>');
      while($task=$stmt->fetchAll(PDO::FETCH_ASSOC)){
        foreach($comments as $task){
if($task['pd']!=='0'){

  print '<div class="diw">';
  // print '<a href="another.php/?'.$atai. '&id='.$task['id']. '"class="diw">';

  print '<dt>';
  print "id=".$task["id"];
  print '</dt>';


  print '<dd>';
  print "comment=".$task["comment"];
  print '</dd>';


  print '<dd>';
  print '<img src="'.$task['img']. '">';
  print '</dd>';

  print '
   <a href="another.php/?'.$atai. '&id='.$task['id']. '">
   詳しく見る
   </a>
   ' ;

   // $dbh = new PDO('mysql:dbname=paging;host=localhost;charset=utf8','dbuser','888888');
   $dbh = new PDO('mysql:dbname=dsp;host=localhost;charset=utf8','root','000000');
   $pg = $task['id'];
   $sqlm = "select * from kome WHERE kiji = $pg";
   $commentsm = array();
   foreach($dbh->query($sqlm)as$roww){
     array_push($commentsm,$roww);
   }
   foreach($commentsm as $tas){
     print '<div class="stream">';

     print $tas["com"];

     print '</div>';

   }
print '</div>';
  // print '</a>';
}
}
        }

      print('</dl>');
      ?>
      </ul>

      <div class="pp">

      </div>

          <?php if ($page>1):?>
            <?php $url_param = url_param_change(Array("page"=>"$paged")); ?>
          <a href="<?php echo $url_param; ?>">前へ</a>
        <?php endif; ?>

          <?php for ($i = 1; $i <=$totalPages;$i++):?>
                <?php if ($page==$i):?>
                  <?php $url_param = url_param_change(Array("page"=>"$i")); ?>
            <strong><a href="<?php echo $url_param; ?>"><?php echo $i; ?></a></strong>
          <?php else: ?>
<?php $url_param = url_param_change(Array("page"=>"$i")); ?>
      <a href="<?php echo $url_param; ?>"><?php echo $i; ?></a>
          <?php endif;?>
          <?php endfor; ?>

          <?php if ($page<$totalPages):?>
            <?php $url_param = url_param_change(Array("page"=>"$pageu")); ?>
            <a href="<?php echo $url_param; ?>">次へ</a>
        <?php endif;?>

        <script src="http://code.jquery.com/jquery-1.9.0rc1.js"></script>
       <script>
       $('.diw').hover(
        function() {
              $(this).addClass('aka');
        },
        function() {
            $(this).removeClass('aka');
        }
       );
       $('.diw').hover(
        function() {
              $(this).children('.stream').addClass('sl');
        },
        function() {
            $(this).children('.stream').removeClass('sl');
        }
       );

       </script>
