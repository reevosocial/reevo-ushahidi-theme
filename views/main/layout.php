<?php  if (isset($_GET['embed'])) { ?>

	<div id="mainmiddle">

	<!-- content column -->
		<div id="content-home" class="clearingfix">
				<?php
				// Map and Timeline Blocks
				echo $div_map;
				echo $div_timeline;
				?>
		</div>
	</div>
<?php } else { ?>

<script type="text/javascript">
$(function(){
	
	// show/hide report filters and layers boxes on home page map
	$("a.toggle").toggle(
		function() { 
			$($(this).attr("href")).show();
			$(this).addClass("active-toggle");
		},
		function() { 
			$($(this).attr("href")).hide();
			$(this).removeClass("active-toggle");
		}
	);
	
});

</script>
<!-- main body -->

		
		
<div id="main" class="clearingfix">
	<div id="mainmiddle">

	<!-- content column -->
		<div id="content-home" class="clearingfix">
				<?php
				// Map and Timeline Blocks
				echo $div_map;
				echo $div_timeline;
				?>
			<div class="wrapper-960" style="padding-top: 140px;">
					<div class="column_1-3">
						
						<div id="box-frontpage" class="box-1" style="width:320px;">
							<div class="box-1-top"></div>
							<?php echo Kohana::lang('ui_main.frontpage-intro'); ?>
							<a data-reveal-id="popup-help" class="frontpage-more" href=""><?php echo Kohana::lang('ui_main.frontpage-intro-more'); ?></a>

<!--							<a id="front-btn-add" href="#"><?php echo Kohana::lang('ui_main.frontpage-add'); ?></a>
							<a id="front-btn-list" href="#"><?php echo Kohana::lang('ui_main.frontpage-list'); ?></a>
							<div class="box-1-bottom"></div>-->
					
						</div>


						<div class="box-1" style="width:320px; margin-top: 70px;">
							<div class="box-1-top"></div>
							<div class="front-btn-filter"><?php echo Kohana::lang('ui_main.frontpage-filter'); ?></div> 
							<?php include("filter-box.php");?>
							<div class="box-1-bottom"></div>
						</div>
						
				</div>

		</div>
			


		</div>
		<!-- / content column -->

	</div>
</div>
<!-- / main body -->

<?php } ?>
