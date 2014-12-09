<?php
/*
  Plugin Name: Our Team Showcase
  Plugin URI: http://smartcatdesign.net/our-team-showcase/
  Description: Display your team members in a very attractive way as a widget or page with a shortcode
  Version: 1.3
  Author: SmartCat
  Author URI: http://smartcatdesign.net
  License: GPL v2
 */

if(!defined('SC_TEAM_PATH'))
    define('SC_TEAM_PATH', plugin_dir_url(__FILE__));


register_activation_hook(__FILE__, 'sc_team');

function sc_team() {
    add_option('sc_team_activation_redirect', true);
    flush_rewrite_rules();
    sc_team_register_options();
}

function sc_team_register_options() {
    // declare options array
    $sc_team_options = array(
        'sc_our_team_template' => 'grid',
        'sc_our_team_social' => 'yes',
        'sc_our_team_profile_link' => 'yes',
        'sc_our_team_member_count' => -1,
        
    );
    // check if option is set, if not, add it
    foreach ($sc_team_options as $option_name => $option_value) {
        if (get_option($option_name) === false) {
            add_option($option_name, $option_value);
        } else {
            update_option($option_name, addslashes($_POST[$option_name]));
        }
    }
}

// redirect when activated
add_action('admin_init', 'sc_team_activation_redirect');

function sc_team_activation_redirect() {
    if (get_option('sc_team_activation_redirect', false)) {
        delete_option('sc_team_activation_redirect');
        wp_redirect(admin_url() . 'edit.php?post_type=team_member&page=sc_team_settings');
    }
}

/**
 * Hook implements admin_menu
 * function adds menu and calls options function
 * function adds submenu and calls reorder function
 */
add_action('admin_menu', 'sc_team_menu');

function sc_team_menu() {
//    add_options_page('Our Team Plugin Settings', 'Our Team Settings', 'administrator', 'sc_team_options.php', 'sc_team_options');
    add_submenu_page('edit.php?post_type=team_member', 'Settings', 'Settings', 'administrator', 'sc_team_settings', 'sc_team_settings');
    add_submenu_page('edit.php?post_type=team_member', 'Re-Order Members', 'Re-Order Members', 'administrator', 'sc_team_reorder', 'sc_team_reorder');
}

function sc_team_reorder() {
    include_once 'inc/reorder.php';
}

function sc_team_settings() {

    if (isset($_REQUEST['sc_our_team_save']) && $_REQUEST['sc_our_team_save'] == 'Update') {
        sc_team_register_options();
    }
    include_once 'inc/options.php';
}

/**
 * Hook implements admin_head
 * Function sets menu icon
 */
add_action('admin_head', 'sc_team_add_menu_icon');

function sc_team_add_menu_icon() {
    ?>
    <style>
        /*        #adminmenu .menu-icon-team_member div.wp-menu-image:before {
                    content: '\f338';
                }*/
    </style>
    <?php
}

/**
 * Hook implements wp_enqueue_scripts
 * function loads plugin styles and scripts
 */
function my_enqueue($hook) {
    wp_enqueue_style('sc_team_admin_style', SC_TEAM_PATH . 'style/sc_our_team_admin.css');
    wp_enqueue_script('my_custom_script', SC_TEAM_PATH . 'script/sc_our_team_admin.js', array('jquery'));
}

add_action('admin_enqueue_scripts', 'my_enqueue');

add_action('wp_enqueue_scripts', 'sc_team_load_styles_scripts');

function sc_team_load_styles_scripts() {
    // plugin main style
    wp_enqueue_style('sc_team_default_style', plugin_dir_url(__FILE__) . 'style/sc_our_team.css', false, '1.2');

    // plugin main script
    wp_enqueue_script('sc_team_default_script', plugin_dir_url(__FILE__) . 'script/sc_our_team.js', array('jquery'), '1.2');
}

/**
 * 
 */
add_shortcode('our-team', 'set_our_team');
function set_our_team($atts) {
    extract(shortcode_atts(array(
                    ), $atts));
    global $content;

    ob_start();

    if (get_option('sc_our_team_template') === false or get_option('sc_our_team_template') == '') {
        include 'inc/grid.php';
        $output = ob_get_clean();
    } else {
        include 'inc/' . esc_attr( get_option('sc_our_team_template') ) . '.php';
        $output = ob_get_clean();
    }
    return $output;
}

/**
 * Hook implements init
 * function creates and registers custom post type
 */
add_action('init', 'team_members');

