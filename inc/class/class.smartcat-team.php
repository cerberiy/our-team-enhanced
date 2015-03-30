<?php


function sc_team_update_order() {
    $post_id = $_POST['id'];
    $sc_member_order = $_POST['sc_member_order'];
    update_post_meta($post_id, 'sc_member_order', $sc_member_order);
    exit();
}
add_action('wp_ajax_sc_team_update_order', 'sc_team_update_order');
add_action('wp_ajax_sc_team_update_order', 'sc_team_update_order');


class SmartcatTeamPlugin {

    const VERSION = '2.2';

    private static $instance;
    private $options;

    public static function instance() {
        if ( !self::$instance ) :
            self::$instance = new self;
            self::$instance->get_options();
            self::$instance->add_hooks();
        endif;
    }

    public static function activate() {

        $options = array(
            'template' => 'grid',
            'social' => 'yes',
            'single_social' => 'yes',
            'name' => 'yes',
            'title' => 'yes',
            'profile_link' => 'yes',
            'member_count' => -1,
            'text_color' => '1F7DCF',
            'columns' => '3',
            'margin' => 5,
            'height' => 170,
            'single_template' => 'standard',
            'redirect' => true,
            'single_image_size' => 'small',
        );

        if ( !get_option( 'smartcat_team_options' ) ) {
            add_option( 'smartcat_team_options', $options );
            $options[ 'redirect' ] = true;
            update_option( 'smartcat_team_options', $options );            
        }


    }

    public static function deactivate() {
        
    }

    private function add_hooks() {
        add_action( 'init', array( $this, 'team_members' ) );
        add_action( 'init', array( $this, 'team_member_positions' ), 0 );
        add_action( 'admin_init', array( $this, 'smartcat_team_activation_redirect' ) );
        add_action( 'admin_menu', array( $this, 'smartcat_team_menu' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'smartcat_team_load_admin_styles_scripts' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'smartcat_team_load_styles_scripts' ) );
        add_shortcode( 'our-team', array( $this, 'set_our_team' ) );
        add_action( 'add_meta_boxes', array( $this, 'smartcat_team_member_info_box' ) );
        add_action( 'save_post', array( $this, 'team_member_box_save' ) );
        add_action( 'widgets_init', array( $this, 'wpb_load_widget' ) );        
        add_action( 'wp_ajax_smartcat_team_update_pm', array( $this, 'smartcat_team_update_order' ) );
        add_action( 'wp_head', array( $this, 'sc_custom_styles' ) );
        add_filter( 'the_content', array( $this, 'smartcat_set_single_content' ) );
        add_filter( 'single_template', array( $this, 'smartcat_team_get_single_template' ) );
    }

    private function get_options() {
        if ( get_option( 'smartcat_team_options' ) ) :
            $this->options = get_option( 'smartcat_team_options' );
        endif;
    }

    /**
     * @todo check if redirect option is set and redirect
     */
    public function smartcat_team_activation_redirect() {
        if ( $this->options[ 'redirect' ] ) :
            $old_val = $this->options;
            $old_val[ 'redirect' ] = false;
            update_option( 'smartcat_team_options', $old_val );
            wp_safe_redirect( admin_url( 'edit.php?post_type=team_member&page=smartcat_team_settings' ) );
        endif;
    }

    public function smartcat_team_menu() {

        add_submenu_page( 'edit.php?post_type=team_member', 'Settings', 'Settings', 'administrator', 'smartcat_team_settings', array( $this, 'smartcat_team_settings' ) );
        add_submenu_page( 'edit.php?post_type=team_member', 'Re-Order Members', 'Re-Order Members', 'administrator', 'smartcat_team_reorder', array( $this, 'smartcat_team_reorder' ) );
    }

    public function smartcat_team_reorder() {
        include_once SC_TEAM_PATH . 'admin/reorder.php';
    }

