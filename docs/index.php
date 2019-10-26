<?php
require_once("./lib/lib.php");
priveledge($db, "agents");//priveledge: more than agents

/*

起動画面：
https://test.currentdir.com/fs/

この画面で必要なデータ：
権限

この画面で必要な処理：


*/

$_view['title'] = "Top";
$_view['back_href'] = "";
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
    <?php e($_SESSION['event']['name']); ?> - <?php e($_SESSION['event']['eventdate']); ?>
  </h3>




  <div style="text-align:center;margin-bottom:1em;">
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



  <table class="table table-bordered">

    <?php if($_SESSION['auth_role'] == "agents"){ ?>
    <tr><td>Welcome Agent</td>
    <td><a href="./agents_index.php" class="btn btn-primary">Agent</a></td></tr>
    <?php } ?>

    <?php if($_SESSION['auth_role'] == "leaders"){ ?>
    <tr><td>Welcome Agent</td>
    <td><a href="./agents_index.php" class="btn btn-primary">Agent</a></td></tr>
    <tr><td>Welcome Leader</td>
    <td><a href="./leaders_index.php" class="btn btn-primary">Leaders</a></td></tr>
    <?php } ?>

    <?php if($_SESSION['auth_role'] == "hq"){ ?>
    <tr><td>Welcome Agent</td>
    <td><a href="./agents_index.php" class="btn btn-primary">Agent</a></td></tr>
    <tr><td>Welcome Leader</td>
    <td><a href="./leaders_index.php" class="btn btn-primary">Leader</a></td></tr>
    <tr><td>Welcome HQ</td>
    <td><a href="./hq_index.php" class="btn btn-secondary">HQ</a></td></tr>
    <?php } ?>

    <?php if($_SESSION['auth_role'] == "admin"){ ?>
    <tr><td>Welcome Agent</td>
    <td><a href="./agents_index.php" class="btn btn-primary">Agent</a></td></tr>
    <tr><td>Welcome Leader</td>
    <td><a href="./leaders_index.php" class="btn btn-primary">Leader</a></td></tr>
    <tr><td>Welcome HQ</td>
    <td><a href="./hq_index.php" class="btn btn-secondary">HQ</a></td></tr>
    <tr><td>Welcome Event manager</td>
    <td><a href="./admin_index.php" class="btn btn-danger">Admin</a></td></tr>
    <?php } ?>

  </table>


  <script>
  var _eid = "<?php print $_SESSION['event']['id']; ?>";

  /* global $ */
  $(document).ready(function(){
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