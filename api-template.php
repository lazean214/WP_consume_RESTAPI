<?php
/*
*Template Name: API Template
*/
get_header();
?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<div class="page-title-container">
	<div class="container">
		<div class="page-title pull-left">
			<h2 class="the-title"><?php echo $_GET['param']; ?></h2>
		</div>
		<ul class="breadcrumbs list-inline pull-right">
			<?php echo trail();?>
		</ul>
	</div>
</div>
<div class="section">
	<div class="container">
		<div class="row">
			<div class="col-md-8">
				
				<div class="bg-white">
					<?php if($_GET): ?>
					<?php 
					$param = $_GET['param']; 
					$api = consumeRestAPI($param, 'end_point_1');
					if($api[0]->post_parent == 0){
						foreach($api as $row){
							echo "<a href=\"?param=$row->post_name\">";
							echo "<div class=\"col-md-6\"><img src=\" $row->guid \" class=\"img-responsive\" alt=\"$row->post_title\"></div>";
							echo "</a>";
						}
					}
					$api2 = consumeRestAPI($param, 'end_point_2');
					if($api2[0]->post_parent <> 0){
						foreach($api2 as $row){
							echo "<h1>$row->post_title</h1>";
							echo "<a href=\"?param=$row->post_name\">";
							echo "<div class=\"col-md-6\"><img src=\" $row->guid \" class=\"img-responsive\" alt=\"$row->post_title\"></div>";
							echo "</a>";
						}
					}
					$api3 = consumeRestAPI($param, 'end_point_4');
					if($api3){
						echo "<h1>" . $api3->post_title . "</h1>";
						echo "<img src=\"$api3->guid\" class=\"img-responsive\">";
						echo $api3->post_content;
						echo $api3->rate;
						echo $api3->inclusion;
						echo $api3->itinerary;
						echo $api3->extra;
						echo $api3->city;
						echo $api3->duration;
						echo date_format(date_create($api3->datef), "F d Y");
						echo date_format(date_create($api3->dater), "F d Y");
						echo date_format(date_create($api3->validity), "F d Y");
					}else{}
					
					?>
					<?php endif;?>
				</div>
			</div>
			<div class="col-md-4">
				<?php get_sidebar()?>
			</div>
		</div>
	</div>
</div>
<?php endwhile; endif; ?>
<!-- /.main content -->
<?php get_footer(); ?>