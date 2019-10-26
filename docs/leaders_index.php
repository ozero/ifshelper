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
      Teams
    </div>
    <div class="fr">
      <a href="#" class="btn btn-success btn-sm" id="hr_addteam">Create Team</a>
    </div>
    <div class="cf"></div>
  </h3>




  <div style="text-align:center;margin-top:2em;margin-bottom:3em;">
    <a href="#" class="btn btn-prime btn-xs"
      id="hr_import_from_cb_start">Paste Stats from Ingress PRIME</a>
  </div>
  <!-- stats paste form -->
  <div id="dv_import_from_cb" style="display:none;line-height:1.8;background-color:#f0f0f0;margin-bottom:1em;padding:3em 1em;color:#6f42c1;text-align:center;">
    <!-- not support Permission API yet -->
    <div id="dv_noPermAPI_cb" style="display:none;">
      Open Prime App and Copy your ALL-TIME Stats.<br>
      <br>
      <img src="./assets/stats_copy.jpg" width="300px"><br>
      <br>
      Then, paste your ALL-TIME Stats here.<br>
      <br>
      <textarea id="tx_noPermAPI_paste_cb" style="width:100%;font-size:80%;height:8em;"></textarea><br>
      <br>
      <a href="#" class="btn btn-outline-secondary btn-xs" id="dv_noPermAPI_parse_cb">Check Stat</a>
    </div>
    <!-- require permission -->
    <div id="dv_promptPerm_cb" style="display:none;">
      Please Grant me to read your clipboard.<br><br>
      <a href="#" class="btn btn-outline-secondary btn-xs" id="hr_promptPerm_cb">Grant & Check</a>
    </div>
    <!-- permission acquired -->
    <div id="dv_grantedPerm_cb" style="display:none;">
      Tap this to check your Stats.<br><br>
      <a href="#" class="btn btn-outline-secondary btn-xs" id="hr_grantedPerm_cb">Check</a>
    </div>
    <!-- permission denieded -->
    <div id="dv_deniedPerm_cb" style="display:none;">
      You denied my access.<br>
      Then you can input manually below.
    </div>
    <!-- invalid clipboard -->
    <div id="dv_readFail_cb" style="display:none;">
      Open Prime App and Copy your ALL-TIME Stats.<br>
      <br>
      <img src="./assets/stats_copy.jpg" width="300px"><br>
      <br>
      <a href="#" class="btn btn-outline-secondary btn-xs" id="hr_checkAgain_cb">Check Again</a>
    </div>
    <!-- read Succeed -->
    <div id="dv_readSuccess_cb" style="display:none;">
      <div id="dv_readSuccess_cb_btn">
        Hi, Agent <span id="sp_cb_name" style="font-weight:bold;font-family:monospace;font-size:120%;"></span> (<span id="sp_cb_fac" style="font-weight:bold;font-family:monospace;font-size:120%;"></span>),<br>
        Your <b>CURRENT</b> Stats would be:<br>
        LV: <input id="tx_cb_lv" type="text" class="form-control" style="display:inline;width:4em;" value=""><br>
        AP(Lifetime): <span id="sp_cb_ap" style="font-weight:bold;font-family:monospace;font-size:150%;"></span>AP<br>
        Trekker: <span id="sp_cb_tr" style="font-weight:bold;font-family:monospace;font-size:150%;"></span>Km<br>
        <hr>
        You can Import as<br><br>
        <a href="#" class="btn btn-outline-secondary btn-xs" id="hr_setstat_start">&#9199; My START Stats.</a><br><br>
        <a href="#" class="btn btn-outline-secondary btn-xs" id="hr_setstat_finish">&#127937; My FINISH Stats.</a><br>
        <br>

      </div>
      <div id="dv_readSuccess_cb_continue" style="display:none;">
        Thanks!!!<br>
        <br>
        <hr>
        <a href="#" class="btn btn-outline-secondary btn-xs"
          id="hr_setstat_check">Check My Stats.</a><br>
      </div>
    </div>

  </div>
  <!-- /stats paste form -->





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
    var _eid = "<?php print $_SESSION['event']['id']; ?>";

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

      //
      primestats_init();

    });


    //
    function primestats_init(){
      //クリップボード上のスタッツを読む操作の開始UI
      $("#hr_import_from_cb_start").on('click',function(){
        $("#dv_import_from_cb").toggle('fast');
      });

      /* global navigator */
      var _caniuse_nav_perm_cb = "ok";
      if(!navigator.permissions){
        _caniuse_nav_perm_cb = "ng";
      }else if(!navigator.permissions.query){
        _caniuse_nav_perm_cb = "ng";
      }else{
        var agent = window.navigator.userAgent.toLowerCase();
        var firefox = (agent.indexOf('firefox') !== -1);
        _caniuse_nav_perm_cb = (firefox)?"ng":_caniuse_nav_perm_cb;
      }
      //console.log("_caniuse_nav_perm_cb:"+_caniuse_nav_perm_cb);

      if(_caniuse_nav_perm_cb == "ng"){
        //console.log("go legacy UI");
        //iOS：初期状態でレガシーUIを表示
        $("#dv_noPermAPI_cb").show('fast');
        $("#dv_noPermAPI_parse_cb").on('click', function(){
          var result = parsePastedText($("#tx_noPermAPI_paste_cb").val());
          if(result === "ng"){
            alert("Paste your ALL-TIME Stats here");
          }else{
            //既に成功時表示を呼んでしまってるけど後付でCB貼り付けエリアを隠す
            $("#dv_noPermAPI_cb").hide('fast');
          }
        });

      }else{
        //Permission APIによるクリップボード読み込みを使用
        //console.log("go Permission API UI");

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
              e.preventDefault();
              parseCbStats();
            });
            $("#hr_checkAgain_cb").on('click',(e) => {
              e.preventDefault();
              $("#dv_readFail_cb").hide('fast', function(){
                parseCbStats();
              });
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
              $("#dv_grantedPerm_cb").show();
            }
            if(permissionStatus.state == "denied"){
              //拒否されたとき
              $("#dv_promptPerm_cb").hide();
              $("#dv_deniedPerm_cb").show();
            }
          };

        });
      }
    }

    //CB読み取りボタンからの処理
    function parseCbStats(){
      $("#dv_promptPerm_cb").hide();
      $("#dv_deniedPerm_cb").hide();

      //CBへの読み取りアクセスを要求（非同期）
      navigator.clipboard.readText()
      .then(text => {
        $("#dv_grantedPerm_cb").hide();
        var result = parsePastedText(text);
        if(result == "ng"){
          //Primeからのスタッツコピペハウツー部分表示
          $("#dv_promptPerm_cb").hide();
          $("#dv_deniedPerm_cb").hide();
          $("#dv_readFail_cb").show('fast');
        }
      })
      .catch((e) => {
        console.error(e);
        //alert('Failed to read from clipboard.');
        $("#dv_promptPerm_cb").hide();
        $("#dv_deniedPerm_cb").hide();
        $("#dv_readFail_cb").show('fast');
      });
    }

    //はりつけられたテキストの処理
    function parsePastedText(text){
      console.log("parseCbStats:" + text);
      var _flag = 0;

      //statsのparse,validation 処理
      var tx1 = text.split("\n");
      if(tx1.length > 1){_flag++}else{return "ng";}
      var tx2 = tx1[1].split("\t");
      if(tx2.length > 1){_flag++}else{return "ng";}
      //Validならタブ区切りをディクショナリに
      var tx1_0 = tx1[0].split("\t");
      var txdata = {};
      for(var i in tx1_0){
        var i2 = tx1_0[i].replace(/\s/g, "").toLowerCase();
        txdata[i2] = tx2[i];
      }console.log(txdata);//DBG
      //全期間かどうかチェック
      var strAllTime = ["全期間","ALL TIME","全部","전체","TOUS TEMPS","SIEMPRE","GESAMT","ЗА ВСЕ ВРЕМЯ"];
      for(var i in strAllTime){
        if(tx2[0] == strAllTime[i]){_flag++;}
      }
      if(parseInt(_flag) != 3){
        return "ng";
      }

      //
      $("#dv_readFail_cb").hide('fast');
      //スタッツ取り込み結果＆上書き先選択を表示
      var _name = txdata['agentname'];//tx2[1];
      var _fac = txdata['agentfaction'];//tx2[2];
      var _ap = parseInt(txdata['currentap']);//tx2[6];
      var _apLifetime = parseInt(txdata['lifetimeap']);//
      var _lv = calcLvByAp(txdata['currentap']);//LvはCurrApから計算。要手修正。
      var _tr = parseInt(txdata['distancewalked']);//tx2[12]);
      $("#sp_cb_name").text(_name);
      $("#sp_cb_fac").text(_fac);
      $("#tx_cb_lv").val(_lv);
      $("#sp_cb_ap").text(_apLifetime.toLocaleString());//表示にはLifetimeAPを使用する
      $("#sp_cb_tr").text(_tr.toLocaleString());
      $("#dv_readSuccess_cb").show('fast');

      //スタッツを開始時データとして取り込む場合
      $("#hr_setstat_start").on('click',function(){
        $("#dv_readSuccess_cb_btn").hide('fast');
        //AJAXで投げて結果表示
        postStats({
          name:_name,
          fac:_fac,
          lv:parseInt($("#tx_cb_lv").val()),
          ap:_apLifetime,//記録にはLifetimeAPを使用する
          tr:_tr,
          mode:"from"
        });
        $("#dv_readSuccess_cb_continue").show('fast');
      });

      //スタッツを終了時データとして取り込む場合
      $("#hr_setstat_finish").on('click',function(){
        $("#dv_readSuccess_cb_btn").hide('fast');
        //AJAXで投げて結果表示
        postStats({
          name:_name,
          fac:_fac,
          lv:parseInt($("#tx_cb_lv").val()),
          ap:_apLifetime,//記録にはLifetimeAPを使用する
          tr:_tr,
          mode:"to"
        });
        $("#dv_readSuccess_cb_continue").show('fast');
      });

      //取り込み後のユーザ詳細画面への遷移
      $('#hr_setstat_check').on('click',function(){
        var href = "./member_index.php?eid="+_eid+"&name="+_name;
        window.location.href = href;
      });
      return "ok";
    }

    //ApからLvを推定:メダル留年についてUI側で手入力で修正できるように
    function calcLvByAp(ap){
      var currentAp = parseInt(ap);
      var lv = 1;
      lv += (currentAp >     2500)?1:0;//2
      lv += (currentAp >    20000)?1:0;
      lv += (currentAp >    70000)?1:0;//4
      lv += (currentAp >   150000)?1:0;
      lv += (currentAp >   300000)?1:0;//6
      lv += (currentAp >   600000)?1:0;
      lv += (currentAp >  1200000)?1:0;//8
      lv += (currentAp >  2400000)?1:0;
      lv += (currentAp >  4000000)?1:0;//10
      lv += (currentAp >  6000000)?1:0;
      lv += (currentAp >  8400000)?1:0;//12
      lv += (currentAp > 12000000)?1:0;
      lv += (currentAp > 17000000)?1:0;//14
      lv += (currentAp > 24000000)?1:0;
      lv += (currentAp > 40000000)?1:0;//16
      return lv;
    }

    //Ajaxで更新
    function postStats(data){
      console.log(data);
      $.ajax({
        type: "POST",
        url: "member_update.php",
        timeout: 10000,
        cache: false,
        data: data,
        dataType: 'html'
      })
      .done(function (response, textStatus, jqXHR) {
        console.log("a");
        console.log(response);
        console.log(textStatus);
        console.log(jqXHR);
        if (response.status === "err") {
            //alert("err: " + response.msg);
        } else {
            //alert("OK");
        }
      })
      .fail(function (jqXHR, textStatus, errorThrown) {

        console.log("b");
        console.log(jqXHR);
        console.log(textStatus);
        console.log(errorThrown);
        //alert("失敗: サーバー内でエラーがあったか、サーバーから応答がありませんでした。");

      })
      .always(function (data_or_jqXHR, textStatus, jqXHR_or_errorThrown) {
        console.log("c");
        console.log(data_or_jqXHR);
        console.log(textStatus);
        console.log(jqXHR_or_errorThrown);
        // done,failを問わず、常に実行される処理
      });
    }


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

