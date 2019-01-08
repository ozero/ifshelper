<?php
require_once("./lib/lib.php");
priveledge($db, "leaders");//priveledge: more than admin

//team list
$_view['events'] = $db->fetchAll(
  "select * from events order by id DESC",[]
);

//$_view['unassigned'] = getAgents($db);

//
$_view['title'] = "ADMIN";
$_view['back_href'] = "index.php";
?>
<html>
  <?php require("./assets/_container.php"); ?>
  <script src="assets/md_link.js"></script>

  <h3>
    <div class="fl">
      Events
    </div>
    <div class="fr">
      <a href="#" class="btn btn-success btn-sm" id="hr_addevent">Create Event</a>
    </div>
    <div class="cf"></div>
  </h3>

  <!-- addform -->
  <div id="dv_addteam" style="display:none;background-color:#f0f0f0;">
    <form action="./leaders_addteam.php" method="POST" id="fr_addteam">
    <table class="table table-bordered">
      <tr>
      <td>Team name</td>
      <td><input type="text" name="arg[name]" id="tx_addname" class="form-control" /></td>
      </tr>

      <tr>
      <td>Team faction</td>
      <td>
        <input type="radio" name="arg[type]" value="1" id="rd_res"/>
        <label for="rd_res">Resistance</label><br>
        <input type="radio" name="arg[type]" value="2" id="rd_enl"/>
        <label for="rd_enl">Enlightened</label><br>
        <input type="radio" name="arg[type]" value="3" id="rd_xf" checked/>
        <label for="rd_xf">Cross faction</label><br>
      </td>
      </tr>

      <tr>
      <td colspan="2" style="text-align:center">
        <input type="submit" class="btn btn-primary confirm" value="Create">
      </td>
      </tr>
    </table>

    </form>
  </div>
  <!-- /addform -->

  <table class="table table-bordered" id="tb_teams">
    <thead>
      <tr>
        <th>Name</th>
        <th>Date</th>
        <th>M.From</th>
        <th>M.To</th>
        <th>Status</th>
      </tr>
    </thead>

    <tbody>
    <?php foreach($_view['events'] as $v0){ ?>
      <tr>
        <td>
          <a href="./admin_event.php?arg[id]=<?php e($v0['id']); ?>" class="btn btn-primary"><?php e("{$v0['name']}"); ?></a>
        </td>
        <td>
          <?php e("{$v0['eventdate']}"); ?>
        </td>
        <td>
          <?php e("{$v0['mfrom']}"); ?>
        </td>
        <td>
          <?php e("{$v0['mto']}"); ?>
        </td>
        <td>
          <?php
          switch($v0['available']){
            case 0:
              print "Public";
              break;
            case 1:
              print "Leader, HQ Only";
              break;
            case 2:
              print "Archived (ADMIN Only)";
              break;
            default:
              print "ERR: {$v0['available']}";
          }
          ?>
        </td>
      </tr>
    <?php } ?>
    </tbody>
  </table>

  <?php require("./assets/_footer.php"); ?>

  <script>
    /* global $ */
    $(document).ready(function(){
      $("#hr_addevent").click(function(e){
        e.preventDefault();

      });

    });
  </script>
</html>
<?php

//Agents
function getAgents($db){
  $ret = ['count'=>0,'agents'=>[]];
  $sql = "SELECT
    a.id as a_id,
    t.id as t_id,
    a.name,
    a.faction
    FROM agents a
    LEFT JOIN teams t ON a.team_id = t.id
    WHERE a.event_id = :eid
  ";
  $bind = [':eid' => $_SESSION['event']['id']];
  $tmp = $db->fetchAll($sql, $bind);

  //
  $count = 0;
  foreach($tmp as $v0){
    $count += ($v0['t_id'] == "")?1:0;
    $ret['agents'][] = $v0;
  }
  $ret['count'] = $count;


  return $ret;
}

