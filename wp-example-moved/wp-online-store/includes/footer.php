<?php



/*



  $Id$







  osCommerce, Open Source E-Commerce Solutions



  http://www.oscommerce.com







  Copyright (c) 2010 osCommerce







  Released under the GNU General Public License



*/







  require(DIR_WS_INCLUDES . 'counter.php');



?>







<div class="grid_24 footer">



  <p align="center"><?php echo FOOTER_TEXT_BODY; ?></p>



</div>







<?php



  if ($banner = tep_banner_exists('dynamic', '468x50')) {



?>







<div class="grid_24" style="text-align: center; padding-bottom: 20px;">



  <?php echo tep_display_banner('static', $banner); ?>



</div>







<?php



  }



?>







<script type="text/javascript">



jQuery('.productListTable tr:nth-child(even)').addClass('alt');



</script>



<br clear="all" />

<?php ob_flush(); ?>

