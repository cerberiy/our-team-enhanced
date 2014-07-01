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
//                            echo wp_get_attachment_url( get_post_thumbnail_id(get_the_ID() ));
            ?>
            <div itemscope itemtype="http://schema.org/Person" class="sc_team_member">
                <?php
                if (has_post_thumbnail())
                    echo the_post_thumbnail('medium');
                else
                    echo '<img src="' . SC_TEAM_PATH . 'img/noprofile.jpg" class="attachment-medium wp-post-image"/>';
                ?>
                <div class="sc_team_member_overlay">
                    <div itemprop="name" class="sc_team_member_name">
                        <a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>">                            
                            <?php the_title() ?>
                        </a>
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

                            get_social($facebook, $twitter, $linkedin, $gplus, $email);
                        ?>
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
