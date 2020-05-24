<?php

// namespace MyApp\Model;




// class User extends \MyApp\Model {
class User {
  // class Model {
    protected $db;
    public function __construct() {
      try {
        $this->db = new \PDO(DSN, DB_USERNAME, DB_PASSWORD);
      } catch (\PDOException $e) {
        echo $e->getMessage();
        exit;
      }
    }
  // }

  public function create($values) {
    $stmt = $this->db->prepare("insert into users (email, password, created, modified) values (:email, :password, now(), now())");
    $res = $stmt->execute([
      ':email' => $values['email'],
      ':password' => password_hash($values['password'], PASSWORD_DEFAULT)
    ]);
    if ($res === false) {
      // throw new \MyApp\Exception\DuplicateEmail();
      echo "DuplicateEmail!";
      exit;
    }
  }
  public function login($values) {
    $stmt = $this->db->prepare("select * from users where email = :email");
    $stmt->execute([
      ':email' => $values['email']
    ]);
    $stmt->setFetchMode(\PDO::FETCH_CLASS, 'stdClass');
    $user = $stmt->fetch();

    if (empty($user)) {
      // throw new \MyApp\Exception\UnmatchEmailOrPassword();
      echo "Invalid!";
      exit;
      // throw new UnmatchEmailOrPassword();
      // throw UnmatchEmailOrPassword();
    }
    if (!password_verify($values['password'], $user->password)) {
          // throw new \MyApp\Exception\UnmatchEmailOrPassword();
          echo "Unmatch!";
          exit;
        }

        return $user;
      }

      public function findAll() {
        $stmt = $this->db->query("select * from users order by id");
        $stmt->setFetchMode(\PDO::FETCH_CLASS, 'stdClass');
        return $stmt->fetchAll();
      }
    }
