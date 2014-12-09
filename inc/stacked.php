<?php
/*
 * Short description
 * @author bilal hassan <info@smartcatdesign.net>
 * 
 */
$args = array(
    'post_type' => 'team_member',
);
$members = new WP_Query($args);
if ($members->have_posts()) {
    while ($members->have_posts()) {
        $members->the_post();
        ?>
        <div class="sc_our_team <?php echo esc_html( get_option('sc_our_team_template') ); ?>">
            <h1><?php the_title() ?></h1>
            <div class='content'>
                <?php the_post_thumbnail(); ?>
                <?php the_content(); ?>
                <?php the_time('F jS, Y'); ?>
                <?php echo get_post_meta(get_the_ID(), 'team_member_facebook', true); ?>
                <?php echo get_post_meta(get_the_ID(), 'team_member_twitter', true); ?>
                <?php echo get_post_meta(get_the_ID(), 'team_member_linkedin', true); ?>
                <?php echo get_post_meta(get_the_ID(), 'team_member_gplus', true); ?>
            </div>
        </div>
        <?php
    }
} else {
    echo 'There are no team members to display';
}
?>
