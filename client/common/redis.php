<?php

// redis utils functions used for PHPConfigService
function load_redis_config(){
    list($_, $configuration) = load_config_from_file(APP_PATH . 'redis' . CONFIG_EXT);
    return $configuration;
}


function get_redis(){
    $configuration = load_redis_config();
    $configuration['PREFIX'] = $configuration['PREFIX']? $configuration['PREFIX']: '_php_config_';

    $redis = new Redis();
    if($redis->pconnect($configuration['HOST'], $configuration['PORT']) === false){
        throw new RedisGoneException;
    }
    $redis->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_PHP);
    $redis->setOption(Redis::OPT_PREFIX, $configuration['PREFIX']);
    $redis->setOption(Redis::OPT_SCAN, Redis::SCAN_RETRY);

    return $redis;
}


class RedisGoneException extends Exception {}
