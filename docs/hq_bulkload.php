<?php
require_once("./lib/lib.php");
priveledge($db, "hq");//priveledge: more than leaders

$_SESSION['bulk_import'] = [];

//
$_view['title'] = "Agents Bulkloader - Head quarter";
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
    Agents Bulkloader - HQ Tool - <?php e($_SESSION['event']['name']); ?>
  </h3>

<pre>## Syntax:
- Separate by "<%%>"
- Write faction as "R" or "E" or ""

## Example:

agent1<%%>E
agent2<%%>R
agent3
agent4<%%>E
:
</pre>

  <form method="post" action="./hq_bulkload_confirm.php">
  <textarea name="arg[src]" style="width:100%;height:16em;">agent1<%%>E
agent2<%%>R
agent3<%%>
agent4<%%>E</textarea>
  <div style="text-align:center;margin-top:0.4em;"><input type="submit" class="btn btn-primary" value="Check data" /></div>
  </form>
  <?php require("./assets/_footer.php"); ?>


  <script>
  /* global $ */
  $(document).ready(function(){

  });
  </script>
</html>
