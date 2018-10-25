	<?php
    if(isset($this->session->scripts))
    {
        foreach ($this->session->scripts as $script) 
        { ?>
            
            <script src="<?= $script ?>"></script>

 	<?php }
    }
	?>


	<?php if (isset($this->session->mapa)): ?>
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCICU1zIV5CiGivUz3fkzxGUuK6W-2G04c&callback=initMap"
    async defer></script>
	<?php endif ?>