<?php
/*
 * Short description
 * @author bilal hassan <info@smartcatdesign.net>
 * 
 */
$args = $this->sc_get_args( $group );
$members = new WP_Query( $args );
?>
<div id="sc_our_team" class="<?php echo $template == '' ? $this->options[ 'template' ] : $template; echo ' sc-col' . $this->options['columns']; ?>">
    <div class="clear"></div>
    <?php
    if ( $members->have_posts() ) {
        while ( $members->have_posts() ) {
            $members->the_post();
            ?>
            <div itemscope itemtype="http://schema.org/Person" class="sc_team_member">
                <div class="sc_team_member_inner">
                    
                    <?php
                    if ( has_post_thumbnail() )
                        echo the_post_thumbnail( 'medium' );
                    else {
                        echo '<img src="' . SC_TEAM_URL . 'inc/img/noprofile.jpg" class="attachment-medium wp-post-image"/>';
                    }
                    ?>
                    
                        <?php if( 'yes' == $this->options['name'] ) : ?>
                        <div itemprop="name" class="sc_team_member_name">
                            <a href="<?php the_permalink() ?>" rel="bookmark" >                            
                                <?php the_title() ?>
                            </a>
                        </div>
                        <?php endif; ?>
                        
                        <?php if( 'yes' == $this->options['title'] ) : ?>
                        <div itemprop="jobtitle" class="sc_team_member_jobtitle">
                            <?php echo get_post_meta( get_the_ID(), 'team_member_title', true ); ?>
                        </div>
                        <?php endif; ?>
                        
                    
                        <div class="sc_team_content">
                            <?php the_content();   ?>
                        </div>
                    
                        <div class='icons <?php echo 'yes' == $this->options[ 'social' ] ? '' : 'hidden'; ?>'>

                            <?php
                            $facebook = get_post_meta( get_the_ID(), 'team_member_facebook', true );
                            $twitter = get_post_meta( get_the_ID(), 'team_member_twitter', true );
                            $linkedin = get_post_meta( get_the_ID(), 'team_member_linkedin', true );
                            $gplus = get_post_meta( get_the_ID(), 'team_member_gplus', true );
                            $email = get_post_meta( get_the_ID(), 'team_member_email', true );

                            $this->get_social( $facebook, $twitter, $linkedin, $gplus, $email );
                            ?>

                        </div>
                    
                        <div class="sc_team_skills">
                            
                            <?php if( get_post_meta( get_the_ID(), 'team_member_skill1', true) ) : ?>
                                <?php echo get_post_meta( get_the_ID(), 'team_member_skill1', true); ?>
                            <?php endif; ?>
                            
                            <?php if( get_post_meta( get_the_ID(), 'team_member_skill_value1', true) ) : ?>
                                <div class="progress" style="width: <?php echo get_post_meta( get_the_ID(), 'team_member_skill_value1', true); ?>0%"></div>
                            <?php endif; ?>
                            
                            
                            <?php if( get_post_meta( get_the_ID(), 'team_member_skill2', true) ) : ?>
                                <?php echo get_post_meta( get_the_ID(), 'team_member_skill2', true); ?>
                            <?php endif; ?>
                            
                            <?php if( get_post_meta( get_the_ID(), 'team_member_skill_value2', true) ) : ?>
                                <div class="progress" style="width: <?php echo get_post_meta( get_the_ID(), 'team_member_skill_value2', true); ?>0%"></div>
                            <?php endif; ?>
                            
                            
                            <?php if( get_post_meta( get_the_ID(), 'team_member_skill3', true) ) : ?>
                                <?php echo get_post_meta( get_the_ID(), 'team_member_skill3', true); ?>
                            <?php endif; ?>
                            
                            <?php if( get_post_meta( get_the_ID(), 'team_member_skill_value4', true) ) : ?>
                                <div class="progress" style="width: <?php echo get_post_meta( get_the_ID(), 'team_member_skill_value4', true); ?>0%"></div>
                            <?php endif; ?>
                            
                            
                            <?php if( get_post_meta( get_the_ID(), 'team_member_skill1', true) ) : ?>
                                <?php echo get_post_meta( get_the_ID(), 'team_member_skill1', true); ?>
                            <?php endif; ?>
                            
                            <?php if( get_post_meta( get_the_ID(), 'team_member_skill_value1', true) ) : ?>
                                <div class="progress" style="width: <?php echo get_post_meta( get_the_ID(), 'team_member_skill_value1', true); ?>0%"></div>
                            <?php endif; ?>
                            
                            
                        </div>
                    
                    
                    <div class="sc_team_member_overlay"></div>
                    <div class="sc_team_more">
                        <a href="<?php the_permalink() ?>" rel="bookmark" class=""> 
                            <img src="<?php echo SC_TEAM_URL . 'inc/img/more.png'?>"/>
                        </a>
                    </div>
                </div>

            </div>
            <?php
        }
    } else {
        echo 'There are no team members to display';
    }
    ?>
    <div class="clear"></div>
</div>
