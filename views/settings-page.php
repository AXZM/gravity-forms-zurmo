<?php
    
    $message = $validimage = false;        

    if(!empty($_POST["uninstall"]))
    {
        check_admin_referer("uninstall", "gf_zurmo_uninstall");
        self::uninstall();
        ?>

        <div class="updated fade" style="padding:20px;">
        	<?php _e(sprintf("Gravity Forms Zurmo Add-On have been successfully uninstalled. It can be re-activated from the %splugins page%s.", "<a href='plugins.php'>","</a>"), "gravityformszurmo")?>
        </div>

        <?php
        return;
    }
    else if(!empty($_POST["gf_zurmo_submit"]))
    {
        check_admin_referer("update", "gf_zurmo_update");
        $settings = array("url" => stripslashes($_POST["gf_zurmo_url"]), "token" => stripslashes($_POST["gf_zurmo_token"]));
        update_option("gf_zurmo_settings", $settings);
    }
    else
    {
        $settings = get_option("gf_zurmo_settings");
    }

    //$api = self::get_api();

    if($api)
    {
        $message = $api->testAccount($settings);
		if ( $message == 'Valid Zurmo URL and API Token.' ) 
		{
			$class = "updated";
			$validimage = '<img src="'.GFCommon::get_base_url().'/images/tick.png"/>';
			$valid = true;
		} 
		else 
		{
			$class = "error";
			$valid = false;
			$validimage = '<img src="'.GFCommon::get_base_url().'/images/cross.png"/>';
		}
	}
    else if(!empty($settings["url"]) || !empty($settings["token"]))
    {
        $message = "<p>Invalid Zurmo URL and/or API Token. Please try another combination.</p>";
        $class = "error";
        $valid = false;
        $validimage = '<img src="'.GFCommon::get_base_url().'/images/cross.png"/>';
    }

    ?>
    <style>
        .ul-square li { list-style: square!important; }
        .ol-decimal li { list-style: decimal!important; }
    </style>
	<div class="wrap">
		<h2><?php _e('Gravity Forms Zurmo Add-on Settings'); ?></h2>
	<?php if($message) {
			echo "<div class='fade below-h2 {$class}'>".wpautop($message)."</div>";
		} ?>

    <form method="post" action="">
        <?php wp_nonce_field("update", "gf_zurmo_update") ?>
        <h3><?php _e("Zurmo Account Information", "gravityformszurmo") ?></h3>

		<table class="form-table">
            <tr>
                <th scope="row"><label for="gf_zurmo_url"><?php _e("Zurmo URL", "gravityformszurmo"); ?></label> </th>
                <td><input type="text" size="75" id="gf_zurmo_url" name="gf_zurmo_url" value="<?php echo esc_attr($settings["url"]) ?>"/> <?php echo $validimage; ?> <br/> Your Zurmo URL, e.g. http://crm.yoururl.com</td>
            </tr>
            <tr>
                <th scope="row"><label for="gf_zurmo_username"><?php _e("Zurmo Username", "gravityformszurmo"); ?></label> </th>
                <td><input type="text" size="75" id="gf_zurmo_username" name="gf_zurmo_username" value="<?php echo esc_attr($settings["username"]) ?>"/> <?php echo $validimage; ?> <br/> Your Zurmo Username</td>
            </tr>
            <tr>
                <th scope="row"><label for="gf_zurmo_password"><?php _e("Zurmo Password", "gravityformszurmo"); ?></label> </th>
                <td><input type="password" size="75" id="gf_zurmo_password" name="gf_zurmo_password" value="<?php echo esc_attr($settings["password"]) ?>"/> <?php echo $validimage; ?> <br/> Your Zurmo URL, e.g. http://crm.yoururl.com</td>
            </tr>
            <tr>
                <td colspan="2" ><input type="submit" name="gf_zurmo_submit" class="button-primary" value="<?php _e("Save Settings", "gravityformszurmo") ?>" /></td>
            </tr>

        </table>
        <div>

        </div>
    </form>

