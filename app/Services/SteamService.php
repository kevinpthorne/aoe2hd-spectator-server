<?php

namespace App\Services;

use App\User;
use ErrorException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SteamService
{

    private static $steamConfig = array();

    /**
     * SteamService constructor.
     */
    public function __construct()
    {
        //Version 3.2
        // Your Steam WebAPI-Key found at http://steamcommunity.com/dev/apikey
        SteamService::$steamConfig['apikey'] = "C782513B31A877DAAC6F646372521C65";
        // The main URL of your website displayed in the login page
        SteamService::$steamConfig['domainname'] = "core.aptitekk.com:8083";
        // Page to redirect to after a successful logout (from the directory the SteamAuth-folder is located in) - NO slash at the beginning!
        SteamService::$steamConfig['logoutpage'] = "/";
        // Page to redirect to after a successful login (from the directory the SteamAuth-folder is located in) - NO slash at the beginning!
        SteamService::$steamConfig['loginpage'] = "/you";

    }

    public function login($session)
    {
        try {
            $openid = new LightOpenID(SteamService::$steamConfig['domainname']);

            if (!$openid->mode) {
                $openid->identity = 'http://steamcommunity.com/openid';
                return redirect($openid->authUrl());
            } elseif ($openid->mode == 'cancel') {
                //error_log('User has canceled authentication!');
                return redirect('/gologin');
            } else {
                if ($openid->validate()) {
                    $id = $openid->identity;
                    $ptn = "/^http:\/\/steamcommunity\.com\/openid\/id\/(7[0-9]{15,25}+)$/";
                    preg_match($ptn, $id, $matches);

                    //$_SESSION['steamid'] = $matches[1];
                    $session->put('steamid', $matches[1]);

                    $this->update($session);
                    return redirect(SteamService::$steamConfig['loginpage']);
                } else {
                    //error_log("User is not logged in.\n");
                    return redirect('/gologin');
                }
            }
        } catch (ErrorException $e) {
            error_log($e->getMessage());
            return redirect('/gologin');
        }
    }

    public function logout()
    {
        return redirect(SteamService::$steamConfig['logoutpage']);
    }

    public function update($session, $steamId = "")
    {
        $url = file_get_contents("http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key="
            . SteamService::$steamConfig['apikey']
            . "&steamids=" . (empty($steamId) ? $session->get('steamid') : $steamId));
        $content = json_decode($url, true);
        $session->put('steam_steamid', $content['response']['players'][0]['steamid']);
        $session->put('steam_communityvisibilitystate', $content['response']['players'][0]['communityvisibilitystate']);
        $session->put('steam_profilestate', $content['response']['players'][0]['profilestate']);
        $session->put('steam_personaname', $content['response']['players'][0]['personaname']);
        $session->put('steam_lastlogoff', $content['response']['players'][0]['lastlogoff']);
        $session->put('steam_profileurl', $content['response']['players'][0]['profileurl']);
        $session->put('steam_avatar', $content['response']['players'][0]['avatar']);
        $session->put('steam_avatarmedium', $content['response']['players'][0]['avatarmedium']);
        $session->put('steam_avatarfull', $content['response']['players'][0]['avatarfull']);
        $session->put('steam_personastate', $content['response']['players'][0]['personastate']);
        if (isset($content['response']['players'][0]['realname'])) {
            $session->put('steam_realname', $content['response']['players'][0]['realname']);
        } else {
            $session->put('steam_realname', "Real name not given");
        }
        $session->put('steam_primaryclanid', $content['response']['players'][0]['primaryclanid']);
        $session->put('steam_timecreated', $content['response']['players'][0]['timecreated']);
        $session->put('steam_uptodate', time());

        try {
            $user = User::findOrFail($session->get('steamid'));
        } catch (ModelNotFoundException $e) {
            $user = new User;

            $user->id = $session->get('steamid');
            $user->name = $session->get('steam_personaname');
            $user->key = "key_" . sha1(uniqid($session->get('steamid')));
            $user->avatar = $session->get('steam_avatarmedium');

            $user->save();
        }

    }

}