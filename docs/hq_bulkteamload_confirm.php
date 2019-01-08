<?php
require_once("./lib/lib.php");
priveledge($db, "hq");//priveledge: more than leaders

$_result_bulkload_check = getBulkload($db);
$_SESSION['bulk_team_import'] = $_result_bulkload_check;

//
$_view['title'] = "Confirm: Team structure Bulkloader - Head quarter";
$_view['back_href'] = "hq_bulkteamload.php";
$_view['check'] = $_result_bulkload_check;
?>
<html>
  <?php require("./assets/_container.php"); ?>
  <style>
    textarea{
      font-size:80%;
      font-family:monospace;
    }
  </style>
  <h3>
    Confirm: Team structure Bulkloader - HQ Tool - <?php e($_SESSION['event']['name']); ?>
  </h3>


  <table class="table table-bordered">
    <thead>
      <th>Team name (Faction)</th>
      <th>Agent name (Faction)</th>
      <th>Checker Result</th>
    </thead>
    <tbody>
    <?php 
    $_has_error = 0;
    foreach($_view['check'] as $v0){
      $_has_error += (!$v0['result'])?1:0;//
    ?>
    <tr>
      <td><?php e("{$v0['team_name']} ({$_view['faction'][$v0['team_faction']]})"); ?></td>
      <td><?php e("{$v0['agent_name']} ({$_view['faction'][$v0['agent_faction']]})"); ?></td>
      <td <?php echo (!$v0['result'])?"style='color:red;'":""; ?>><?php e($v0['error_code']); ?></td>
    </tr>
    <?php } ?>
    </tbody>
  </table>
  
  <?php if($_has_error == 0){ ?>
  <div style="text-align:center;margin-top:0.4em;">
    <a href="./hq_bulkteamload_do.php" class="btn btn-warning confirm"/>Bulkload these Team structure</a>
  </div>
  <?php }else{ ?>
  <div style="text-align:center;margin-top:0.4em;">
    Please fix error above.
  </div>
  <?php } ?>


  <?php require("./assets/_footer.php"); ?>


  <script>
  /* global $ */
  $(document).ready(function(){

  });
  </script>
</html>
<?php

function getBulkload($db){
  $ret = [];
  $src1 = explode("\n", $_POST['arg']['src']);
  foreach($src1 as $v0){
    $v0a = explode("<%%>", trim($v0));
    if($v0a[0] == ""){
      continue;
    }
    //er($v0a);
    
    //faction
    $faction = 0;
    switch(substr(strtoupper("".array_shift($v0a)), 0, 1)){
      case "R":
        $faction = 1;
        break;
      case "E":
        $faction = 2;
        break;
      case "X":
        $faction = 3;
        break;
      default:
        $faction = -1;
        continue;//弾く
    }
    //team name
    $team_name = array_shift($v0a);
    
    //check
    $leader = $v0a[0];
    //er($v0a);
    foreach($v0a as $v1){
      if(trim($v1) == ""){
        continue;
      }
      $tmp_check = is_valid_agent($db, $_SESSION['event']['id'], $v1, $faction);
      $ret[] = [
        'team_faction'=>$faction,//tf
        'team_name'=>$team_name,
        'team_leader'=>$leader,//tn
        'agent_faction'=>$tmp_check[2],//af
        'agent_name'=>$v1,//an
        'result'=>$tmp_check[0],//result
        'error_code'=>$tmp_check[1],//errorcode
      ];
    }

  }
  //er([$_POST,$ret]);exit;
  return $ret;
}

function is_valid_agent($db, $event_id, $agent_name, $team_faction){
  $ret = [];
  $sql1 = "SELECT * FROM agents WHERE event_id = :eid and name = :name";
  $bind1 = [':eid'=>$_SESSION['event']['id'], ':name'=>$agent_name];
  $_tmp_result = $db->fetchAll($sql1, $bind1);
  if($_tmp_result[0]['id'] == ""){
    $ret = [false, 'Agent: Not found in DB', 0];
  }elseif(
    ($team_faction < 3)
    &&($team_faction != $_tmp_result[0]['faction'])
  ){
    $ret = [false, 'Agent: WRONG FACTION', $_tmp_result[0]['faction']];
  }else{
    $ret = [true, "OK", $_tmp_result[0]['faction']];
  }
  //er(["is_valid_agent", $event_id, $agent_name, $team_faction, $_tmp_result, $ret]);
  return $ret;
}