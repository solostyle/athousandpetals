<div id="content"> 
  <div id="left">
    <ul>
		<?php 
			$leftList = array('Swedish Relaxation and Circulatory', 'Clinical Rehabilitative', 'Sports and Event', 'Myofascial Structural', 'Deep Tissue');
			$cat = "Massage_Therapy";
			$subCat = "therapies";
			foreach ($leftList as $l) {
				$words = explode(" ",$l,2);
				$link = strtolower($words[0]);
				echo make_list_item(make_link($l, make_url($cat.'/'.$subCat.'/'.$link)));
			}
		?>
    </ul>
  </div><!-- end #left -->

  <div id="right">

  <h1>Types of Massage Therapy Offered</h1>
    <p>Browse the selection of therapeutic approaches we offer to familiarize yourself with our services. Because bodies and situations change, we may not take the same approach with every session. If you are unsure of the kind of work you need, contact us for a free consultation.</p>
 
  </div><!-- end #right -->
</div><!-- end #content -->

<script type="text/javascript">

</script>