<?php if($valid) { ?>
	<div class="hr-divider"></div>

	<h3>Usage Instructions</h3>

	<div class="delete-alert alert_gray">
		<div class="wp-caption" style="float:right; width:235px; margin:20px;"><img src="<?php echo self::get_base_password(); ?>/settings.jpg" /><small>How the form appears on the Form Settings page</small></div>
		<h4>To integrate a form with Highrise:</h4>
		<ol class="ol-decimal">
			<li>Edit the form you would like to integrate (choose from the <a href="<?php _e(admin_url('admin.php?page=gf_edit_forms')); ?>">Edit Forms page</a>).</li>
			<li>Click "Form Settings"</li>
			<li><strong>Check the box "Enable Highrise integration"</strong></li>
			<li>Save the form</li>
		</ol>
		<p>Note: <strong>Form entries must have First &amp; Last Names</strong> for data to be saved to Highrise.</p>
	</div>


    <h4>Form Fields</h4>
    <p>Fields will be automatically mapped by Highrise using the default Gravity Forms labels. If you change the labels of your fields, make sure to use the following keywords in the label to match and send data to Highrise.</p>
    <p>Text in <span class="description">gray italics</span> is the Parameter name for the field. Use this text to set the "Parameter Name" for each field (in the field settings under Advanced >  Allow field to be populated dynamically > Parameter Name)</p>

    <ul class="ul-square">
    	<li><code>name</code> (use to auto-split names into First Name and Last Name fields) <span class="description">BothNames</span></li>
        <li><code>first name</code> <span class="description">sFirstName</span></li>
        <li><code>last name</code>  <span class="description">sLastName</span></li>
        <li><code>company</code> <span class="description">sCompany</span></li>
        <li><code>email</code> <span class="description">sEmail</span></li>
        <li><code>phone</code> <span class="description">sPhone</span></li>
        <li><code>mobile</code> <span class="description">sMobile</span></li>
        <li><code>fax</code> <span class="description">sFax</span></li>
        <li><code>address</code> <span class="description">sAddress</span></li>
        <li><code>city</code> <span class="description">sCity</span></li>
        <li><code>country</code> <span class="description">sCountry</span></li>
        <li><code>zip</code> <span class="description">sZip</span></li>
        <li><code>website</code> <span class="description">sZip</span></li>
        <li><code>twitter</code> <span class="description">sTwitter</span></li>
        <li><code>zip</code> <span class="description">sZip</span></li>
        <li><code>subject</code> <span class="description">sTitle</span></li>
        <li><code>tags</code> (comma-separated) <span class="description">sFirstName</span></li>
        <li><code>question</code>, <code>message</code>, or <code>comments</code> for Notes  <span class="description">sNotes</span></li>
        <li><code>background</code>, <code>staff_comment</code> <span class="description">sBackground</span></li>
        <li>Anything not recognized by the list will be added to Staff Comments</li>
    </ul>

	<h4>Adding Tags</h4>
	<p>To add tags for a specific form create a hidden field and label the field <code>tags</code>. Then, under "Advanced", add the list of tags you would like to add. Seperate multiple tags with commas.</p>

    <form action="" method="post">
        <?php wp_nonce_field("uninstall", "gf_zurmo_uninstall") ?>
        <?php if(GFCommon::current_user_can_any("gravityforms_zurmo_uninstall")){ ?>
            <div class="hr-divider"></div>

            <h3><?php _e("Uninstall Highrise Add-On", "gravityformszurmo") ?></h3>
            <div class="delete-alert alert_red">
            	<h3><?php _e('Warning', 'gravityformszurmo'); ?></h3>
            	<p><?php _e("This operation deletes ALL Highrise Feeds. ", "gravityformszurmo") ?></p>
                <?php
                $uninstall_button = '<input type="submit" name="uninstall" value="' . __("Uninstall Highrise Add-On", "gravityformszurmo") . '" class="button" onclick="return confirm(\'' . __("Warning! ALL Highrise Feeds will be deleted. This cannot be undone. \'OK\' to delete, \'Cancel\' to stop", "gravityformszurmo") . '\');"/>';
                echo apply_filters("gform_zurmo_uninstall_button", $uninstall_button);
                ?>
            </div>
        <?php } ?>
    </form>
    <?php } // end if($api) ?>
    </div>