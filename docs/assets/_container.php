<html>
<head>
  <meta charset="utf8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <script
    src="https://code.jquery.com/jquery-3.3.1.min.js"
    integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
    crossorigin="anonymous"></script>
  <link
    href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
    rel="stylesheet"
    integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO"
    crossorigin="anonymous">
  <script
    src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.bundle.min.js"
    integrity="sha384-pjaaA8dDz/5BgdFUPX6M/9SUZv4d12SUPF0axWc+VRZkx5xU3daN+lYb49+Ax+Tl"
    crossorigin="anonymous"></script>
  <link href="./assets/style.css" rel="stylesheet">
  <title><?php e($_view['title']); ?> - FS helper</title>
</head>
<body>

<div id="nav">
  <div class="fl" style="line-height:23px;">
    <a href="./index.php" style="color:white;text-decoration:none;">FS helper | <?php e($_SESSION['event']['name']);?></a>
  </div>
  <div class="fr">
    <?php if($_SESSION['auth_role'] != ""){ ?>
    <span>You're "<?php e($_SESSION['auth_role']); ?>"</span>&nbsp;
    <a href="./logout.php" class="btn btn-info btn-sm">Logout</a>
    <?php } ?>
  </div>
  <div class="cf"></div>
</div>
<div id="container">

<?php
  //Display flashing message
  if(isset($_SESSION['flash']['message'])){
    //class: success,info,warning,danger
?>
  <div class="alert alert-<?php e($_SESSION['flash']['class']); ?>" role="alert">
    <?php e($_SESSION['flash']['message']); ?>
  </div>
<?php
    $_SESSION['flash'] = [];
  }
?>
