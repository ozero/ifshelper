<?php
require_once("./lib/lib.php");
priveledge($db, "hq");//priveledge: more than leaders

if(!isset($_SESSION['event']['id'])){
  priveledge_fail();
}

/*
FevGamesの報告スプシ用ダンプファイルを出力
*/

$filename = "FsReport({$_SESSION['event']['eventdate']}_{$_SESSION['event']['name']})_for_FevGames.tsv.txt";
header('Content-Type: application/force-download;');
header('Content-Disposition: attachment; filename="'.$filename.'"');

print "========================================\n";
print "FS STATS GAIN REPORT for FEVGAMES - RES \n";
print "========================================\n";
dumpStat($db, $_SESSION['event']['id'], 1);
print "\n\n\n";
print "========================================\n";
print "FS STATS GAIN REPORT for FEVGAMES - ENL \n";
print "========================================\n";
dumpStat($db, $_SESSION['event']['id'], 2);
print "\n\n\n";


exit;

// ------------------------------------------------

function dumpStat($db, $event_id, $faction){
    $stats = $db->fetchAll(
      "SELECT
      name, lvfrom, apfrom, trfrom, lvto, apto, trto,
      (lvto - lvfrom) as lvgain,
      (apto - apfrom) as apgain,
      (trto - trfrom) as trgain
      FROM agents
      WHERE event_id = :eid
      AND faction = :fac
      AND apto > 0
      ORDER BY name",[
        ':eid'=>$_SESSION['event']['id'],
        ':fac'=>$faction
      ]
    );
    foreach($stats as $v0){
        $line=[
            $v0['name'],
            $v0['lvfrom'],
            $v0['apfrom'],
            $v0['trfrom'],
            $v0['lvto'],
            $v0['apto'],
            $v0['trto'],
            $v0['lvgain'],
            $v0['apgain'],
            $v0['trgain']
        ];
        print join("\t", $line)."\n";
    }
    return;
}
