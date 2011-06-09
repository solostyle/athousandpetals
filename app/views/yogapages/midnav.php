    <!-- show mid-level navigation -->
    <?php 
        $division = "Therapeutic_Yoga";
        $pages = array('bio','therapy','sessions','style','policies','blog','contact');
        
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
