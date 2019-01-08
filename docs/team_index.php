<?php
require_once("./lib/lib.php");
priveledge($db, "leaders");//priveledge: more than leaders

//
$_view['team'] = $db->fetchAll(
  "SELECT * FROM teams WHERE id = :id",[
    ':id'=>$_GET['arg']['id']
  ]
);
$_view['agents'] = $db->fetchAll(
  "SELECT * FROM agents WHERE
    team_id = :tid
    AND event_id = :eid
    ORDER BY name",[
    ':eid'=>$_SESSION['event']['id'],
    ':tid'=>$_GET['arg']['id']
  ]
);
$_view['ag_list'] = $db->fetchAll(
  "SELECT a.id as a_id, a.name as a_name, a.faction, t.id as t_id, t.name as t_name
    FROM agents a
    LEFT JOIN teams t ON a.team_id = t.id
    WHERE a.event_id = :eid
    ORDER BY a.name",[
    ':eid'=>$_SESSION['event']['id']
  ]
);
//
$_view['title'] = $_view['team'][0]['name']." / Teams - {$_SESSION['event']['name']}";
$_view['back_href'] = "leaders_index.php";
?>
<html>
  <?php require("./assets/_container.php"); ?>
  <h3>
    <div class="fl">
      <?php e("{$_view['team'][0]['name']}
        ({$_view['faction'][$_view['team'][0]['team_type']]} Team)"); ?>
    </div>
    <div class="cf"></div>
  </h3>

  <table class="table table-bordered">
    <thead>
    <tr>
      <td>Agent</td>
      <td>Lv</td>
      <td>AP</td>
      <td>Tr</td>
    </tr>
    </thead>

    <tbody>
    <?php foreach($_view['agents'] as $v0){ ?>
      <tr>
      <td rowspan="2">
        <a href="./member_index.php?arg[id]=<?php e($v0['id']); ?>"
          class="btn btn-warning"><?php e($v0['name']); ?></a>
      </td>
      <td class="num"><?php e(($v0['lvfrom']!=="")?$v0['lvfrom']:"*NO INPUT*"); ?></td>
      <td class="num"><?php e(($v0['apfrom']!=="")?number_format($v0['apfrom']):"*NO INPUT*"); ?></td>
      <td class="num"><?php e(($v0['trfrom']!=="")?number_format($v0['trfrom']):"*NO INPUT*"); ?></td>
      </tr>
      <tr>
      <td class="num"><?php e(($v0['lvto']!=="")?$v0['lvto']:"*NO INPUT*"); ?></td>
      <td class="num"><?php e(($v0['apto']!=="")?number_format($v0['apto']):"*NO INPUT*"); ?></td>
      <td class="num"><?php e(($v0['trto']!=="")?number_format($v0['trto']):"*NO INPUT*"); ?></td>
      </tr>
      <tr style="background-color:#eee;">
      <td style="text-align:right;">GAINED:</td>
      <td class="num"><?php e($v0['lvto'] - $v0['lvfrom']); ?>Lv</td>
      <td class="num"><?php e(number_format($v0['apto'] - $v0['apfrom'])); ?>AP</td>
      <td class="num"><?php e($v0['trto'] - $v0['trfrom']); ?>km</td>
      </tr>
    <?php } ?>
    </tbody>

    <form action="./team_statusupdate.php" method="post">
      <input type="hidden" name="arg[team_id]" value="<?php e($_GET['arg']['id']);?>">
    <tfoot style="background-color:#ccc;color:#666;">
      <tr>
        <td>
          Did you input All AP Gain?
        </td>
        <td colspan="3">
          <select name="arg[status]">
            <option value="1">No</option>
            <option value="2" <?php echo ($_view['team'][0]['status']==2)?"selected":"";?>>
              🎊DONE🎉
            </option>
          </select>
          <input type="submit" value="Change" class="btn btn-sm btn-default">
        </td>
      </tr>
    </tfoot>
    </form>

  </table>

  <h3>
    <div class="fl">
    Invite agents
    </div>
    <div class="fr">
      <a class="btn btn-info btn-sm" href="#" id="hr_addmember">Add New Agent</a>
    </div>
    <div class="cf">
    </div>
  </h3>

  <!-- addform -->
  <div id="dv_addmember" style="display:none;background-color:#f0f0f0;">
    <form action="./team_addmember.php" method="POST" id="fr_addmember">
      <input type="hidden" name="arg[team_id]" value="<?php e($_GET['arg']['id']); ?>">
      <table class="table table-bordered">
        <tr>
        <td>New Agent name</td>
        <td><input type="text" name="arg[name]" id="tx_addname" class="form-control" /></td>
        </tr>

        <tr>
        <td>Faction</td>
        <td>
          <input type="radio" name="arg[faction]" value="1" id="rd_res"
            <?php echo ($_view['team'][0]['team_type'] == "1")?"checked":"" ?>
          />
          <label for="rd_res">Resistance</label><br>
          <input type="radio" name="arg[faction]" value="2" id="rd_enl"
            <?php echo ($_view['team'][0]['team_type'] == "2")?"checked":"" ?>
          />
          <label for="rd_enl">Enlightened</label><br>
        </td>
        </tr>

        <tr>
        <td colspan="2" style="text-align:center">
          <input type="submit" class="btn btn-primary confirm" value="Add">
        </td>
        </tr>
      </table>
    </form>
  </div>
  <!-- /addform -->

  <form method="post" action="team_moveagent.php">
    <input type="hidden" name="arg[team_id]" value="<?php e($_GET['arg']['id']); ?>">
    <div class="form-row">
      <div class="form-group col-md-8">
        <select class="form-control" id="tx_teamname" name="arg[agent_id]">
          <?php foreach($_view['ag_list'] as $v0){ ?>
            <?php
            if(($_view['team'][0]['team_type'] == 1) && ($v0['faction'] == 2) ){
              //continue;//hide enl from res team
            }
            if(($_view['team'][0]['team_type'] == 2) && ($v0['faction'] == 1) ){
              //continue;//hide res from enl team
            }
            if($v0['t_id'] == $_GET['arg']['id'] ){
              //continue;//hide current team member
            }
            ?>
            <option value="<?php e($v0['a_id']); ?>">
              <?php e("({$_view['faction'][$v0['faction']]}){$v0['a_name']} - {$v0['t_name']}"); ?>
            </option>
          <?php } ?>
        </select>
      </div>
      <div class="form-group col-md-4 text-center">
        <label>&nbsp;</label>
        <input type="submit" class="btn btn-default btn-xs confirm" value="Invite">
      </div>
    </div>
  </form>

  <hr style="margin:3em 0;">

  <div style="text-align:center;margin:6em 0 6px 0;">
    <a class="btn btn-danger btn-sm" href="#" id="hr_advanced_setting">
      Advanced setting
    </a>
  </div>

  <!-- Modal:Advanced Setting -->
  <div id="dv_advanced_setting" style="display:none;">

    <h3>
      <div class="fl">
        Setting
      </div>
      <div class="fr">
        <a href="#" id="hr_deleteteam" class="btn btn-danger btn-sm">Delete this team</a>
      </div>
      <div class="cf">
      </div>
    </h3>

    <!-- delete-team form -->
    <div id="dv_deleteteam" class="div-center"
      style="display:none;padding:1.4em;background-color:#cc0000;color:white;font-weight:bold;">
      <form action="./team_delete.php" method="POST" id="fr_teamdelete">
      This button DESTORYs your team.<br>
      NO UNDO.<br>
      <br>
      Are you sure?<br>
      <hr>
      <div class="div-center" style="padding:1.4em;background-color:#000;">
          <input type="hidden" name="arg[id]" value="<?php e($_GET['arg']['id']); ?>">
            <input type="submit" class="btn btn-DANGER confirm" value="DELETE">
      </div>
      </form>
    </div>
    <!-- /delete-team form -->


    <form method="post" action="./team_update.php">
      <input type="hidden" name="arg[id]" value="<?php e($_GET['arg']['id']); ?>">
      <div class="form-row">
        <div class="form-group col-md-6">
          <label for="tx_teamname">Team name</label>
          <input type="text" class="form-control" id="tx_teamname" placeholder="Team name" name="arg[name]"
            value="<?php echo($_view['team'][0]['name']); ?>">
        </div>
        <div class="form-group col-md-6">
          <div style="margin-bottom:10px;">Team type:</div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="arg[team_type]" id="rd_res" value="1"
              <?php echo($_view['team'][0]['team_type'] == 1)?"checked":""; ?>>
            <label class="form-check-label" for="rd_res">Resistance</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="arg[team_type]" id="rd_enl" value="2"
              <?php echo($_view['team'][0]['team_type'] == 2)?"checked":""; ?>>
            <label class="form-check-label" for="rd_enl">Enlightened</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="arg[team_type]" id="rd_xf" value="3"
              <?php echo($_view['team'][0]['team_type'] == 3)?"checked":""; ?>>
            <label class="form-check-label" for="rd_xf">Cross faction</label>
          </div>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group col-md-12 text-center">
          <input type="submit" class="btn btn-primary btn-sm confirm" value="Update">
        </div>
      </div>
    </form>

  </div>
  <!-- /Modal:Advanced Setting -->



  <?php require("./assets/_footer.php"); ?>

  <script>
  /* global $ */
  $(document).ready(function(){
    //
    $("#hr_addmember").on('click', function(e){
      e.preventDefault();
      $("#dv_addmember").toggle("fast");
    });
    $("#hr_deleteteam").on('click', function(e){
      e.preventDefault();
      $("#dv_deleteteam").toggle("fast");
    });
    $("#hr_advanced_setting").on('click', function(e){
      e.preventDefault();
      $("#dv_advanced_setting").toggle("fast");
    });
    $("#fr_addmember").on('submit', function(e){
      if($("#tx_addname").val() == ""){
        e.preventDefault();
        alert ("ERROR: Write Agent name to add.");
        return false;
      }
    });
  });
  </script>

</html>