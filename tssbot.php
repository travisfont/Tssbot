<?php

set_time_limit(0);

/* configs */

define('HOST',     'localhost');
define('USER',     'root');
define('PASSWORD',  NULL);
define('DATABASE', 'tssbot');

define('SERVER',   'pratchett.freenode.net');
define('PORT',     6667);
define('NICK',    'Tssbot'.rand());

// the great beloved channel list
$channels = array
(
    'ubuntu',
    'freenode',
    'bash'
);

// open irc socket connection ^ OF COURSE!
if ($ircSocket = fsockopen(SERVER, PORT, $errno, $errstr))
{
    // time to say hello to the server
    fwrite($ircSocket, "USER ".NICK." ".NICK." ".NICK." :".NICK."\n");
    fwrite($ircSocket, "NICK ".NICK."\n");

    // connecting to channels
    foreach ($channels as $channel)
    {
        fwrite($ircSocket, "JOIN #". $channel."\n");
    }

    // PRIVMSG #channel :HALLO!
    // PRIVMSG User :Moien kolleg!

    while (1)
    {
        while ($data = fgets($ircSocket))
        {
            echo nl2br($data);
            flush();

            // separate all data
            $exData = explode(' ', $data);

            // send PONG back to the server
            if ($exData[0] == "PING")
            {
                fwrite($ircSocket, "PONG ".$exData[1]."\n");
            }
            else
            {
                // looping through the channels when there's a response (required so data isn't lost per channel)
                foreach ($channels as $channel)
                {
                    // finding data PRIVMSG responses
                    if (strpos($data, "PRIVMSG #".$channel." :") !== FALSE)
                    {
                        // extracting the PRIVMSG responses (by channel - of course ;-)
                        list($nick, $msg) = explode("PRIVMSG #".$channel." :", $data);
                        break;
                    }
                    else
                    {
                        // server responses
                        $nick    = 'SERVER';
                        $msg     = $data;
                        $channel = 'SERVER';
                    }
                }

                // logging the responses
                myquery("INSERT INTO `logs` (`id`,  `channel`, `nick`, `msg`) VALUES (NULL ,  '".$channel."', '".$nick."', '".$msg."');");
            }
        }
    }
}
else
{
    // WTF ?!?!
    echo $errno . ": " . $errstr;
}


/* --------- REDO THIS WITH PDO WHEN NOT LAZY --- */

function myquery($query)
{
    if (!$link = mysql_connect(HOST, USER, PASSWORD))
    {
        die('error - connection credentials incorrect: '.mysql_error());
    }
    if(!$db = mysql_select_db(DATABASE))
    {
        die("error - db unavailable.");
    }
    $result = mysql_query($query);
    if (mysql_error())
    {
        echo mysql_error() ."\n";
        return FALSE;
    }
    else
    {
        if (strpos($query, 'SELECT') !== FALSE)
        {
            $output = array();
            while ($row = mysql_fetch_assoc($result))
            {
                $output[] = $row;
            }

            mysql_close($link);
            return $output;
        }
        else
        {
            mysql_close($link);
            return $result;
        }
    }
}

#END;
