<?php

namespace PluginTools;

use IRC\Command\CommandInterface;
use IRC\Command\CommandSender;
use IRC\Plugin\PluginBase;

class PluginTools extends PluginBase{

    public function onCommand(CommandInterface $command, CommandSender $sender, CommandSender $room, array $args){
        switch($command->getCommand()){
            case 'makeplugin':
                $result = $this->makePlugin($args[1]);
                if($result){
                    $sender->sendNotice("Build successful.");
                } else {
                    $sender->sendNotice("That plugin doesn't seem to exist.");
                }
                break;
            case 'extractplugin':
                $result = $this->extractPlugin($args[1]);
                if($result){
                    $sender->sendNotice("Extracted code successfully");
                } else {
                    $sender->sendNotice("Couldn't extract code.");
                }
                break;
        }
    }

    public function extractPlugin($plugin){
        if(is_file("plugins/".basename($plugin).".phar")){
            $phar = new \Phar("plugins/".basename($plugin).".phar");
            $phar->extractTo("plugins/".basename($plugin)."/builds/".time());
            return true;
        }
        return false;
    }

    public function makePlugin($plugin){
        if(is_file("plugins/".basename($plugin)."/plugin.json")){
            $data = json_decode(file_get_contents("plugins/".basename($plugin)."/plugin.json"), true);
            if($data){
                if( !empty($data["main"]) &&
                    !empty($data["name"]) &&
                    !empty($data["api"]) &&
                    !empty($data["version"]) &&
                    !empty($data["description"])){

                    $phar = new \Phar($data["name"].".phar");
                    if($phar->canWrite()){
                        return $phar->buildFromDirectory("plugins/".basename($plugin));
                    }
                }
            }
        }
        return false;
    }

}