function team_members() {
    $labels = array(
        'name' => _x('Team', 'post type general name'),
        'singular_name' => _x('Team Member', 'post type singular name'),
        'add_new' => _x('Add New', 'book'),
        'add_new_item' => __('Add New Member'),
        'edit_item' => __('Edit Member'),
        'new_item' => __('New Team Member'),
        'all_items' => __('All Team Members'),
        'view_item' => __('View Team Member'),
        'search_items' => __('Search Team Members'),
        'not_found' => __('No member found'),
        'not_found_in_trash' => __('No member found in the Trash'),
        'parent_item_colon' => '',
        'menu_name' => 'Our Team'
    );
    $args = array(
        'labels' => $labels,
        'description' => 'Holds our team members specific data',
        'public' => true,
        'menu_position' => 5,
        'menu_icon' => SC_TEAM_PATH . 'img/icon.png',
        'supports' => array('title', 'editor', 'thumbnail'),
        'has_archive' => true,
    );
    register_post_type('team_member', $args);
    //Ensure the $wp_rewrite global is loaded
    global $wp_rewrite;
    //Call flush_rules() as a method of the $wp_rewrite object
    $wp_rewrite->flush_rules( false );
}

/**
 * Hook to add custom fields box
 * calls a function
 */
add_action('add_meta_boxes', 'team_member_info_box');

function team_member_info_box() {
    add_meta_box(
            'team_member_info_box', __('Additional Information', 'myplugin_textdomain'), 'team_member_info_box_content', 'team_member', 'advanced', 'high'
    );
}

/**
 * function called by team_member_info_box
 */
function team_member_info_box_content($post) {
    //nonce
    wp_nonce_field(plugin_basename(__FILE__), 'team_member_info_box_content_nonce');

    //social

    echo '<p><em>Fields that are left blank, will simply not display any output</em></p>';

    echo '<table>';

    echo '<tr><td><lablel for="team_member_title">Job Title</lablel></td>';
    echo '<td><input type="text" value="' . get_post_meta($post->ID, 'team_member_title', true) . '" id="team_member_title" name="team_member_title" placeholder="Enter Job Title"/></td></tr>';

    echo '<tr><td><lablel for="team_member_email">Email Address</lablel></td>';
    echo '<td><input type="text" value="' . get_post_meta($post->ID, 'team_member_email', true) . '" id="team_member_email" name="team_member_email" placeholder="Enter Email Address"/></td></tr>';

    echo '<tr><td><lablel for="team_member_facebook">Facebook URL</lablel></td>';
    echo '<td><input type="text" value="' . get_post_meta($post->ID, 'team_member_facebook', true) . '" id="team_member_facebook" name="team_member_facebook" placeholder="Enter Facebook URL"/></td></tr>';

    echo '<tr><td><label for="team_member_twitter">Twitter URL</lablel></td>';
    echo '<td><input type="text" value="' . get_post_meta($post->ID, 'team_member_twitter', true) . '" id="team_member_twitter" name="team_member_twitter" placeholder="Enter Twitter URL"/></td></tr>';

    echo '<tr><td><lablel for="team_member_linkedin">Linkedin URL</lablel></td>';
    echo '<td><input type="text" value="' . get_post_meta($post->ID, 'team_member_linkedin', true) . '" id="team_member_linkedin" name="team_member_linkedin" placeholder="Enter Linkedin URL"/></td></tr>';

    echo '<tr><td><lablel for="team_member_gplus">Google Plus URL</lablel></td>';
    echo '<td><input type="text" value="' . get_post_meta($post->ID, 'team_member_gplus', true) . '" id="team_member_gplus" name="team_member_gplus" placeholder="Enter Google Plus URL"/></td></tr>';

    echo '</table>';
}

/**
 * Hook that handles submitted data
 */
add_action('save_post', 'team_member_box_save');

function team_member_box_save($post_id) {

    $slug = 'team_member';

    if (isset($_POST['post_type'])) {
        if ($slug != $_POST['post_type']) {
            return;
        }
    }

    // get var values
    if (get_post_meta($post_id, 'sc_member_order', true) == '' || get_post_meta($post_id, 'sc_member_order', true) === FALSE)
        update_post_meta($post_id, 'sc_member_order', 0);


    if (isset($_REQUEST['team_member_title'])) {
        $facebook_url = $_POST['team_member_title'];
        update_post_meta($post_id, 'team_member_title', $facebook_url);
    }

    if (isset($_REQUEST['team_member_email'])) {
        $facebook_url = $_POST['team_member_email'];
        update_post_meta($post_id, 'team_member_email', $facebook_url);
    }


    if (isset($_REQUEST['team_member_facebook'])) {
        $facebook_url = $_POST['team_member_facebook'];
        update_post_meta($post_id, 'team_member_facebook', $facebook_url);
    }

    if (isset($_REQUEST['team_member_twitter'])) {
        $twitter_url = $_POST['team_member_twitter'];
        update_post_meta($post_id, 'team_member_twitter', $twitter_url);
    }

    if (isset($_REQUEST['team_member_linkedin'])) {
        $linkedin_url = $_POST['team_member_linkedin'];
        update_post_meta($post_id, 'team_member_linkedin', $linkedin_url);
    }

    if (isset($_REQUEST['team_member_gplus'])) {
        $gplus_url = $_POST['team_member_gplus'];
        update_post_meta($post_id, 'team_member_gplus', $gplus_url);
    }
}

