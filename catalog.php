<?php
include('inc/functions.php');

$pageTitle = 'Full Media Library';
$section = null;
$items_per_page = 8;

if(isset($_GET["cat"])){
	if($_GET["cat"] == 'books'){
		$pageTitle = 'Books Media Library';
		$section = 'books';
	}elseif($_GET["cat"] == 'movies'){
		$pageTitle = 'Movies Media Library';
		$section = 'movies';
	}elseif($_GET["cat"] == 'music'){
		$pageTitle = 'Music Media Library';
		$section = 'music';
	}
}

if(isset($_GET['pg'])){
	$current_page = filter_input(INPUT_GET, "pg", FILTER_SANITIZE_NUMBER_INT);
}

if(empty($current_page)){
	$current_page = 1;
}

$total_items = get_catalog_count($section);
$total_page = ceil($total_items / $items_per_page);

// limit results in redirect
$limit_results = "";

if(!empty($section)){
	$limit_results = 'cat'.$section."&";
}

// redirect too-large page numbers to the last page
if($current_page > $total_pages){
	header("location:catalog.php?".$limit_results."pg=".$total_pages);
}

// redirect too-small page numbers to the first page

if($current_page < 1){
	header("location:catalog.php?pg=1");
}

// determin the offset (number of items to skip) for the current page
// for example: on page 3 with 8 items per page, the offse woluld be 16

$offset = ($current_page - 1) * $items_per_page;

if(empty($section)){
	$catalog = full_catalog_array();
}else{
	$catalog = category_catalog_array($section);
}

include('inc/header.php') ?>

<div class="section catalog page">
	<div class="wrapper">
		<h1><?php

		if($section != null){
			echo "<a href=\"catalog.php\">Go to Full Catalog</a> &gt; ";
		}
		echo $pageTitle ?>



		</h1>
		<ul class="items">
			<?php

			foreach($catalog as $item){

				echo get_item_html($item);
			}

			?>
		</ul>
	</div>

</div>



<?php include('inc/footer.php') ?>
