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
$sql="UPDATE teams SET name = :name, team_type=:type
  WHERE id = :tid";
$bind = [
  ':name'=>$_POST['arg']['name'],
  ':type'=>$_POST['arg']['team_type'],
  ':tid'=>$_POST['arg']['id']
];
$db->execute($sql,$bind);

//Flash message
$_SESSION['flash']['class'] = "success";
$_SESSION['flash']['message'] = "👍 Team setting was updated.";

header('Location: '.SITEROOT.'/team_index.php?arg[id]='.$_POST['arg']['id']);
exit();
