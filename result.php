<?php
//header('Content-type: text/plain; charset=utf-8');

// define('_DB_SERVER_', 'localhost');
// define('_DB_NAME_', 'big5_ne');
// define('_DB_USER_', 'root');
// define('_DB_PASSWD_', '');
// define('_DB_PREFIX_', 'ne_');
// define('_MYSQL_ENGINE_', 'InnoDB');
// define('_PS_CACHING_SYSTEM_', 'CacheMemcache');
// define('_PS_CACHE_ENABLED_', '0');
// //define('_COOKIE_KEY_', 'eLQQayYVFNOUNbT1XPIzmVVh6HgmqMVKSpr2gaPzjc6IMFcji3tCu1k2');
// define('_COOKIE_KEY_', 'pYu1Nt0ESrO0yMmMA2BuU2eyiXVubHrj6GJhHKgI9SxGVwxVmshdaKJu');
// define('_COOKIE_IV_', '1t4wwZGZ');
// define('_PS_CREATION_DATE_', '2017-11-20');
// if (!defined('_PS_VERSION_'))
	// define('_PS_VERSION_', '1.6.1.17');
// define('_RIJNDAEL_KEY_', 'c9htvzBWVEERZnIq7EjOf1tqiERVjC16');
// define('_RIJNDAEL_IV_', '2/ffXLNkHcgYWhlCT0Lc8Q==');

define('_DB_SERVER_', 'sql.big5.nazwa.pl');
define('_DB_NAME_', 'big5_ne');
define('_DB_USER_', 'big5_ne');
define('_DB_PASSWD_', 'New-electric-v2');
define('_DB_PREFIX_', 'ne_');
define('_MYSQL_ENGINE_', 'InnoDB');
define('_PS_CACHING_SYSTEM_', 'CacheMemcache');
define('_PS_CACHE_ENABLED_', '0');
//define('_COOKIE_KEY_', 'eLQQayYVFNOUNbT1XPIzmVVh6HgmqMVKSpr2gaPzjc6IMFcji3tCu1k2');
define('_COOKIE_KEY_', 'pYu1Nt0ESrO0yMmMA2BuU2eyiXVubHrj6GJhHKgI9SxGVwxVmshdaKJu');
define('_COOKIE_IV_', '1t4wwZGZ');
define('_PS_CREATION_DATE_', '2017-11-20');
if (!defined('_PS_VERSION_'))
	define('_PS_VERSION_', '1.6.1.17');
define('_RIJNDAEL_KEY_', 'c9htvzBWVEERZnIq7EjOf1tqiERVjC16');
define('_RIJNDAEL_IV_', '2/ffXLNkHcgYWhlCT0Lc8Q==');

//include_once('../../config/config.inc.php');
//include_once('../../init.php');

//define('_PS_BASE_URL_', 'http://'.'192.168.1.100'.$_SERVER['PHP_SELF']);
define('_PS_BASE_URL_', 'https://new-electric.pl');
define('_DB_CHARSET_', 'utf8');

class AmplifiersClass
{
    /**
    * Get all available products
    *
    * @param int $id_lang Language id
    * @param int $start Start number
    * @param int $limit Number of products to return
    * @param string $order_by Field for ordering
    * @param string $order_way Way for ordering (ASC or DESC)
    * @return array Products details
    */
    public $dbh;
    
