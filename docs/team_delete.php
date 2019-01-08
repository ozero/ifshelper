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
$sql="DELETE FROM teams WHERE id = :tid";
$bind = [
  ':tid'=>$_POST['arg']['id']
];
//er([$_POST,$sql,$bind]);exit;
$db->execute($sql,$bind);

//Flash message
$_SESSION['flash']['class'] = "info";
$_SESSION['flash']['message'] = "👍 Team was Deleted.";

header('Location: '.SITEROOT.'/leaders_index.php');
exit();
