<?php
require_once("./lib/lib.php");
priveledge($db, "hq");//priveledge: more than leaders



//refresh event tips
$event = $db->fetchAll("select * from events where id = :id",[
  ':id' => $_SESSION['event']['id']
]);
$_SESSION['event'] = $event[0];

//
$_view['event'] = $db->fetchAll(
  "SELECT * from events where id = :eid",[
    ':eid'=>$_SESSION['event']['id']
  ]
);
$_view['event_tips'] = json_decode($_view['event'][0]['memo'], true);
$_view['title'] = "Head quarter";
$_view['back_href'] = "index.php";

$_view['leders_login'] = SITEROOT."/auth.php?key=".SHORTHAND_PASS
  ."_{$_SESSION['event']['id']}_{$_view['event'][0]['pass_leaders']}";

?>
<html>
  <?php require("./assets/_container.php"); ?>
  <script src="assets/autosize.min.js"></script>
  <style>
    textarea{
      font-size:80%;
      font-family:monospace;
    }
    .small{
      font-size:80%;
      color:#999;
    }
  </style>

  <h3>
    HQ Tool
  </h3>
  <a href="./hq_bulkload.php" class="btn btn-secondary">Agent Bulkloader</a>
  <a href="./hq_bulkteamload.php" class="btn btn-secondary">Team Bulkloader</a>
  <a href="./hq_export_fevg.php" class="btn btn-default btn-outline-secondary">Export:Report for FevGames</a>

  <h3>
    <div>
      Setting - <?php e($_SESSION['event']['name']); ?>
    </div>
  </h3>



  <form method="post" action="hq_update.php" id="fr_eventhq">
    <input type="hidden" name="arg[id]" value="<?php e($_SESSION['event']['id']); ?>">
    <table class="table table-bordered">
      <tr>
        <td>Event Name</td>
        <td><input type="text" class="form-control" name="arg[name]"
          value="<?php e($_view['event'][0]['name']); ?>"></td>
      </tr>
      <tr>
        <td>Event ENABLED</td>
        <td>
        <select name="arg[available]">
          <option value="0" <?php echo ($_view['event'][0]['available'] === 0)?"selected":""; ?>>Enabled</option>
          <option value="1" <?php echo ($_view['event'][0]['available'] === 1)?"selected":""; ?>>Disabled</option>
        </select>
        &nbsp; <span style="font-size:80%;color:#999;">(for Leaders, Agents)</span>
        </td>
      </tr>
      <tr>
        <td>Date</td>
        <td><input type="text" class="form-control" name="arg[eventdate]"
          value="<?php e($_view['event'][0]['eventdate']); ?>"></td>
      </tr>
      <tr>
        <td colspan="2">
          <p style="margin-bottom:0.7em;">
            Event Tips for Leaders - <span style="font-size:80%;color:#999;">
            <a href="https://markdown-it.github.io/" target="_blank">MARKDOWN</a> enabled
          </span></p>
          <textarea class="form-control autosize" name="arg[tips_leaders]" rows="5"
          ><?php echo $_view['event_tips']['tips_leaders']; ?></textarea>
        </td>
      </tr>
      <tr>
        <td colspan="2">
          <p style="margin-bottom:0.7em;">
            Event Tips for Agents - <span style="font-size:80%;color:#999;">
            <a href="https://markdown-it.github.io/" target="_blank">MARKDOWN</a> enabled
          </span></p>
          <textarea class="form-control autosize" name="arg[tips_agents]" rows="5"
          ><?php echo $_view['event_tips']['tips_agents']; ?></textarea>
        </td>
      </tr>
      <tr>
        <td>Measurement from</td>
        <td><input type="text" class="form-control" name="arg[mfrom]" placeholder="2099-12-31 13:00:00"
          value="<?php e($_view['event'][0]['mfrom']); ?>"></td>
      </tr>
      <tr>
        <td>Measurement to</td>
        <td><input type="text" class="form-control" name="arg[mto]" placeholder="2099-12-31 15:00:00"
          value="<?php e($_view['event'][0]['mto']); ?>"></td>
      </tr>
      <tr>
        <td colspan="2">
          <p style="margin-bottom:0.7em;">
            Memo for HQ Team
          </p>
          <textarea class="form-control autosize" name="arg[tips_hq]" rows="5"
          ><?php echo $_view['event_tips']['tips_hq']; ?></textarea>
        </td>
      </tr>
      <tr>
        <td>HQ Password <span class="small">(like 1224hq)</span></td>
        <td><input type="text" class="form-control" name="arg[pass_hq]"
          value="<?php e($_view['event'][0]['pass_hq']); ?>"></td>
      </tr>
      <tr>
        <td>Leaders password <span class="small">(like 1224)</span></td>
        <td><input type="text" class="form-control" name="arg[pass_leaders]"
          value="<?php e($_view['event'][0]['pass_leaders']); ?>"></td>
      </tr>
      <tr>
        <td>( -&gt; Leaders login URL )</td>
        <td><input type="text" class="form-control"
          value="<?php e($_view['leders_login']); ?>"></td>
      </tr>
      <tr>
        <td>Agents password <span class="small">(like 24)</span></td>
        <td><input type="text" class="form-control" name="arg[pass_agents]"
          value="<?php e($_view['event'][0]['pass_agents']); ?>"></td>
      </tr>
      <tr>
        <td colspan="2" style="text-align:center">
          <input type="submit" class="btn btn-primary" value="update">
        </td>
      </tr>
    </table>
  </form>

  <?php require("./assets/_footer.php"); ?>

  <script>
  /* global $ */
  $(document).ready(function(){
    //
    $("#hr_addteam").on('click', function(){
    });
    $("#fr_eventhq").on('submit', function(){
      if($('input[name="arg[name]"]').val() == ""){
        alert ("ERROR: Input Event name.");
        return false;
      }
      if($('input[name="arg[eventdate]"]').val() == ""){
        alert ("ERROR: Input Event date.");
        return false;
      }
      if($('input[name="arg[mfrom]"]').val() == ""){
        alert ("ERROR: Input Mesasurement from.");
        return false;
      }
      if($('input[name="arg[mto]"]').val() == ""){
        alert ("ERROR: Input Mesasurement to.");
        return false;
      }
      if($('input[name="arg[pass_hq]"]').val() == ""){
        alert ("ERROR: Input HQ Password.");
        return false;
      }
      if($('input[name="arg[pass_leaders]"]').val() == ""){
        alert ("ERROR: Input Leaders Password.");
        return false;
      }
      if($('input[name="arg[pass_agents]"]').val() == ""){
        alert ("ERROR: Input Agents Password.");
        return false;
      }
      if(
        ($('input[name="arg[pass_agents]"]').val() == $('input[name="arg[pass_hq]"]').val())
        ||($('input[name="arg[pass_agents]"]').val() == $('input[name="arg[pass_leaders]"]').val())
        ||($('input[name="arg[pass_leaders]"]').val() == $('input[name="arg[pass_hq]"]').val())
      ){
        alert ("ERROR: Passwords cant't be same. (HQ, Leaders, Agents)");
        return false;
      }
    });

    /* global autosize */
    autosize($('.autosize'));
  });
  </script>
</html>