    public function smartcat_team_settings() {

        if ( isset( $_REQUEST[ 'sc_our_team_save' ] ) && $_REQUEST[ 'sc_our_team_save' ] == 'Update' ) :
            update_option( 'smartcat_team_options', $_REQUEST[ 'smartcat_team_options' ] );
        endif;

        include_once SC_TEAM_PATH . 'admin/options.php';
    }

    public function smartcat_team_load_admin_styles_scripts( $hook ) {
        wp_enqueue_style( 'smartcat_team_admin_style', SC_TEAM_URL . 'inc/style/sc_our_team_admin.css' );
        wp_enqueue_script( 'smartcat_team_color_script', SC_TEAM_URL . 'inc/script/jscolor/jscolor.js', array( 'jquery' ) );
        wp_enqueue_script( 'smartcat_team_script', SC_TEAM_URL . 'inc/script/sc_our_team_admin.js', array( 'jquery' ) );
    }

    function smartcat_team_load_styles_scripts() {

        // plugin main style
        wp_enqueue_style( 'smartcat_team_default_style', SC_TEAM_URL . 'inc/style/sc_our_team.css', false, '1.0' );

        // plugin main script
        wp_enqueue_script( 'smartcat_team_default_script', SC_TEAM_URL . 'inc/script/sc_our_team.js', array( 'jquery' ), '1.0' );
    }

    function set_our_team( $atts ) {
        extract( shortcode_atts( array(
            'group' => '',

                        ), $atts ) );
        global $content;

        ob_start();
        
        $template = '';

        if( $template == '' ) :
            if ( $this->options[ 'template' ] === false or $this->options[ 'template' ] == '' ) :
                include SC_TEAM_PATH . 'inc/template/grid.php';
            else :
                include SC_TEAM_PATH . 'inc/template/' . $this->options[ 'template' ] . '.php';
            endif;
        else :
            include SC_TEAM_PATH . 'inc/template/grid.php';
        endif;

        $output = ob_get_clean();
        return $output;
    }

    function team_members() {
        $labels = array(
            'name' => _x( 'Team', 'post type general name' ),
            'singular_name' => _x( 'Team Member', 'post type singular name' ),
            'add_new' => _x( 'Add New', 'book' ),
            'add_new_item' => __( 'Add New Member' ),
            'edit_item' => __( 'Edit Member' ),
            'new_item' => __( 'New Team Member' ),
            'all_items' => __( 'All Team Members' ),
            'view_item' => __( 'View Team Member' ),
            'search_items' => __( 'Search Team Members' ),
            'not_found' => __( 'No member found' ),
            'not_found_in_trash' => __( 'No member found in the Trash' ),
            'parent_item_colon' => '',
            'menu_name' => 'Our Team'
        );
        $args = array(
            'labels' => $labels,
            'description' => 'Holds our team members specific data',
            'public' => true,
            'menu_icon' => SC_TEAM_URL . 'inc/img/icon.png',
            'supports' => array( 'title', 'editor', 'thumbnail' ),
            'has_archive' => false,
        );
        register_post_type( 'team_member', $args );
        flush_rewrite_rules();
    }

    public function team_member_positions() {
        $labels = array(
            'name' => _x( 'Groups', 'taxonomy general name' ),
            'singular_name' => _x( 'Group', 'taxonomy singular name' ),
            'search_items' => __( 'Search Groups' ),
            'all_items' => __( 'All Groups' ),
            'parent_item' => __( 'Parent Group' ),
            'parent_item_colon' => __( 'Parent Group:' ),
            'edit_item' => __( 'Edit Group' ),
            'update_item' => __( 'Update Group' ),
            'add_new_item' => __( 'Add New Group' ),
            'new_item_name' => __( 'New Group' ),
            'menu_name' => __( 'Groups' ),
        );
        $args = array(
            'labels' => $labels,
            'hierarchical' => true,
        );
        register_taxonomy( 'team_member_position', 'team_member', $args );
    }

