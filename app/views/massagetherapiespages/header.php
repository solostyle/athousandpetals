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