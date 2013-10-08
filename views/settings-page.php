<?php
if(!class_exists("ZurmoAPI"))
    require_once("../src/Zurmo/ZurmoAPI.php");
    
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

        $settings = array(
            "url" => stripslashes($_POST["gf_zurmo_url"]), 
            "username" => stripslashes($_POST["gf_zurmo_username"]),
            "password" => stripslashes($_POST["gf_zurmo_password"]),
        );

        update_option("gf_zurmo_settings", $settings);

    }
    else
    {

        $settings = get_option("gf_zurmo_settings");

    }

    $zurmo = new ZurmoAPI;
    $api = self::get_api($zurmo);

    if($api)
    {
		if ( $api['token'] ) 
		{
			$class = "updated";
            $message = "<p>Congratulations, Zurmo has been connected to Gravity Forms.</p>";
			$validimage = '<img src="'.GFCommon::get_base_url().'/images/tick.png"/>';
			$valid = true;
		} 
		else 
		{
			$class = "error";
            $message = "<p>Invalid stuff.</p>";
			$valid = false;
			$validimage = '<img src="'.GFCommon::get_base_url().'/images/cross.png"/>';
		}

	}
    else if(empty($api))
    {

        $message = "<p>Invalid Zurmo URL and/or Username & Password. Please try another combination.</p>";
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

		<h2><?php _e('Zurmo Add-on Settings'); ?></h2>
        <?php if($message): ?>
			<div class="fade below-h2 <?php echo $class; ?>"><?php echo wpautop($message); ?></div>
		<?php endif; ?>
        <form method="post" action="">
            <?php wp_nonce_field("update", "gf_zurmo_update") ?>
            <h3><?php _e("Zurmo Account Information", "gravityformszurmo") ?></h3>

    		<table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="gf_zurmo_url"><?php _e("Zurmo URL", "gravityformszurmo"); ?></label> 
                    </th>
                    <td>
                        <input type="text" size="75" id="gf_zurmo_url" name="gf_zurmo_url" value="<?php echo esc_attr($settings["url"]) ?>"/> 
                        <?php echo $validimage; ?> <br/> Your Zurmo URL, e.g. http://crm.yoururl.com
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="gf_zurmo_username"><?php _e("Zurmo Username", "gravityformszurmo"); ?></label> 
                    </th>
                    <td><input type="text" size="75" id="gf_zurmo_username" name="gf_zurmo_username" value="<?php echo esc_attr($settings["username"]) ?>"/> 
                        <?php echo $validimage; ?> <br/> Your Zurmo Username
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="gf_zurmo_password"><?php _e("Zurmo Password", "gravityformszurmo"); ?></label> 
                    </th>
                    <td><input type="password" size="75" id="gf_zurmo_password" name="gf_zurmo_password" value="<?php echo esc_attr($settings["password"]) ?>"/> 
                        <?php echo $validimage; ?> <br/> Your Zurmo Password
                    </td>
                </tr>
                <tr>
                    <td colspan="2" >
                        <input type="submit" name="gf_zurmo_submit" class="button-primary" value="<?php _e("Save Settings", "gravityformszurmo") ?>" />
                    </td>
                </tr>

            </table>
        </form>

<?php if($valid) { ?>
    
    <h2>Extra Settings Here</h2>
    <p>Whatever</p>

<?php } // end if($api) ?>
    </div>
