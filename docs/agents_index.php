<?php
require_once("./lib/lib.php");
priveledge($db, "agents");//priveledge: more than leaders

//
$_view['title'] = "Agents";
$_view['back_href'] = "";
?>
<html>
  <?php require("./assets/_container.php"); ?>
  <script
    src="https://cdnjs.cloudflare.com/ajax/libs/markdown-it/8.4.2/markdown-it.min.js"
    integrity="sha256-JdPG0DllQPmyIeUeYRUCvk6K3tY7C7RZyVKEcT56yzQ="
    crossorigin="anonymous"></script>
  <script src="assets/md_link.js"></script>

  <h3>
    Score of <?php e($_SESSION['event']['name']); ?>
  </h3>

  <ul>
    <li>Measturement Start: <?php e($_SESSION['event']['mfrom']); ?></li>
    <li>Measturement Ends&nbsp;: <?php e($_SESSION['event']['mto']); ?></li>
  </ul>
  <a href="./hq_stats.php" class="btn btn-primary">Stats Total</a>
  <a href="./hq_ranking.php" class="btn btn-secondary">Agents Ranking</a>

  <hr>

  <h4>Tips for agents</h4>
  <div id="dv_tips_agents_src" style="display:none;"><?php
    $tmp_eventmemo = json_decode($_SESSION['event']['memo'],true);
    print $tmp_eventmemo['tips_agents'];
  ?></div>
  <div id="dv_tips_agents"></div>

  <?php require("./assets/_footer.php"); ?>

  <script>
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
    });;
    /* global md_render_linkblank */
    md_render_linkblank();//customize in md_link.js
    var result = window.md.render($("#dv_tips_agents_src").text());
    $("#dv_tips_agents").html(result);

  });
  </script>
</html>
