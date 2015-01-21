/**
 * 
 * @author Smartcat Jan 21, 2015
 */
jQuery( document ).ready(function($){

    var member_height = $('#sc_our_team.grid_circles .sc_team_member').width();
    $('#sc_our_team.grid_circles .sc_team_member').each(function(){
        $(this).css({
            height: member_height
        });
    });    

    
    $('#sc_our_team .sc_team_member').hover(function(){
        $('.sc_team_member_overlay',this).stop(true,false).fadeIn(440);
        $('.wp-post-image',this).addClass('zoomIn');
        $('.sc_team_more',this).addClass('show');
        
    },function(){
       $('.sc_team_member_overlay',this).stop(true,false).fadeOut(440)       
       $('.wp-post-image',this).removeClass('zoomIn');
       $('.sc_team_more',this).removeClass('show');
       
    });

});
