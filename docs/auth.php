<?php
require_once("./lib/lib.php");
priveledge($db, "");//all priveledge

/*

起動画面：
https://test.currentdir.com/fs/

この画面で必要なデータ：

この画面で必要な処理：
認可して権限を与える

idがイベント名っぽいやつ
passが日付っぽい4桁、リーダーならfs+日付っぽい4桁とかかなあ。


*/

//
if(isset($_POST['arg'])){
  auth($db, $_POST['arg']);
}elseif(isset($_GET['arg']) && ($_GET['arg']['z'] == SHORTHAND_PASS) ){
  $_arg = $_GET['arg'];
  auth($db, $_arg);
}elseif(isset($_GET['key'])){
  //auth.php?key=a79tyq09_7_jou [spass]_[eid]_[passwd]
  $_key = explode("_",$_GET['key']);
  if($_key[0] == SHORTHAND_PASS){
    auth($db, [
      'event'=>$_key[1],
      'password'=>$_key[2]
    ]);
  }
}

$_view['events'] = $db->fetchAll(
  "select * from events where available < 2 order by eventdate, name",
  []
);
$_view['title'] = "Auth";
$_view['back_href'] = "";

?>
<html>
  <?php require("./assets/_container.php"); ?>
  <h3>
    Login - FS Helper
  </h3>

  <form action="auth.php" method="post">
  <table class="table table-bordered">
  <tr>
  <td>Event:</td>
  <td>
  <select name="arg[event]">
    <?php foreach($_view['events'] as $v0){ ?>
    <option value="<?php e($v0['id']); ?>">
      <?php e($v0['name']); ?> (<?php e($v0['eventdate']); ?>)
    </option>
    <?php } ?>
    <option value="manager">( Event manager )</option>
  </select>
  </td>
  </tr>
  <tr>
  <td>Password:</td>
  <td><input type="password" name="arg[password]" class="form-control"></td>
  </tr>
  <tr>
  <td colspan="2" style="text-align:center;"><input type="submit" value="login" class="btn btn-primary"></td>
  </tr>
  </table>
  </form>

</html>
<?php


function auth($db, $arg){
  //fail case
  if(!isset($arg['event'])){
    priveledge_fail();
  }
  if(!isset($arg['password'])){
    priveledge_fail();
  }
  if($arg['password'] == ""){
    priveledge_fail();
  }

  //auth: admin (event manager)
  if(
    ($arg['event'] == "manager")
    &&($arg['password'] == EVENTAMANAGER_PASS)
  ){
    $_SESSION['auth_role'] = "admin";
    priveledge_success();
  }

  //auth: generic account
  $event = $db->fetchAll("select * from events where id = :id",[
    ':id' => $arg['event']
  ]);
  if(!isset($event[0]['id'])){
    priveledge_fail();//event not found
  }else{
    
    if($arg['password'] == $event[0]['pass_hq']){
      $_SESSION['auth_role'] = "hq";
      
    }elseif($arg['password'] == $event[0]['pass_leaders']){
      $_SESSION['auth_role'] = "leaders";
      
    }elseif($arg['password'] == $event[0]['pass_agents']){
      $_SESSION['auth_role'] = "agents";
      
    }else{
      priveledge_fail();
    }
    
    //get event metadata
    $_SESSION['event'] = $event[0];

    switch(intval($event[0]['available'])){
      case 0://available:0 : ok for all
        break;
      case 1://available:1 : ok for only HQ, admin
        if(($_SESSION['auth_role'] != "hq") && ($_SESSION['auth_role'] != "admin")){
          $_SESSION['flash']['class'] = "warning";
          $_SESSION['flash']['message'] = "🌜 This event is not published.";
          priveledge_fail();
        }
        break;
      case 2://available:2 : ok for only admin
        if($_SESSION['auth_role'] != "admin"){
          $_SESSION['flash']['class'] = "warning";
          $_SESSION['flash']['message'] = "👷 This event is not ready.";
          priveledge_fail();
        }
        break;
    }
    

    //Flash message
    $_SESSION['flash']['class'] = "success";
    $_SESSION['flash']['message'] = "🙌 Welcome to FS Helper";

    //redirect
    priveledge_success($_SESSION['auth_role']);
  }
  return;
}

