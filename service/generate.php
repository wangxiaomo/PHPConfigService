<?php

// main generator used for PHPConfigService
define('VERSION', '1.0.1');

define('ROOT',        getcwd());
define('APP_PATH',    ROOT . '/');
define('CONF_PATH',   ROOT . '/conf/');
define('COMMON_PATH', ROOT . '/common/');
define('SNAP_PATH',   ROOT . '/snap/');

define('EXT',        '.php');
define('CONFIG_EXT', '.inc' . EXT);

define('GLOBAL_NAMESPACE', 'global'); // 通用配置

include COMMON_PATH . 'common.php';
include COMMON_PATH . 'redis.php';


function generator(){
    list($global_config_hash, $global_config) = load_config_files($path=CONF_PATH);
    $redis = get_redis();
    $pub_channel = load_redis_config()['CHANNEL'];
    $snap_hash = $redis->get('global_hash');
    if($snap_hash){
        if($global_config_hash === $snap_hash){
            die('synced');
        }
    }
    $redis->set('global_hash', $global_config_hash);
    foreach($global_config as $app=>$config){
        $snap_config = $redis->get($app);
        $redis->set($app, $config['serialized']);
        if(md5($snap_config) !== $config['hash']){
            $redis->publish($pub_channel, $app . '#' . $config['serialized']);
        }
    }
    $contents = serialize($global_config);
    $filename = SNAP_PATH . 'snap' . time();
    file_put_contents($filename, $contents);
}


generator();
