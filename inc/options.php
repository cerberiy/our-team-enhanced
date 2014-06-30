<?php include_once 'setting.php'; ?>
    <div class="width70 left">
        <p><em>This plugin is currently going through some serious development! This is just a sneak peak!
            Over the next couple of weeks I will be adding many new additions such as more templates, color customizations,
            and many more features that I am sure you will love !</em></p>
        <form name="post_form" method="post" action="" enctype="multipart/form-data">
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
                            <select>
                                <option>Grid Default</option>
                                <option disabled="disabled">Grid Circles - <em>Coming Soon</em></option>
                            <option disabled="disabled">Stacked - <em>Coming Soon</em></option>
                            </select>
                        </td>
                    </tr>
                </tbody>
            </table>
            
            
            <!--<input type="submit" name="wp_popup_reset" value="Reset" class="button button-primary" onclick="return confirm_reset();"/>-->         
            <input type="submit" name="wp_popup_save" value="Update" class="button button-primary" />         
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