<?php
require_once("./lib/lib.php");
priveledge($db, "leaders");//priveledge: more than leaders

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
    .emoji{font-size:160%;}
    .btn-prime {
      color:white;
      background-color: #6f42c1;
      border-color: #6f42c1;
    }
    .btn-prime:focus, .btn-prime.focus {
      color:white;
      background-color: #6f42c1;
      border-color: #6f42c1;
    }
    .btn-prime:hover, .btn-prime.hover {
      color:white;
      background-color: #5f32b1;
      border-color: #6f42c1;
    }

  </style>
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


  <div style="text-align:center;margin-bottom:1em;">
    <a href="#" class="btn btn-prime btn-xs" id="hr_import_from_cb_start">Paste Stats from Ingress PRIME</a>
  </div>
  <!-- stats paste form -->
  <div id="dv_import_from_cb" style="display:none;line-height:1.8;background-color:#f0f0f0;margin-bottom:1em;padding:3em 1em;color:#6f42c1;text-align:center;">
    <div id="dv_promptPerm_cb" style="display:none;">
      Please Grant me to read your clipboard.<br><br>
      <a href="#" class="btn btn-outline-secondary btn-xs" id="hr_promptPerm_cb">Grant & Check</a>
    </div>
    <div id="dv_grantedPerm_cb" style="display:none;">
      Tap this to check your Stats.<br><br>
      <a href="#" class="btn btn-outline-secondary btn-xs" id="hr_grantedPerm_cb">Check</a>
    </div>
    <div id="dv_deniedPerm_cb" style="display:none;">
      You denied my access.<br>
      Then you can input manually below.
    </div>
    <div id="dv_readFail_cb" style="display:none;">
      Open Prime App and Copy your ALL-TIME Stats.<br>
      <br>
      <img src="./assets/stats_copy.jpg" width="300px"><br>
      <br>
      <a href="#" class="btn btn-outline-secondary btn-xs" id="hr_checkAgain_cb">Check Again</a>
    </div>
    <div id="dv_readSuccess_cb" style="display:none;">
      <div id="dv_readSuccess_cb_btn">
        Your Stats is:<br>
        AP: <span id="sp_cb_ap" style="font-weight:bold;font-family:monospace;font-size:150%;"></span>AP<br>
        Trekker: <span id="sp_cb_tr" style="font-weight:bold;font-family:monospace;font-size:150%;"></span>Km<br>
        <hr>
        You can Import as<br><br>
        <a href="#" class="btn btn-outline-secondary btn-xs" id="hr_setstat_start">&#9199; My START Stats.</a><br><br>
        <a href="#" class="btn btn-outline-secondary btn-xs" id="hr_setstat_finish">&#127937; My FINISH Stats.</a><br>
        <br>
      </div>
      <div id="dv_readSuccess_cb_continue" style="font-weight:bold;color:red;display:none;">
        Ready to Update.<br>
        <br>
        1. Input your Lv from/to.<br>
        2. Tap UPDATE.<br>
      </div>
    </div>

  </div>
  <!-- /stats paste form -->



  <form method="post" action="member_update.php" id="fr_agent">
    <input type="hidden" name="arg[id]" value="<?php e($_view['agent'][0]['id']); ?>"/>

  <table class="table table-bordered member_numeric">
    <tr>
      <td>Name</td>
      <td colspan="3">
        <input type="text" name="arg[name]" class="form-control"
          value="<?php e($_view['agent'][0]['name']); ?>">
      </td>
    </tr>
    <tr>
      <td>Faction</td>
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
      <td>Input</td>
      <td>From</td>
      <td class="emoji">&#x23e9;</td>
      <td>To</td>
    </tr>

    <tr>
      <td rowspan="2">LV</td>
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
      <td rowspan="2">AP</td>
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
      <td rowspan="2">Trekker</td>
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
      <td>Memo
      </td>
      <td colspan="3">
        <textarea name="arg[memo]" class="form-control" rows="3"><?php echo($_view['agent'][0]['memo']); ?></textarea>
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


    $("#hr_import_from_cb_start").on('click',function(){
      $("#dv_import_from_cb").toggle('fast');
    });

    //初期化：クリップボードアクセス許可を見る
    navigator.permissions.query({
      name: 'clipboard-read'
    }).then(permissionStatus => {
      //表示する内容を変える： Will be 'granted', 'denied' or 'prompt':
      console.log("permissionStatus.state is now:" + permissionStatus.state);
      if(permissionStatus.state == "prompt"){
        //初期状態：パーミッション要確認
        $("#dv_promptPerm_cb").show();
        $("#hr_promptPerm_cb").on('click',(e) => {
          e.preventDefault();parseCbStats();
        });
      }
      if(permissionStatus.state == "granted"){
        //初期状態：パーミッション許可済み
        $("#dv_grantedPerm_cb").show();
        $("#hr_grantedPerm_cb").on('click',(e) => {
          e.preventDefault();parseCbStats();
        });
        $("#hr_checkAgain_cb").on('click',(e) => {
          e.preventDefault();parseCbStats();
        });
      }
      if(permissionStatus.state == "denied"){
        //初期状態：パーミッション拒否済み
        $("#dv_deniedPerm_cb").show();
      }

      //パーミッションが変わったら表示内容を変えるやつ
      permissionStatus.onchange = () => {
        console.log("permissionStatus.state changed to:" + permissionStatus.state);
        if(permissionStatus.state == "granted"){
          //許可されたとき
          $("#dv_promptPerm_cb").hide();
        }
        if(permissionStatus.state == "denied"){
          //拒否されたとき
          $("#dv_promptPerm_cb").hide();
          $("#dv_deniedPerm_cb").show();
        }
      };
    });



  });

  //CB読み取りボタンからの処理
  function parseCbStats(){
    $("#dv_promptPerm_cb").hide();
    $("#dv_deniedPerm_cb").hide();
    $("#dv_readFail_cb").hide('fast');

    //CBへの読み取りアクセスを要求（非同期）
    navigator.clipboard.readText()
    .then(text => {
      $("#dv_grantedPerm_cb").hide();
      console.log("parseCbStats:" + text);
      var _flag = 0;
      //TODO: statsのparse,validation 処理
      var tx1 = text.split("\n");
      if(tx1.length > 1){_flag++}
      var tx2 = tx1[1].split("\t");
      if(tx2.length > 1){_flag++}
      var strAllTime = ["全期間","ALL TIME","全部","전체","TOUS TEMPS","SIEMPRE","GESAMT","ЗА ВСЕ ВРЕМЯ"];
      for(var i in strAllTime){
        if(tx2[0] == strAllTime[i]){_flag++;}
      }
      //
      if(_flag != 3){
        //Primeからのスタッツコピペハウツー部分表示
        $("#dv_promptPerm_cb").hide();
        $("#dv_deniedPerm_cb").hide();
        $("#dv_readFail_cb").show('fast');
      }else{
        //スタッツ取り込み結果＆上書き先選択を表示
        var _name = tx2[1];//Lvはスタッツには出てこない。要手入力。
        var _fac = tx2[2];
        var _ap = tx2[6];
        var _tr = tx2[12];
        $("#sp_cb_ap").text(_ap);
        $("#sp_cb_tr").text(_tr);
        $("#dv_readSuccess_cb").show('fast');
        //
        $("#hr_setstat_start").on('click',function(){
          $("#dv_readSuccess_cb_btn").hide('fast');
          $("#dv_readSuccess_cb_continue").show('fast');
          $("#tx_apfrom").val(_ap);
          $("#tx_trfrom").val(_tr);
        });
        $("#hr_setstat_finish").on('click',function(){
          $("#dv_readSuccess_cb_btn").hide('fast');
          $("#dv_readSuccess_cb_continue").show('fast');
          $("#tx_apto").val(_ap);
          $("#tx_trto").val(_tr);
        });

      }
    })
    .catch((e) => {
      //console.error(e);
      //alert('Failed to read from clipboard.');
      $("#dv_promptPerm_cb").hide();
      $("#dv_deniedPerm_cb").hide();
      $("#dv_readFail_cb").show('fast');

    });
  }
  </script>
</html>