    public static function ConnectDb() 
    {
            $dbh = new PDO('mysql:host='._DB_SERVER_.'; dbname='._DB_NAME_.'; charset='._DB_CHARSET_, _DB_USER_, _DB_PASSWD_);
            return $dbh;
    }
    public static function getProductsAm($id_lang, $start, $limit, $order_by, $order_way, $id_category = false, /*D:\wamp64\www\new-electric-v2\classes\Product.php*/
        $only_active = false, Context $context = null, $id_product_my = null)
    {
        $id_lang = 1;
        $sql = 'SELECT p.`id_product`, p.`price`, pl.`description_short`, pl.`link_rewrite`, pl.`name`, p.`id_category_default`
				FROM `'._DB_PREFIX_.'product` p

				LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (p.`id_product` = pl.`id_product`)'.
                ($id_category ? 'LEFT JOIN `'._DB_PREFIX_.'category_product` c ON (c.`id_product` = p.`id_product`)' : '').'
				WHERE pl.`id_lang` = '.(int)$id_lang.
                    ($id_category ? ' AND c.`id_category` = '.(int)$id_category : '').

                    ($id_product_my ? ' AND p.`id_product` = '.$id_product_my : '').
                    ($only_active ? ' AND product_shop.`active` = 1' : '').'
				ORDER BY '.(isset($order_by_prefix) ? $order_by_prefix.'.' : '').'`'.$order_by.'` '.$order_way.
                ($limit > 0 ? ' LIMIT '.(int)$start.','.(int)$limit : '');
                
        $sql2 = 'SELECT *
				FROM `'._DB_PREFIX_.'product` p
                LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (p.`id_product` = pl.`id_product`)
                LEFT JOIN `'._DB_PREFIX_.'product_shop` ps ON (p.`id_product` = ps.`id_product`)'.
                ($id_category ? 'LEFT JOIN `'._DB_PREFIX_.'category_product` c ON (c.`id_product` = p.`id_product`)' : '').'
                WHERE pl.`id_lang` = '.(int)$id_lang.
                    ($id_category ? ' AND c.`id_category` = '.(int)$id_category : '').
                    ($only_active ? ' AND ps.`active` = 1' : '').
                    ($limit > 0 ? ' LIMIT '.(int)$start.','.(int)$limit : '');
                    
        //$rq = $this->ConnectDb()->query($sql);
        //$dbh = new PDO('mysql:host='._DB_SERVER_.'; dbname='._DB_NAME_.'; charset='._DB_CHARSET_, _DB_USER_, _DB_PASSWD_);
        $dbh = AmplifiersClass::ConnectDb();
		$rq = $dbh->query($sql2);
        $rq = $rq->fetchAll(PDO::FETCH_ASSOC);
        
        return ($rq);
    }
    public static function getProductDefaultCategoryAm($id_product, $id_lang = null, $id_category_default){

        $sql = 'SELECT c.id_category, c.id_parent, cl.name, cl.link_rewrite
                FROM '._DB_PREFIX_.'category_product cp
                INNER JOIN '._DB_PREFIX_.'category c ON cp.id_category = c.id_category
                INNER JOIN '._DB_PREFIX_.'category_lang cl ON cl.id_category = c.id_category
                WHERE cp.id_product = ' . $id_product . ' and cl.id_lang = ' . $id_lang . ' and cl.id_category = '.$id_category_default.'
                ORDER BY c.level_depth DESC
                LIMIT 1';
        $dbh = AmplifiersClass::ConnectDb();
		$result = $dbh->query($sql);
        $result = $result->fetch(PDO::FETCH_ASSOC);

        return $result;
    }

