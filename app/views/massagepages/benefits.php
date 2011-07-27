<div id="content"> 
  <div id="left">
    <ul>
      <?php 
	 $lists = array();

	 foreach ($lists as $l) {
	 $link = str_replace(" ", "_", $l);
	 echo make_list_item(make_link($l, make_url('Massage_Therapy/benefits/'.$link)));
	 }
	 ?>
    </ul>
  </div><!-- end #left -->
  
  <div id="right">
    
    <h1>Benefits of Massage Therapy</h1>
    <p></p>

    <p></p>

  </div><!-- end #right -->
</div<<!-- end #content -->

<script type="text/javascript">

</script>
