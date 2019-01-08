<?php
require_once("./lib/lib.php");
priveledge($db, "hq");//priveledge: more than leaders

if(!isset($_SESSION['event']['id'])){
  priveledge_fail();
}

$import = $_SESSION['bulk_team_import'];
//$_SESSION['bulk_team_import'] = [];

//teams
$_teams = [];
foreach($import as $v0){
  $_teams[$v0['team_name']] = $v0;
}
$team_name_id = [];//team id key to update agent
foreach($_teams as $k0=>$v0){
  $sql_im="INSERT INTO teams (name,team_type,event_id) values(
    :name, :type, :eid)";
  $bind_im = [
    ':name'=>"{$v0['team_name']}",
    ':type'=>$v0['team_faction'],
    ':eid'=>$_SESSION['event']['id']
  ];
  $new_teamid = $db->insert($sql_im,$bind_im);
  //team id key to update agent
  $team_name_id[$v0['team_name']] = $new_teamid;
}

//agents
foreach($import as $v0){
  /*
    'team_faction'
    'team_leader'
    'team_id' //added at above code
    'agent_faction'
    'agent_name'
    'team_name'
  */
  $sql_1 = "UPDATE agents
      SET team_id = :tid
      WHERE event_id = :eid
      AND name = :name";
  $bind_1 = [
    ":tid" => $team_name_id[$v0['team_name']],
    ":eid" => $_SESSION['event']['id'],
    ":name" => trim($v0['agent_name'])
  ];
  $db->execute($sql_1, $bind_1);
}

//Flash message
$_SESSION['flash']['class'] = "success";
$_SESSION['flash']['message'] = "🚛 Loaded successfully.";

header('Location: '.SITEROOT.'/hq_index.php');
exit;
