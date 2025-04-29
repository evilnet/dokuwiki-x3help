<?php

/**
 * AfterNET Java Chat
 *
 * Please create the directory "x3help" in your installation of
 * DokuWiki. Now you can put there any HTML or PHP File you want to
 * this directory.
 *
 * <x3help>
 * 
 * The syntax includes the PHP file per include an puts the result into
 * the wiki page.
 *
 * @license    GNU_GPL_v2
 * @author     Neil_Spierling_<sirvulcan@gmail.com>
 */
 
if(!defined('DOKU_INC')) define('DOKU_INC',realpath(dirname(__FILE__).'/../../').'/');
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(DOKU_PLUGIN.'syntax.php');

class syntax_plugin_x3help extends DokuWiki_Syntax_Plugin {
    function getInfo(){
        return array(
            'author' => 'Neil Spierling',
            'email'  => 'sirvulcan@sirvulcan.co.nz',
            'date'   => '2007-08-14',
            'name'   => 'X3 Help',
            'desc'   => 'Interactive X3 help system.',
            'url'    => 'http://www.afternet.org',
        );
    }
 
 
    function getType(){ return 'container'; }
    function getPType(){ return 'normal'; }
    function getAllowedTypes() { 
        return array('substition','protected','disabled');
    }
    function getSort(){ return 195; }
 
    function connectTo($mode) {
        $this->Lexer->addSpecialPattern('<x3help>',$mode,'plugin_x3help');
    }
    function handle($match, $state, $pos, $handler){
 
        switch ($state) {
          case DOKU_LEXER_SPECIAL :
            return array($state, $match);
 
          default:
            return array($state);
        }
    }
 
    function render($mode, $renderer, $indata) {
        if($mode == 'xhtml'){
          list($state, $data) = $indata;
 
          switch ($state) {
            case DOKU_LEXER_SPECIAL :
              $renderer->info['cache'] = FALSE;
              ob_start();

$cfg->help = array( array('name' => 'X3', 'file' => 'chanserv.help'),
                    array('name' => 'AuthServ', 'file' => 'nickserv.help'),
                    array('name' => 'O3', 'file' => 'opserv.help'),
                    array('name' => 'Global', 'file' => 'global.help'),
                    array('name' => 'ModCmd', 'file' => 'modcmd.help'),
                    array('name' => 'HelpServ', 'file' => 'mod-helpserv.help'),
                    array('name' => 'MemoServ', 'file' => 'mod-memoserv.help'),
                    array('name' => 'Snoop', 'file' => 'mod-snoop.help'),
                    array('name' => 'Sockcheck', 'file' => 'mod-sockcheck.help'),
                    array('name' => 'SaxDB', 'file' => 'saxdb.help'),
                    array('name' => 'SendMail', 'file' => 'sendmail.help'),
                    array('name' => 'SpamServ', 'file' => 'spamserv.help')
                  );
$cfg->replacements = array(   array('$C' => "X3"),
                              array('$N' => "AuthServ"),
                              array('$O' => "O3"),
                              array('$$' => "\$"),
                              array('$G' => "Global"),
                              array('$X' => "SpamServ"),
                              array('$s' => "X3.AfterNET.Services") // dcraig was here
                           );

$cfg->module_replacements = array(   array('MemoServ' => 'MemoServ'),
                                     array('X3' => 'X3'),
                                     array('HelpServ' => 'HelpServ'),
                                     array('Snoop' => 'O3'),
                                     array('SaxDB' => 'O3'),
                                     array('ModCmd' => 'O3'),
                                     array('SendMail' => 'O3')
                            );

//error_reporting(E_ALL);

require_once( '/data/web/afternet/www/lib/plugins/x3help/lib/x3help.php' );

function sefRelToAbs($stuff) {
	return $stuff;
}

function draw_header()
{
?>
<script src="/lib/plugins/x3help/js/ajax.js" language="JavaScript"></script>
<script src="/lib/plugins/x3help/js/ajax-dynamic-list.js" language="JavaScript"></script>

<style type="text/css">
        /* Big box with list of options */
        #ajax_listOfOptions{
                position:absolute;      /* Never change this one */
                width:175px;    /* Width of box */
                height:250px;   /* Height of box */
                overflow:auto;  /* Scrolling features */
                border:1px solid #317082;       /* Dark green border */
                background-color:#FFF;  /* White background color */
                text-align:left;
                font-size:0.9em;
                z-index:100;
        }
        #ajax_listOfOptions div{        /* General rule for both .optionDiv and .optionDivSelected */
                margin:1px;
                padding:1px;
                cursor:pointer;
                font-size:0.9em;
        }
        #ajax_listOfOptions .optionDiv{ /* Div for each item in list */

        }
        #ajax_listOfOptions .optionDivSelected{ /* Selected item in the list */
                background-color:#317082;
                color:#FFF;
        }
        #ajax_listOfOptions_iframe{
                background-color:#F00;
                position:absolute;
                z-index:5;
        }

        form{
                display:inline;
        }
</style>
<TABLE border=0 width="100%" height="100%" cellpadding=0 cellspacing=0>
<tr><td valign="top">
<?php /* include("guidebar.php"); */ ?>
</td>
<td valign="top" width="100%" style="padding-left: 2em;">
<h1>Search X3 Help System</h1>
<?php
}

