<?php
require_once('/data/web/afternet/www/lib/plugins/x3help/lib/x3help.php' );
if(isset($_GET['getCommandsByLetters']) && isset($_GET['letters']) && isset($_GET['module'])){
	$mod = strtolower($_GET['module']);
        $letters = $_GET['letters'];
        $letters = preg_replace("/[^a-z0-9 ]/si","",$letters);

	if ($mod == "x3")
		$mod = "chanserv";
	else if ($mod == "o3")
		$mod = "opserv";
	else if ($mod == "snoop")
		$mod = "mod-snoop";
	else if ($mod == "memoserv")
		$mod = "mod-memoserv";
	else if ($mod == "track")
		$mod = "mod-track";
	else if ($mod == "authserv")
		$mod = "nickserv";
	else if ($mod == "helpserv")
		$mod = "mod-helpserv";

	$help = readhelp('/data/web/afternet/www/lib/plugins/x3help/' . $mod . ".help");
        $list = '';
        foreach($help as $k => $v)
        {
		$s = str_replace("<", "&lt;", $k);
		$t = str_replace(">", "&gt;", $s);
		$letters = strtoupper($letters);

	if (preg_match("^".$letters."^", $t)) {
			$i++;
			echo $i."###".$t."|";
		}
	}

}

?>
