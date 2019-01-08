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
$sql="UPDATE agents SET
  name = :name,
  faction = :faction,
  lvfrom = :lvfrom,
  lvto = :lvto,
  apfrom = :apfrom,
  apto = :apto,
  trfrom = :trfrom,
  trto = :trto,
  memo = :memo
  WHERE id = :id";
$bind = [
  ':name'=>$_POST['arg']['name'],
  ':faction'=>$_POST['arg']['faction'],
  ':lvfrom'=>$_POST['arg']['lvfrom'],
  ':lvto'=>$_POST['arg']['lvto'],
  ':apfrom'=>$_POST['arg']['apfrom'],
  ':apto'=>$_POST['arg']['apto'],
  ':trfrom'=>$_POST['arg']['trfrom'],
  ':trto'=>$_POST['arg']['trto'],
  ':memo'=>$_POST['arg']['memo'],
  ':id'=>$_POST['arg']['id']
];
$db->execute($sql,$bind);

//Flash message
$_SESSION['flash']['class'] = "success";
$_SESSION['flash']['message'] = "🌟 Stats was updated successfully!";

header('Location: '.SITEROOT.'/member_index.php?arg[id]='.$_POST['arg']['id']);
exit;
