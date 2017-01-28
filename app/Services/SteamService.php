<?php

namespace App\Services;

use ErrorException;
use Log;

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

    public function login()
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

                    $_SESSION['steamid'] = $matches[1];
                    error_log($_SESSION['steamid']);
                    $this->update();
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

    public function update($steamId = "")
    {
        $url = file_get_contents("http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key="
            . SteamService::$steamConfig['apikey']
            . "&steamids=" . (empty($steamId) ? $_SESSION['steamid'] : $steamId));
        $content = json_decode($url, true);
        $_SESSION['steam_steamid'] = $content['response']['players'][0]['steamid'];
        $_SESSION['steam_communityvisibilitystate'] = $content['response']['players'][0]['communityvisibilitystate'];
        $_SESSION['steam_profilestate'] = $content['response']['players'][0]['profilestate'];
        $_SESSION['steam_personaname'] = $content['response']['players'][0]['personaname'];
        $_SESSION['steam_lastlogoff'] = $content['response']['players'][0]['lastlogoff'];
        $_SESSION['steam_profileurl'] = $content['response']['players'][0]['profileurl'];
        $_SESSION['steam_avatar'] = $content['response']['players'][0]['avatar'];
        $_SESSION['steam_avatarmedium'] = $content['response']['players'][0]['avatarmedium'];
        $_SESSION['steam_avatarfull'] = $content['response']['players'][0]['avatarfull'];
        $_SESSION['steam_personastate'] = $content['response']['players'][0]['personastate'];
        if (isset($content['response']['players'][0]['realname'])) {
            $_SESSION['steam_realname'] = $content['response']['players'][0]['realname'];
        } else {
            $_SESSION['steam_realname'] = "Real name not given";
        }
        $_SESSION['steam_primaryclanid'] = $content['response']['players'][0]['primaryclanid'];
        $_SESSION['steam_timecreated'] = $content['response']['players'][0]['timecreated'];
        $_SESSION['steam_uptodate'] = time();
    }

}