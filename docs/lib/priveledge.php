<?php



//権限確認
function priveledge($db, $requirement){
  
  //get event metadata
  if(isset($_SESSION['event']['id'])){
    $event = $db->fetchAll("select * from events where id = :id",[
      ':id' => $_SESSION['event']['id']
    ]);
    $_SESSION['event'] = $event[0];
  }

  //ゲスト可
  if($requirement == ""){
    return;
  }

  //全ユーザ許可
  if($requirement == "agents"){
    if(
      ($_SESSION['auth_role'] != "agents") &&
      ($_SESSION['auth_role'] != "leaders") &&
      ($_SESSION['auth_role'] != "hq") &&
      ($_SESSION['auth_role'] != "admin")
    ){
      priveledge_fail();
    }
    //エージェントからのアクセスを一時的に遮断したい時はここをコメントアウト
    //print "リーダー以外からのアクセスを一時的に遮断しています。";exit;
    return;
  }

  //リーダー向けページ
  if($requirement == "leaders"){
    if(
      ($_SESSION['auth_role'] != "leaders") &&
      ($_SESSION['auth_role'] != "hq") &&
      ($_SESSION['auth_role'] != "admin")
    ){
      priveledge_fail();
    }
    return;
  }

  //HeadQuarter向けページ
  if($requirement == "hq"){
    if(
      ($_SESSION['auth_role'] != "hq") &&
      ($_SESSION['auth_role'] != "admin")
    ){
      priveledge_fail();
    }
    return;
  }

  //鯖缶向けページ
  if($requirement == "admin"){
    if($_SESSION['auth_role'] != "admin"){
      priveledge_fail();
    }
    return;
  }

  //例外
  print "Exception: priveledge";exit;
  //priveledge_fail();
  return;
}
//認証失敗時のリダイレクト
function priveledge_fail(){
  header('Location: '.SITEROOT.'/auth.php');
  exit();
}
//認証成功時のリダイレクト
function priveledge_success($redirect = ""){
  if($redirect == "agents"){
    header('Location: '.SITEROOT."/agents_index.php");
  }elseif($redirect == "leaders"){
    header('Location: '.SITEROOT."/leaders_index.php");
  }else{
    header('Location: '.SITEROOT);//HQ
  }
  exit();
}


