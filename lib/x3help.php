<?php

// This tool is for reading X3 helpfiles for presentation on the web.
//
// Copyright Alex Schumann
//
// 8/17/2005

/*  Content we are parseing looks like this ..
"MYACCESS" ("/msg $S MYACCESS [<nick|*account>]",
        "Lists channels where you have access and infolines in each.",
        "Network staff may specify a nickname or *account to view the list for another user.",
        "Your access level in the channel may be followed by a comma and one of the following characters:",
        "  s     Your access in the channel has been suspended.",
        "  o     AutoOp is active.",
        "  v     AutoVoice is active.",
        "  i     AutoInvite is active.",
        "$uSee Also:$u access, users");
*/

/* Which should be translated to an array such as this */
$help['MYACCESS'] = array('/msg $S MYACCESS [<nick|*account>]',
                          'Lists channels where you have access and infolines in each.',
                          'Network staff may specify a nickname or *account to view the list for another user.',
                          'Your access level in the channel may be followed by a comma and one of the following characters:',
                          '  s     Your access in the channel has been suspended.',
                          '  o     AutoOp is active.',
                          '  v     AutoVoice is active.',
                          '  i     AutoInvite is active.',
                          '$uSee Also:$u access, users');


function htmlhelp($string, $module, $help, $cfg)
{
    $string = htmlspecialchars($string);
    foreach($cfg->replacements as $r)
        foreach($r as $k => $v) {
            $string = str_replace($k, $v, $string);
            if(stristr($string, '$S') != FALSE) {
                foreach($cfg->module_replacements as $s)
                    foreach($s as $l => $w) {
                         if (strcasecmp($l, $module) == 0) {
                             $string = str_replace("\$S", $w, $string);
                         }
                    }
            }
        }
    $count = 0;

    /* Wrap long lines */
    if(strlen($string) > 80)
    {
        $jj = 60;
        while($jj < strlen($string))
        {
		for($i=$jj;$i<strlen($string);$i++)
                {
		   if($string[$i] == ' ')
                   {
		     $string[$i] = "\n";
                     break;
                   }
                }
                $jj = $i+60;
        }
    }

    /* Special handling for the see also line */
    if(preg_match('/^\$u(S|s)ee (A|a)lso:{0,1}\$u/', $string))
    {
        $wordarray = preg_split('/(\$u |\, |\: )/', $string, -1, PREG_SPLIT_DELIM_CAPTURE);
        /* foreach($wordarray as $w) echo "DEBUG: $w<BR>\n"; */
    }
    else /* Break up into words but save the seperators */
    {
        $wordarray = preg_split('/($|\s|\$b|\:|\,|\.|\;|$u)/', $string, -1, PREG_SPLIT_DELIM_CAPTURE);
    }

    /* Look if each word matches a help string, and link it */
    for($i = 0; $i<count($wordarray);$i++)
    {
        if(array_key_exists(strtoupper($wordarray[$i]), $help)) {
            $link = sefRelToAbs( '?module='. $module . '&search='. strtoupper($wordarray[$i]));
            $wordarray[$i] = "<a href=\"". $link . "\">{$wordarray[$i]}</a>";
        }
    }
    /* Re-join the words to a string */
    $string = join('', $wordarray);

    while(preg_match('/\$b/', $string))
    {
        if(++$count % 2) $b = '<b>';
        else $b = '</b>';
        $string = preg_replace('/\\$b/', $b, $string, 1);
    }
    $count = 0;
    while(preg_match('/\$u/', $string))
    {
        if(++$count % 2) $b = '<i>';
        else $b = '</i>';
        $string = preg_replace('/\\$u/', $b, $string, 1);
    }
    preg_replace('/\\$!/', ".", $string, 1);
    return($string);
}


function readhelp($file)
{
    $raw = file_get_contents($file);

    //header("content-type: text/plain");
    //echo "DEBUG: Read $file, got ". strlen($raw) . " bytes.\n";
    $help = array();
    $reg_quote = 0;
    $reg_parenthises = 0;
    $reg_newline = 1;
    $reg_escape = 0;
    $reg_branch = 0;
    $line = 1;

    $buff = '';
    $key = false;
    $branch = array();

    for($i = 0;$i<strlen($raw);$i++)
    {
        $c = $raw[$i];

        /* If we are currently in a quoted string, handle that.. */
        if($reg_quote)
        {
            if($c == '\\')
            {
                $reg_escape++;
                continue;
            }

            if($reg_escape)
            {
                $buff .= $c;
                $reg_escape--;
            }
            elseif($c == '"') /* End of a "string", save it in $array */
            {
                $reg_quote--;
                if($key === false) /* We have no key, so this must be the key.. */
                {
                   $key = strtoupper($buff);
                   //echo "DEBUG: Line $line: We have no key, so making $key our key\n";
                   $help[$key] = array();
                }
                else
                {
                    /* If we have got a { but havnt set its variable yet, do so */
                    if($reg_branch and !array_key_exists($reg_branch, $branch))
                    {
                        //echo "DEBUG: Line $line: No branch $reg_branch found, so making $buff a branch";
                        $branch[$reg_branch] = "$buff";
                    }
                    else /* Else, must be actual content.. save it */
                    {
                        //echo "DEBUG: Line $line: key={$key} reg_branch={$reg_branch} \$branch[\$reg_branch]={$branch[$reg_branch]}\n";
                        //echo "Setting \$help[\"". $key.join('|', $branch). "\"][] = \"$buff\"\n";
                        if($branch)
                            $help[$key]['branch'][join(',',$branch)][] = $buff;
                        else
                            $help[$key][] = $buff;
                    }
                }
                $buff = '';
            }
            else
            {
                $buff .= $c;
            }
        }
        else /* Not in a quoted string.. */
        {
            switch($c)
            {
                case '"': $reg_quote++;
                          break;
                case '(': $reg_parenthises++;
                          break;
                case ')': $reg_parenthises--; 
                          break;
                case ';':
                            //echo "DEBUG Line $line: Got a ; while reg_branch=$reg_branch and key=$key<BR>";
                            if($reg_parenthises)
                                die("Line $line: Got ; when inside a () block, don't know what to do");
                            else
                            {
                                if($reg_branch == 0)
                                {
                                   //echo "DEBUG: Line $line: End of storage\n";
                                   $key = false; /* reset */
                                }
                                else
                                {
                                    $foo = array_pop($branch);
                                    //echo "DEBUG: Line $line: End of branch [$foo]\n";
                                }
                            }
                            break;
                case '{': $reg_branch++;
                          //echo "DEBUG: Line $line: Increased \$reg_branch to $reg_branch\n";
                          break;
                case '}': //echo "DEBUG: Line $line: Decreased \$reg_branch to $reg_branch\n";
                          $reg_branch--;
                          break;
                case ' ': case "\t": case ",": break;
                case "\n": $line++; break;
                default:
                        die("Line $line: Got [$c] (".ord($c).") and don't know what to do with it!");
            }
        }
    
    }
   return($help);
}




?>
