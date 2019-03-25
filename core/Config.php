<?php

namespace Core;

class Config {
    
    private static $mode = 'dev';
    private static $loadedConfigs = [];
    private static $configLocation = 'config/';
    private static $configConfiged = false;
    
    private static function getBaseLine() {
        if(!self::$configConfiged) {
            self::$configConfiged = true;
            self::$mode = (getenv('mode')) ? getenv('mode') : self::$mode;
            self::$configLocation = (getenv('configLocation')) ? getenv('configLocation') : self::$configLocation;
        }
    }
    
    static function get($config) {
        self::getBaseLine();
        if(!isset(self::$loadedConfigs[$config])) {
            $environmentFilePath = self::$configLocation.self::$mode.'/'.$config.'.json';
            $globalFilePath = self::$configLocation.$config.'.json';
            if(file_exists($environmentFilePath) && file_exists($globalFilePath)) {
                $environmentConfig = json_decode(file_get_contents($environmentFilePath), true);
                $globalConfig = json_decode(file_get_contents($globalFilePath), true);
                $loadedConfig = array_merge($globalConfig, $environmentConfig);
            } else if(file_exists($environmentFilePath)) {
                $loadedConfig = json_decode(file_get_contents($environmentFilePath), true);
            } else if(file_exists($globalFilePath)) {
                $loadedConfig = json_decode(file_get_contents($globalFilePath), true);
            } else {
                die("Error: Trying to load unknown configuration: $config");
            }
            self::$loadedConfigs[$config] = json_decode(json_encode(self::readConfig($loadedConfig)));
        }
        return self::$loadedConfigs[$config];
    }
    
    private static function readConfig($config) {
        foreach($config as &$configItem) {
            if(is_array($configItem)) {
                $configItem = self::readConfig($configItem);
            } else {
                if(is_string($configItem)) {
                    if(preg_match('/ENV\[(.*)\]/', $configItem, $matches)) {
                        $configItem = (getenv($matches[1])) ? getenv($matches[1]) : null;
                    } else if(preg_match('/SERVER\[(.*)\]/', $configItem, $matches)) {
                        $configItem = (isset($_SERVER[$matches[1]])) ? $_SERVER[$matches[1]] : null;
                    } else if(preg_match('/GET\[(.*)\]/', $configItem, $matches)) {
                        $configItem = (isset($_GET[$matches[1]])) ? $_GET[$matches[1]] : null;
                    } else if(preg_match('/POST\[(.*)\]/', $configItem, $matches)) {
                        $configItem = (isset($_POST[$matches[1]])) ? $_POST[$matches[1]] : null;
                    } else if(preg_match('/REQUEST\[(.*)\]/', $configItem, $matches)) {
                        $configItem = (isset($_REQUEST[$matches[1]])) ? $_REQUEST[$matches[1]] : null;
                    } else if(preg_match('/FILE\[(.*)\]/', $configItem, $matches)) {
                        $fileParts = explode('.', $matches[1]);
                        if(file_exists($matches[1])) {
                            $file = file_get_contents($matches[1]);
                            if(strtolower($fileParts[count($fileParts) - 1]) == 'json') {
                                $file = json_decode($file, true);
                                $configItem = self::readConfig($file);
                            } else {
                                $configItem = $file;
                            }
                        } else {
                            $configItem = null;
                        }
                    } else if(preg_match('/CONFIG\[(.*)\]/', $configItem, $matches)) {
                        $string = str_replace([
                            '->', '.', ':'
                        ], ' ', $matches[1]);
                        $parts = explode(' ', $string);
                        $configFile = $parts[0];
                        unset($parts[0]);
                        $newString = implode('->', $parts);
                        $newString = ($newString == '') ? '' : '->'.$newString;
                        $code = "\$newConfig = Core\Config::get(\"$configFile\")$newString;";
                        eval($code);
                        $configItem = $newConfig;
                    }
                }
            }
        }
        return $config;
    }
    
}