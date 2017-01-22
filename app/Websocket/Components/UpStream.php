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

    function process($streamer, $data, WsServer $server)
    {
        $query = $streamer->query;
        print_r("Receiving {$query['filename']} from " . $query['player'] . "\n");
        $filename = str_replace(".aoe2record", "", $query['filename']);
        $player = $query['player'];
        // @todo: see if file exists. if so, deny
        $fileWriter = fopen("../../public/recs/" . $player . "." . $filename . '.aoe2record', "a");
        $success = fwrite($fileWriter, $data);
        $streamer->sizeSent += strlen(serialize($data))/1024;
        if ($success) {
            $msg = 'continue';
        } else {
            $msg = 'error';
        }
        fclose($fileWriter);
        $server->send($streamer, $msg);
    }

    function connected($client, WsServer $server)
    {
        $client->spectator = false;
    }

    function closed($client, WsServer $server)
    {
        // TODO: Implement closed() method.
    }
}