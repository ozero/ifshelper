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
    FevGames IFS Bulkloader
  </h3>




  <form method="post" action="hq_importifs_update.php" id="fr_eventhq">
    <input type="hidden" name="arg[id]" value="<?php e($_SESSION['event']['id']); ?>">
    <table class="table table-bordered">
      <tr>
        <td>"Enl Leader	" is:</td>
        <td><input type="text" class="form-control"
        name="arg[enl1]"></td>
      </tr>
      <tr>
        <td>"Res Leader	" is:</td>
        <td><input type="text" class="form-control"
        name="arg[res1]"></td>
      </tr>
      <tr>
        <td>Paste "Enl Attendees" here<br>(ignores duplicates.)</td>
        <td><textarea class="form-control"
        name="arg[enl2]" style="height:20em;"></textarea></td>
      </tr>
      <tr>
        <td>Paste "Res Attendees" here<br>(ignores duplicates.)</td>
        <td><textarea class="form-control"
        name="arg[res2]" style="height:20em;"></textarea></td>
      </tr>

      <tr>
        <td colspan="2" style="text-align:center">
          <input type="submit" class="btn btn-primary" value="import">
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
      if($('input[name="arg[pass_agents]"]').val() == ""){
        alert ("ERROR: Input Agents Password.");
        return false;
      }
    });

    /* global autosize */
    autosize($('.autosize'));
  });
  </script>
</html>
