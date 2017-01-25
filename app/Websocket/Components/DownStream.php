<?php
/**
 * Created by PhpStorm.
 * User: kevint
 * Date: 1/15/2017
 * Time: 1:03 AM
 */

namespace AoE2HDSpectatorServer;


use WsLib\WsComponentInterface;
use WsLib\Client;
use WsLib\WsServer;

class DownStream implements WsComponentInterface
{

    private $_lastFilename;

    function process(Client $client, $message, WsServer $server)
    {
        $command = json_decode($message, true);
        if (isset($command['action']) && $command['action'] === 'pull') {
            $filename = str_replace(".aoe2record", "", $client->query['filename']);
            $player = $client->query['player'];

            $position = 0;

            $fileReader = fopen("../../public/recs/" . $player . "." . $filename . '.aoe2record', "r");

            $this->_lastFilename = "../../public/recs/" . $player . "." . $filename . '.aoe2record';

            if ($fileReader == false) {
                $server->disconnect($client->socket);
                return;
            }
            if (isset($command['position'])) {
                fseek($fileReader, $command['position']);
                $position = $command['position'];
            }

            //if (flock($fileReader, LOCK_SH | LOCK_NB)) {
            if ($position < (256 * 1024)) { //mgz header information
                $buffer = fgets($fileReader, (256 * 1024));
            } else {
                $buffer = fgets($fileReader, 1024);
            }
            //flock($fileReader, LOCK_UN);
            $step = strlen($buffer);
            clearstatcache();
            if ($step == 0 && filesize($this->_lastFilename) > $position) {
                //echo "File error while downstreaming\n";
                $server->send($client, '{"status":"error","position":' . $position . '}');
                return;
            } elseif ($step == 0 && filesize($this->_lastFilename) <= $position) {
                //echo "EOF, prolly just waiting for streamer $position : " . filesize($this->_lastFilename) . " - was given {$command['position']} to start\n";
                $server->send($client, '{"status":"eof","position":' . $position . '}');
                return;
            } else {
                //print_r("Giving {$client->query['filename']} from " . $client->query['player'] . "\n");
                $server->send($client, $buffer, 'binary');
                $position += $step;
                $server->send($client, '{"action":"continue","position":' . $position . '}');

            }
            //}

            fclose($fileReader);
        } elseif (isset($command['action']) && strcasecmp($command['action'], 'sha1')) {
            $server->send($client, '{"action":"checksum","type":"sha1","value":"' . sha1_file($this->_lastFilename) . '"}');
            return;
        } elseif (isset($command['action']) && strcasecmp($command['action'], 'md5')) {
            $server->send($client, '{"action":"checksum","type":"md5","value":"' . md5_file($this->_lastFilename) . '"}');
            return;
        }
    }

    function connected(Client $client, WsServer $server)
    {
        $client->spectator = true;
        $this->client = $client;
        $this->query = $client->query;
    }

    function closed(Client $client, WsServer $server)
    {
        //$server->stdout("SHA1 Checksum: " . sha1_file($this->_lastFilename));
    }
}