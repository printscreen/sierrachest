 <?php


$oldDb = new PDO("mysql:host=$servername;dbname=sierra_old", $username, $password);
$db = new PDO("mysql:host=$servername;dbname=sierra", $username, $password);

$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

/*
 * REBUILD NEW DB FROM SCRATCH
 */
$db->query('SET FOREIGN_KEY_CHECKS = 0');
$result = $db->query('SHOW TABLES');
while($row = $result->fetch(PDO::FETCH_ASSOC)) {
    $table = $row['Tables_in_sierra'];
    $db->query("DROP TABLE $table");
}
$db->query('SET FOREIGN_KEY_CHECKS = 1');

$sql = file_get_contents('build.sql');
$db->query($sql);

/*
 * END REBUILD OF NEW DB
 */

// Tranform game genre into game type

$result = $oldDb->query('SELECT * FROM genres ORDER BY id ASC');
while($row = $result->fetch(PDO::FETCH_ASSOC)) {
    $name = $row['name'];
    $id = $row['id'];
    $db->query("INSERT INTO game_type (game_type_id ,name) VALUES ($id, '$name')");
}

// Tranform game groups into game series
$result = $oldDb->query('SELECT * FROM groups ORDER BY id ASC');
$query = $db->prepare("INSERT INTO game_series (game_series_id, name) VALUES (:game_series_id, :name)");
while($row = $result->fetch(PDO::FETCH_ASSOC)) {
    $query->bindValue(':game_series_id', $row['id'], PDO::PARAM_INT);
    $query->bindValue(':name', $row['name'], PDO::PARAM_STR);
    $query->execute();
}

// Move over ERSB
$result = $oldDb->query('SELECT * FROM ESRB ORDER BY id ASC');
$query = $db->prepare("INSERT INTO esrb (esrb_id, name, description, age, image) VALUES (:esrb_id, :name, :long, :age, :icon)");
while($row = $result->fetch(PDO::FETCH_ASSOC)) {
    $query->bindValue(':esrb_id', $row['id'], PDO::PARAM_INT);
    $query->bindValue(':name', $row['name'], PDO::PARAM_STR);
    $query->bindValue(':long', $row['long'], PDO::PARAM_STR);
    $query->bindValue(':age', $row['age'], PDO::PARAM_STR);
    $query->bindValue(':icon', $row['icon'], PDO::PARAM_STR);
    $query->execute();
}

// Move games over
$result = $oldDb->query('SELECT * FROM games');
$query = $db->prepare("INSERT INTO game (game_id, slug, title, description, cover_art, release_date, system_requirements, esrb_id, banner, gog_link, ebay_link, completion_date)VALUES (:game_id, :slug, :title, :description, :cover_art, :release_date, :system_requirements, :esrb_id,:banner, :gog_link, :ebay_link, :completion_date)");
while($row = $result->fetch(PDO::FETCH_ASSOC)) {
    $matches = array();
    preg_match('/src="([^"]*)"/i', $row['banner'], $matches);
    $banner = '';
    if(!isset($matches[1]) && !empty($matches[1])) {
        $banner = $matches[1];
    }

    $esrb = null;
    if (!empty($row['ESRB_id'])) {
        $esrb = $row['ESRB_id'];
    }
    $query->bindValue(':game_id', $row['id'], PDO::PARAM_INT);
    $query->bindValue(':slug', $row['slug'], PDO::PARAM_STR);
    $query->bindValue(':title', $row['title'], PDO::PARAM_STR);
    $query->bindValue(':description', $row['description'], PDO::PARAM_STR);
    $query->bindValue(':cover_art', $row['cover'], PDO::PARAM_STR);
    $query->bindValue(':release_date', $row['release_date'], PDO::PARAM_STR);
    $query->bindValue(':system_requirements', $row['tech_support'], PDO::PARAM_STR);
    $query->bindValue(':esrb_id', $esrb, PDO::PARAM_INT);
    $query->bindValue(':banner', $banner, PDO::PARAM_STR);
    $query->bindValue(':gog_link', $row['gog_link'], PDO::PARAM_STR);
    $query->bindValue(':ebay_link', $row['ebay_link'], PDO::PARAM_STR);
    $query->bindValue(':completion_date', $row['Completion'], PDO::PARAM_STR);
    $query->execute();
}

// News
$result = $oldDb->query('SELECT * FROM news ORDER BY id ASC');
$query = $db->prepare("INSERT INTO news (news_id, date, title, content, blurb, image, external_url, active)VALUES (:news_id, :date, :title, :content, :blurb, :image, :external_url, :active)");
while($row = $result->fetch(PDO::FETCH_ASSOC)) {
    $active = $row['display'] == '1';
    $query->bindValue(':news_id', $row['id'], PDO::PARAM_INT);
    $query->bindValue(':date', $row['date'], PDO::PARAM_STR);
    $query->bindValue(':title', $row['title'], PDO::PARAM_STR);
    $query->bindValue(':content', $row['content'], PDO::PARAM_STR);
    $query->bindValue(':blurb', $row['content_short'], PDO::PARAM_STR);
    $query->bindValue(':image', $row['image'], PDO::PARAM_STR);
    $query->bindValue(':external_url', $row['news_url'], PDO::PARAM_STR);
    $query->bindValue(':active', $active, PDO::PARAM_BOOL);
    $query->execute();
}

