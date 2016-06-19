<?php

namespace PluginTools;

use IRC\Command\CommandInterface;
use IRC\Command\CommandSender;
use IRC\Plugin\PluginBase;

/**
 * Class PluginTools
 * @package PluginTools
 * @license Public Domain
 */
class PluginTools extends PluginBase{

    public function onCommand(CommandInterface $command, CommandSender $sender, CommandSender $room, array $args){
        switch($command->getCommand()){
            case 'makeplugin':
                if(!empty($args[1]) and $this->makePlugin($args[1])){
                    $sender->sendNotice("Build successful.");
                    if(!empty($args[2]) and $args[2] === "load"){
                        if($this->getPluginManager()->loadPlugin($args[1], true) !== false){
                            $sender->sendNotice("Plugin ".$args[1]." has been loaded.");
                        } else {
                            $sender->sendNotice("Plugin couldn't be loaded.");
                        }
                    }
                } else {
                    $sender->sendNotice("That plugin doesn't seem to exist.");
                }
                break;
            case 'extractplugin':
                if(!empty($args[1]) and $this->extractPlugin($args[1])){
                    $sender->sendNotice("Extracted code successfully.");
                } else {
                    $sender->sendNotice("Couldn't extract code.");
                }
                break;
        }
    }

    public function extractPlugin($plugin){
        if(is_file("plugins".DIRECTORY_SEPARATOR.basename($plugin).".phar")){
            $phar = new \Phar("plugins".DIRECTORY_SEPARATOR.basename($plugin).".phar");
            $phar->extractTo("plugins".DIRECTORY_SEPARATOR.basename($plugin)."_".time());
            return true;
        }
        return false;
    }

    public function makePlugin($plugin){
        if(is_file("plugins".DIRECTORY_SEPARATOR.basename($plugin).DIRECTORY_SEPARATOR."plugin.json")){
            $data = json_decode(file_get_contents("plugins".DIRECTORY_SEPARATOR.basename($plugin).DIRECTORY_SEPARATOR."plugin.json"), true);
            if($data){
                if( !empty($data["main"]) &&
                    !empty($data["name"]) &&
                    !empty($data["api"]) &&
                    !empty($data["version"]) &&
                    !empty($data["description"])){

                    $phar = new \Phar("plugins".DIRECTORY_SEPARATOR.$data["name"].".phar");
                    if($phar->canWrite()){
                        return $phar->buildFromDirectory("plugins".DIRECTORY_SEPARATOR.basename($plugin));
                    }
                }
            }
        }
        return false;
    }

}