<?php

// redis configuration used for PHPConfigService
return array('redis', array(
    'HOST'      =>  '127.0.0.1',
    'PORT'      =>  6379,
    'TIMEOUT'   =>  2.5,
    'PREFIX'    =>  '',
    'CHANNEL'   =>  'PHPConfigService',
));
