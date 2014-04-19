<?php
//ini_set('default_socket_timeout', -1);

// sync clients used for PHPConfigService
define('VERSION', '1.0.1');

define('ROOT',        getcwd());
define('APP_PATH',    ROOT . '/');
define('COMMON_PATH', ROOT . '/common/');
define('CONF_PATH',   ROOT . '/conf/');

define('EXT',        '.php');
define('CONFIG_EXT', '.inc' . EXT);

include COMMON_PATH . 'common.php';
include COMMON_PATH . 'redis.php';


function handle_sub_event(&$redis, $channel, $msg){
    list($app, $remote_config) = array_filter(array_map('trim', explode('#', $msg, 2)));

    list($_, $local_config) = load_config_from_file(APP_PATH . 'local' . CONFIG_EXT);
    $app_config_filename = CONF_PATH . $local_config['_NAMESPACE'] . CONFIG_EXT;
    list($_, $app_config) = load_config_from_file($app_config_filename);
    // only matters is the $msg variable.
    if($app === $local_config['_NAMESPACE']){
        if(md5($remote_config) !== $app_config['hash']){
            $config = unserialize($remote_config);
            $config['hash'] = md5($remote_config);
            file_put_contents($app_config_filename, serialize($config));
        }
    }
}


function setup_configuration(&$redis){
    list($_, $local_config) = load_config_from_file(APP_PATH . 'local' . CONFIG_EXT);
    $app_config_filename = CONF_PATH . $local_config['_NAMESPACE'] . CONFIG_EXT;
    if(!file_exists($app_config_filename)){
        $remote_config = $redis->get($local_config['_NAMESPACE']);
        $config = unserialize($remote_config);
        $config['hash'] = md5($remote_config);
        file_put_contents($app_config_filename, serialize($config));
    }
}


$sub_channel = load_redis_config()['CHANNEL'];
$redis = get_redis();
setup_configuration($redis);
$redis->subscribe(array($sub_channel), 'handle_sub_event');
