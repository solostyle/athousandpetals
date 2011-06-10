<div id="content"> 
   <div id="left">
   <ul>
   <?php 
   $lists = array('Vivamus imperdiet', 'Aenean in lorem vel purus porttitor.', 'Phasellus at neque id sapien dapibus ornare.', 'Nulla pharetra orci laoreet turpis faucibus sagittis ut et metus.');

foreach ($lists as $l) {
  $link = str_replace(" ", "_", $l);
  echo make_list_item(make_link($l, make_url($link.'/')));
}
?>
</ul>
    </div><!-- end #left -->

    <div id="right">
<h1>Lorem Ipsum</h1>

<p>Nunc tempus nunc quis lorem pretium ut porttitor nunc consectetur. Quisque pellentesque condimentum massa. Nulla quis enim vitae est egestas porta ac ac tortor. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam consequat mi imperdiet eros fringilla placerat eleifend felis molestie. Ut consectetur interdum pharetra. Nunc mattis fringilla leo, quis fermentum nulla rhoncus vitae. Donec rhoncus urna sit amet eros cursus condimentum. Nam non tortor nisl. Nam eu mauris arcu. Mauris sit amet tempus mi. Aenean vitae odio massa, at rhoncus orci. Phasellus euismod consectetur tellus malesuada imperdiet. </p>

<h2>Dolor sit Amet</h2>
<p>Etiam gravida massa eu risus tincidunt nec posuere orci pulvinar. Nunc diam sem, dignissim quis fermentum a, tempus sit amet nisi. Vestibulum porttitor magna quis odio dignissim pellentesque. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Nunc blandit, nulla id volutpat interdum, mauris nulla pellentesque leo, quis viverra tellus dui a nulla. Vestibulum id arcu vitae mauris fermentum pulvinar id nec augue. Morbi non placerat augue. Pellentesque vulputate porttitor nibh, at imperdiet massa luctus quis. Suspendisse scelerisque turpis lacus. Mauris ut dui nisi. Integer mollis metus et enim fringilla hendrerit. Nulla nec erat non ipsum interdum aliquet ut eu erat. Donec semper, lacus eu facilisis mattis, libero purus lacinia dolor, ac pulvinar leo eros ut libero. </p>

    </div><!-- end #right -->

<script type="text/javascript">

</script>