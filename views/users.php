<style>
    #gform_title .zurmo,
    #gform_enable_user_label {
        float:right;
        background: url('<?php echo WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__)); ?>/zurmo-icon.gif') right top no-repeat;
        height: 16px;
        width: 16px;
        cursor: help;
    }
    #gform_enable_user_label {
        float: none;
        width: auto;
        background-position: left top;
        padding-left: 18px;
        cursor:default;
    }
</style>

<script type="text/javascript">
    jQuery(document).ready(function($) {
        var zurmoUsers = "<h4 class='gf_settings_subgroup_title'>Zurmo Lead Owner</h4><select id='gform_enable_user' name='form_enable_user'><?php 
        foreach($users as $user)
        {echo "<option value='".$user['id']."'>".$user['firstName']." ".$user['lastName']."</option>";}
        
        ?> </select> <label for='gform_enable_user' id='gform_enable_user_label'><?php _e("Leads captured from this form will be submitted to Zurmo with this user as Owner", "gravity-forms-zurmo"); echo ' '.$tooltip; ?></label>";

        if($('#gform_enable_user').length === 0) {
            $('.gform_panel_form_settings #gform_custom_settings').append(zurmoUsers);
        }
		
		 	console.log(form);
		 	if(form.enableUser)
		 	{
		 		$("#gform_enable_user").val(form.enableUser);   
		 	}
		 	else
		 	{
		 		form.enableUser = $("#gform_enable_user").val();
		 	}
                
      	$("#gform_enable_user").on('change', function(){
      		
      		form.enableUser = $("#gform_enable_user").val();
      		
      	});
      	
        $('.tooltip_form_users').tooltip({
            // Use the tooltip attribute of the element for the content
            content: $('.tooltip_form_users').attr('title'),
            show: { delay: 200 },
            hide: { delay: 200, effect: 'fade' }
        });
    });
</script>