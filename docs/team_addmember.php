<?php
require_once("./lib/lib.php");
priveledge($db, "leaders");//priveledge: more than leaders

if(!isset($_POST['arg'])){
  header('Location: '.SITEROOT.'/leader_index.php');
  exit();
}
if(!isset($_SESSION['event']['id'])){
  priveledge_fail();
}

//
$sql="insert into agents (name,team_id,event_id,faction) values(
  :name, :tid, :eid, :faction)";
$bind = [
  ':name'=>$_POST['arg']['name'],
  ':tid'=>$_POST['arg']['team_id'],
  ':eid'=>$_SESSION['event']['id'],
  ':faction'=>$_POST['arg']['faction']
];
$new_memberid = $db->insert($sql,$bind);

//Flash message
$_SESSION['flash']['class'] = "success";
$_SESSION['flash']['message'] = "🙋 '{$_POST['arg']['name']}' joined to your team! Input details.";

//
header('Location: '.SITEROOT.'/member_index.php?arg[id]='.$new_memberid);
exit();
