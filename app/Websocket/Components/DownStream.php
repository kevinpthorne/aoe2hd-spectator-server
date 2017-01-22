<?php
/**
 * Created by PhpStorm.
 * User: kevint
 * Date: 1/15/2017
 * Time: 1:03 AM
 */

namespace AoE2HDSpectatorServer;


use WsLib\WsComponentInterface;
use WsLib\WsServer;

class DownStream implements WsComponentInterface
{
    const ERROR_CAP = 10;

    private $_lastFilename;

    function process($client, $message, WsServer $server)
    {
        if($message === "1") {
            sleep(1);

            $query = $client->query;
            $filename = str_replace(".aoe2record", "", $query['filename']);
            $player = $query['player'];

            echo "{$client->id} requested \"recs/$player.$filename.aoe2record\"\n";

            $errors = 0;
            $position = 0;

            $fileReader = fopen("../../public/recs/" . $player . "." . $filename . '.aoe2record', "r");

            $this->_lastFilename = "../../public/recs/" . $player . "." . $filename . '.aoe2record';

            if($fileReader == false) {
                $server->disconnect($client->socket);
                return;
            }
            if(isset($query['position'])) {
                fseek($fileReader, $query['position']);
                $position = $query['position'];
            }
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
                $server->send($client, $buffer, 'binary');
                $position += $step;
                //echo "\t$position\n";
                usleep(100000);
            }

            fclose($fileReader);
            //$conn->send("{'position':$position}");
            //$client->sizeSent += $position/1024; //@todo: fix math

            sleep(2);

            $server->disconnect($client->socket);

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