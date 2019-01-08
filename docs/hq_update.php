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


//update memo json
$_tmp_event = $db->fetchAll(
  "SELECT * from events where id = :eid",[
    ':eid'=>$_SESSION['event']['id']
  ]
);
$_tmp_memo = json_decode($_tmp_event[0]['memo'], true);
$_tmp_memo['tips_leaders'] = $_POST['arg']['tips_leaders'];
$_tmp_memo['tips_agents'] = $_POST['arg']['tips_agents'];
$_tmp_memo['tips_hq'] = $_POST['arg']['tips_hq'];
$_tmp_memo_json = json_encode($_tmp_memo);

//update db
$sql="UPDATE events SET
  name = :name,
  eventdate = :eventdate,
  mfrom = :mfrom,
  mto = :mto,
  available = :available,
  pass_hq = :pass_hq,
  pass_leaders = :pass_leaders,
  pass_agents = :pass_agents,
  memo = :memo
  WHERE id = :id";
$bind = [
  ':name'=>$_POST['arg']['name'],
  ':eventdate'=>$_POST['arg']['eventdate'],
  ':mfrom'=>$_POST['arg']['mfrom'],
  ':mto'=>$_POST['arg']['mto'],
  ':available'=>$_POST['arg']['available'],
  ':pass_hq'=>$_POST['arg']['pass_hq'],
  ':pass_leaders'=>str_replace("_","",$_POST['arg']['pass_leaders']),
  ':pass_agents'=>$_POST['arg']['pass_agents'],
  ':memo'=>$_tmp_memo_json,
  ':id'=>$_POST['arg']['id']
];
$db->execute($sql,$bind);

//Flash message
$_SESSION['flash']['class'] = "success";
$_SESSION['flash']['message'] = "🏕 Event setting was updated.";

header('Location: '.SITEROOT.'/hq_index.php?arg[id]='.$_POST['arg']['id']);
exit;
