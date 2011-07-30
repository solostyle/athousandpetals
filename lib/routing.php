<?php

$routing = array(
                '/admin\/(.*?)\/(.*?)\/(.*)/' => 'admin/\1_\2/\3',
                '/^([0-9]{4}\/.*)/' => 'ids/index/\1',
                '/^tags\/(.*?)/' => 'tags/index/\1',
                '/^Massage_Therapy\/therapies\/(.+)/' => 'massagetherapiespages/\1',
                '/^Massage_Therapy[\/]?(.*)/' => 'massagepages/\1',
                '/^Therapeutic_Yoga[\/]?(.*)/' => 'yogapages/\1',
                '/^Meditation_Classes[\/]?(.*)/' => 'meditationpages/\1',
                '/^about/' => 'passives/about',
                '/^publishfeeds/' => 'passives/publishfeeds',
                 );
// Does the above match if there is no slash after the controller name?
// Yes. Adding [\/]? makes it match if there's a slash or not

// Does the above match even if there is nothing after that slash?
// Yes. It was failing in index.php because the \1 match which is "" was considered set, even if it was empty. Added the empty check.

/* If the root domain name is requested
 * e.g. athousandpetals.com
 * then this is where they will be directed
 */
$default['controller'] = 'shells';
$default['action'] = 'index';