/**
 * Create custom widget
 */
// Creating the widget
class sc_team_widget extends WP_Widget {

    function __construct() {
        parent::__construct(
                'sc_team_widget', __('Our Team Widget', 'sc_team_widget_domain'), array('description' => __('Use this widget to display the Our Team anywhere on the site.', 'sc_team_widget_domain'),)
        );
    }

    // Creating widget front-end
    // This is where the action happens
    public function widget($args, $instance) {
        $title = apply_filters('widget_title', $instance['title']);

        // before and after widget arguments are defined by themes
        echo $args['before_widget'];
        if (!empty($title))
            echo $args['before_title'] . $title . $args['after_title'];

        // This is where you run the code and display the output
        include 'inc/widget.php';
        //        echo $args['after_title'];
    }

    // Widget Backend
    public function form($instance) {
        if (isset($instance['title'])) {
            $title = $instance['title'];
        } else {
            $title = __('Meet Our Team', 'sc_team_widget_domain');
        }
        // Widget admin form
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>
        <?php
    }

    // Updating widget replacing old instances with new
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title']) ) ? strip_tags($new_instance['title']) : '';
        return $instance;
    }

}

// Class sc_team_widget ends here
// Register and load the widget
function wpb_load_widget() {
    register_widget('sc_team_widget');
}

add_action('widgets_init', 'wpb_load_widget');


/**
 * Show post thumbnails on backend
 */
add_filter('manage_posts_columns', 'posts_columns', 5);
add_action('manage_posts_custom_column', 'posts_custom_columns', 5, 2);

function posts_columns($defaults) {
    $defaults['riv_post_thumbs'] = __('Profile Picture');
    return $defaults;
}

function posts_custom_columns($column_name, $id) {
    if ($column_name === 'riv_post_thumbs') {
        echo the_post_thumbnail('thumbnail');
    }
}

/**
 * ajax function to update post order
 */
add_action('wp_ajax_my_update_pm', 'sc_team_update_order');
add_action('wp_ajax_nopriv_my_update_pm', 'sc_team_update_order');

function sc_team_update_order() {
    $post_id = $_POST['id'];
    $sc_member_order = $_POST['sc_member_order'];
    //update_post_meta($post_id, $meta_key, $sc_member_order)
    update_post_meta($post_id, 'sc_member_order', $sc_member_order);
}

/**
 * Aux functions
 */
//social function
function get_social($facebook, $twitter, $linkedin, $gplus, $email) {
    if ('yes' == get_option('sc_our_team_social')) {
        if ($facebook != '')
            echo '<a href="' . $facebook . '"><img src="' . SC_TEAM_PATH . 'img/fb.png" class="sc-social"/></a>';
        if ($twitter != '')
            echo '<a href="' . $twitter . '"><img src="' . SC_TEAM_PATH . 'img/twitter.png" class="sc-social"/></a>';
        if ($linkedin != '')
            echo '<a href="' . $linkedin . '"><img src="' . SC_TEAM_PATH . 'img/linkedin.png" class="sc-social"/></a>';
        if ($gplus != '')
            echo '<a href="' . $gplus . '"><img src="' . SC_TEAM_PATH . 'img/google.png" class="sc-social"/></a>';
        if ($email != '')
            echo '<a href=mailto:"' . $email . '"><img src="' . SC_TEAM_PATH . 'img/email.png" class="sc-social"/></a>';
    }
}


function get_custom_post_type_template($single_template) {
     global $post;

     if ($post->post_type == 'my_post_type') {
          $single_template = dirname( __FILE__ ) . '/post-type-template.php';
     }
     return $single_template;
}
add_filter( 'single_template', 'get_custom_post_type_template' );


function sc_get_args(){
    $args = array(
        'post_type' => 'team_member',
        'meta_key' => 'sc_member_order',
        'orderby' => 'meta_value_num',
        'order' => 'ASC',
        'posts_per_page' => esc_attr( get_option('sc_our_team_member_count') ),
    );
    
    return $args;
}

function set_single_content($content) {
    global $post;

    if ($post->post_type == 'team_member') {
        $facebook = get_post_meta(get_the_ID(), 'team_member_facebook', true);
        $twitter = get_post_meta(get_the_ID(), 'team_member_twitter', true);
        $linkedin = get_post_meta(get_the_ID(), 'team_member_linkedin', true);
        $gplus = get_post_meta(get_the_ID(), 'team_member_gplus', true);
        $email = get_post_meta(get_the_ID(), 'team_member_email', true);
        
        echo '<div class="sc_team_single_icons">';
        get_social($facebook, $twitter, $linkedin, $gplus, $email);
        echo '</div>';
    }
    return $content;
}
add_filter('the_content', 'set_single_content');