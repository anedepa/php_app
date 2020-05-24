<?php

ini_set('display_errors', 1);
define('DSN', 'mysql:dbhost=localhost;dbname=dsp');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '000000');
define('SITE_URL', 'http://' . $_SERVER['HTTP_HOST']);

require_once( 'User.php');

class Login {
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
      // $_SESSION['me']がセットされていて空じゃなかったら
      return isset($_SESSION['me']) && !empty($_SESSION['me']);
    }

    public function me() {
      return $this->isLoggedIn() ? $_SESSION['me'] : null;
    }



  public function run() {
    if ($this->isLoggedIn()) {
      header('Location: ' . SITE_URL);
      exit;
    }


    if(isset($_POST['pd'])){
      $this->postProcess();

    }
  }

  protected function postProcess() {
    try {
      $this->_validate();
    } catch (\MyApp\Exception\EmptyPost $e) {
    // } catch (\EmptyPost $e) {
      $this->setErrors('login', $e->getMessage());
    }
    $this->setValues('email', $_POST['email']);

        if ($this->hasError()) {
          return;
        } else {
          try {

            $userModel = new User();

            $user = $userModel->login([
              'email' => $_POST['email'],
              'password' => $_POST['password']
            ]);

          } catch (\Exception $e) {

            $this->setErrors('login', $e->getMessage());
            return;

          }

          // login処理
          // セッションハイジャック対策
          session_regenerate_id(true);
          $_SESSION['me'] = $user;
          // redirect to home　index.phpの１ページ目に飛ぶ
          header('Location:/?page=1&atai1=5');
          // header('Location:index.php/?page=1&atai1=5');
          exit;
        }
      }
      private function _validate() {
        $atai=$_SERVER['QUERY_STRING'];
    if (!isset($_POST['token']) || $_POST['token'] !== $_SESSION['token']) {
      echo "Invalid Token!";
      print '
       <a href="/?'.$atai. '">
       戻る
       </a>
       ' ;
      exit;
    }

    if (!isset($_POST['email']) || !isset($_POST['password'])) {
      echo "Invalid Form!";
      print '
       <a href="/?'.$atai. '">
       戻る
       </a>
       ' ;
      exit;
    }

    if ($_POST['email'] === '' || $_POST['password'] === '') {
      // throw new \MyApp\Exception\EmptyPost();
      echo "入力してください";
      print '
       <a href="/?'.$atai. '">
       戻る
       </a>
       ' ;
      exit;
    }
  }

}


session_start();


$app = new login();
$app->run();


 function h($s) {
  return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title>Log In</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
<h1>ログイン</h1>

  <div id="container">
    <form action="" method="post" id="login">
      <p>
        <input type="text" name="email" placeholder="email" value="<?= isset($app->getValues()->email) ? h($app->getValues()->email) : ''; ?>">

      </p>
    <p>
      <input type="password" name="password" placeholder="password">
    </p>
    <p class="err"><?= h($app->getErrors('login')); ?></p>

    <input type="submit" name="pd">

    <p class="fs12"><a href="signup.php">新規登録はこちら</a></p>
    <input type="hidden" name="token" value="<?= h($_SESSION['token']); ?>">
  </form>
</div>
<p>kida@gmail.com lll</p>

<?php  require_once( 'cont.php');?>

</body>
</html>
