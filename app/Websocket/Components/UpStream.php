<?php
/**
 * Created by PhpStorm.
 * User: kevint
 * Date: 1/15/2017
 * Time: 12:32 AM
 */

namespace AoE2HDSpectatorServer;

use App\User;
use App\Game;

use Illuminate\Database\Eloquent\ModelNotFoundException;

use WsLib\Client;
use WsLib\WsComponentInterface;
use WsLib\WsServer;

class UpStream implements WsComponentInterface
{

    function process(Client $streamer, $data, WsServer $server)
    {
        $query = $streamer->query;

        print_r("Receiving {$query['filename']} from " . $streamer->user->name . "\n");

        $player = $streamer->user->id;
        // @todo: see if file exists. if so, deny
        if (!file_exists('../../public/recs')) {
            mkdir('../../public/recs', 0755, false);
        }
        $fileWriter = fopen("../../public/recs/" . $player . "." . $streamer->game->id . '.aoe2record', "a");
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

        if(!isset($client->query['key'])) {
            $server->disconnect($client->socket);
            echo "No key specified\n";
            return;
        } else {
            $key = $client->query['key'];
            try {
                $user = User::where('key', $key)->firstOrFail();

                $game = new Game;
                $game->id = uniqid("g");
                $game->user_id = $user->id;
                $game->filename = $client->query['filename'];
                $game->time_start = date("Y-m-d H:i:s");
                $game->time_end = null;

                $game->save();

                $client->user = $user;
                $client->game = $game;

                $server->send($client, $game->id);

            } catch(ModelNotFoundException $e) {
                echo "No user found\n";
                $server->disconnect($client->socket);
                return;
            }
        }

        print_r("Ready to receive {$client->query['filename']} from " . $client->query['key'] . "\n");
    }

    function closed(Client $client, WsServer $server)
    {
        $game = Game::find($client->game->id);
        $game->time_end = date("Y-m-d H:i:s");
        $game->save();
    }
}