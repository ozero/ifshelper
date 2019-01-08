<?php
require_once("./lib/lib.php");
priveledge($db, "hq");//priveledge: more than leaders

if(!isset($_SESSION['event']['id'])){
  priveledge_fail();
}

$import = $_SESSION['bulk_import'];
$_SESSION['bulk_import'] = [];

foreach($import as $v0){
  //prevent duplicate agent name
  $sql_0 = "SELECT id FROM agents WHERE event_id = :eid and name = :name";
  $bind_0 = [
    ":eid" => $_SESSION['event']['id'],
    ":name" => trim($v0[0])
  ];
  $tmp = $db->fetchAll($sql_0, $bind_0);
  if(isset($tmp[0]['id'])){
    continue;
  }
  
  //
  $sql_1 = "INSERT INTO agents (
      team_id, event_id,name,faction,
      lvfrom,lvto,apfrom,apto,trfrom,trto
      ) values(-1, :e,:n,:f, 0,0, 0,0, 0,0 )";
  $bind_1 = [
    ":e" => $_SESSION['event']['id'],
    ":n" => trim($v0[0]),
    ":f" => $v0[1]
  ];
  //er([$v0, $sql_1, $bind_1]);
  $db->insert($sql_1, $bind_1);
}

//Flash message
$_SESSION['flash']['class'] = "success";
$_SESSION['flash']['message'] = "🚛 Loaded successfully.";

header('Location: '.SITEROOT.'/hq_index.php');
exit;
