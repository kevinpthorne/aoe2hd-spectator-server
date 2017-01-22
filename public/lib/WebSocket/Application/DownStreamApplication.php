<?php
/**
 * Created by PhpStorm.
 * User: kevint
 * Date: 1/11/2017
 * Time: 9:38 PM
 */

namespace WebSocket\Application;

use WebSocket\Connection;

class DownStreamApplication extends Application
{

    private $spectators = array();

    /**
     * @param Connection $spectators
     */
    public function onConnect($spectator)
    {
        $id = $spectator->getClientId();
        $this->spectators[$id] = $spectator;
    }

    /**
     * @param Connection $spectator
     */
    public function onDisconnect($spectator)
    {
        $id = $spectator->getClientId();
        unset($this->spectators[$id]);
    }

    /**
     * @param Connection $spectator
     */
    public function onData($request, $spectator)
    {
        $filename = $spectator->getQueryParameters()['filename'];
        $player = $spectator->getQueryParameters()['player'];
        if ($filename === null || $player === null) {
            // @todo: invalid request trigger error...
            $spectator->close(1002);
        }
        $fileReader = fopen("recs/" . $player . "." . $filename . '.aoe2record', "r");
        if($fileReader === false) {
            $spectator->close(1002);
        }
        $errors = 0;
        while (true) {
            $buffer = fgets($fileReader, 4096);
            if (strlen($buffer) == 0) {
                $errors++;
                $spectator->log("Error, attempt " . $errors . "/5");
                if ($errors > 5) {
                    fclose($fileReader);
                    $spectator->close(1001);
                }
            }
            $spectator->send($buffer, 'binary');
        }
    }
}