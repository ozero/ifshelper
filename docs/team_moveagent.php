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
$sql="UPDATE agents SET team_id = :tid WHERE id = :aid";
$bind = [
  ':tid'=>$_POST['arg']['team_id'],
  ':aid'=>$_POST['arg']['agent_id']
];
//er([$_POST, $sql, $bind]);exit;
$db->execute($sql,$bind);

//Flash message
$_SESSION['flash']['class'] = "success";
$_SESSION['flash']['message'] = "➡️ New member was moved to your team!";

//
header('Location: '.SITEROOT.'/team_index.php?arg[id]='.$_POST['arg']['team_id']);
exit();
