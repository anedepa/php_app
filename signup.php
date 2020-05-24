<?php

// 新規登録

ini_set('display_errors', 1);


define('DSN', 'mysql:dbhost=localhost;dbname=dsp');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '000000');

define('SITE_URL', 'http://' . $_SERVER['HTTP_HOST']);


require_once( 'User.php');

class Signup {


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

// これのおかげで、ログイン状態の場合画面変遷できず必ずホームに来る
  public function run() {
    if ($this->isLoggedIn()) {
      header('Location: ' . SITE_URL);
      exit;
    }

    // if ($_SERVER['REQUEST_METHOD'] === 'POST') {
if(isset($_POST['pe'])){
      $this->postProcess();
    }
  }

  protected function postProcess() {

    try {
      $this->_validate();
    // } catch (\MyApp\Exception\InvalidEmail $e) {
    } catch (\MyApp\Exception $e) {
      echo $e->getMessage();
      exit;
      $this->setErrors('email', $e->getMessage());

    // } catch (\MyApp\Exception\InvalidPassword $e) {
      } catch (\MyApp\Exception $e) {

      $this->setErrors('password', $e->getMessage());
    }

    // echo "success";
    //     exit;

        $this->setValues('email', $_POST['email']);

        if ($this->hasError()) {
          return;
        } else {
          // create user
          try {
            // $userModel = new \MyApp\Model\User();
            $userModel = new User();
            $userModel->create([
              'email' => $_POST['email'],
              'password' => $_POST['password']
            ]);
          // } catch (\MyApp\Exception\DuplicateEmail $e) {
          } catch (\MyApp\Exception $e) {
            $this->setErrors('email', $e->getMessage());
            return;
          }


          header('Location: ' . SITE_URL . '/login.php');
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

          if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            
            echo "InvalidEmail!";
            print '
             <a href="/?'.$atai. '">
             戻る
             </a>
             ' ;
            exit;
          }

          if (!preg_match('/\A[a-zA-Z0-9]+\z/', $_POST['password'])) {
            // throw new \MyApp\Exception\InvalidPassword();
            echo "InvalidPassword!";
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

// $app = new MyApp\Controller\Signup();
$app = new Signup();
$app->run();


function h($s) {
  return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

// echo __DIR__;
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title>Sign Up</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
<h1>新規登録</h1>
  <div id="container">
    <form action="" method="post" id="signup">
      <p>
        <input type="text" name="email" placeholder="email" value="<?= isset($app->getValues()->email) ? h($app->getValues()->email) : ''; ?>">

      </p>
    <p class="err"><?= h($app->getErrors('email')); ?></p>
    <p>
      <input type="password" name="password" placeholder="password">
    </p>
    <p class="err"><?= h($app->getErrors('password')); ?></p>
    <!-- <div class="btn" onclick="document.getElementById('signup').submit();">Sign Up</div> -->
    <input type="submit" name="pe">
    <p class="fs12"><a href="login.php">ログインフォームへ</a></p>
    <input type="hidden" name="token" value="<?= h($_SESSION['token']); ?>">
  </form>
</div>
<?php  require_once( 'cont.php');?>
</body>
</html>
