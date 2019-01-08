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
$sql="DELETE FROM agents WHERE id = :aid";
$bind = [
  ':aid'=>$_POST['arg']['id']
];
//er([$_POST,$sql,$bind]);exit;
$db->execute($sql,$bind);

//Flash message
$_SESSION['flash']['class'] = "info";
$_SESSION['flash']['message'] = "👍 Agent was Deleted.";

header('Location: '.SITEROOT.'/leaders_index.php');
exit();


//Flash message
$_SESSION['flash']['class'] = "info";
$_SESSION['flash']['message'] = "🚧 Agent was erased.";

header('Location: '.SITEROOT.'/team_index.php?arg[id]='.$_POST['arg']['team_id']);
exit;
