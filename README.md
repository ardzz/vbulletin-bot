# [vBulletin RCE - BOT](https://github.com/ardzz/vbulletin-bot)
[![Version](https://img.shields.io/badge/Version-1.0-brightgreen.svg?maxAge=259200)]()
[![Stage](https://img.shields.io/badge/Release-Beta-green.svg)]()
[![Build](https://img.shields.io/badge/Type_-_exploit-green.svg?maxAge=259200)]()
[![HitCount](http://hits.dwyl.io/ardzz/vbulletin-bot.svg)](http://hits.dwyl.io/ardzz/vbulletin-bot)
![screenshot](https://raw.githubusercontent.com/ardzz/vbulletin-bot/master/screenshot/vbulletin-2.jpg)

The vBulletin team about the zero-day public disclosure, now tracked as CVE-2019-16759, the project maintainers today released security patches for vBulletin versions 5.5.2, 5.5.3, and 5.5.4

## Requirements
* PHP 7.\*.\*
* PHP cURL

## Usage
```bash
php composer.phar dump-autoload -o
php vBot.php list_targets.txt
```
## Dork
```
intext:Powered by vBulletin Version 5.5.
Kembangin sendiri ya, ga jago dorking soalnya aku wkwkwk
```
#### Tested on vBulletin v5.5.0, 5.5.1, 5.5.2
