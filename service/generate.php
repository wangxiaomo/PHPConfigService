<?php

// main generator used for PHPConfigService
define('VERSION', '1.0.1');

define('ROOT',        getcwd());
define('APP_PATH',    ROOT . '/');
define('CONF_PATH',   ROOT . '/conf/');
define('COMMON_PATH', ROOT . '/common/');

define('EXT',        '.php');
define('CONFIG_EXT', '.inc' . EXT);

define('GLOBAL_NAMESPACE', 'global'); // 通用配置

include COMMON_PATH . 'common.php';


function generator(){
    $global_config = load_config_files($path=CONF_PATH);
    // 1.从 redis 取数据,判断是否发送 pub 事件.

    // 2.写入 redis

    // 3.写入快照
}


generator();
