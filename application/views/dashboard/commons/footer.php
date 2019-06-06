</div>

</div>

<!-- Jquery JS-->

<script src="<?php echo base_url('assets/vendor/jquery-3.2.1.min.js')?>"></script>
<!-- Bootstrap JS-->
<script src="<?php echo base_url('assets/vendor/bootstrap-4.1/popper.min.js')?>"></script>
<script src="<?php echo base_url('assets/vendor/bootstrap-4.1/bootstrap.min.js')?>"></script>
<!-- Vendor JS       -->
<script src="<?php echo base_url('assets/vendor/slick/slick.min.js')?>">
</script>
<script src="<?php echo base_url('assets/vendor/wow/wow.min.js')?>"></script>
<script src="<?php echo base_url('assets/vendor/animsition/animsition.min.js')?>"></script>
<script src="<?php echo base_url('assets/vendor/bootstrap-progressbar/bootstrap-progressbar.min.js')?>">
</script>
<script src="<?php echo base_url('assets/vendor/counter-up/jquery.waypoints.min.js')?>"></script>
<script src="<?php echo base_url('assets/vendor/counter-up/jquery.counterup.min.js')?>">
</script>
<script src="<?php echo base_url('assets/vendor/circle-progress/circle-progress.min.js')?>"></script>
<script src="<?php echo base_url('assets/vendor/perfect-scrollbar/perfect-scrollbar.js')?>"></script>
<script src="<?php echo base_url('assets/vendor/chartjs/Chart.bundle.min.js')?>"></script>
<script src="<?php echo base_url('assets/vendor/select2/select2.min.js')?>"></script>

<script src="<?php echo base_url('assets/js/generic/genericView.js')?>"></script>
<script src="<?php echo base_url('assets/js/generic/genericRequest.js')?>"></script>
<script src="<?php echo base_url('assets/js/generic/genericControl.js')?>"></script>
<script src="<?php echo base_url('assets/js/generic/genericModal.js')?>"></script>
<script src="<?php echo base_url('assets/js/generic/genericMap.js')?>"></script>

<!-- Main JS-->
<script src="<?php echo base_url('assets/js/main.js')?>"></script>

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
</body>

</html>
<!-- end document-->