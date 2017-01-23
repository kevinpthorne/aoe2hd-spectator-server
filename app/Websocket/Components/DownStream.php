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

    private $server;
    private $client;
    private $message;

    private $query;

    /**
     * DownStream constructor.
     * @param $_server
     */
    public function __construct(WsServer &$server)
    {
        $this->server = $server;
    }

    function run() {

        echo "{$this->client->socket}\n";

        $filename = str_replace(".aoe2record", "", $this->query['filename']);
        $player = $this->query['player'];

        echo "{$this->client->id} requested \"recs/$player.$filename.aoe2record\"\n";

        $errors = 0;
        $position = 0;

        $fileReader = fopen("../../public/recs/" . $player . "." . $filename . '.aoe2record', "r");

        $this->_lastFilename = "../../public/recs/" . $player . "." . $filename . '.aoe2record';

        if($fileReader == false) {
            $this->server->disconnect($this->client->socket);
            return;
        }
        if(isset($query['position'])) {
            fseek($fileReader, $query['position']);
            $position = $query['position'];
        }

        sleep(1);

        while($errors < DownStream::ERROR_CAP) {
            $buffer = "";
            if(flock($fileReader, LOCK_SH | LOCK_NB)) {
                if ($position < (256 * 1024)) { //mgz header information
                    $buffer = fgets($fileReader, (256 * 1024));
                } else {
                    $buffer = fgets($fileReader, 8192);
                }
                flock($fileReader, LOCK_UN);
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
            $this->server->send($this->client, $buffer, 'binary');
            $position += $step;
            //echo "\t$position\n";
            usleep(100000);
        }

        fclose($fileReader);
        //$conn->send("{'position':$position}");
        //$client->sizeSent += $position/1024; //@todo: fix math

        sleep(1);

        $this->server->disconnect($this->client->socket);
    }

    function process(&$client, $message)
    {
        print_r($client);
        $this->client = clone $client;
        $this->query = $client->query;
        if($message === "start") {
            $this->start();
        }
    }

    function connected(&$client)
    {
        $client->spectator = true;
    }

    function closed(&$client)
    {
        $this->server->stdout("MD5 Checksum: ". md5_file ($this->_lastFilename));
    }
}