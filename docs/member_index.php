<?php
require_once("./lib/lib.php");
priveledge($db, "leaders");//priveledge: more than leaders

//search by event_id and name
if(!isset($_GET['arg']['id'])){
  $names = $db->fetchAll(
    "select * from agents where event_id = :eid and name = :n",[
      ':eid'=>$_GET['eid'],
      ':n'=>$_GET['name']
    ]
  );
  header('Location: '.SITEROOT.'/member_index.php?arg[id]='.$names[0]['id']);
  exit;
}


$_view['agent'] = $db->fetchAll(
  "select * from agents where id = :id",[
    ':id'=>$_GET['arg']['id']
  ]
);
$_view['team'] = $db->fetchAll(
  "select * from teams where id = :id",[
    ':id'=>$_view['agent'][0]['team_id']
  ]
);
$_view['title'] = "Member - {$_view['team'][0]['name']} - {$_SESSION['event']['name']}";
$_view['back_href'] = "team_index.php?arg[id]={$_view['team'][0]['id']}";
?>
<html>
  <?php require("./assets/_container.php"); ?>
  <style>
    .emoji{font-size:120%;}
    th{font-family: 'Open Sans Condensed', sans-serif;}
    .table td, .table th {
      padding: .5rem;
    }

  </style>
  <link href="https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300&display=swap" rel="stylesheet">
  <h3>
    <div class="fl">
      Edit Member
    </div>
    <div class="fr">
      <a class="btn btn-danger btn-sm" href="#" id="hr_eraseagent">Erase this Agent</a>
    </div>
    <div class="cf"></div>
  </h3>

  <!-- erase form -->
  <div id="dv_eraseagent" class="div-center"
    style="display:none;background-color:#cc0000;padding:1em;color:white;font-weight:bold;margin-bottom:1em;">
    <form action="./member_erase.php" method="POST" id="fr_erasemember">
      <input type="hidden" name="arg[id]" value="<?php e($_GET['arg']['id']); ?>"/>
      <input type="hidden" name="arg[team_id]" value="<?php e($_view['team'][0]['id']); ?>"/>
      You're going to ERASE this agent from FS.<br>
      NOT From Your Team. From This FS.<br>
      NO UNDO.<br>
      <br>
      Are you sure?<br>
      <hr>
      <div style="background-color:black;padding:1em;">
      <input type="submit" class="btn btn-danger confirm" value="ERASE">
      </div>
    </form>
  </div>
  <!-- /erase form -->


  <form method="post" action="member_update.php" id="fr_agent">
    <input type="hidden" name="arg[id]" value="<?php e($_view['agent'][0]['id']); ?>"/>

  <table class="table table-bordered member_numeric">
    <tr>
      <th>Name</th>
      <td colspan="3">
        <input type="text" name="arg[name]" class="form-control"
          value="<?php e($_view['agent'][0]['name']); ?>">
      </td>
    </tr>
    <tr>
      <th>Faction</th>
      <td colspan="3">
        <input type="radio" value="1" name="arg[faction]"
          <?php echo ($_view['agent'][0]['faction'] == "1")?"checked":"" ?>
        >Resistance /
        <input type="radio" value="2" name="arg[faction]"
          <?php echo ($_view['agent'][0]['faction'] == "2")?"checked":"" ?>
        >Enlightened
      </td>
    </tr>

    <tr>
      <th>Input</th>
      <td>From</td>
      <td class="emoji">&#x23e9;</td>
      <td>To</td>
    </tr>

    <tr>
      <th rowspan="2">LV</th>
      <td>
        <input type="number" name="arg[lvfrom]" class="form-control" id="tx_lvfrom"
          value="<?php e($_view['agent'][0]['lvfrom']); ?>">
        <div class="numeric" id="tx_lvfrom_num"><?php
          e(number_format($_view['agent'][0]['lvfrom'])); ?></div>
      </td>
      <td class="emoji">&#x23e9;</td>
      <td>
        <input type="number" name="arg[lvto]" class="form-control" id="tx_lvto"
          value="<?php e($_view['agent'][0]['lvto']); ?>">
        <div class="numeric" id="tx_lvto_num"><?php
          e(number_format($_view['agent'][0]['lvto'])); ?></div>
      </td>
    </tr>
    </tr>
    <tr>
      <td style="background-color:#eee;">GAIN</td>
      <td style="background-color:#eee;" colspan="2">
        <div class="numeric"><?php e(number_format(
          $_view['agent'][0]['lvto'] - $_view['agent'][0]['lvfrom']
        )); ?> Lv GAIN</div>
      </td>
    </tr>

    <tr>
      <th rowspan="2">AP</th>
      <td>
        <input type="number" name="arg[apfrom]" class="form-control" id="tx_apfrom"
          value="<?php e($_view['agent'][0]['apfrom']); ?>">
        <div class="numeric" id="tx_apfrom_num"><?php
          e(number_format($_view['agent'][0]['apfrom'])); ?></div>
      </td>
      <td class="emoji">&#x23e9;</td>
      <td>
        <input type="number" name="arg[apto]" class="form-control" id="tx_apto"
          value="<?php e($_view['agent'][0]['apto']); ?>">
        <div class="numeric" id="tx_apto_num"><?php
          e(number_format($_view['agent'][0]['apto'])); ?></div>
      </td>
    </tr>
    <tr>
      <td style="background-color:#eee;">GAIN</td>
      <td style="background-color:#eee;" colspan="2">
        <div class="numeric"><?php e(number_format(
          $_view['agent'][0]['apto'] - $_view['agent'][0]['apfrom']
        )); ?> AP GAIN</div>
      </td>
    </tr>

    <tr>
      <th rowspan="2">Trekker</th>
      <td>
        <input type="number" name="arg[trfrom]" class="form-control" id="tx_trfrom"
          value="<?php e($_view['agent'][0]['trfrom']); ?>">
        <div class="numeric" id="tx_trfrom_num"><?php
          e(number_format($_view['agent'][0]['trfrom'])); ?></div>
      </td>
      <td class="emoji">&#x23e9;</td>
      <td>
        <input type="number" name="arg[trto]" class="form-control" id="tx_trto"
          value="<?php e($_view['agent'][0]['trto']); ?>">
        <div class="numeric" id="tx_trto_num"><?php
          e(number_format($_view['agent'][0]['trto'])); ?></div>
      </td>
    </tr>
    <tr>
      <td style="background-color:#eee;">GAIN</td>
      <td style="background-color:#eee;" colspan="2">
        <div class="numeric"><?php e(number_format(
          $_view['agent'][0]['trto'] - $_view['agent'][0]['trfrom']
        )); ?> km GAIN</div>
      </td>
    </tr>

    <tr>
      <th>Memo</th>
      <td colspan="3">
        <textarea name="arg[memo]" class="form-control" rows="10"><?php echo($_view['agent'][0]['memo']); ?></textarea>
      </td>
    </tr>

    <tr>
    <td colspan="4" style="text-align:center;">
      <input type="submit" class="btn btn-primary" value="Update">
    </td>
    </tr>

  </table>
  </form>

  <?php require("./assets/_footer.php"); ?>

  <script>


  /* global $ */
  $(document).ready(function(){
    //
    $("#hr_eraseagent").on('click',function(){
      $("#dv_eraseagent").toggle('fast');
    });

    //3ケタ区切り自動更新
    $("input[type=number]").each(function(i,e){
      $(e).on('blur',function(ev){
        let id = this.id;
        $("#"+id+"_num").text(parseInt($(this).val()).toLocaleString());
      });
    });

    //input validation
    $("#tx_lvfrom").on('blur', function(){
      if($(this).val() > 16){alert("LV <= 16. Your input:"+$(this).val())}
    });
    $("#tx_lvto").on('blur', function(){
      if($(this).val() > 16){alert("LV <= 16. Your input:"+$(this).val())}
    });
    $("#fr_agent").on('submit', function(){
      var from = 0;
      var to = 0;
      //lv
      from = parseInt($("#tx_lvfrom").val());
      to = parseInt($("#tx_lvto").val());
      if( (to > 0) && (from > to) ){
        alert("INPUT ERROR: Level gain ( "+from.toLocaleString()+" -> "+to.toLocaleString()+" )");
        return false;
      }
      if( (to - from) > 15 ){
        alert("INPUT ERROR: LV gain Too BIG ( "+ (to - from).toLocaleString() +" )");
        return false;
      }
      //ap
      from = parseInt($("#tx_apfrom").val());
      to = parseInt($("#tx_apto").val());
      if( (to > 0) && (from > to) ){
        alert("INPUT ERROR: AP gain ( "+from.toLocaleString()+" -> "+to.toLocaleString()+" )");
        return false;
      }
      if( (to - from) > 2000000 ){
        alert("INPUT ERROR: AP gain Too BIG ( "+ (to - from).toLocaleString() +" )");
        return false;
      }
      //trekker
      from = parseInt($("#tx_trfrom").val());
      to = parseInt($("#tx_trto").val());
      if( (to > 0) && (from > to) ){
        alert("INPUT ERROR: Trekker gain ( "+from.toLocaleString()+" -> "+to.toLocaleString()+" )");
        return false;
      }
      if( (to - from) > 20 ){
        alert("INPUT ERROR: Trekker gain Too BIG ( "+ (to - from).toLocaleString() +" )");
        return false;
      }
    });

  }
  </script>
</html>