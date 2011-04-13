<?php

$routing = array(
                '/admin\/(.*?)\/(.*?)\/(.*)/' => 'admin/\1_\2/\3',
                '/^([0-9]{4}\/.*)/' => 'ids/index/\1',
                '/^tags\/(.*?)/' => 'tags/index/\1',
                '/^categories\/(.*?)/' => 'categories/index/\1',
                '/^about/' => 'passives/about',
                '/^publishfeeds/' => 'passives/publishfeeds',
                 );

/* If the root domain name is requested
 * e.g. athousandpetals.com
 * then this is where they will be directed
 */
$default['controller'] = 'shells';
$default['action'] = 'index';