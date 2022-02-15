<?php

class Setup
{
    private static $config;

    public function __construct() {
        self::log("Starting setup");
        self::$config = self::getConfig();

        if (self::$config) {
            self::updateComposer(self::$config);
        }
    }

    /**
     * Replace contents of composer json
     *
     * @return bool
     */
    private static function updateComposer()
    {
        return self::makeReplacements(
            "{{BPREPLACE-SLUG}}",
            self::$config->slug,
            __DIR__ . '/composer.json'
        );
    }

    /**
     * Replace contents of file
     *
     * @param string $from
     * @param string $to
     * @param string $filename
     * @return bool
     */
    public static function makeReplacements($from, $to, $filename)
    {

        if (!file_exists($filename)) {
            self::err("File does not exist: " . $filename);
            return false;
        }

        $content = str_replace(
            $from,
            $to,
            file_get_contents($filename)
        );

        var_dump($content); 

        if (!file_put_contents($filename, $content)) {
            self::err("File is unwriteable: " . $filename);
            return false;
        }

        self::log("Updated file: " . $filename);
        return true;
    }

    /**
     * Get config
     *
     * @return void
     */
    public static function getConfig()
    {
        self::log("Fetching config file from: " . self::getConfigPath());
        if (file_exists(self::getConfigPath())) {
            self::log("Found config file");
            return (object) json_decode(
                file_get_contents(self::getConfigPath()),
                true
            );
        } else {
            self::log("Could not find setup.json");
        }
        return false;
    }

    /**
     * Get configuration file path
     *
     * @return string
     */
    private static function getConfigPath()
    {
        return __DIR__ . '/setup.json';
    }

    /**
     * Log message to user
     *
     * @param string $message
     */
    public static function log($log, $prefix = "[LOG]")
    {
        echo $prefix . " " . $log . "\n";
    }

    public static function err($log)
    {
        self::log($log, "[ERROR]");
    }
}

new Setup();