    public static function getProductImages($id_product)
    {
        $sql = 'SELECT `id_image` 
                FROM `'._DB_PREFIX_.'image` 
                WHERE `id_product` = '.(int)($id_product);
                
        $dbh = AmplifiersClass::ConnectDb();
        $result = $dbh->query($sql);
        $result = $result->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

}

//var_dump(showResult());
// echo $_POST['a1'].'<br />';
// echo $_POST['a2'].'<br />';
// echo $_POST['a3'].'<br />';
// echo $_POST['a4'].'<br />';
// echo $_POST['a5'].'<br />';
// echo $_POST['a6'].'<br />';

$a = $_POST['a1']; /*a,b,c,d,e,f,g,h*/ /*typ wzmacniacza*/ /**/
$b = $_POST['a2']; /*a,b,c*/ /*ilość grzybków*/ /*a:1g, b:2g, c:3g-4g*/
$c = $_POST['a3']; /*a,b*/ /*antena zewnętrzna panel lub tajfun*/ /*a:panelowa, b:tajfun*/
if ( $c == 'c' || $c == 'd' || $c == 'e' || $c == 'f') {
    $c = 'b';
}

//echo $_POST['a1'].$_POST['a2'].$_POST['a3'].$_POST['a4'].$_POST['a5'].$_POST['a6'];
//echo $a.$b.$c;
// foreach (AmplifiersClass::showResult() as $result) {
    // foreach ($result as $key => $value) {
        // //echo $key.'-'.$value.'<br />';
        // $accept_id[] = $value;
    // }
// }

$am_cat = false;
$do = true;
$check_url = explode('://', _PS_BASE_URL_);
//var_dump($check_url);
//echo $check_url[1];
//echo _PS_BASE_URL_;
if ( $check_url[1] == '192.168.1.100') {
    if( $_POST['a1'] == 'a' ) {
        $am_cat = 135;
    } elseif ( $_POST['a1'] == 'b' ) {
        $am_cat = 136;
    } elseif ( $_POST['a1'] == 'c' ) {
        $am_cat = 137;
    } elseif ( $_POST['a1'] == 'd' ) {
        $am_cat = 138;
    } elseif ( $_POST['a1'] == 'e' ) {
        $am_cat = 139;
    } elseif ( $_POST['a1'] == 'f' ) {
        $am_cat = 140;
    } elseif ( $_POST['a1'] == 'g' ) {
        $am_cat = 141;
    } elseif ( $_POST['a1'] == 'h' ) {
        $am_cat = 142;
    }
}
if ( $check_url[1]== 'new-electric.pl') {
    require_once 'category.php';
}

$get = AmplifiersClass::getProductsAm($id_lang=1, $start=0, $limit=0, $order_by='id_product', $order_way='DESC', $id_category=$am_cat, $only_active=true, $context=null, $id_product_my=null);


if ($get) {
    ?>
    <div id="chat_display_result_beafore" class="columns-container col-xs-12 col-sm-12"><span id="result_close">X</span></div>
	<ul class="product_list product-list">
    <?php
    foreach ($get as $result) {
        foreach ($result as $key => $value) {
            //echo $key.'-'.$value.'<br />'; 
        }
        /**  **/
        
        /** CATEGORY **/
        $id_category_default = $result['id_category_default'];
        $cd = AmplifiersClass::getProductDefaultCategoryAm($result['id_product'], 1, $result['id_category_default']);
        //var_dump($cd);
        $cdn = $cd['name'];
        /** PRODUCT **/
        $id_product = $result['id_product'];//set your product ID here
        
        $name = $result['name'];
        //$price = round($result['price']*1.23, 2); // (23/100)+100
        $rate = 23;
        $price = round( $result['price']+($result['price']*($rate/100)), 2); // (23/100)+100
        $sale = 5;
        $sale_price = round( $price-($price*($sale/100)), 2); // (23/100)+100
        $price = number_format($price, 2, ',', ' ');
        $sale_price = number_format($sale_price, 2, ',', ' ');
        $description_short = $result['description_short'];
        $max_char = 155;
        if ( strlen($description_short) > $max_char ) {
            $description_short = substr($result['description_short'], 0, $max_char)."...";
        }
        /** LINK **/
        //$languageiso = Language::getIsoById(intval(Configuration::get('PS_LANG_DEFAULT'))).'/';
        $languageiso = '/pl/';
        $link_rewrite = 'https://new-electric.pl'.$languageiso.$cd['link_rewrite'].'/'.$id_product.'-'.$result['link_rewrite'].'.html';
        $imageId = AmplifiersClass::getProductImages($id_product);
        //var_dump($imageId);
        $img_id = $imageId['id_image'];
        //http://192.168.1.100/new-electric-v2/1781-large_default/komplet-gsmdcs-black-abs.jpg
        //https://new-electric.pl/1781-large_default/komplet-gsmdcs-black-abs.jpg 
        //$imageLink = 'http://192.168.1.100/new-electric-v2/'.$img_id.'-large_default/'.$result['link_rewrite'].'.jpg';
        $imageLink = 'https://new-electric.pl/'.$img_id.'-large_default/'.$result['link_rewrite'].'.jpg';
        //echo $imagePath.'<br />';
        //echo '<img src="http://'.$imagePath.'" /><br />';
        
        /*.tpl*/
        ?>
        <!-- Products list -->
        <li class="ajax_block_product col-xs-12 col-sm-6 col-md-4">
            <div class="product-block wt_container_thumbnail">
                <div class="product-container" itemscope="" itemtype="http://schema.org/Product">
                    <div class="left-block">
                        <!--<h5 class="cat-name"><?php //echo $cdn; ?></h5>-->
                        <h3 class="product-name">
                            <a class="product-name pd-product-name" target="_blank" href="<?php echo $link_rewrite; ?>" title="<?php echo $name; ?>"><?php echo $name; ?></a>
                        </h3>
                        <div class="product-block wt_container_thumbnail" wt-name-module="wtproductfilter" id-tab="1" wt-data-id-product="<?php echo $id_product; ?>">
                            <div class="div-product-image"> 
                                <a class="product_img_link" target="_blank" href="<?php echo $link_rewrite; ?>" title="Komplet GSM Silver LCD Omni z bat" itemprop="url"> 
                                    <img class="replace-2x img-responsive wt-image" src="<?php echo $imageLink; ?>" alt="<?php echo $name; ?>" title="<?php echo $name; ?>" itemprop="image"> 
                                    <span class="overlay"></span> 
                                </a>
                                <p class="sale-bkg animated"> 
                                    <span class="sale">-<?php echo $sale; ?>%</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="right-block">
                        <p class="product-desc pd-product-desc" itemprop="description"><?php echo $description_short; ?></p>
                        <div class="content_price clearfix"> 
                            <span class="price special-price"><?php echo $sale_price; ?> zł</span> 
                            <span class="old-price"><?php echo $price; ?> zł</span>
                        </div>

                        <div class="product-flags"> <span class="discount"></span></div>
                    </div>
                    <div class="end" style="width: 100%; height:17px; clear:both;"></div>
                </div>
            </div>
        </li>
        <!--/ Products list -->
        <?php
    }
} else {
    ?>
    <div id="pd-sorry">
        <p id="" class="">Przykro nam, że nie znaleźliśmy gotowego zestawu spełniającego Twoje kryteria, ale nie martw się skomponujemy go indywidualnie dla Ciebie.</p>
        <p id="" class="">Zapraszamy do kontaktu z naszym ekspertem.</p>
        <p id="" class=""><a target="_blank" href="https://new-electric.pl/pl/kontakt"><strong>Formularz kontaktowy</strong></a></p>
        <p id="" class="">Telefon: 733 101 606</p>
        <p id="" class="">Telefon: 508 964 552</p><!--Robert-->
    </div>
    <?php
}