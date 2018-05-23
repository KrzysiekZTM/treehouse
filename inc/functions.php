<?php

function get_catalog_count($category = NULL){
  include 'connection.php';
  try {
    $sql = 'SELECT COUNT(media_id) FROM Media'

    if(!empty($category)){
      $result = $db -> prepare($sql.'WHERE LOWER(category) = ?');
      $result->bindParam(1, $category, PDO::PARAM_STR);
    }else{
        $result = $db -> prepare($sql);
    }

    $result -> execute();
    $count = $result->fetchColumn(0);

  } catch (Exception $e) {
    echo $->getMessage();
  }

}

function full_catalog_array(){
    include ("connection.php");

    try{
        $results = $db->query("SELECT media_id, title, category, img FROM Media ORDER BY REPLACE(REPLACE(REPLACE(title, 'The ', ''), 'An ', ''), 'A ', '')");
    } catch(Exception $e){
        echo $e->getMessage();
        echo "Unable to retrieve results";
        exit;
    }
    $catalog = $results->fetchAll(PDO::FETCH_ASSOC);
    return $catalog;
}

function category_catalog_array($category){
    include ("connection.php");
    $category = strtolower($category);
    try{
        $results = $db->prepare(
          "SELECT media_id, title, category, img
          FROM Media
          WHERE LOWER(category) = ?
          ORDER BY REPLACE(REPLACE(REPLACE(title, 'The ', ''), 'An ', ''), 'A ', '')"
        );
          $results -> bindParam(1, $category, PDO::PARAM_STR);
          $results -> execute();
    } catch(Exception $e){
        echo $e->getMessage();
        echo "Unable to retrieve results";
        exit;
    }
    $catalog = $results->fetchAll(PDO::FETCH_ASSOC);
    return $catalog;
}

function random_catalog_array(){
    include ("connection.php");

    try{
        $results = $db->query(
          "SELECT media_id, title, category, img
          FROM Media
          ORDER BY RAND()
          LIMIT 4");
    } catch(Exception $e){
        echo $e->getMessage();
        echo "Unable to retrieve results";
        exit;
    }
    $catalog = $results->fetchAll(PDO::FETCH_ASSOC);
    return $catalog;
}

function single_item_array($id){
    include ("connection.php");

    try{
        $results = $db->prepare(
            "SELECT media.media_id, media.title, media.category, media.img, media.format, media.year, genres.genre, books.publisher, books.isbn
            FROM Media
            JOIN Genres ON media.genre_id = genres.genre_id
            LEFT OUTER JOIN books ON media.media_id = books.media_id
            WHERE media.media_id = ?"
        );
        $results -> bindParam(1, $id, PDO::PARAM_INT);
        $results->execute();
    } catch(Exception $e){
        echo $e->getMessage();
        echo "Unable to retrieve results";
        exit;
    }
    $item = $results->fetch(PDO::FETCH_ASSOC);

    if(empty($item)){
        return $item;
    }

    try{
        $results = $db->prepare(
            "SELECT fullname, role
            FROM media_people
            JOIN people ON media_people.people_id = people.people_id
            WHERE media_people.media_id = ?"
        );
        $results -> bindParam(1, $id, PDO::PARAM_INT);
        $results->execute();
    } catch(Exception $e){
        echo $e->getMessage();
        echo "Unable to retrieve results";
        exit;
    }

    while($row = $results->fetch()){
        $item[$row['role']][] = $row['fullname'];
    }

    return $item;

}

function get_item_html($item){

	$output =  '<li><a href="details.php?id='
	. $item['media_id'] .'""><img src=" '
	. $item['img'] . ' " alt=" '
	. $item['title'] . ' ">
	<p>View Details</p> </a></li>';
	return $output;

}

function array_category($catalog, $category, $sortby){


	$output = array();
	foreach($catalog as $id => $item){
		if($category == null OR strtolower($item['category']) == strtolower($category)){
			$sort  = $item[strtolower($sortby)];
			$sort = ltrim($sort, "The ");
			$sort = ltrim($sort, "A ");
			$sort = ltrim($sort, "An ");
			$output[$id] = $sort;
		}
	}
	asort($output);
	return array_keys($output);
}

function array_custom_sort($catalog, $sortby){

	$output = array();
	foreach($catalog as $key => $item){
		$output[$key] = $catalog[$sortby];
	}

	asort($output);
	return array_keys($output);
}
