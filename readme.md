[![Logo](https://github.com/kevinpthorne/aoe2hd-spectator-client/raw/master/src/main/resources/icons/ready.png)]() 
Age of Empires 2 HD - Spectator Server
======

This is the server backend/website for the *unofficial* companion app that streams AoE2 HD games live.

Forenote, currently broken, please don't try to install yet!

## Overview

This server is broken into 2 parts: **Websocket** server backend that manages the actually streaming of recordings and **Laravel** for a web frontend to display available live games. Both parts are written on PHP 7.

## How it works

![Overview](https://github.com/kevinpthorne/aoe2hd-spectator-server/blob/master/docs/graphics/Overview.png)

This is essentially what Voobly does under the hood, just not as integrated since HD doesn't expose controls like UserPatch does.

![UpStream](https://github.com/kevinpthorne/aoe2hd-spectator-server/blob/master/docs/graphics/Upstream.png)

Age of Empires 2 saves the recording files as the game progresses. (This is why recording games on older computers/hard drives lags the game). The [Client](https://github.com/kevinpthorne/aoe2hd-spectator-client) take the recording file, as it's coming, and upload them to a Relay Server (this repo). Clients can then, after accessing the Server's webpage, can then download and watch the game.

![DownStream](https://github.com/kevinpthorne/aoe2hd-spectator-server/blob/master/docs/graphics/Downstream.png)

## Requirements
 
At the moment, you'll need a fast hard drive to minimize load times. Other requirements:

- PHP 7
  - pthreads v3 extention to enable for the Websocket Server

## How to run

If you *really* want to play it in its current broken form, this is how you would run it.

```php artisan serve``` - Web front

```cd app\Websocket; php -dextension=path/to/php_pthreads.so App.php``` - Actual streaming server

## Configuration

You can edit ```App.php``` to change the port of the Websocket server.

The web front end by default uses port 8000 for development purposes; change it with this:

```php artisan serve --port=8080```

## DISCLAIMER

This is not only served AS-IS, but Microsoft, Ensemble Studios, Skybox, and whoever else you want to associate Age of Empires 2 with has nothing to do with these projects. Purely started by me, Kevin "echospot" Thorne.

## License & Contributing

LICENSE.MD includes all licenses, mostly for libraries included (such as [PHP-Websockets](https://github.com/ghedipunk/PHP-Websockets), Java Websocket Libraries, etc). It also will include an Apache License 2.0. [This means you can contribute freely! Just tell everyone what you did and include previous licenses!](https://tldrlegal.com/license/apache-license-2.0-(apache-2.0))
