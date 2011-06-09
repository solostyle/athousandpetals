    <!-- show mid-level navigation -->
    <?php 
        $division = "Meditation_Classes";
        $pages = array('about','benefits','instruction','research','blog','contact');
        
        foreach ($pages as $p) {
            $link = str_replace(" ", "_", $p);
            // if it's the current page, put a class on it
            if ($p == $currentPage) {
                echo '<li class="currentPage">'.make_link($p, make_url($division.'/'.$link));
            } else {
                echo make_list_item(make_link($p, make_url($division.'/'.$link)));
            }
        }
        mysql_close();
    ?>
