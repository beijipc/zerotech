<?php
function my_theme_setup() {
	/*
	效率优化============================================================
	*/
	//过滤google字体
	add_filter('gettext_with_context', 'disable_open_sans', 888, 4 );
	//清除无用的页头信息
	remove_page_head_actions();
	//清除Emoji
	remove_emoji();
	//============================================================


    /*
    美化============================================================
    */
    //管理员导航条WP LOGO
    add_action( 'admin_bar_menu', 'cwp_remove_wp_logo_from_admin_bar_new', 25 );
    //移除某些WP自带的小工具
  	add_action( 'widgets_init', 'coolwp_remove_meta_widget',11 );

		//定制后台head
		//add_action('admin_head', 'custom_admin_head');
    //定制登陆界面
    add_action('login_head', 'custom_login');
    add_filter('login_headerurl', 'custom_login_logo_url');
    //定制仪表盘
    add_action( 'wp_dashboard_setup', 'remove_dashboard_widgets' );
 		add_action( 'load-index.php', 'remove_welcome_panel' );
    //加载后台的JS和CSS
    add_action( 'admin_enqueue_scripts', 'add_admin_enqueue_scripts');
    //加载前端JS、CSS
    //add_action( 'wp_enqueue_scripts', 'wva_wp_enqueue_scripts' );
    //============================================================

	//设置后台列数
	 add_filter('screen_layout_columns', 'wpdx_screen_layout_columns');
	//设置默认列数
 add_filter('get_user_option_screen_layout_dashboard', 'wpdx_screen_layout_dashboard');

}
add_action( 'after_setup_theme', 'my_theme_setup' );


//过滤google字体
function disable_open_sans($translations, $text, $context, $domain ){
    if ('Open Sans font: on or off' == $context && 'on' == $text ) {
        $translations = 'off';
    }
    return $translations;
}

//登陆界面
function custom_login(){
    echo '<style type="text/css">.login h1 a {height:100px;width:100%;background-size:100%;background-image: url('.get_stylesheet_directory_uri().'/files/logo_login.png); } </style>';
}
//登录LOGO链接
function custom_login_logo_url($url) {
    //在此输入你需要链接到的URL地址
    return get_bloginfo('url');
}

//定制后台head
function custom_admin_head(){
}

//加载前端JS、CSS
function add_wp_enqueue_scripts(){
    //get_stylesheet_directory_uri();//获取子主题style.css所在目录
    //get_stylesheet_uri();//获取子主题style.css路径
    //get_template_directory_uri();//获取父主题目录
}

//加载后台的JS和CSS
function add_admin_enqueue_scripts(){
    global $current_user;
    if($current_user->roles[0] == 'site_admin'){
        //维护者后台样式
        wp_enqueue_style( 'custom-roles-css', get_stylesheet_directory_uri().'/files/roles.css', false );
    }
    //加载CSS
    wp_enqueue_style( 'custom-admin-css', get_stylesheet_directory_uri().'/files/admin.css', false );
    //加载JS
    wp_enqueue_script( 'custom-admin-js', get_stylesheet_directory_uri().'/files/admin.js',array(), 'v1.0', false );

    // 去除已注册的 jquery 脚本
    //wp_deregister_script( 'jquery' );
    // 注册 jquery 脚本
    //wp_register_script( 'jquery', 'http://code.jquery.com/jquery.min.js', array(), 'lastest', false );
    // 提交加载 jquery 脚本
    //wp_enqueue_script( 'jquery' );
}



/**
 * 移除页头动作勾子 ================================================================================
 */

function remove_page_head_actions(){
	remove_action( 'wp_head', 'wp_generator' ); //wp版本信息
	remove_action( 'wp_head', 'rsd_link' ); //移除离线编辑器开放接口
  remove_action( 'wp_head', 'wlwmanifest_link' ); //移除离线编辑器开放接口
	remove_action( 'wp_head', 'feed_links', 2 ); //移除feed
  remove_action( 'wp_head', 'feed_links_extra', 3 ); //移除feed
  remove_action( 'wp_head', 'index_rel_link' );//去除本页唯一链接信息
  remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );//清除前后文信息
  remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );//清除前后文信息
  remove_action( 'wp_head', 'adjacent_posts_rel_link', 10, 0 );
}