    public function smartcat_team_member_info_box() {

        add_meta_box(
                'smartcat_team_member_info_box', __( 'Additional Information', 'myplugin_textdomain' ), array( $this, 'smartcat_team_member_info_box_content' ), 'team_member', 'normal', 'high'
        );
    }

    public function smartcat_team_member_info_box_content( $post ) {
        //nonce
        wp_nonce_field( plugin_basename( __FILE__ ), 'smartcat_team_member_info_box_content_nonce' );

        //social

        echo '<p><em>Fields that are left blank, will simply not display any output</em></p>';

        echo '<div class="sc_options_table">';
        
        echo '<table>';

        echo '<tr><td><lablel for="team_member_title">Job Title</lablel></td>';
        echo '<td><input type="text" value="' . get_post_meta( $post->ID, 'team_member_title', true ) . '" id="team_member_title" name="team_member_title" placeholder="Enter Job Title"/></td></tr>';

        echo '<tr><td><lablel for="team_member_email"><img src="' . SC_TEAM_URL . 'inc/img/email.png" width="20px"/></lablel></td>';
        echo '<td><input type="text" value="' . get_post_meta( $post->ID, 'team_member_email', true ) . '" id="team_member_email" name="team_member_email" placeholder="Enter Email Address"/></td></tr>';

        echo '<tr><td><lablel for="team_member_facebook"><img src="' . SC_TEAM_URL . 'inc/img/fb.png" width="20px"/></lablel></td>';
        echo '<td><input type="text" value="' . get_post_meta( $post->ID, 'team_member_facebook', true ) . '" id="team_member_facebook" name="team_member_facebook" placeholder="Enter Facebook URL"/></td></tr>';

        echo '<tr><td><label for="team_member_twitter"><img src="' . SC_TEAM_URL . 'inc/img/twitter.png" width="20px"/></lablel></td>';
        echo '<td><input type="text" value="' . get_post_meta( $post->ID, 'team_member_twitter', true ) . '" id="team_member_twitter" name="team_member_twitter" placeholder="Enter Twitter URL"/></td></tr>';

        echo '<tr><td><lablel for="team_member_linkedin"><img src="' . SC_TEAM_URL . 'inc/img/linkedin.png" width="20px"/></lablel></td>';
        echo '<td><input type="text" value="' . get_post_meta( $post->ID, 'team_member_linkedin', true ) . '" id="team_member_linkedin" name="team_member_linkedin" placeholder="Enter Linkedin URL"/></td></tr>';

        echo '<tr><td><lablel for="team_member_gplus"><img src="' . SC_TEAM_URL . 'inc/img/google.png" width="20px"/></lablel></td>';
        echo '<td><input type="text" value="' . get_post_meta( $post->ID, 'team_member_gplus', true ) . '" id="team_member_gplus" name="team_member_gplus" placeholder="Enter Google Plus URL"/></td></tr>';

        echo '</table>';
        echo '</div>';
        
        
        echo '<div class="sc_options_table">'
        . '<h2>Skills</h2>'
                . '<p><strong><em>Pro Version</em></strong></p>';
        
        echo '</div>'
        . '<div class="clear"></div>';

    }

