	<?php
    if(isset($this->session->scripts))
    {
        foreach ($this->session->scripts as $script) 
        { ?>
            
            <script src="<?= $script ?>"></script>

 	<?php }
    }
	?>


    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAPHWVMBuM2tU-MWEBh5UNXq_8hwKar9wc&callback=initMap"
    type="text/javascript"></script>