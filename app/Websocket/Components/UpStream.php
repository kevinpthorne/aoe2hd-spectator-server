<?php
/**
 * Created by PhpStorm.
 * User: kevint
 * Date: 1/15/2017
 * Time: 12:32 AM
 */

namespace AoE2HDSpectatorServer;

use WsLib\WsComponentInterface;
use WsLib\WsServer;

class UpStream implements WsComponentInterface
{

    private $_server;

    private $_streamer;
    private $_data;

    /**
     * DownStream constructor.
     * @param $_server
     */
    public function __construct(WsServer $server)
    {
        $this->_server = $server;
    }

    function process($streamer, $data)
    {
        $this->_data = $data;
        //$this->start();

        $query = $this->_streamer->query;
        print_r("Receiving {$query['filename']} from " . $query['player'] . "\n");
        $filename = str_replace(".aoe2record", "", $query['filename']);
        $player = $query['player'];
        // @todo: see if file exists. if so, deny
        if (!file_exists('../../public/recs')) {
            mkdir('../../public/recs', 0755, false);
        }
        $fileWriter = fopen("../../public/recs/" . $player . "." . $filename . '.aoe2record', "a");
        $success = false;
        if(flock($fileWriter, LOCK_EX | LOCK_NB)) {
            $success = fwrite($fileWriter, $this->_data);
            flock($fileWriter, LOCK_UN);
        }
        //$this->_streamer->sizeSent += strlen(serialize($this->_data))/1024;
        if ($success) {
            $msg = 'continue';
        } else {
            $msg = 'error';
        }
        fclose($fileWriter);
        $this->_server->send($this->_streamer, $msg);
    }

    function connected($client)
    {
        $this->_streamer = $client;
        $client->spectator = false;
    }

    function closed($client)
    {
        // TODO: Implement closed() method.
    }
}