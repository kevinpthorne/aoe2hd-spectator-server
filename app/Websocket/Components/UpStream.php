<?php
/**
 * Created by PhpStorm.
 * User: kevint
 * Date: 1/15/2017
 * Time: 12:32 AM
 */

namespace AoE2HDSpectatorServer;

use Thread;
use WsLib\WsComponentInterface;
use WsLib\WsServer;

class UpStream extends Thread implements WsComponentInterface
{

    private $_server;

    private $_streamer;
    private $_data;

    /**
     * DownStream constructor.
     * @param $_server
     */
    public function __construct($server)
    {
        $this->_server = $server;
    }

    function run() {
        $query = $this->_streamer->query;
        print_r("Receiving {$query['filename']} from " . $query['player'] . "\n");
        $filename = str_replace(".aoe2record", "", $query['filename']);
        $player = $query['player'];
        // @todo: see if file exists. if so, deny
        $fileWriter = fopen("../../public/recs/" . $player . "." . $filename . '.aoe2record', "a");
        $success = false;
        if(flock($fileWriter, LOCK_EX | LOCK_NB)) {
            $success = fwrite($fileWriter, $this->_data);
            flock($fileWriter, LOCK_UN);
        }
        $this->_streamer->sizeSent += strlen(serialize($this->_data))/1024;
        if ($success) {
            $msg = 'continue';
        } else {
            $msg = 'error';
        }
        fclose($fileWriter);
        $this->_server->send($this->_streamer, $msg);
    }

    function process($streamer, $data)
    {
        $this->_streamer = $streamer;
        $this->_data = $data;

        $this->start();
    }

    function connected($client)
    {
        $client->spectator = false;
    }

    function closed($client)
    {
        // TODO: Implement closed() method.
    }
}