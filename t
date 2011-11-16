[1mdiff --git a/lib/shared.php b/lib/shared.php[m
[1mindex a5b7e48..5faf87c 100644[m
[1m--- a/lib/shared.php[m
[1m+++ b/lib/shared.php[m
[36m@@ -98,7 +98,7 @@[m [mfunction DetermineRequest() {[m
   $queryString = array();[m
 [m
   if (!isset($url)) {[m
[31m-        // Go to the home page[m
[32m+[m[32m    // Go to the home page[m
     $controller = $default['controller'];[m
     $action = $default['action'];[m
   } else {[m
[36m@@ -118,6 +118,11 @@[m [mfunction DetermineRequest() {[m
         $queryString = $urlArray;[m
     } else {[m
         $action = 'index'; // Default Action[m
[32m+[m		[32m// How do I give a default action for each controller?[m
[32m+[m		[32m// Create a default.php in the /lib directory that has a[m[41m [m
[32m+[m		[32m// function that takes in a controller name and spits back a default controller?[m
[32m+[m		[32m// Isn't that basically like a switch statement? It's hard-coded. But I guess[m
[32m+[m		[32m// it would have to be hard-coded in somewhere.. If that's what I really want to do.[m
     }[m
   }[m
   [m
