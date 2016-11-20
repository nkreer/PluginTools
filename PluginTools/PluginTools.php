<?php

namespace PluginTools;

use IRC\Command\CommandInterface;
use IRC\Command\CommandSender;
use IRC\Logger;
use IRC\Plugin\PluginBase;
use IRC\Utils\BashColor;

/**
 * Class PluginTools
 * @package PluginTools
 * @license Public Domain
 */
class PluginTools extends PluginBase{

    public function onLoad(){
        Logger::info(BashColor::HIGHLIGHT."Loading folder plugins");
        $this->loadAllFolderPlugins();
    }

    public function onCommand(CommandInterface $command, CommandSender $sender, CommandSender $room, array $args){
        switch($command->getCommand()) {
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
            case 'loadfolderplugin':
                $success = $this->loadFolderPlugin($args[1]);
                if($success){
                    $sender->sendNotice("Loaded folder plugin ".$args[1]." successfully.");
                } else {
                    $sender->sendNotice("Error while loading folder plugin ".$args[1]);
                }
                break;
        }
    }

    /**
     * @param $plugin
     * @return bool
     */
    public function extractPlugin($plugin){
        if(is_file("plugins".DIRECTORY_SEPARATOR.basename($plugin).".phar")){
            $phar = new \Phar("plugins".DIRECTORY_SEPARATOR.basename($plugin).".phar");
            $phar->extractTo("plugins".DIRECTORY_SEPARATOR.basename($plugin)."_".time());
            return true;
        }
        return false;
    }

    /**
     * @param $plugin
     * @return array|bool
     */
    public function makePlugin($plugin){
        if(is_file("plugins".DIRECTORY_SEPARATOR.basename($plugin).DIRECTORY_SEPARATOR."plugin.json")){
            $data = json_decode(file_get_contents("plugins".DIRECTORY_SEPARATOR.basename($plugin).DIRECTORY_SEPARATOR."plugin.json"), true);
            if($data){
                if(!empty($data["main"]) &&
                    !empty($data["name"]) &&
                    !empty($data["api"]) &&
                    !empty($data["version"]) &&
                    !empty($data["description"]))
                {
                    $phar = new \Phar("plugins".DIRECTORY_SEPARATOR.$data["name"].".phar");
                    if($phar->canWrite()){
                        return $phar->buildFromDirectory("plugins".DIRECTORY_SEPARATOR.basename($plugin));
                    }
                }
            }
        }
        return false;
    }

    public function loadAllFolderPlugins(){
        foreach(scandir("plugins") as $item){
            $this->loadFolderPlugin($item);
        }
    }

    /**
     * @param $name
     * @return bool|int
     */
    public function loadFolderPlugin($name){
        if(is_dir("plugins".DIRECTORY_SEPARATOR.$name)){
            if(is_file("plugins".DIRECTORY_SEPARATOR.$name.DIRECTORY_SEPARATOR."plugin.json")){
                return $this->getConnection()->getPluginManager()->loadPlugin($name, false, true);
            }
        }
        return false;
    }

}