    public function team_member_box_save( $post_id ) {

        $slug = 'team_member';

        if ( isset( $_POST[ 'post_type' ] ) ) {
            if ( $slug != $_POST[ 'post_type' ] ) {
                return;
            }
        }

        // get var values
        if ( get_post_meta( $post_id, 'sc_member_order', true ) == '' || get_post_meta( $post_id, 'sc_member_order', true ) === FALSE )
            update_post_meta( $post_id, 'sc_member_order', 0 );


        if ( isset( $_REQUEST[ 'team_member_title' ] ) ) {
            $facebook_url = $_POST[ 'team_member_title' ];
            update_post_meta( $post_id, 'team_member_title', $facebook_url );
        }

        if ( isset( $_REQUEST[ 'team_member_email' ] ) ) {
            $facebook_url = $_POST[ 'team_member_email' ];
            update_post_meta( $post_id, 'team_member_email', $facebook_url );
        }


        if ( isset( $_REQUEST[ 'team_member_facebook' ] ) ) {
            $facebook_url = $_POST[ 'team_member_facebook' ];
            update_post_meta( $post_id, 'team_member_facebook', $facebook_url );
        }

        if ( isset( $_REQUEST[ 'team_member_twitter' ] ) ) {
            $twitter_url = $_POST[ 'team_member_twitter' ];
            update_post_meta( $post_id, 'team_member_twitter', $twitter_url );
        }

        if ( isset( $_REQUEST[ 'team_member_linkedin' ] ) ) {
            $linkedin_url = $_POST[ 'team_member_linkedin' ];
            update_post_meta( $post_id, 'team_member_linkedin', $linkedin_url );
        }

        if ( isset( $_REQUEST[ 'team_member_gplus' ] ) ) {
            $gplus_url = $_POST[ 'team_member_gplus' ];
            update_post_meta( $post_id, 'team_member_gplus', $gplus_url );
        }
    }

    public function wpb_load_widget() {
        register_widget( 'smartcat_team_widget' );
    }

    public function smartcat_team_update_order() {
        $post_id = $_POST[ 'id' ];
        $sc_member_order = $_POST[ 'sc_member_order' ];
        update_post_meta( $post_id, 'sc_member_order', $sc_member_order );
    }

