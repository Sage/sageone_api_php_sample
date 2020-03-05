<?php
namespace SageAccounting;

class ViewConfig
{
    private $config;

    const FILE_NAME = "config/view.yml";

    /**
    * Loads the config from the YAML file.
    */
    public function __construct()
    {
        $this->config = yaml_parse_file(self::FILE_NAME);
    }

    /**
    * Returns the configured value for $key
    */
    public function get($key)
    {
        return $this->config[$key];
    }
}
