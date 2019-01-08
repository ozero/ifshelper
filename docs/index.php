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
  <h3>
    <?php e($_SESSION['event']['name']); ?> - <?php e($_SESSION['event']['eventdate']); ?>
  </h3>

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

</html>