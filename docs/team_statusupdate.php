<?php
require_once("./lib/lib.php");
priveledge($db, "leaders");//priveledge: more than leaders

if(!isset($_POST['arg'])){
  header('Location: '.SITEROOT.'/leaders_index.php');
  exit();
}
if(!isset($_SESSION['event']['id'])){
  priveledge_fail();
}

//
$sql="UPDATE teams SET status = :stat
  WHERE id = :tid";
$bind = [
  ':stat'=>$_POST['arg']['status'],
  ':tid'=>$_POST['arg']['team_id']
];
//er([$_POST,$sql,$bind]);exit;
$db->execute($sql,$bind);

//Flash message
$_SESSION['flash']['class'] = "success";
$_SESSION['flash']['message'] = "🔢 AP INPUT status was updated.";

header('Location: '.SITEROOT.'/team_index.php?arg[id]='.$_POST['arg']['team_id']);
exit();
