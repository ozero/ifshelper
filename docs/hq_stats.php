<?php
require_once("./lib/lib.php");
priveledge($db, "agents");//priveledge: more than agents

//
$_view['total'] = getFactionTotal($db);
$_view['teams'] = getTeams($db);
$_view['title'] = "Stats Total";
$_view['back_href'] = "index.php";

?>
<html>
  <?php require("./assets/_container.php"); ?>
  <link
    href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css"
    rel="stylesheet">
  <script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>

  <h3>
    Stats Total - <?php e($_SESSION['event']['name']); ?>
  </h3>

  <h4>Faction Total</h4>
  <table id="dv_faction" class="table table-bordered">
    <thead>
    <tr>
      <td>Faction</td>
      <td>Lv+</td>
      <td>AP+</td>
      <td>Tr+</td>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td>RESISTANCE</td>
      <td class="num"><?php e(number_format($_view['total'][1]['lvgain']));?></td>
      <td class="num"><?php e(number_format($_view['total'][1]['apgain']));?></td>
      <td class="num"><?php e(number_format($_view['total'][1]['trgain']));?></td>
    </tr>
    <tr>
      <td>ENLIGHTENED</td>
      <td class="num"><?php e(number_format($_view['total'][2]['lvgain']));?></td>
      <td class="num"><?php e(number_format($_view['total'][2]['apgain']));?></td>
      <td class="num"><?php e(number_format($_view['total'][2]['trgain']));?></td>
    </tr>
    <tr>
      <td>TOTAL</td>
      <td class="num"><?php e(number_format(
        $_view['total'][1]['lvgain']+$_view['total'][2]['lvgain']
      ));?></td>
      <td class="num"><?php e(number_format(
        $_view['total'][1]['apgain']+$_view['total'][2]['apgain']
      ));?></td>
      <td class="num"><?php e(number_format(
        $_view['total'][1]['trgain']+$_view['total'][2]['trgain']
      ));?></td>
    </tr>
    </tbody>
  </table>

  <h4>Teams</h4>
  <table id="dv_teams" class="table table-bordered">
    <thead>
    <tr>
      <td>#</td>
      <td>Name</td>
      <td>Type</td>
      <td>Lv+</td>
      <td>AP+</td>
      <td>Tr+</td>
    </tr>
    </thead>
    <tbody>
    <?php foreach($_view['teams'] as $v0){ ?>
    <tr>
      <td></td>
      <td><?php e($v0['info']['name']);?></td>
      <td><?php e($_view['faction'][$v0['info']['team_type']]);?></td>
      <td class="num"><?php e(number_format($v0['stat']['lvgain']));?></td>
      <td class="num"><?php e(number_format($v0['stat']['apgain']));?></td>
      <td class="num"><?php e(number_format($v0['stat']['trgain']));?></td>
    </tr>
    <?php } ?>
    </tbody>
  </table>

  <?php require("./assets/_footer.php"); ?>

  <script>
  /* global $ */
  $(document).ready(function(){
    var t = $('#dv_teams').DataTable({
      paging: false
    });
    t.on( 'order.dt search.dt', function () {
        t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();
  });
  </script>
</html>
<?php

//Total by faction
function getFactionTotal($db){
  $ret = [];
  $sql = "SELECT
    faction,
    SUM(lvgain) as lvgain,
    SUM(apgain) as apgain,
    SUM(trgain) as trgain
    FROM (
      SELECT
      id,
      faction,
      lvto - lvfrom as lvgain,
      apto - apfrom as apgain,
      trto - trfrom as trgain
      FROM agents
      WHERE event_id = :eid
    ) facgain
    GROUP BY faction
  ";
  $bind = [':eid' => $_SESSION['event']['id']];
  $_tmp_total = $db->fetchAll($sql, $bind);

  //
  foreach($_tmp_total as $v0){
    $ret[$v0['faction']] = $v0;
  }
  return $ret;
}

//Total by team
function getTeams($db){
  $ret = [];

  $sql = "SELECT * from teams where event_id = :eid";
  $bind = [':eid' => $_SESSION['event']['id']];
  $teams_0 = $db->fetchAll($sql, $bind);

  foreach($teams_0 as $v0){
    $sql_stat = "SELECT
      SUM(lvgain) as lvgain,
      SUM(apgain) as apgain,
      SUM(trgain) as trgain
      FROM (
        SELECT
        team_id,
        lvto - lvfrom as lvgain,
        apto - apfrom as apgain,
        trto - trfrom as trgain
        FROM agents
        WHERE team_id = :tid
        AND event_id = :eid
      ) gain
      GROUP BY team_id
    ";
    $bind_stat = [
      ':eid'=>$_SESSION['event']['id'],
      ':tid'=>$v0['id']
    ];
    $_tmp_teamstat = $db->fetchAll($sql_stat, $bind_stat);

    //
    $ret[$v0['id']] = [
      'info'=>$v0,
      'stat'=>$_tmp_teamstat[0]
    ];
  }
  return $ret;
}