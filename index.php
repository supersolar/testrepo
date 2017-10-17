<?
require_once('ini_set.php');
require_once('definitions.inc');
require_once('functions.inc');
require_once('nav/navigation.php');

session_start();

$_SESSION['page_id'] = 1;
$page_id = 1;
echo $page_id;
die('test4');




// setup default content
$meta_title = '';
$meta_keywords = '';
$meta_description = '';
$content = '';

$uses_js[] = 'lib/jquery-1.3.2.min.js';
$uses_js[] = 'lib/jquery-ui.min.js';
$uses_js[] = 'divshow.js';

// query database for page details
$sql =  'SELECT '.
		'p.id, pc.h1, pc.h2, pc.h3, pc.c1, pc.c2, pc.c3, '.
		'pc.c1_link, pc.c2_link, pc.c3_link, '.
		'pc.image1_id, pc.image1_id_link, '.
		'pc.ql1, pc.ql2, pc.ql3, pc.ql4, pc.ql5, pc.ql6, pc.ql7, pc.ql8, '.
		'pc.ql_title1, pc.ql_title2, pc.ql_title3, pc.ql_title4, pc.ql_title5, pc.ql_title6, pc.ql_title7, pc.ql_title8, '.
		'p.keywords, p.description, '.
		'CONCAT("'.FILE_PATH_LIBRARY.'", l1.filename) AS image1_id_filename '.
		'FROM pg p '.
		'INNER JOIN pg_content_01 pc ON pc.id = p.content_id '.
		'LEFT JOIN library l1 ON l1.id=pc.image1_id '.
		'WHERE p.id = "1" ';

$res = query( $sql );

$i = mysql_fetch_assoc($res);
$meta_title = stripslashes(strip_tags($i['title']));
$meta_keywords = stripslashes(strip_tags($i['keywords']));
$meta_description = stripslashes(strip_tags($i['description']));

/****************************************************
* Begin Page Output
****************************************************/
require_once('header.php');
?>
<div id="home_top"><!-- begin home_top -->
	<div class="container"><!-- begin container -->
		<div class="col_left"><!-- begin col_left -->
			<?=stripslashes(getQuickLinks($i));?>
		</div><!-- end col_left -->
		<div class="col_mid"><!-- begin col_mid -->
			<div class="hero"><!-- begin hero -->
				<?=getSlideshow();?>
				<?//=(!empty($i['image1_id_link'])?getLinks($i['image1_id_link'],(file_exists($i['image1_id_filename'])&&!is_dir($i['image1_id_filename'])?'<img src="'.ROOT.$i['image1_id_filename'].'" alt="" />':'')):'');?>

			</div><!-- end hero -->
		</div><!-- end col_mid -->
		<div class="col_right">
			<?=(!empty($i['h1'])?'<h2>'.stripslashes($i['h1']).'</h2>':'');?>
			<?=(!empty($i['c1'])?stripslashes($i['c1']):'');?>
			<?=(!empty($i['c1_link'])?getLinks($i['c1_link'],'<img src="'.ROOT.'images/btn_learnmore_g.gif" alt="Learn more" />','button'):'');?>
		</div><!-- end col_right -->
		<div class="cleaner"></div><!-- end cleaner -->
	</div><!-- end container -->
</div><!-- end home_top -->
<div id="main"><!-- begin main -->
	<div class="container"><!--begin container-->
		<div class="threecol first">
			<?=(!empty($i['h2'])?'<h3>'.stripslashes($i['h2']).'</h3>':'');?>
			<?=(!empty($i['c2'])?'<p>'.stripslashes($i['c2']).'</p>':'');?>
			<?=(!empty($i['c2_link'])?getLinks($i['c2_link'],'<img src="'.ROOT.'images/btn_learnmore_w.gif" alt="Learn more" />','button'):'');?>
		</div><!-- end threecol first -->
		<div class="threecol">
			<?=(!empty($i['h3'])?'<h3>'.stripslashes($i['h3']).'</h3>':'');?>
			<?=(!empty($i['c3'])?'<p>'.stripslashes($i['c3']).'</p>':'');?>
			<?=(!empty($i['c3_link'])?getLinks($i['c3_link'],'<img src="'.ROOT.'images/btn_learnmore_w.gif" alt="Learn more" />','button'):'');?>
		</div><!-- end threecol -->
		<div class="threecol last">
			<h3><a href="<?ROOT?>news/index.php">News &amp; Updates</a></h3>
			<?=getLatestNews();?>
		</div><!-- end threecol last -->
		<div class="cleaner"></div><!-- end cleaner -->
	</div><!--end container-->
