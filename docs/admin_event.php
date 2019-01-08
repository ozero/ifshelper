<?php
require_once("./lib/lib.php");
priveledge($db, "leaders");//priveledge: more than admin

//team list
$_view['event'] = $db->fetchAll(
  "select * from events where id = :id",[
    ':id'=>$_GET['arg']['id']
  ]
);

//$_view['unassigned'] = getAgents($db);

//
$_view['title'] = "ADMIN: Event";
$_view['back_href'] = "admin_index.php";
?>
<html>
  <?php require("./assets/_container.php"); ?>
  <script
    src="https://cdnjs.cloudflare.com/ajax/libs/markdown-it/8.4.2/markdown-it.min.js"
    integrity="sha256-JdPG0DllQPmyIeUeYRUCvk6K3tY7C7RZyVKEcT56yzQ="
    crossorigin="anonymous"></script>
  <script src="assets/md_link.js"></script>
  <script src="assets/autosize.min.js"></script>

  <h3>
    <div class="fl">
      Events
    </div>
    <div class="fr">
    </div>
    <div class="cf"></div>
  </h3>

  <form action="./admin_updateevent.php" method="POST">

  <table class="table table-bordered" id="tb_teams">
    <tbody>
      <tr>
        <td>Name</td>
        <td><input type="text" name="arg[name]" class="form-control"
          value="<?php e($_view['event'][0]['name']); ?>">
        </td>
      </tr>
      <tr>
        <td>Eventdate</td>
        <td><input type="text" name="arg[eventdate]" class="form-control"
          value="<?php e($_view['event'][0]['eventdate']); ?>">
        </td>
      </tr>
      <tr>
        <td>Measurment From</td>
        <td><input type="text" name="arg[mfrom]" class="form-control"
          value="<?php e($_view['event'][0]['mfrom']); ?>">
        </td>
      </tr>
      <tr>
        <td>Measurment To</td>
        <td><input type="text" name="arg[mto]" class="form-control"
          value="<?php e($_view['event'][0]['mto']); ?>">
        </td>
      </tr>
      <tr>
        <td>Password HQ</td>
        <td><input type="text" name="arg[pass_hq]" class="form-control"
          value="<?php e($_view['event'][0]['pass_hq']); ?>">
        </td>
      </tr>
      <tr>
        <td>Password Leaders</td>
        <td><input type="text" name="arg[pass_leaders]" class="form-control"
          value="<?php e($_view['event'][0]['pass_leaders']); ?>">
        </td>
      </tr>
      <tr>
        <td>Password Agents</td>
        <td><input type="text" name="arg[pass_agents]" class="form-control"
          value="<?php e($_view['event'][0]['pass_agents']); ?>">
        </td>
      </tr>
      <tr>
        <td>Event status</td>
        <td>
          <select name="arg[available]">
            <?php $_ = $_view['event'][0]['pass_agents']?>
            <option value="0" <?php echo ($_ == 0)?"selected":""; ?>>Public</option>
            <option value="1" <?php echo ($_ == 1)?"selected":""; ?>>HQ, Leaders only</option>
            <option value="2" <?php echo ($_ == 2)?"selected":""; ?>>Archived(ADMIN only)</option>
          </select>
        </td>
      </tr>
      <?php $_memo = json_decode($_view['event'][0]['memo'], true); ?>
      <tr>
        <td>memo:tips_agents</td>
        <td>
          <textarea class="form-control autosize" name="arg[tips_agents]" rows="5"
          ><?php echo $_memo['tips_agents']; ?></textarea>
        </td>
      </tr>
      <tr>
        <td>memo:tips_leaders</td>
        <td>
          <textarea class="form-control autosize" name="arg[tips_leaders]" rows="5"
          ><?php echo $_memo['tips_leaders']; ?></textarea>
        </td>
      </tr>
      <tr>
        <td>memo:tips_hq</td>
        <td>
          <textarea class="form-control autosize" name="arg[tips_hq]" rows="5"
          ><?php echo $_memo['tips_hq']; ?></textarea>
        </td>
      </tr>
      <tr>
        <td colspan="2" style="text-align:center;">
          <input type="submit" value="Update" class="btn btn-success">
        </td>
      </tr>
    </tbody>
  </table>

  </form>

  <?php require("./assets/_footer.php"); ?>

  <script>
    /* global $ */
    $(document).ready(function(){
      /* global autosize */
      autosize($('.autosize'));
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

