<?php

$routing = array(
                '/admin\/(.*?)\/(.*?)\/(.*)/' => 'admin/\1_\2/\3',
                '/^([0-9]{4}\/.*)/' => 'ids/index/\1',
                '/^tags\/(.*?)/' => 'tags/index/\1',
                '/^Massage_Therapy\/(.*?)/' => 'massagepages/\1',
                '/^Therapeutic_Yoga\/(.*?)/' => 'yogapages/\1',
                '/^Meditation_Classes\/(.*?)/' => 'meditationpages/\1',
                '/^about/' => 'passives/about',
                '/^publishfeeds/' => 'passives/publishfeeds',
                 );
// TODO: does the above match only if there is a slash after the controller name?
// TODO: does the above match even if there is nothing after that slash?

/* If the root domain name is requested
 * e.g. athousandpetals.com
 * then this is where they will be directed
 */
$default['controller'] = 'shells';
$default['action'] = 'index';
// TODO: check shells/index's js to see what is going on