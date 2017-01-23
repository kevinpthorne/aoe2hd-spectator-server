<?php
/**
 * Created by PhpStorm.
 * User: kevint
 * Date: 1/15/2017
 * Time: 1:03 AM
 */

namespace AoE2HDSpectatorServer;


use Thread;
use WsLib\WsComponentInterface;
use WsLib\WsServer;

class DownStream extends Thread implements WsComponentInterface
{
    const ERROR_CAP = 10;

    private $_lastFilename;

    private $_server;
    private $_client;
    private $_message;

    /**
     * DownStream constructor.
     * @param $_server
     */
    public function __construct($server)
    {
        $this->_server = $server;
    }


    function run() {
        $query = $this->_client->query;
        $filename = str_replace(".aoe2record", "", $query['filename']);
        $player = $query['player'];

        echo "{$this->_client->id} requested \"recs/$player.$filename.aoe2record\"\n";

        $errors = 0;
        $position = 0;

        $fileReader = fopen("../../public/recs/" . $player . "." . $filename . '.aoe2record', "r");

        $this->_lastFilename = "../../public/recs/" . $player . "." . $filename . '.aoe2record';

        if($fileReader == false) {
            $this->_server->disconnect($this->_client->socket);
            return;
        }
        if(isset($query['position'])) {
            fseek($fileReader, $query['position']);
            $position = $query['position'];
        }

        sleep(1);

        while($errors < DownStream::ERROR_CAP) {
            if ($position < (256 * 1024)) { //mgz header information
                $buffer = fgets($fileReader, (256 * 1024));
            } else {
                $buffer = fgets($fileReader, 1024);
            }
            $step = strlen($buffer);
            if ($step == 0) {
                echo "Retrying...[Attempt " . ($errors+1) . "/" . DownStream::ERROR_CAP . "]\n";
                fseek($fileReader, $position);
                $errors++;
                sleep(1);
                continue;
            }
            $errors = 0;
            $this->_server->send($this->_client, $buffer, 'binary');
            $position += $step;
            //echo "\t$position\n";
            usleep(100000);
        }

        fclose($fileReader);
        //$conn->send("{'position':$position}");
        //$client->sizeSent += $position/1024; //@todo: fix math

        sleep(1);

        $this->_server->disconnect($this->_client->socket);
    }

    function process($client, $message, WsServer $server)
    {
        if($message === "start") {
            $_server = $server;
            $_client = $client;
            $_message = $message;

            $this->start();
        }
    }

    function connected($client, WsServer $server)
    {
        $client->spectator = true;
    }

    function closed($client, WsServer $server)
    {
        $server->stdout("MD5 Checksum: ". md5_file ($this->_lastFilename));
    }
}