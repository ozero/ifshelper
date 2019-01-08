<?php

class pdoutil{
  var $db;//connection

  public function __construct(){
    try {
      $this->db = new PDO(
        DB_DSN,DB_USER,DB_PASS,
        array(PDO::ATTR_EMULATE_PREPARES => false)
      );
    } catch (PDOException $e) {
      exit('DB Connection failed: '.$e->getMessage());
    }
  }

  public function fetchAll($sql, $bind){
    /* example:
    $sql = 'SELECT name, colour, calories
        FROM fruit
        WHERE calories < :calories AND colour = :colour';*/
    $sth = $this->db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    /* example:
    $bind = array(':calories' => 150, ':colour' => 'red');
    */
    $sth->execute($bind);
    return $sth->fetchAll(PDO::FETCH_ASSOC);
  }

  public function execute($sql, $bind){
    /* example:
    $sql = 'SELECT name, colour, calories
        FROM fruit
        WHERE calories < :calories AND colour = :colour';*/
    $sth = $this->db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    /* example:
    $bind = array(':calories' => 150, ':colour' => 'red');
    */
    return $sth->execute($bind);
  }

  public function insert($sql, $bind){
    $inserted_id = false;
    $sth = $this->db->prepare($sql);
    $this->db->beginTransaction();
    try {
      $sth->execute($bind);
      // get last inserted id
      $inserted_id = $this->db->lastInsertId('id');
      // finish transaction
      $this->db->commit();

    } catch (Exception $e) {
      $this->db->rollBack();
      echo "Pdoutil: execute: exception:\n",  $e->getMessage(), "\n";
    }
    return $inserted_id;
  }

}