<?php

// doraemon is the interface used for PHPConfigService
define('VERSION', '1.0.1');

define('ROOT',        getcwd());
define('APP_PATH',    ROOT . '/');
define('COMMON_PATH', ROOT . '/common/');
define('CONF_PATH',   ROOT . '/conf/');

define('EXT',        '.php');
define('CONFIG_EXT', '.inc' . EXT);

include COMMON_PATH . 'common.php';


function C($key, $default=''){
    static $_config = null;

    if(empty($_config)){
        list($_, $local_config) = load_config_from_file(APP_PATH . 'local' . CONFIG_EXT);
        $app_config_filename = CONF_PATH . $local_config['_NAMESPACE'] . CONFIG_EXT;
        $serialized_content = file_get_contents($app_config_filename);
        $app_config = unserialize($serialized_content);

        $_config = array_merge($app_config, $local_config);
    }
    return isset($_config[$key])? $_config[$key]: $default;
}
