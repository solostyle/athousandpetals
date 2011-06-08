    <!-- show mid-level navigation -->
    <ul id="midnav"><?php 
        select_db();
        $cats = rtrv_categories();
        //echo make_list_item(make_link('About', make_url('about'))); // removed for now june 8, 2011
        foreach ($cats as $c) {
            $link = str_replace(" ", "_", $c);
            echo make_list_item(make_link($c, make_url($link.'/')));
        }
        mysql_close();
            ?>
    </ul>
<!-- show pages instead of categories, obviously -->