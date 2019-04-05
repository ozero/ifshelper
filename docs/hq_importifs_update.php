<?php
require_once("./lib/lib.php");
priveledge($db, "hq");//priveledge: more than leaders

if(!isset($_POST['arg'])){
  header('Location: '.SITEROOT.'/index.php');
  exit();
}
if(!isset($_SESSION['event']['id'])){
  priveledge_fail();
}

ob_start();
//print "<pre>";print_r($_POST);print_r($_SESSION);//exit;

$ifs = new importifs($db, $_SESSION['event']['id']);
//
$members = $ifs->processData(
  $_POST['arg']['enl1'], $_POST['arg']['res1'],
  $_POST['arg']['enl2'], $_POST['arg']['res2']);
er(['members',$members]);

$team_name_idlist = $ifs->insert_teams();
er(['team_name_idlist',$team_name_idlist]);
//
$ifs->build_team($team_name_idlist, $members);

//exit;
ob_end_clean();

//Flash message
$_SESSION['flash']['class'] = "success";
$_SESSION['flash']['message'] = "🏕 Succesfully imported from Fevgames.";

header('Location: '.SITEROOT.'/hq_index.php?arg[id]='.$_POST['arg']['id']);
exit;


//-------------------

class importifs{
  var $db;
  var $event_id;

  public function __construct($db, $event_id){
    $this->db = $db;
    $this->event_id = $event_id;

    /*
    $this->db->execute(
      "DELETE FROM agents WHERE event_id = :eid",
      [":eid" => $this->event_id]
    );
    $this->db->execute(
      "DELETE FROM teams WHERE event_id = :eid",
      [":eid" => $this->event_id]
    );
    */
  }

  //ペーストされたデータを整形&Insert
  public function processData($elead, $rlead, $eatt, $ratt){

    $ret = [];
    //build list
    $e1 = explode("\n",$eatt);
    $e1[] = trim($elead);
    sort($e1);
    $r1 = explode("\n",$ratt);
    $r1[] = trim($rlead);
    sort($r1);
    //check
    foreach($e1 as $k0=>$v0){
      $v0 = mb_convert_kana(trim($v0), "a");
      $e1[$k0] = $v0;
      $len = mb_strlen($v0, "UTF-8");
      $wdt = mb_strwidth($v0, "UTF-8");
      if($len != $wdt){
        print "使用できないエージェント名です(ENL)：{$v0}\n\n";exit;
      }
    }
    foreach($r1 as $k0=>$v0){
      $v0 = mb_convert_kana(trim($v0), "a");
      $r1[$k0] = $v0;
      $len = mb_strlen($v0, "UTF-8");
      $wdt = mb_strwidth($v0, "UTF-8");
      if($len != $wdt){
        print "使用できないエージェント名です(RES)：{$v0}\n\n";exit;
      }
    }
    //insert ENL
    foreach($e1 as $k0=>$v0){
      //er([$k0, $v0]);
      $tmp_ag = trim($v0);
      $this->insert_agent(2, $tmp_ag);
    }
    //insert RES
    foreach($r1 as $k0=>$v0){
      $tmp_ag = trim($v0);
      $this->insert_agent(1, $tmp_ag);
    }
    //
    $ret = ['e'=>$e1, 'r'=>$r1];
    return $ret;
  }

  //チーム一覧をInsertする
  public function insert_teams(){
    $db=$this->db;
    $event_id = $_SESSION['event']['id'];
    //名前別XFチームをつくる
    $teams = [
      "0-9で始まる名前の方",
      "Aで始まる名前の方",
      "Bで始まる名前の方",
      "Cで始まる名前の方",
      "Dで始まる名前の方",
      "Eで始まる名前の方",
      "Fで始まる名前の方",
      "Gで始まる名前の方",
      "Hで始まる名前の方",
      "Iで始まる名前の方",
      "Jで始まる名前の方",
      "Kで始まる名前の方",
      "Lで始まる名前の方",
      "Mで始まる名前の方",
      "Nで始まる名前の方",
      "Oで始まる名前の方",
      "Pで始まる名前の方",
      "Qで始まる名前の方",
      "Rで始まる名前の方",
      "Sで始まる名前の方",
      "Tで始まる名前の方",
      "Uで始まる名前の方",
      "Vで始まる名前の方",
      "Wで始まる名前の方",
      "Xで始まる名前の方",
      "Yで始まる名前の方",
      "Zで始まる名前の方"
    ];
    $team_name_id = [];
    foreach($teams as $k0=>$v0_name){

      //prevent duplicate team name
      $sql_0 = "SELECT id FROM teams WHERE event_id = :eid and name = :name";
      $bind_0 = [
        ":eid" => $this->event_id,
        ":name" => trim($v0_name)
      ];
      $tmp = $this->db->fetchAll($sql_0, $bind_0);
      if(isset($tmp[0]['id'])){
        er(["ERROR: insert_teams: prevent duplicate team name.", $event_id, $faction, $v0_name]);
        $team_name_id[strtolower(substr($v0_name,0,1))] = ['id'=>$tmp[0]['id'], 'name'=>$v0_name];
        continue;
      }

      //
      $sql_im="INSERT INTO teams (name,team_type,event_id) values(
        :name, :type, :eid)";
      $bind_im = [
        ':name'=>$v0_name,
        ':type'=>3,
        ':eid'=>$event_id
      ];
      $new_teamid = $db->insert($sql_im,$bind_im);
      //team id key to update agent
      $team_name_id[strtolower(substr($v0_name,0,1))] = ['id'=>$new_teamid, 'name'=>$v0_name];
    }
    return $team_name_id;
  }

  //エージェントたちをチームに割り当てる
  public function build_team($team_name_ids, $members){

    //名前見てチームIDを書いてupdate
    foreach($members as $k0_fac=>$v0_ags){
      foreach($v0_ags as $v1_ag){
        if(trim($v1_ag) == ""){continue;}
        $_initChar = strtolower(substr($v1_ag, 0, 1));
        $_initChar = (is_numeric($_initChar))?"0":$_initChar;//0-9は0に
        $_tmp_team = $team_name_ids[$_initChar];
        $sql_1 = "UPDATE agents
            SET team_id = :tid
            WHERE event_id = :eid
            AND name = :name";
        $bind_1 = [
          ":tid" => $_tmp_team['id'],
          ":eid" => $this->event_id,
          ":name" => $v1_ag
        ];
        er(["build_team", $sql_1, $bind_1]);
        $this->db->execute($sql_1, $bind_1);
      }
    }
    return;
  }

  //Agent エンティティをinsert
  private function insert_agent($faction, $name){
    //
    if(trim($name) == ""){return;}

    //prevent duplicate agent name
    $sql_0 = "SELECT id FROM agents WHERE event_id = :eid and name = :name";
    $bind_0 = [
      ":eid" => $this->event_id,
      ":name" => trim($name)
    ];
    $tmp = $this->db->fetchAll($sql_0, $bind_0);
    if(isset($tmp[0]['id'])){
      er(["ERROR: insert_agent: prevent duplicate agent name.", $this->event_id, $faction, $name]);
      return;
    }

    //
    $sql_1 = "INSERT INTO agents (
        team_id, event_id,name,faction,
        lvfrom,lvto,apfrom,apto,trfrom,trto
        ) values(-1, :e,:n,:f, 0,0, 0,0, 0,0 )";
    $bind_1 = [
      ":e" => $this->event_id,
      ":n" => trim($name),
      ":f" => $faction
    ];
    //er([$v0, $sql_1, $bind_1]);
    $this->db->insert($sql_1, $bind_1);
    return;
  }

}

