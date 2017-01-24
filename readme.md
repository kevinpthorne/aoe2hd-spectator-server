# Age of Empires 2 HD - Spectator Server

This is the server backend/website for the *unofficial* companion app that streams AoE2 HD games live.

Forenote, currently broken, please don't try to install yet!

## Overview

This server is broken into 2 parts: **Websocket** server backend that manages the actually streaming of recordings and **Laravel** for a web frontend to display available live games. Both parts are written on PHP 7.

## How it works

![Overview](https://github.com/kevinpthorne/aoe2hd-spectator-server/blob/master/docs/graphics/Simple%20Diagram.svg "Overview")

## Requirements
 
At the moment, you'll need a fast hard drive to minimize load times. Other requirements:

- PHP 7
  - pthreads v3 extention to enable for the Websocket Server

## How to run

If you *really* want to play it in its current broken form, this is how you would run it.

```php artisan serve``` - Web front

```cd app\Websocket; php App.php``` - Actual streaming server

## Configuration

You can edit ```App.php``` to change the port of the Websocket server.

The web front end by default uses port 8000 for development purposes; change it with this:

```php artisan serve --port=8080```
