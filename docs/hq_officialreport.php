<?php
require_once("./lib/lib.php");
priveledge($db, "agents");//priveledge: more than agents

//
$_view['agents'] = getAgents($db);

$_view['title'] = "Stats Total";
$_view['back_href'] = "index.php";


// https://docs.google.com/spreadsheets/d/1a8W5nXgWA_PcEAPmsyEY6mconokyeLXdBwSY96UJ6w0/edit#gid=0

?>
<html>
  <?php require("./assets/_container.php"); ?>
  <link
    href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css"
    rel="stylesheet">
  <script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>

  <h3>
    Agents Ranking - <?php e($_SESSION['event']['name']); ?>
  </h3>

  <table id="dv_agents" class="table table-bordered">
    <thead>
    <tr>
      <td>#</td>
      <td>Agent</td>
      <td>Faction</td>
      <td>Team</td>
      <td>Lv+</td>
      <td>AP+</td>
      <td>Tr+</td>
    </tr>
    </thead>
    <tbody>
    <?php foreach($_view['agents'] as $v0){ ?>
    <tr>
      <td></td>
      <td><?php e($v0['a_name']);?></td>
      <td><?php e($_view['faction'][$v0['a_faction']]);?></td>
      <td><?php e($v0['t_name']);?></td>
      <td class="num"><?php e(number_format($v0['lvgain']));?></td>
      <td class="num"><?php e(number_format($v0['apgain']));?></td>
      <td class="num"><?php e(number_format($v0['trgain']));?></td>
    </tr>
    <?php } ?>
    </tbody>
  </table>

  <?php require("./assets/_footer.php"); ?>

  <script>
    /* global $ */
    $(document).ready(function(){
      var t = $('#dv_agents').DataTable({
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

//Agents Ranking (Sort )
function getAgents($db){
  $ret = [];
  $sql = "SELECT *,
    a.id as a_id,
    t.id as t_id,
    a.name as a_name,
    t.name as t_name,
    a.faction as a_faction,
    (lvto - lvfrom) as lvgain,
    (apto - apfrom) as apgain,
    (trto - trfrom) as trgain
    FROM agents a
    LEFT JOIN teams t ON a.team_id = t.id
    WHERE a.event_id = :eid
    ORDER BY apgain
  ";
  $bind = [':eid' => $_SESSION['event']['id']];
  $ret = $db->fetchAll($sql, $bind);

  return $ret;
}