// Box
$result = $oldDb->query('SELECT * FROM box ORDER BY id ASC');
$query = $db->prepare("INSERT INTO box (box_id, active, game_id, upca, page, content, title, complete, spine, height, width, digital) VALUES (:box_id, :active, :game_id, :upca, :page, :content, :title, :complete, :spine, :height, :width, :digital)");
while($row = $result->fetch(PDO::FETCH_ASSOC)) {
    $active = $row['active'] == '1';
    $complete = $row['complete'] == '1';
    $digital = $row['Digital'] == '1';

    $query->bindValue(':box_id', $row['id'], PDO::PARAM_INT);
    $query->bindValue(':active', $active, PDO::PARAM_BOOL);
    $query->bindValue(':game_id', $row['games_id'], PDO::PARAM_INT);
    $query->bindValue(':upca', $row['upca'], PDO::PARAM_STR);
    $query->bindValue(':page', $row['page'], PDO::PARAM_STR);
    $query->bindValue(':content', $row['content'], PDO::PARAM_STR);
    $query->bindValue(':title', $row['box_title'], PDO::PARAM_STR);
    $query->bindValue(':complete', $complete, PDO::PARAM_BOOL);
    $query->bindValue(':spine', $row['spine'], PDO::PARAM_STR);
    $query->bindValue(':height', $row['spine_height'], PDO::PARAM_INT);
    $query->bindValue(':width', $row['spine_width'], PDO::PARAM_INT);
    $query->bindValue(':digital', $digital, PDO::PARAM_BOOL);
    try {
       $query->execute();

   } catch (Exception $e) {
    echo 'BOX'. PHP_EOL;
    $data = $db->query('SELECT game_id FROM game ORDER BY gamed_id DESC LIMIT 10');
    var_dump($data->fetchAll()
        );
    var_dump($row);
    echo $e->getMessage();
    exit;
   }
}

// Store
$result = $oldDb->query('SELECT * FROM stores ORDER BY store_id ASC');
$query = $db->prepare("INSERT INTO store (store_id, name, url, shipping, country, image, description, email, joined) VALUES (:store_id, :name, :url, :shipping, :country, :image, :description, :email, :joined)");
while($row = $result->fetch(PDO::FETCH_ASSOC)) {
    $query->bindValue(':store_id', $row['store_id'], PDO::PARAM_INT);
    $query->bindValue(':name', $row['store_name'], PDO::PARAM_STR);
    $query->bindValue(':url', $row['store_website'], PDO::PARAM_STR);
    $query->bindValue(':shipping', $row['shipping'], PDO::PARAM_STR);
    $query->bindValue(':country', $row['store_country'], PDO::PARAM_STR);
    $query->bindValue(':image', $row['store_image'], PDO::PARAM_STR);
    $query->bindValue(':description', $row['store_description'], PDO::PARAM_STR);
    $query->bindValue(':email', $row['email'], PDO::PARAM_STR);
    $query->bindValue(':joined', $row['store_joindate'], PDO::PARAM_STR);
    $query->execute();
}

// Store Items
$result = $oldDb->query('SELECT * FROM store_items ORDER BY item_id ASC');
$query = $db->prepare("INSERT INTO store_item (store_item_id, title, store_id, game_id, box_id, url, list_date, expiration, comments, image, auction, swap, fixed_price, price, currency, digital) VALUES (:store_item_id, :title, :store_id, :game_id, :box_id, :url, :list_date, :expiration, :comments, :image, :auction, :swap, :fixed_price, :price, :currency, :digital)");
while($row = $result->fetch(PDO::FETCH_ASSOC)) {
    $auction = $row['auction'] == '1';
    $swap = $row['swap'] == '1';
    $fixedPrice = $row['fixed_price'] == '1';
    $digital = $row['digital'] == '1';
    $query->bindValue(':store_item_id', $row['item_id'], PDO::PARAM_INT);
    $query->bindValue(':title', $row['item_title'], PDO::PARAM_STR);
    $query->bindValue(':store_id', $row['store_id'], PDO::PARAM_INT);
    $query->bindValue(':game_id', $row['games_id'], PDO::PARAM_INT);
    $query->bindValue(':box_id', $row['box_id'], PDO::PARAM_INT);
    $query->bindValue(':url', $row['item_url'], PDO::PARAM_STR);
    $query->bindValue(':list_date', $row['listdate'], PDO::PARAM_STR);
    $query->bindValue(':expiration', $row['expiration'], PDO::PARAM_STR);
    $query->bindValue(':comments', $row['comments'], PDO::PARAM_STR);
    $query->bindValue(':image', $row['image'], PDO::PARAM_STR);
    $query->bindValue(':auction', $auction, PDO::PARAM_BOOL);
    $query->bindValue(':swap', $swap, PDO::PARAM_BOOL);
    $query->bindValue(':fixed_price', $fixedPrice, PDO::PARAM_BOOL);
    $query->bindValue(':price', $row['price'], PDO::PARAM_STR);
    $query->bindValue(':currency', $row['currency'], PDO::PARAM_STR);
    $query->bindValue(':digital', $digital, PDO::PARAM_BOOL);
    try {
       $query->execute();
   } catch (Exception $e) {
    echo $e->getMessage();
   }
}

// Set initial display of store items
$db->query('UPDATE store_item SET display_number = 1 WHERE store_item_id = 103');
$db->query('UPDATE store_item SET display_number = 2 WHERE store_item_id = 102');
$db->query('UPDATE store_item SET display_number = 3 WHERE store_item_id = 101');
$db->query('UPDATE store_item SET display_number = 4 WHERE store_item_id = 100');
$db->query('UPDATE store_item SET display_number = 5 WHERE store_item_id = 99');
$db->query('UPDATE store_item SET display_number = 6 WHERE store_item_id = 98');
$db->query('UPDATE store_item SET display_number = 7 WHERE store_item_id = 97');
$db->query('UPDATE store_item SET display_number = 8 WHERE store_item_id = 96');









?>