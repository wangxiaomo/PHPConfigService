<?php

// common functions used for PHPConfigService

function dump($var, $echo=true){
    ob_start();
    var_dump($var);
    $output = ob_get_clean();
    if(!extension_loaded('xdebug')){
        $output = preg_replace('/\]\=\>\n(\s+)/m', '] => ', $output);
        $output = '<pre>' . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
    }
    if($echo){
        echo $output;
    }
    return $output;
}


function endswith($haystack, $needle){
    return $needle === "" || substr($haystack, -strlen($needle)) === $needle;
}


function get_config_files($path){
    $files = glob($path . '*');
    foreach($files as $file){
        if(is_file($file) && endswith($file, CONFIG_EXT)){
            $lists[] = $file;
        }
    }
    return $lists;
}


function load_config_files($path=CONF_PATH){
    $common = load_global_config();
    $config_files = get_config_files($path);
    foreach($config_files as $file){
        list($namespace, $configuration) = load_config_from_file($file);
        $configuration = array_merge($common, $configuration);
        $configurations[$namespace] = $configuration;
        $serialized_content = serialize($configuration);
        $configurations[$namespace]['hash'] = md5($serialized_content);
        $configurations[$namespace]['serialized'] = $serialized_content;
    }
    return $configurations;
}


function load_config_from_file($file){
    return include($file);
}


function load_global_config(){
    list($_, $configuration) = load_config_from_file(CONF_PATH . GLOBAL_NAMESPACE . CONFIG_EXT);
    return $configuration;
}
