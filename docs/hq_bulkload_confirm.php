<?php
require_once("./lib/lib.php");
priveledge($db, "hq");//priveledge: more than leaders

$_view['check'] =  getBulkload();
$_SESSION['bulk_import'] = $_view['check'];
$_view['faction'] = ["*UNDEF*","RES","ENL","XF"];
//
$_view['title'] = "Confirm: Agents Bulkloader - Head quarter";
$_view['back_href'] = "hq_bulkload.php";

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
    Confirm: Agents Bulkloader - HQ Tool - <?php e($_SESSION['event']['name']); ?>
  </h3>

  <table class="table table-bordered">
    <thead>
      <th>Agent name</th>
      <th>Faction</th>
    </thead>
    <tbody>
    <?php foreach($_view['check'] as $v0){ ?>
    <tr>
      <td><?php e($v0[0]); ?></td>
      <td><?php e($_view['faction'][$v0[1]]); ?></td>
    </tr>
    <?php } ?>
    </tbody>
  </table>
  
  <div style="text-align:center;margin-top:0.4em;">
    <a href="./hq_bulkload_do.php" class="btn btn-warning confirm"/>Bulk import these agents</a>
  </div>


  <?php require("./assets/_footer.php"); ?>


  <script>
  /* global $ */
  $(document).ready(function(){

  });
  </script>
</html>
<?php

function getBulkload(){
  $ret = [];
  $src1 = explode("\n", $_POST['arg']['src']);
  foreach($src1 as $v0){
    $v0a = explode("<%%>", trim($v0));
    if($v0a[0] == ""){
      continue;
    }
    $v0a[1] = strtoupper("".$v0a[1]);
    $faction = 0;
    switch(substr($v0a[1], 0, 1)){
      case "R":
        $faction = 1;
        break;
      case "E":
        $faction = 2;
        break;
      case "":
        break;
      default:
        continue;//弾く
    }
    $ret[] = [
      mb_convert_kana($v0a[0], 'KVsa'),
      $faction
    ];
  }
  //er([$_POST,$ret]);
  return $ret;
}
