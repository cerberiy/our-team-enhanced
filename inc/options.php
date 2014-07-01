<?php include_once 'setting.php'; ?>
    <div class="width70 left">
        <p><em>This plugin is currently going through some serious development! This is just a sneak peak!
            Over the next couple of weeks I will be adding many new additions such as more templates, color customizations,
            and many more features that I am sure you will love !</em></p>
            <p>To display the Team, copy and paste this shortcode into the page or widget where you want to show it. <b class="larger bold editcursor">[our-team]</b></p>
        <form name="sc_our_team_post_form" method="post" action="" enctype="multipart/form-data">
            <p></p>
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
                                <option value="grid" <?php echo 'grid' == get_option('sc_our_team_template') ? 'selected=selected' : ''; ?>>Grid Default</option>
                                <option value="grid_circles" <?php echo 'grid_circles' == get_option('sc_our_team_template') ? 'selected=selected' : ''; ?>>Grid Circles</option>
                                <!--<option value="list" <?php echo 'list' == get_option('sc_our_team_template') ? 'selected=selected' : ''; ?>>List</option>-->
                                <!--<option disabled="disabled">Stacked - <em>Coming Soon</em></option>-->
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