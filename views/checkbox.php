<style>
    #gform_title .zurmo,
    #gform_enable_zurmo_label {
        float:right;
        background: url('<?php echo WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__)); ?>/zurmo-icon.gif') right top no-repeat;
        height: 16px;
        width: 16px;
        cursor: help;
    }
    #gform_enable_zurmo_label {
        float: none;
        width: auto;
        background-position: left top;
        padding-left: 18px;
        cursor:default;
    }
</style>

<script type="text/javascript">
    jQuery(document).ready(function($) {
        var zurmoCheckbox = "<h4 class='gf_settings_subgroup_title'>Zurmo</h4><input type='checkbox' id='gform_enable_zurmo' /> <label for='gform_enable_zurmo' id='gform_enable_zurmo_label'><?php _e("Enable Zurmo Integration", "gravity-forms-zurmo"); echo ' '.$tooltip; ?></label>";

        if($('#gform_enable_zurmo').length === 0) {
            $('.gform_panel_form_settings #gform_custom_settings').append(zurmoCheckbox);
        }

        if($().prop) {
            $("#gform_enable_zurmo").prop("checked", form.enableZurmo ? true : false);
        } else {
            $("#gform_enable_zurmo").attr("checked", form.enableZurmo ? true : false);
        }

        $("#gform_enable_zurmo").live('click change ready', function() {

            var checked = $(this).is(":checked")

            form.enableZurmo = checked;

            if(checked) {
                $("#gform_title").append('<span class="zurmo" title="<?php _e("zurmo integration is enabled.", "gravity-forms-zurmo") ?>"></span>');
            } else {
                $("#gform_title .zurmo").remove();
            }
        }).trigger('ready');

        $('.tooltip_form_zurmo').tooltip({
            // Use the tooltip attribute of the element for the content
            content: $('.tooltip_form_zurmo').attr('title'),
            show: { delay: 200 },
            hide: { delay: 200, effect: 'fade' }
        });
    });
</script>