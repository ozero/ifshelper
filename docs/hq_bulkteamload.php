<?php
require_once("./lib/lib.php");
priveledge($db, "hq");//priveledge: more than leaders

$_SESSION['bulk_team_import'] = [];

//
$_view['title'] = "Team structure Bulkloader - Head quarter";
$_view['back_href'] = "hq_index.php";

?>
<html>
  <?php require("./assets/_container.php"); ?>
  <style>
    textarea{
      font-size:80%;
      font-family:monospace;
    }
  </style>
  <h3>
    Team structure Bulkloader - HQ Tool - <?php e($_SESSION['event']['name']); ?>
  </h3>

<pre>## Syntax:
- [Faction]<%%>[Team name]<%%>[Agent name 1]<%%>[Agent name 2]...
- Separate by "<%%>"
- Write Team faction as "R" or "E" or "XF"

## Example:

E<%%>team name<%%>ENLAgent000<%%>ENLAgent001<%%>ENLAgent002<%%>ENLAgent003<%%>ENLAgent004
R<%%>team name<%%>RESAgent000<%%>RESAgent001<%%>RESAgent002<%%>RESAgent003<%%>RESAgent004
:
</pre>

  <form method="post" action="./hq_bulkteamload_confirm.php">
  <textarea name="arg[src]" style="width:100%;height:16em;">E<%%>ETeam000<%%>ENLAgent000<%%>ENLAgent001<%%>ENLAgent002<%%>ENLAgent003<%%>ENLAgent004
R<%%>RTeam000<%%>RESAgent000<%%>RESAgent001<%%>RESAgent002<%%>RESAgent003<%%>RESAgent004

</textarea>
  <div style="text-align:center;margin-top:0.4em;"><input type="submit" class="btn btn-primary" value="Check data" /></div>
  </form>
  <?php require("./assets/_footer.php"); ?>


  <script>
  /* global $ */
  $(document).ready(function(){

  });
  </script>
</html>