    public function sc_custom_styles() {
        ?>
        <style>
            #sc_our_team a,
            .sc_our_team_lightbox .name{ color: #<?php echo $this->options['text_color']; ?>; }
            .grid#sc_our_team .sc_team_member .sc_team_member_name,
            .grid#sc_our_team .sc_team_member .sc_team_member_jobtitle,
            .grid_circles#sc_our_team .sc_team_member .sc_team_member_jobtitle,
            .grid_circles#sc_our_team .sc_team_member .sc_team_member_name,
            #sc_our_team_lightbox .progress{ background: #<?php echo $this->options[ 'text_color' ]; ?>;}
            .stacked#sc_our_team .smartcat_team_member{ border-color: #<?php echo $this->options[ 'text_color' ]; ?>;}
            .grid#sc_our_team .sc_team_member_inner{ height: <?php echo $this->options[ 'height' ]; ?>px; }
            .grid#sc_our_team .sc_team_member{ padding: <?php echo $this->options['margin']; ?>px;}

        </style>
        <?php
    }

    public function smartcat_set_single_content( $content ) {
        global $post;

        if( is_single() ) :
            if ( $post->post_type == 'team_member' && 
                    $this->options['single_template'] == 'standard' && 
                    $this->options['single_social']  == 'yes' 
            ) :
                $facebook = get_post_meta( get_the_ID(), 'team_member_facebook', true );
                $twitter = get_post_meta( get_the_ID(), 'team_member_twitter', true );
                $linkedin = get_post_meta( get_the_ID(), 'team_member_linkedin', true );
                $gplus = get_post_meta( get_the_ID(), 'team_member_gplus', true );
                $email = get_post_meta( get_the_ID(), 'team_member_email', true );

                $content .= '<div class="clear"></div>'
                        . '<div class="smartcat_team_single_icons">';
                $content .= $this->smartcat_get_social_content( $facebook, $twitter, $linkedin, $gplus, $email );
                $content .= '</div>';
            endif;
        else :
            
        endif;
        
        return $content;
    }

    public function get_social( $facebook, $twitter, $linkedin, $gplus, $email ) {
        if ( $facebook != '' )
            echo '<a href="' . $facebook . '"><img src="' . SC_TEAM_URL . 'inc/img/fb.png" class="sc-social"/></a>';
        if ( $twitter != '' )
            echo '<a href="' . $twitter . '"><img src="' . SC_TEAM_URL . 'inc/img/twitter.png" class="sc-social"/></a>';
        if ( $linkedin != '' )
            echo '<a href="' . $linkedin . '"><img src="' . SC_TEAM_URL . 'inc/img/linkedin.png" class="sc-social"/></a>';
        if ( $gplus != '' )
            echo '<a href="' . $gplus . '"><img src="' . SC_TEAM_URL . 'inc/img/google.png" class="sc-social"/></a>';
        if ( $email != '' )
            echo '<a href=mailto:' . $email . '><img src="' . SC_TEAM_URL . 'inc/img/email.png" class="sc-social"/></a>';
    }
    
    public function smartcat_get_social_content( $facebook, $twitter, $linkedin, $gplus, $email ){
        
        $content = null;
        
        if ( 'yes' == $this->options[ 'social' ] ) {
            if ( $facebook != '' )
                $content .= '<a href="' . $facebook . '"><img src="' . SC_TEAM_URL . 'inc/img/fb.png" class="sc-social"/></a>';
            if ( $twitter != '' )
                $content .= '<a href="' . $twitter . '"><img src="' . SC_TEAM_URL . 'inc/img/twitter.png" class="sc-social"/></a>';
            if ( $linkedin != '' )
                $content .= '<a href="' . $linkedin . '"><img src="' . SC_TEAM_URL . 'inc/img/linkedin.png" class="sc-social"/></a>';
            if ( $gplus != '' )
                $content .= '<a href="' . $gplus . '"><img src="' . SC_TEAM_URL . 'inc/img/google.png" class="sc-social"/></a>';
            if ( $email != '' )
                $content .= '<a href=mailto:' . $email . '><img src="' . SC_TEAM_URL . 'inc/img/email.png" class="sc-social"/></a>';
        }        
        return $content;
    }

    public function get_single_social( $social ) {
        if ( 'yes' == $this->options[ 'social' ] ) :
            if ( $social != '' )
                echo '<li><a href="' . $social . '"><img src="' . SC_TEAM_URL . 'inc/img/fb.png" class="sc-social"/></a></li>';

        endif;
    }

    public function sc_get_args( $group ) {
        $args = array(
            'post_type' => 'team_member',
            'meta_key' => 'sc_member_order',
            'orderby' => 'meta_value_num',
            'order' => 'ASC',
            'team_member_position' => $group,
            'posts_per_page' => $this->options[ 'member_count' ],
        );
        return $args;
    }

    public function smartcat_team_get_single_template( $single_template ) {
            
        global $post;


        return $single_template;
    }

}

class smartcat_team_widget extends WP_Widget {

    function __construct() {
        parent::__construct(
                'smartcat_team_widget', __( 'Our Team Sidebar Widget', 'smartcat_team_widget_domain' ), array( 'description' => __( 'Use this widget to display the Our Team anywhere on the site.', 'smartcat_team_widget_domain' ), )
        );
    }

    // Creating widget front-end
    // This is where the action happens
    public function widget( $args, $instance ) {
        $title = apply_filters( 'widget_title', $instance[ 'title' ] );

        // before and after widget arguments are defined by themes
        echo $args[ 'before_widget' ];
        if ( !empty( $title ) )
            echo $args[ 'before_title' ] . $title . $args[ 'after_title' ];

        // This is where you run the code and display the output
        include SC_TEAM_PATH . 'inc/template/widget.php';

    }

    // Widget Backend
    public function form( $instance ) {
        if ( isset( $instance[ 'title' ] ) ) {
            $title = $instance[ 'title' ];
        } else {
            $title = __( 'Meet Our Team', 'smartcat_team_widget_domain' );
        }
        // Widget admin form
        ?>
        <p>
            <label for="////<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
            <input class="widefat" id="////<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        <?php
    }

    // Updating widget replacing old instances with new
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance[ 'title' ] = (!empty( $new_instance[ 'title' ] ) ) ? strip_tags( $new_instance[ 'title' ] ) : '';
        return $instance;
    }

}