</div><!-- end main -->
<? require_once('footer.php'); ?>

<?
/*********************************************************
					SLIDESHOW
**********************************************************/
function getSlideshow() {
	$sql =
		'SELECT '.
		'pc.image1_id_link, pc.image2_id_link, pc.image3_id_link, pc.image4_id_link, pc.image5_id_link, '.
		'CONCAT("'.FILE_PATH_LIBRARY.'", l1.filename) AS image1_id_filename, '.
		'CONCAT("'.FILE_PATH_LIBRARY.'", l2.filename) AS image2_id_filename, '.
		'CONCAT("'.FILE_PATH_LIBRARY.'", l3.filename) AS image3_id_filename, '.
		'CONCAT("'.FILE_PATH_LIBRARY.'", l4.filename) AS image4_id_filename, '.
		'CONCAT("'.FILE_PATH_LIBRARY.'", l5.filename) AS image5_id_filename '.
		'FROM pg p '.
		'INNER JOIN pg_content_01 pc ON pc.id = p.content_id '.
		'LEFT JOIN library l1 ON l1.id=pc.image1_id '.
		'LEFT JOIN library l2 ON l2.id=pc.image2_id '.
		'LEFT JOIN library l3 ON l3.id=pc.image3_id '.
		'LEFT JOIN library l4 ON l4.id=pc.image4_id '.
		'LEFT JOIN library l5 ON l5.id=pc.image5_id '.
		'WHERE p.id = "1" ';

	$res = query($sql);

	$slideshow = '';
	$slideshow .= '<div id="slideID" class="divshow">';
	while($s = mysql_fetch_assoc($res)) {
		for( $n=1; $n<6; $n++ ) {
			if($s['image'.($n).'_id_filename']) {
				$slideshow .= '<div class="slide">';
				if(!empty($s['image'.$n.'_id_link'])) {
					$slideshow .= '<a href="'.$s['image'.$n.'_id_link'].'">';
					$slideshow .= '<img src="'.$s['image'.$n.'_id_filename'].'" />';
					$slideshow .= '</a>';
				} else {
					$slideshow .= '<img src="'.$s['image'.$n.'_id_filename'].'" />';
				}
				$slideshow .= '</div><!-- end slide -->';
			}
		}
	}
	/*
	$slideshow .= '<div class="hero_control">';
	$slideshow .= '<a href="javascript:divshow[\'slideID\'].prev()">PREVIOUS</a>&nbsp;';
	$slideshow .= '<a href="javascript:divshow[\'slideID\'].next()">NEXT</a>';
	$slideshow .= '</div><!-- end slide-controls -->';
	*/
	$slideshow .= '</div><!-- end divshow -->';
/*
	<div class="hero_control">
		<img src="<?=ROOT;?>images/arrow_rew.gif" class="rew" />
		<img src="<?=ROOT;?>images/arrow_ff.gif" class="ff" />
	</div>
*/
	return $slideshow;
}
/*********************************************************
						NEWS
**********************************************************/
function getLatestNews($limit=3) {
	$sql = "SELECT id, headline, news_date, body FROM news ".
			"WHERE ".
			"expiration_date > '".date("Y-m-d")."' OR expiration_date = '0000-00-00' ".
			"ORDER BY news_date DESC LIMIT ".$limit;

	$res = query($sql);

	if (mysql_num_rows($res)) {
		$str = '<ul class="home_news">'."\n";

		while ($i = mysql_fetch_assoc($res)) {
			$str .= '<li>';
			$str .= '<span class="date">';
			$str .= date("n/j", strtotime($i['news_date']));
			$str .= ' - '.stripslashes(($i['headline']));
			$str .= '</span>';
			$str .= '<p>'.stripslashes(create_text_snippet($i['body'],8)).'</p>';
			$str .= '<a href="'.ROOT.'news/index.php?nid='.$i['id'].'">Read&nbsp;more</a>';
			$str .= '</li>'."\n";
		}
		$str .= '</ul>'."\n";
	}
	return $str;
}
?>
