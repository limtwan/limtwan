<?php

/**
 * @file
 * Default simple view template to display a list of rows.
 *
 * @ingroup views_templates
 */

?>

<div id="carousel_highlighted" class="carousel slide" data-ride="carousel"> 
  
  	<!-- heading box -->
  	<div class="front_heading_box">
        <h1 class='front_heading h2 text-left'><?php print t("โฮหมุบุ๊ค คลังรูปภาพ แบบบ้าน บ้านสวย"); ?></h1>
        <p class='front_subheading h3 text-left'><?php print t("ค้นไอเดียแต่งบ้าน หามือโปรเรื่องบ้านกับเราได้ที่นี่"); ?></p>
  	</div>

	<!-- Wrapper for slides -->
	<div class="carousel-inner">
		<?php foreach ($rows as $id => $row): ?>
			<div class="item<?php if ($id == 0) { print ' active'; } ?>"> <?php print $row; ?> </div>
		<?php endforeach; ?>
	</div>
	<!-- Controls --> 
	<a class="left carousel-control" href="#carousel_highlighted" data-slide="prev"> <span class="glyphicon glyphicon-chevron-left"></span> </a> 
	<a class="right carousel-control" href="#carousel_highlighted" data-slide="next"> <span class="glyphicon glyphicon-chevron-right"></span> </a> 
</div>