function remove_emoji(){
	remove_action('admin_print_scripts',	'print_emoji_detection_script');
	remove_action('admin_print_styles',	'print_emoji_styles');

	remove_action('wp_head',		'print_emoji_detection_script',	7);
	remove_action('wp_print_styles',	'print_emoji_styles');

	remove_action('embed_head',		'print_emoji_detection_script');

	remove_filter('the_content_feed',	'wp_staticize_emoji');
	remove_filter('comment_text_rss',	'wp_staticize_emoji');
	remove_filter('wp_mail',		'wp_staticize_emoji_for_email');
}
 /**
 * 自定义仪表盘 ================================================================================
 */

function remove_dashboard_widgets() {
    global $wp_meta_boxes;
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);    //快速发布
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);    //WordPress China 博客
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);  //其它WordPress新闻

    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);//链入链接
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);     //概况
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);   //插件
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_drafts']);  //近期草稿
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']);//近期评论
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_activity']);//近期评论
    //添加仪表盘项目
    wp_add_dashboard_widget(
       'welcome_dashboard_widget',         // Widget slug.
       '欢迎',         // Title.
       'welcome_dashboard_widget_function' // Display function.
    );
}
//添加仪表盘欢迎页
function remove_welcome_panel() {
    remove_action('welcome_panel', 'wp_welcome_panel');
    $user_id = get_current_user_id();
    if (0 !== get_user_meta( $user_id, 'show_welcome_panel', true ) ) {
        update_user_meta( $user_id, 'show_welcome_panel', 0 );
    }
}
function welcome_dashboard_widget_function() {
	echo "后台已准备就绪，欢迎使用 ".get_bloginfo('name')." 后台管理系统！";
}
 function cwp_remove_wp_logo_from_admin_bar_new( $wp_admin_bar ) {
    $wp_admin_bar->remove_node( 'wp-logo' );
}
 function coolwp_remove_meta_widget() {
     unregister_widget('WP_Widget_Pages');
     unregister_widget('WP_Widget_Calendar');
     //unregister_widget('WP_Widget_Archives');
     unregister_widget('WP_Widget_Links');
     unregister_widget('WP_Widget_Meta');
    // unregister_widget('WP_Widget_Search');
    // unregister_widget('WP_Widget_Text');
     unregister_widget('WP_Widget_Categories');
     unregister_widget('WP_Widget_Recent_Posts');
     unregister_widget('WP_Widget_Recent_Comments');
     unregister_widget('WP_Widget_RSS');
     unregister_widget('WP_Widget_Tag_Cloud');
     unregister_widget('WP_Nav_Menu_Widget');
    /*register my custom widget*/
    //register_widget('WP_Widget_Meta_Mod');
}

 //设置栏数量
 function wpdx_screen_layout_columns($columns) {
	$columns['dashboard'] = 2;
	return $columns;
}
 //设置默认栏目
 function wpdx_screen_layout_dashboard() { return 1; }




// 筛选器
function add_taxonomy_filters($taxonomy_name) {
//	global $typenow;

	// an array of all the taxonomyies you want to display. Use the taxonomy name or slug


	// must set this to the post type you want the filter(s) displayed on
//	if( $typenow == 'scene' ){
		$taxonomies = array($taxonomy_name);
		foreach ($taxonomies as $tax_slug) {
			$tax_obj = get_taxonomy($tax_slug);
			// var_dump($tax_slug);
			// var_dump($_GET[$tax_slug]);
			$tax_name = $tax_obj->labels->name;
			$terms = get_terms($tax_slug);
			if(count($terms) > 0) {
				echo "<select name='$tax_slug' id='$tax_slug' class='postform'>";
				echo "<option value=''>所有$tax_name</option>";
				foreach ($terms as $term) {
					// var_dump($term);
					// echo $_GET[$tax_name];
					if(isset($_GET[$tax_slug])){
						echo '<option value='. $term->slug, $_GET[$tax_slug] == $term->slug ? ' selected="selected"' : '','>' . $term->name .' (' . $term->count .')</option>';
					}else{
						echo '<option value='. $term->slug . '>' . $term->name .' (' . $term->count .')</option>';
					}
				}
				echo "</select>";
			}
		}
//	}
}
