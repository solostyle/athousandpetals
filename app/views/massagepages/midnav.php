    <!-- show mid-level navigation -->
    <?php 
        $division = "Massage_Therapy";
        $pages = array('about','benefits','therapies','reviews','policies','forms','blog','contact');
        
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
