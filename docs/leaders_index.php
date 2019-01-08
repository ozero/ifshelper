<?php
require_once("./lib/lib.php");
priveledge($db, "leaders");//priveledge: more than leaders

//team list
$_view['teams'] = $db->fetchAll(
  "select * from teams where event_id = :eid order by name",[
    'eid'=>$_SESSION['event']['id']
  ]
);

$_view['unassigned'] = getAgents($db);

//
$_view['title'] = "Leaders";
$_view['back_href'] = "index.php";
?>
<html>
  <?php require("./assets/_container.php"); ?>
  <script
    src="https://cdnjs.cloudflare.com/ajax/libs/markdown-it/8.4.2/markdown-it.min.js"
    integrity="sha256-JdPG0DllQPmyIeUeYRUCvk6K3tY7C7RZyVKEcT56yzQ="
    crossorigin="anonymous"></script>
  <script src="assets/md_link.js"></script>

  <h3>
    <div class="fl">
      Teams
    </div>
    <div class="fr">
      <a href="#" class="btn btn-success btn-sm" id="hr_addteam">Create Team</a>
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
        <th>Status</th>
      </tr>
    </thead>

    <tbody>
    <?php foreach($_view['teams'] as $v0){ ?>
      <tr>
        <td>
          <a href="./team_index.php?arg[id]=<?php e($v0['id']); ?>" class="btn btn-primary">
            <?php 
              e("{$v0['name']} ({$_view['faction'][$v0['team_type']]})");
            ?>
          </a>
        </td>
        <td>
          <?php
            echo ($v0['status'] == 2)?"🎊AP GAIN: DONE🎉":"-";
          ?>
        </td>
      </tr>
    <?php } ?>
    </tbody>

    <tfoot style="background-color:#ccc;color:#666;">
      <tr>
        <td>
          <select class="form-control">
            <option>**Assign these agents to teams above.**</option>
            <?php foreach($_view['unassigned']['agents'] as $v0){ ?>
            <option><?php e("({$_view['faction'][$v0['faction']]}) {$v0['name']}");?></option>
            <?php } ?>
          </select>
        </td>
        <td>
          <?php e($_view['unassigned']['count']);?> left.
        </td>
      </tr>
    </tfoot>
  </table>

  <h3>
    Documents for Leaders
  </h3>
  <div id="dv_tips_leader_src" style="display:none;"><?php
  $tmp_eventmemo = json_decode($_SESSION['event']['memo'],true);
  print $tmp_eventmemo['tips_leaders'];
  ?></div>
  <div id="dv_tips_leader"></div>

  <?php require("./assets/_footer.php"); ?>

  <script>
    /* global $ */
    $(document).ready(function(){
      //
      $("#hr_addteam").on('click', function(){
        $("#dv_addteam").toggle("fast");
      });
      //
      $("#fr_addteam").on('submit', function(){
        if($("#tx_addname").val() == ""){
          alert ("ERROR: Write your new team name.");
          return false;
        }
      });

      //Markdown
      window.md = window.markdownit({
        html: true,
        linkify: true,
        typographer: true
      });
      /* global md_render_linkblank */
      md_render_linkblank();//customize in md_link.js
      var result = window.md.render($("#dv_tips_leader_src").text());
      $("#dv_tips_leader").html(result);

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

