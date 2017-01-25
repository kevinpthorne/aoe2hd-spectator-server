<?php
/**
 * Created by PhpStorm.
 * User: kevint
 * Date: 1/15/2017
 * Time: 12:32 AM
 */

namespace AoE2HDSpectatorServer;

use WsLib\Client;
use WsLib\WsComponentInterface;
use WsLib\WsServer;

class UpStream implements WsComponentInterface
{

    function process(Client $streamer, $data, WsServer $server)
    {
        $query = $streamer->query;

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
            $success = fwrite($fileWriter, $data);
            flock($fileWriter, LOCK_UN);
        }
        //$streamer->sizeSent += strlen(serialize($this->_data))/1024;
        if ($success) {
            $msg = 'continue';
        } else {
            $msg = 'error';
        }
        fclose($fileWriter);
        $server->send($streamer, $msg);
    }

    function connected(Client $client, WsServer $server)
    {
        print_r("Ready to receive {$client->query['filename']} from " . $client->query['player'] . "\n");
        $client->spectator = false;
    }

    function closed(Client $client, WsServer $server)
    {
        // TODO: Implement closed() method.
    }
}