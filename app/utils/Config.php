<?php
namespace Utils;


class Config {

    /**
     * Data store
     */
    private $_data;

    /**
     * Holds an instance of the class
     */
    private static $_instance;

    /**
     * Restrict direct initialisation, use Config::getInstance() instead
     */
    private function __construct(){
        $this->_data = array();
    }

    /**
     * Parse config.ini
     *
     * @throws \Exception
     */
    public static function parseConfig() {
        $config = parse_ini_file(SITE_ROOT . 'config.ini');
        if(!is_array($config)) {
            throw new \Exception("Missing or empty config.ini");
        }

        foreach($config as $key => $value) {
            self::set($key, $value);
        }
    }

    /**
     * Get instance of the object
     *
     * @return Config
     */
    public static function getInstance() {
        if (!isset(self::$_instance)) {
            self::$_instance = new self;
        }

        return self::$_instance;
    }

    /**
     * Prevent users from cloning the object
     *
     * @throws \Exception
     */
    public function __clone() {
        throw new \Exception("Cloning the object is forbidden");
    }

    /**
     * Retrive data
     *
     * @param string  $key
     * @return mixed
     * @throws \Exception
     */
    public function __get($key) {
        if(!array_key_exists($key, $this->_data)) {
            /**
             * throw an exception on unknown key so we'd be able to
             * differentiate between unset and null/false values
             */
            throw new \Exception("Unknown key");
        }

        return $this->_data[$key];
    }

    /**
     * Set value
     *
     * @param string  $key
     * @param mixed   $value
     * @throws \Exception
     */
    public function __set($key, $value) {
        if(array_key_exists($key, $this->_data)) {
            throw new \Exception("Value override is forbidden");
        }

        $this->_data[$key] = $value;
    }

    /**
     * Check if a key exists
     *
     * @param string $key
     * @return bool
     */
    public static function keyExists($key) {
        return array_key_exists($key, self::getInstance()->_data);
    }

    /**
     * Convenience method to set value
     *
     * @param string $key
     * @param mixed $value
     * @throws \Exception
     */
    public static function set($key, $value) {
        self::getInstance()->$key = $value;
    }

    /**
     *  Convenience method to get value
     *
     * @param string $key
     * @return mixed
     * @throws \Exception
     */
    public static function get($key) {
        return self::getInstance()->$key;
    }
}