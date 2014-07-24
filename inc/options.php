<?php include_once 'setting.php'; ?>
<div class="width70 left">
    <p>To display the Team, copy and paste this shortcode into the page or widget where you want it to show <b class="larger bold editcursor">[our-team]</b></p>
    <p><iframe src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2FSmartcatDesign&amp;width&amp;layout=standard&amp;action=like&amp;show_faces=false&amp;share=false&amp;height=35&amp;appId=233286813420319" scrolling="no" frameborder="0" style="border:none; overflow:hidden; height:35px;" allowTransparency="true"></iframe></p>
    <form name="sc_our_team_post_form" method="post" action="" enctype="multipart/form-data">
        <table class="widefat">
            <thead>
                <tr>
                    <th colspan="2"><b>General Settings</b></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Template</td>
                    <td>
                        <select name="sc_our_team_template">
                            <option value="grid" <?php echo 'grid' == get_option('sc_our_team_template') ? 'selected=selected' : ''; ?>>Grid - Boxes</option>
                            <option value="grid_circles" <?php echo 'grid_circles' == get_option('sc_our_team_template') ? 'selected=selected' : ''; ?>>Grid - Circles</option>
                            <option disabled="disabled">List - Stacked (pro version)</option>
                            <option disabled="disabled">Honey Comb (pro version)</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Display Social Icons</td>
                    <td>
                        <select name="sc_our_team_social">
                            <option value="yes" <?php echo 'yes' == get_option('sc_our_team_social') ? 'selected=selected' : ''; ?>>Yes</option>
                            <option value="no" <?php echo 'no' == get_option('sc_our_team_social') ? 'selected=selected' : ''; ?>>No</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Max Number of members to display</td>
                    <td>
                        <input type="text" value="<?php echo get_option('sc_our_team_member_count'); ?>" name="sc_our_team_member_count" placeholder="number of members to show"/><br>
                        <em>Set to -1 to display all members</em>
                    </td>
                </tr>
                <tr>
                    <td>Text Color</td>
                    <td>
                        <em class="red">Pro version</em>
                    </td>
                </tr>
                <tr>
                    <td>Honeycomb Color</td>
                    <td>
                        <em class="red">Pro version</em>
                    </td>
                </tr>
                <tr>
                    <td>Member Groups</td>
                    <td>
                        <em class="red">Pro version</em>
                    </td>
                </tr>
                <tr>
<!--                        <td>Link to Bio page</td>
                    <td>
                        <select name="sc_our_team_social">
                            <option value="yes" <?php echo 'yes' == get_option('sc_our_team_profile_link') ? 'selected=selected' : ''; ?>>Yes</option>
                            <option value="no" <?php echo 'no' == get_option('sc_our_team_profile_link') ? 'selected=selected' : ''; ?>>No</option>
                        </select>
                    </td>                        -->
                </tr>
            </tbody>
        </table>
        <table class="widefat">
            <thead>
                <tr>
                    <th colspan="4">Demo</th>
                </tr>
                <tr>
                    <td>
                        Grid Boxes & Grid Circles Demo<br>
                        <img src="<?php echo SC_TEAM_PATH; ?>/screenshot-1.png" width="100%"/>
                    </td>
                    <td>
                        Honeycomb Demo<br>
                        <img src="<?php echo SC_TEAM_PATH; ?>/screenshot-4.jpg" width="100%"/>
                    </td>
                    <td>
                        Stacked List Demo<br>
                        <img src="<?php echo SC_TEAM_PATH; ?>/screenshot-5.jpg" width="100%"/>
                    </td>
                    <td></td>
                </tr>
            </thead>
        </table>
        <input type="submit" name="sc_our_team_save" value="Update" class="button button-primary" />         
    </form>
</div>    
</div>
<script>
    function confirm_reset() {
        if (confirm("Are you sure you want to reset to defaults ?")) {
            return true;
        } else {
            return false;
        }
    }
    jQuery(document).ready(function($) {
        $("#sc_popup_shortcode").focusout(function() {
            var shortcode = jQuery(this).val();
            shortcode = shortcode.replace(/"/g, "").replace(/'/g, "");
            jQuery(this).val(shortcode);
        });

    });

</script>