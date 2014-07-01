/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

jQuery(document).ready(function($){
   $('.grid#sc_our_team .sc_team_member').hover(function(){
       $('.sc_team_member_overlay',this).stop(true,false).animate({'height' : '100%'},350);
   },function(){
       $('.sc_team_member_overlay',this).stop(true,false).animate({'height' : '35px'},330);
   });
   
   $('.grid_circles#sc_our_team .sc_team_member').hover(function(){
       $('.sc_team_member_overlay',this).stop(true,false).fadeIn(350);
   },function(){
       $('.sc_team_member_overlay',this).stop(true,false).fadeOut(330);
   });
   
});
