<?php
/**
 * Created by Bilal Hassan.
 * Date: 2014-06-26
 * Time: 11:04 AM
 */

$args = array(
    'post_type' => 'team_member',
    'meta_key' => 'sc_member_order',
    'orderby' => 'meta_value',
    'order' => 'ASC'
);
$members = new WP_Query($args);
?>
<div id="sc_our_team" class="<?php echo get_option('sc_our_team_template'); ?>">
    <?php
    if ($members->have_posts()) {
        while ($members->have_posts()) {
            $members->the_post();
            if (has_post_thumbnail())
                $thumb_url = wp_get_attachment_url(get_post_thumbnail_id(get_the_ID()));

            else {
//                                echo '<img src="' . SC_TEAM_PATH .'img/noprofile.jpg" class="attachment-medium wp-post-image"/>';
                $thumb_url = SC_TEAM_PATH . 'img/noprofile.jpg';
            }
//                            echo wp_get_attachment_url( get_post_thumbnail_id(get_the_ID() ));
            ?>
            <div itemscope itemtype="http://schema.org/Person" class="sc_team_member">
                <div class="sc_team_member_inner" style="background-image: url(<?php echo $thumb_url; ?>);">
                    <div class="sc_team_member_overlay">
                        <div itemprop="name" class="sc_team_member_name">
                            <?php the_title() ?>
                        </div>
                        <div itemprop="jobtitle" class="sc_team_member_jobtitle">
                            <?php echo get_post_meta(get_the_ID(), 'team_member_title', true); ?>
                        </div>
                        <div class='icons'>

                            <?php // the_content(); ?>
                            <?php
                            $facebook = get_post_meta(get_the_ID(), 'team_member_facebook', true);
                            $twitter = get_post_meta(get_the_ID(), 'team_member_twitter', true);
                            $linkedin = get_post_meta(get_the_ID(), 'team_member_linkedin', true);
                            $gplus = get_post_meta(get_the_ID(), 'team_member_gplus', true);
                            $email = get_post_meta(get_the_ID(), 'team_member_email', true);

                            if ($facebook != '')
                                echo '<a href="' . $facebook . '"><img src="' . SC_TEAM_PATH . 'img/fb.png"/></a>';
                            if ($twitter != '')
                                echo '<a href="' . $twitter . '"><img src="' . SC_TEAM_PATH . 'img/twitter.png"/></a>';
                            if ($linkedin != '')
                                echo '<a href="' . $linkedin . '"><img src="' . SC_TEAM_PATH . 'img/linkedin.png"/></a>';
                            if ($gplus != '')
                                echo '<a href="' . $gplus . '"><img src="' . SC_TEAM_PATH . 'img/google.png"/></a>';
                            if ($email != '')
                                echo '<a href=mailto:"' . $email . '"><img src="' . SC_TEAM_PATH . 'img/email.png"/></a>';
                            ?>

                        </div>
                    </div>

                </div>

            </div>
        <?php
        }
    } else {
        echo 'There are no team members to display';
    }
    ?>
</div>
