<?php

// keeps the script process alive
set_time_limit(0);

// server configurations + channels
require_once 'configs.php';

// database class
require_once 'class.database.php';

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

    // intialize database connection
    $db = new DB;

    // PRIVMSG #channel :HALLO!
    // PRIVMSG User :Moien kolleg!

    while (TRUE)
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
                $db->insertLog($channel, $nick, $msg);
            }
        }
    }
}
else
{
    // WTF ERROR? NO WAY?!?!
    echo $errno . ": " . $errstr;
}

#END;
