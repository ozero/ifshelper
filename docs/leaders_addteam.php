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
$sql="insert into teams (name,team_type,event_id) values(
  :name, :type, :eid)";
$bind = [
  ':name'=>$_POST['arg']['name'],
  ':type'=>$_POST['arg']['type'],
  ':eid'=>$_SESSION['event']['id']
];
$new_teamid = $db->insert($sql,$bind);

//Flash message
$_SESSION['flash']['class'] = "success";
$_SESSION['flash']['message'] = "🎉 Your new team '{$_POST['arg']['name']}' was created successfully!";

header('Location: '.SITEROOT.'/team_index.php?arg[id]='.$new_teamid);
exit();
