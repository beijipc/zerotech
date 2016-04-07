<?php
/** common functions */
require_once( dirname( __FILE__ ) . '/files/common.php' );

function mytheme_setup() {
	/*
  注册 ============================================================
  */
  // 注册菜单
  register_nav_menus( array(
		'primary' => '顶导航',
		'secondary'  => '底导航',
	) );

	//定制筛选器
  add_action( 'restrict_manage_posts', 'custom_add_taxonomy_filters' );

	//定制列表页的列
	add_filter('manage_posts_columns', 'posts_columns_counts', 1);
	//为列填充内容
	add_action('manage_posts_custom_column', 'posts_custom_columns_counts', 10, 2);
  //重写搜索规则
  add_filter( 'search_rewrite_rules', 'my_search_rewrite_rules' );
  //加载前端CSS和JS
  // add_action( 'wp_enqueue_scripts', 'my_scripts' );
}
add_action( 'after_setup_theme', 'mytheme_setup' );



function my_scripts() {
	// Add custom fonts, used in the main stylesheet.
	wp_enqueue_style( 'twentysixteen-fonts', twentysixteen_fonts_url(), array(), null );

	// Add Genericons, used in the main stylesheet.
	wp_enqueue_style( 'genericons', get_template_directory_uri() . '/genericons/genericons.css', array(), '3.4.1' );

	// Theme stylesheet.
	wp_enqueue_style( 'twentysixteen-style', get_stylesheet_uri() );

	// Load the Internet Explorer specific stylesheet.
	wp_enqueue_style( 'twentysixteen-ie', get_template_directory_uri() . '/css/ie.css', array( 'twentysixteen-style' ), '20150930' );
	wp_style_add_data( 'twentysixteen-ie', 'conditional', 'lt IE 10' );

	// Load the Internet Explorer 8 specific stylesheet.
	wp_enqueue_style( 'twentysixteen-ie8', get_template_directory_uri() . '/css/ie8.css', array( 'twentysixteen-style' ), '20151230' );
	wp_style_add_data( 'twentysixteen-ie8', 'conditional', 'lt IE 9' );

	// Load the Internet Explorer 7 specific stylesheet.
	wp_enqueue_style( 'twentysixteen-ie7', get_template_directory_uri() . '/css/ie7.css', array( 'twentysixteen-style' ), '20150930' );
	wp_style_add_data( 'twentysixteen-ie7', 'conditional', 'lt IE 8' );

	// Load the html5 shiv.
	wp_enqueue_script( 'twentysixteen-html5', get_template_directory_uri() . '/js/html5.js', array(), '3.7.3' );
	wp_script_add_data( 'twentysixteen-html5', 'conditional', 'lt IE 9' );

	wp_enqueue_script( 'twentysixteen-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20151112', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	if ( is_singular() && wp_attachment_is_image() ) {
		wp_enqueue_script( 'twentysixteen-keyboard-image-navigation', get_template_directory_uri() . '/js/keyboard-image-navigation.js', array( 'jquery' ), '20151104' );
	}

	wp_enqueue_script( 'twentysixteen-script', get_template_directory_uri() . '/js/functions.js', array( 'jquery' ), '20151204', true );

	wp_localize_script( 'twentysixteen-script', 'screenReaderText', array(
		'expand'   => __( 'expand child menu', 'twentysixteen' ),
		'collapse' => __( 'collapse child menu', 'twentysixteen' ),
	) );
}


//定制筛选器
function custom_add_taxonomy_filters(){
	global $typenow;

	switch ($typenow) {
    case 'product':
      add_taxonomy_filters('taxonomy_product');
      break;
		default:
      break;
  }
}

// 定制列表页的列
function posts_columns_counts($defaults){
    global $post;
		$post_type = get_post_type($post);
   /* if($post_type=="event"){
    	$defaults['lan_status'] = '是否双语';
    }*/

    $defaults['thumb_image'] = '缩略图';
    return $defaults;
}

// 为列填充内容
function posts_custom_columns_counts($column_name, $id){
    global $post;

    switch ( $column_name ) {
		 case 'thumb_image' :
//              $image = get_field('image');
//              if($image):
//		 	    		$url = $image['sizes']['admin-list-thumbnail'];
//              echo "<img src='".$url."' />";
//              endif;
            break;
     case 'lan_status' :
//        $lan = get_field("switch_lan");
//        $lan = ($lan==1)?"是":"";
//        echo $lan;
        break;
        default:
            break;
    }
}
// 注册缩略图规则
function add_customize_image_size(){
  add_theme_support('post-thumbnails');
  set_post_thumbnail_size( 100, 100, true );

  //默认已存在 thumbnail, medium, large, full 四个尺寸规则
  //add_image_size( 'admin-list-thumbnail', '100', '100', false );

	//产品图尺寸 1：1
	add_image_size( '840x840', '840', '840', true );
	add_image_size( '350x350', '350', '350', true );

	//页头图尺寸  16：7.5
	add_image_size( '1600x750', '1600', '750', true );
	add_image_size( '1200x563', '1200', '563', true );
	add_image_size( '600x281', '600', '281', true );//品牌缩略图
	add_image_size( '200x94', '200', '94', true );//溢美家主菜单

}

//重写地址规则
function my_search_rewrite_rules( $rules ){
	$rules['search/(.+)/by/(.+)/?$'] = 'index.php?s=$matches[1]&by=$matches[2]';
	return $rules;
}
