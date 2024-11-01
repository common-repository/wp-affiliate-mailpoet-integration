<?php

function wp_aff_mailpoet_settings_menu() {
    echo '<div class="wrap">';
    echo '<div id="poststuff"><div id="post-body">';
    echo '<h2>Affiliate MailPoet Addon</h2>';

    if (isset($_POST['aff_mailpoet_save_data'])) {
        $list_id = $_REQUEST['aff_mailpoet_list_id'];
        update_option('wp_aff_mailpoet_list_id', $list_id);

        echo '<div id="message" class="updated fade"><p>';
        echo 'Successfully Updated!';
        echo '</p></div>';
    }
    ?>
    <div class="postbox">
        <h3 class="hndle"><label for="title">Configure MailPoet List (<a href="http://www.tipsandtricks-hq.com/wordpress-affiliate/mailpoet-newsletter-affiliate-plugin-integration-950" target="_blank">Read Instructions</a>)</label></h3>
        <div class="inside">

            <form method="post" action="">
                <table class="form-table" border="0" cellspacing="0" cellpadding="6">

                    <tr valign="top"><td width="25%" align="left">
                            MailPoet List ID
                        </td><td align="left">
                            <input name="aff_mailpoet_list_id" type="text" size="20" value="<?php echo get_option('wp_aff_mailpoet_list_id'); ?>"/>                   
                            <p class="description">Your affiliates will be subscribed to this MailPoet list when they signup for an account.</p>
                        </td>
                    </tr>

                </table>

                <div class="submit">
                    <input type="submit" name="aff_mailpoet_save_data" class="button-primary" value="Save" />
                </div>     

            </form>

        </div>
    </div>

    </div></div><!-- End of poststuff and body -->
    </div><!-- End of wrap -->
    <?php
}
