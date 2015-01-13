<?php

class DB
{
    private $connection;

    public function __construct()
    {
        $this->connection = new PDO('mysql:host='.HOST.';dbname='.DATABASE.';charset=utf8', USER, PASSWORD);
    }

    public function insertLog($channel, $nick, $msg)
    {
        try
        {
            $query = "INSERT INTO `logs` (`id`, `channel`, `nick`, `msg`) VALUES (NULL, :channel, :nick, :msg)";
            $insert = $this->connection->prepare($query);
            $params = array
            (
                'channel' => $channel,
                'nick'    => $nick,
                'msg'     => trim($msg)
            );

            if ($insert->execute($params))
            {
                return TRUE;
            }
            else
            {
                return FALSE;
            }
        }
        catch (PDOException $ex)
        {
            return $ex;
        }
    }
}