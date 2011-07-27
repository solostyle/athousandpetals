<div id="content"> 
  <div id="left">
    <ul>
      <?php 
	 $lists = array();

	 foreach ($lists as $l) {
	 $link = str_replace(" ", "_", $l);
	 echo make_list_item(make_link($l, make_url('Massage_Therapy/policies/'.$link)));
	 }
	 ?>
    </ul>
  </div><!-- end #left -->
  
  <div id="right">
    
    <h1>Policies and Procedures</h1>
    <h2>Cancellation Policy</h2>
    <h2>Tardiness Policy</h2>
    <h2>Conduct and Behavior</h2>
    <h2>Draping and Modesty</h2>
    <h2>Referrals</h2>
    <h2>Background Music and Environment</h2>
    <h2>Payment Policy</h2>
    <h2>Right to Refuse Service</h2>
    <h2>Informed Consent</h2>
    <h2>Privacy Policy</h2>
    <h3>Personal Health History and Records</h3>
    <h3>Oral or Other Communication</h3>
    <h2>Liability Disclaimer</h2>

  </div><!-- end #right -->
</div<<!-- end #content -->

<script type="text/javascript">

</script>
