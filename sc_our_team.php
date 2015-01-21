<?php
/*
  Plugin Name: Our Team Showcase
  Plugin URI: http://smartcatdesign.net/downloads/our-team-showcase/
  Description: Display your team members in a very attractive way as a widget or page with a shortcode
  Version: 2.0
  Author: SmartCat
  Author URI: http://smartcatdesign.net
  License: GPL v2
 * 
 * @author          Bilal Hassan <bilal@smartcat.ca>
 * @copyright       Smartcat Design <http://smartcatdesign.net>
 */


// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) {
    die;
}
if (!defined('SC_TEAM_PATH'))
    define('SC_TEAM_PATH', plugin_dir_path(__FILE__));
if (!defined('SC_TEAM_URL'))
    define('SC_TEAM_URL', plugin_dir_url(__FILE__));


require_once ( plugin_dir_path( __FILE__ ) . 'inc/class/class.smartcat-team.php' );


// activation and de-activation hooks
register_activation_hook( __FILE__, array( 'SmartcatTeamPlugin', 'activate' ) );
register_deactivation_hook( __FILE__, 'SmartcatTeamPlugin', 'deactivate' );

SmartcatTeamPlugin::instance();



