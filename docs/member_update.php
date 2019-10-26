<?php
require_once("./lib/lib.php");
priveledge($db, "leaders");//priveledge: more than leaders

//exception
if(!isset($_POST)){
  header('Location: '.SITEROOT.'/leaders_index.php');
  exit();
}
if(!isset($_SESSION['event']['id'])){
  //priveledge_fail();
}

//Update
if(isset($_POST['arg'])){
  update_legacy($db);
  //Flash message
  $_SESSION['flash']['class'] = "success";
  $_SESSION['flash']['message'] = "🌟 Stats was updated successfully!";

  header('Location: '.SITEROOT.'/member_index.php?arg[id]='.$_POST['arg']['id']);
  exit;

}else{
  update_ajax($db);
}



// ----------------------------------------------

function update_ajax($db){
  //check & insert into 1st-name-team if not exist
  $teams = $db->fetchAll(
    "SELECT id FROM teams
    WHERE event_id = :eid
    ORDER BY name",[
      ':eid'=>$_SESSION['event']['id']
    ]
  );
  $agent_exist = $db->fetchAll(
    "SELECT id,name,memo FROM agents
    WHERE event_id = :eid
    AND name = :name ",[
      ':eid'=>$_SESSION['event']['id'],
      ':name'=>trim($_POST['name'])
    ]
  );
  if(!isset($agent_exist[0]['name'])){
    $sql_1 = "INSERT INTO agents (
      team_id, event_id, name, faction,
      lvfrom,lvto,apfrom,apto,trfrom,trto
      ) values(:tid,:e,:n,:f, 0,0, 0,0, 0,0 )";
    $bind_1 = [
      ":tid" => -1,//$teams[0]['id'],
      ":e" => $_SESSION['event']['id'],
      ":n" => trim($_POST['name']),
      ":f" => (trim($_POST['fac']) == "Enlightened")?2:1
    ];
    //er([$_POST, $sql_1, $bind_1]);
    $db->insert($sql_1, $bind_1);
  }

  //
  $_memo_new = "Update ".date("Y/m/d H:i:s")."\n"
    ."LV: {$_POST['lv']}\n"
    ."AP: {$_POST['ap']}\n"
    ."Tr: {$_POST['tr']}\n"
    ."------------\n".$agent_exist[0]['memo'];

  //
  $sql_upd_from="UPDATE agents SET
    lvfrom = :lvfrom,
    apfrom = :apfrom,
    trfrom = :trfrom,
    memo = :memo
    WHERE event_id = :eid
    AND agents.name = :name ";
  $bind_from = [
    ':lvfrom'=>$_POST['lv'],
    ':apfrom'=>$_POST['ap'],
    ':trfrom'=>$_POST['tr'],
    ':memo'=>$_memo_new,
    ':eid'=>$_SESSION['event']['id'],
    ':name'=>$_POST['name']
  ];

  $sql_upd_to="UPDATE agents SET
    lvto = :lvto,
    apto = :apto,
    trto = :trto,
    memo = :memo
    WHERE event_id = :eid
    AND agents.name = :name ";
  $bind_to = [
    ':lvto'=>$_POST['lv'],
    ':apto'=>$_POST['ap'],
    ':trto'=>$_POST['tr'],
    ':memo'=>$_memo_new,
    ':eid'=>$_SESSION['event']['id'],
    ':name'=>$_POST['name']
  ];

  if($_POST['mode'] == "from"){
    $db->execute($sql_upd_from, $bind_from);
  }else{
    $db->execute($sql_upd_to, $bind_to);
  }
  //er([$_POST, $agent_exist[0], $sql_upd_from, $bind_from, $sql_upd_to, $bind_to]);
  print "['ok']";
}

function update_legacy($db){
  //
  $sql="UPDATE agents SET
    name = :name,
    faction = :faction,
    lvfrom = :lvfrom,
    lvto = :lvto,
    apfrom = :apfrom,
    apto = :apto,
    trfrom = :trfrom,
    trto = :trto,
    memo = :memo
    WHERE id = :id";
  $bind = [
    ':name'=>$_POST['arg']['name'],
    ':faction'=>$_POST['arg']['faction'],
    ':lvfrom'=>$_POST['arg']['lvfrom'],
    ':lvto'=>$_POST['arg']['lvto'],
    ':apfrom'=>$_POST['arg']['apfrom'],
    ':apto'=>$_POST['arg']['apto'],
    ':trfrom'=>$_POST['arg']['trfrom'],
    ':trto'=>$_POST['arg']['trto'],
    ':memo'=>$_POST['arg']['memo'],
    ':id'=>$_POST['arg']['id']
  ];
  $db->execute($sql,$bind);
}