function draw_footer()
{
?>
<br><i><hr>
<?php /*
maintained by <b>&lt;</b><a href="mailto:sirvulcan@gmail.com">sirvulcan@gmail.com</a><b>&gt;</b> - <b>[</b><a 
href="http://evilnet.sourceforge.net/index.php?option=com_x3help&Itemid=81">Home</a><b>]</b> -
<b>[</b><a href="/lib/plugins/com_x3help/com_x3help.tar.gz">Source</a><b>]</b></i>
/* ?>
</td>
<?php
/*
<td style="background: white; border-right: 2px solid #F00;">&nbsp;&nbsp;&nbsp;</td>
<td style="background: #333; border-left: 2px solid #00F;">&nbsp;&nbsp;</td>
*/
?>
</tr></table>

<?php
}

function draw_search_form($module, $search, $cfg)
{
   ?>
   <form name="x3" method="GET" action="?option=<?=$_GET["option"]?>">
   Search X3 Help module
   <select name="module">
   <?php foreach($cfg->help as $v) { ?>
     <option value="<?=$v['name']?>" <?=$v['name']==$module?'SELECTED':''?>><?=$v['name']?></option>
   <?php } ?>
   </select>:
   <input type="Text" name="search" onfocus="ajax_showOptions(this,'getCommandsByLetters',event);" onkeyup="ajax_showOptions(this,'getCommandsByLetters',event);" value="<?=htmlspecialchars($search)?>">
   <input type="hidden" id="search_hidden" name="command_ID">
   <input type="submit" value="Search">
   <input type="hidden" name="option" value="<?=$_GET["option"]?>">
   <input type="hidden" name="Itemid" value="<?=$_GET["Itemid"]?>">
   </form>
   <?php
}

function text_wrap($log_text, $limit, $divider=" ") {
    $words = explode($divider, $log_text);
    $word_count = count($words);
    $char_counter = 0;
    $block = "";
    foreach ($words as $value) {
        $chars = strlen($value);
        $block .= $value;
        $char_counter = $char_counter + $chars;
        if ($char_counter >= $limit) {
            $block .= " \n ";
            $char_counter = 0;
        } else {
            $block .= " ";
        }
    }
    return rtrim($block);
}

function draw_help($help_cfg, $search, $module, $cfg)
{
    $help = readhelp('/data/web/afternet/www/lib/plugins/x3help/' . $cfg->help[$help_cfg]['file']);
    if($help[$search])
    {
        echo "<pre>";
        /* Exact match. Show it */
        //echo "Help file $help_cfg (".$cfg->help[$help_cfg]['file']."): Key $search<PRE>"; print_r($help[$search]); echo "</PRE>";
        if($help[$search]['branch'])
        {
            echo "<h2>The help for this query depends on config settings:</h2>";
            foreach($help[$search]['branch'] as $setting => $h)
            {
                $config = "<h3>CONFIG OPTION(s): ". join(' and ', split(',',$setting)). "</h3>\n";
		if (strlen($config) > 70) {
                    $newconfig = wordwrap($config, 70, "\n", 1);
                    echo $newconfig . "";
                } else
                    echo $config . "\n";

                foreach($h as $line)
                {
                    $text = htmlhelp($line, $module, $help, $cfg);
                    if (strlen($text) > 265) {
                        $newtext = wordwrap($text, 265, "\n", 1);
                        echo $newtext . "\n";
                    } else
                        echo $text . "\n";
                }
                echo "\n\n";
            }
        }
        else
        {
            foreach($help[$search] as $line)
            {
                $text = htmlhelp($line, $module, $help, $cfg);
                if (strlen($text) > 265) {
                    $newtext = wordwrap($text, 250, "\n", 1);
                    echo $newtext . "\n";
                } else
                    echo $text . "\n";
            }
        }
        echo "</pre>";
    }
    else
    {
       /* No match. Look for something close? */
	echo "<br>";
       $link = sefRelToAbs( '?module='. $cfg->help[$help_cfg]['name']. '&search='. urlencode("<INDEX>"));
       echo "<br>Sorry, no help on '$search'. Try the <a href=\"". $link . "\">Default Help</a><BR>";
       $list = '';
       foreach($help as $k => $v)
       {
           $link = sefRelToAbs( '?module='. $module . '&search='. $k);
           $s = str_replace("<", "&lt;", $k);
           $t = str_replace(">", "&gt;", $s);
           $list .= '<a href="'. $link . '">'.$t.'</a>, ';
       }
       $list = substr($list, 0, strlen($list)-2);
       echo "<bR>Or, pick from the help index: $list<BR>";
    }

}


draw_header();
$search = strtoupper($_REQUEST['search']);
$module = $_REQUEST['module'];
draw_search_form($module, $search, $cfg);
if(!$search or $search == '')
    $search = "<INDEX>";
if(!$module)
{
    $module = $cfg->help[0]['name'];
}
foreach($cfg->help as $k => $v)
    if($v['name'] == $module)
    {
       $help_cfg = $k;
       break;
    }
draw_help($help_cfg, $search, $module, $cfg);
draw_footer();

              $content = ob_get_contents();
              ob_end_clean();
              $renderer->doc .= $content;
              break;  
          }
          return true;
        }
        
        // unsupported $mode
        return false;
    } 
}
 
