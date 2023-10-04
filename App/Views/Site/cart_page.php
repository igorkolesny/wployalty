<?php
/**
 * @author      Wployalty (Alagesan)
 * @license     http://www.gnu.org/licenses/gpl-3.0.html
 * @link        https://www.wployalty.net
 * */

defined('ABSPATH') || die;
$earn_campaign_helper = \Wlr\App\Helpers\EarnCampaign::getInstance();
$woocommerce_helper = new \Wlr\App\Helpers\Woocommerce();
$theme_color = isset($branding) && is_array($branding) && isset($branding["theme_color"]) && !empty($branding["theme_color"]) ? $branding["theme_color"] : "#4F47EB";
$border_color = isset($branding) && is_array($branding) && isset($branding["border_color"]) && !empty($branding["border_color"]) ? $branding["border_color"] : "#CFCFCF";
$heading_color = isset($branding) && is_array($branding) && isset($branding["heading_color"]) && !empty($branding["heading_color"]) ? $branding["heading_color"] : "#1D2327";
$background_color = isset($branding) && is_array($branding) && isset($branding["background_color"]) && !empty($branding["background_color"]) ? $branding["background_color"] : "#ffffff";
$button_text_color = isset($branding) && is_array($branding) && isset($branding["button_text_color"]) && !empty($branding["button_text_color"]) ? $branding["button_text_color"] : "#ffffff";
$is_right_to_left = is_rtl();
?>
<style>
    .wlr-myaccount-page {
    <?php echo !empty($background_color) ? esc_attr("background-color:".$background_color.";") : "";?>
    }

    .wlr-myaccount-page .wlr-heading {
    <?php echo !empty($heading_color) ? esc_attr("color:" . $heading_color . " !important;") : "";?><?php echo !empty($theme_color) ? esc_attr("border-left: 3px solid " . $theme_color . " !important;") : "";?>
    }

    .wlr-myaccount-page .wlr-theme-color-apply {
    <?php echo isset($theme_color) && !empty($theme_color) ?  esc_attr("color :".$theme_color.";") : "";?>;
    }

    .wlr-myaccount-page .wlr-earning-options .wlr-card .wlr-date {
    <?php echo $is_right_to_left ? "left: 12px;right:unset;": "right:12px;left:unset;";?>
    }

    .wlr-myaccount-page .wlr-your-reward .wlr-reward-type-name {
    <?php echo $is_right_to_left ? "float: left;border-radius: 8px 0 2px 0;": "float:right;";?>
    }

    .wlr-myaccount-page .wlr-progress-bar .wlr-progress-level {
        background-color: <?php echo esc_attr($theme_color);?>;
    }

    .wlr-myaccount-page .wlr-text-color,
    .alertify .wlr-text-color {
        color: <?php echo esc_attr($heading_color);?>
    }

    .wlr-myaccount-page .wlr-border-color {
        border-color: <?php echo esc_attr($border_color);?>;
    }

    .wlr-myaccount-page .wlr-button-text-color {
        color: <?php echo esc_attr($button_text_color);?>
    }

    .wlr-myaccount-page table:not( .has-background ) th {
        background-color: <?php echo esc_attr($theme_color."30");?>;
    }

    .wlr-myaccount-page table thead {
        outline: solid 1px<?php echo esc_attr($border_color);?>
    }

    .alertify .ajs-ok {
        color: <?php echo esc_attr($button_text_color);?>;
        background: <?php echo esc_attr($theme_color);?>;
    }

    .alertify .ajs-cancel {
        border: <?php echo esc_attr("1px solid ".$theme_color);?>;
        color: <?php echo esc_attr($theme_color);?>;
    }

</style>
<div class="wlr-myaccount-page">
    <?php do_action('wlr_before_customer_reward_cart_page_content'); ?>
    <?php if ((isset($user) && is_object($user) && isset($user->id) && $user->id > 0) || get_current_user_id()): ?>
        <div class="wlr-user-details">
            <div class="wlr-heading-container">
                <h3 class="wlr-heading"><?php echo esc_html(sprintf(__('My %s', 'wp-loyalty-rules'), $earn_campaign_helper->getPointLabel(3))); ?></h3>
            </div>
            <?php
            $is_user_available = (isset($user) && is_object($user) && isset($user->id) && $user->id > 0);
            $level_check = $is_user_available && isset($user->level_data) && is_object($user->level_data) && isset($user->level_data->current_level_name) && !empty($user->level_data->current_level_name); ?>
            <div class="wlr-points-container" style="<?php echo !$level_check ? 'margin-bottom: 0px' : ''; ?>">
                <div id="<?php echo esc_attr(WLR_PLUGIN_PREFIX . '-points') ?>">
                    <div id="<?php echo esc_attr(WLR_PLUGIN_PREFIX . '-available-points'); ?>" class="wlr-border-color">
                        <div>
                            <?php $img_icon = isset($branding) && is_array($branding) && isset($branding["available_point_icon"]) && !empty($branding["available_point_icon"]) ? $branding["available_point_icon"] : ""; ?>
                            <?php echo \Wlr\App\Helpers\Base::setImageIcon($img_icon, "available-points", array("alt" => __("Available point", "wp-loyalty-rules"), "height" => 64, "width" => 64)); ?>
                        </div>
                        <div>
                            <?php $user_points = (int)(isset($user) && !empty($user) && isset($user->points) && !empty($user->points) ? $user->points : 0); ?>
                            <span id="<?php echo esc_attr(WLR_PLUGIN_PREFIX . '-available-points-heading'); ?>"
                                  class="wlr-text-color">
        <?php echo esc_html(sprintf(__('Available %s', 'wp-loyalty-rules'), $earn_campaign_helper->getPointLabel($user_points))) ?></span>
                            <div id="<?php echo esc_attr(WLR_PLUGIN_PREFIX . '-available-point-value') ?>"
                                 class="wlr-text-color">
                                <?php echo $user_points; ?>
                            </div>
                        </div>
                    </div>
                    <div id="<?php echo esc_attr(WLR_PLUGIN_PREFIX . '-redeemed-points') ?>" class="wlr-border-color">
                        <div>
                            <?php $img_icon = isset($branding) && is_array($branding) && isset($branding["redeem_point_icon"]) && !empty($branding["redeem_point_icon"]) ? $branding["redeem_point_icon"] : ""; ?>
                            <?php echo \Wlr\App\Helpers\Base::setImageIcon($img_icon, "redeem-points", array("alt" => __("Redeem point", "wp-loyalty-rules"), "height" => 64, "width" => 64)); ?>
                        </div>
                        <div>
                            <?php $user_total_points = (int)(isset($user) && !empty($user) && isset($user->used_total_points) && !empty($user->used_total_points) ? $user->used_total_points : 0); ?>
                            <span id="<?php echo esc_attr(WLR_PLUGIN_PREFIX . '-redeemed-points-heading') ?>"
                                  class="wlr-text-color">
        <?php echo esc_html(sprintf(__('Redeemed %s', 'wp-loyalty-rules'), $earn_campaign_helper->getPointLabel($user_total_points))) ?></span>
                            <div id="<?php echo esc_attr(WLR_PLUGIN_PREFIX . '-redeemed-point-value') ?>"
                                 class="wlr-text-color">
                                <?php echo $user_total_points; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php if ($is_user_available && isset($user->level_id) && $user->level_id > 0 && $level_check): ?>
                    <div id="<?php echo esc_attr(WLR_PLUGIN_PREFIX . '-levels') ?>" class="wlr-border-color">
                        <div class="wlr-level-image">
                            <?php if (isset($user->level_data->current_level_image) && !empty($user->level_data->current_level_image)): ?>
                                <?php echo \Wlr\App\Helpers\Base::setImageIcon($user->level_data->current_level_image, "", array("alt" => __("Level image", "wp-loyalty-rules"), "height" => 48, "width" => 48)); ?>
                            <?php endif; ?>
                        </div>
                        <div class="wlr-level-data-section">
                            <div class="wlr-level-name-section">
                                <p id="<?php echo esc_attr(WLR_PLUGIN_PREFIX . '-level-name'); ?>"
                                   class="wlr-points-name wlr-text-color">
                                    <?php echo !empty($user->level_data) && !empty($user->level_data->current_level_name) ? esc_html(__($user->level_data->current_level_name, 'wp-loyalty-rules')) : '' ?>
                                </p>
                                <p id="<?php echo esc_attr(WLR_PLUGIN_PREFIX . '-next-level-name'); ?>"
                                   class="wlr-points-name wlr-text-color">
                                    <?php echo !empty($user->level_data) && !empty($user->level_data->next_level_name) ? esc_html(__($user->level_data->next_level_name, 'wp-loyalty-rules')) : '' ?>
                                </p>
                            </div>
                            <div class="wlr-level-content">
                                <?php
                                if (isset($user->level_data->current_level_start) && isset($user->level_data->next_level_start) && $user->level_data->next_level_start > 0):
                                    $css_width = (($user->earn_total_point - $user->level_data->current_level_start) / ($user->level_data->next_level_start - $user->level_data->current_level_start)) * 100;
                                    $needed_point = $user->level_data->next_level_start - $user->earn_total_point;
                                    ?>
                                    <div class="level-points">
                                        <div class="wlr-progress-bar">
                                            <div class="wlr-progress-level"
                                                 style="<?php echo esc_attr("width:" . $css_width . '%'); ?>">
                                            </div>
                                        </div>
                                        <p class="wlr-progress-content wlr-text-color">
                                            <?php echo esc_html(sprintf(__('%d %s more needed to unlock next level', 'wp-loyalty-rules'), (int)$needed_point, $earn_campaign_helper->getPointLabel($needed_point))); ?>
                                        </p>
                                    </div>
                                <?php else: ?>
                                    <div class="level-points">
                                        <p class="wlr-progress-content wlr-text-color">
                                            <?php echo esc_html__('Congratulations! You have reached the final level', 'wp-loyalty-rules'); ?>
                                        </p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
    <?php do_action('wlr_before_customer_reward_cart_page_user_rewards_content'); ?>
    <!--    customer rewards start here -->
    <?php
    if (isset($user_rewards) && !empty($user_rewards)): ?>
        <div class="wlr-your-reward">
            <div class="wlr-heading-container"><h3
                        class="wlr-heading"><?php echo esc_html(sprintf(__('My %s', 'wp-loyalty-rules'), $earn_campaign_helper->getRewardLabel(3))); ?></h3>
            </div>
            <div class="wlr-customer-reward">
                <?php $card_key = 1;
                foreach ($user_rewards as $u_reward): ?>
                    <?php
                    $button_text = isset($branding) && is_array($branding) && isset($branding["redeem_button_text"]) && !empty($branding["redeem_button_text"]) ? $branding["redeem_button_text"] : "";
                    $revert_button = sprintf(__('Revert to %s', 'wp-loyalty-rules'), $earn_campaign_helper->getPointLabel(3));
                    $redeem_button_color = isset($branding) && is_array($branding) && isset($branding["redeem_button_color"]) && !empty($branding["redeem_button_color"]) ? $branding["redeem_button_color"] : "";
                    $button_color = $redeem_button_color ? "background:" . $redeem_button_color . ";" : "background:" . $theme_color . ";";
                    $redeem_button_text_color = isset($branding) && is_array($branding) && isset($branding["redeem_button_text_color"]) && !empty($branding["redeem_button_text_color"]) ? $branding["redeem_button_text_color"] : "";
                    $button_text_color = $redeem_button_text_color ? "color:" . $redeem_button_text_color . ";" : "";
                    $css_class_name = 'wlr-button-reward wlr-button wlr-button-action';
                    ?>
                    <div
                            class="<?php echo (isset($u_reward->discount_code) && !empty($u_reward->discount_code)) ? 'wlr-coupon-card' : 'wlr-reward-card'; ?> wlr-border-color">
                        <div style="<?php echo $is_right_to_left ? "margin-left: -12px;" : "margin-right: -12px;"; ?>">
                            <p class="wlr-reward-type-name wlr-text-color wlr-border-color">
                                <?php echo $u_reward->reward_type_name; ?>
                                <?php $discount_value = isset($u_reward->discount_value) && !empty($u_reward->discount_value) && ($u_reward->discount_value != 0) ? ($u_reward->discount_value) : ''; ?>
                                <?php if ($discount_value > 0 && isset($u_reward->discount_type) && in_array($u_reward->discount_type, array('percent', 'fixed_cart', 'points_conversion'))): ?>
                                    <?php if (($u_reward->discount_type == 'points_conversion') && !empty($u_reward->discount_code)) : ?>
                                        <?php echo " - " . $woocommerce_helper->getCustomPrice($discount_value); ?>
                                    <?php elseif ($u_reward->discount_type != 'points_conversion'): ?>
                                        <?php echo ($u_reward->discount_type == 'percent') ? " - " . round($discount_value) . "%" : " - " . $woocommerce_helper->getCustomPrice($discount_value); ?>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </p>
                        </div>
                        <div class="wlr-card-container">
                            <div class="wlr-card-icon-container">
                                <div class="wlr-card-icon">
                                    <?php $discount_type = isset($u_reward->discount_type) && !empty($u_reward->discount_type) ? $u_reward->discount_type : "" ?>
                                    <?php $img_icon = isset($u_reward->icon) && !empty($u_reward->icon) ? $u_reward->icon : "" ?>
                                    <?php echo \Wlr\App\Helpers\Base::setImageIcon($img_icon, $discount_type, array("alt" => $u_reward->name)); ?>
                                </div>

                            </div>
                            <div class="wlr-card-inner-container">
                                <h4 class="wlr-name wlr-text-color">
                                    <?php echo \Wlr\App\Helpers\Base::readMoreLessContent($u_reward->name, $card_key, 60, __("Show more", "wp-loyalty-rules"), __("Show less", "wp-loyalty-rules"), 'card-my-reward-name', 'wlr-name wlr-pre-text wlr-text-color'); ?>
                                </h4>
                                <?php $description = apply_filters('wlr_my_account_reward_desc', $u_reward->description, $u_reward); ?>
                                <?php if (isset($u_reward->discount_code) && !empty($u_reward->discount_code)): ?>
                                    <?php
                                    $button_text = isset($branding) && is_array($branding) && isset($branding["apply_coupon_button_text"]) && !empty($branding["apply_coupon_button_text"]) ? $branding["apply_coupon_button_text"] : "";
                                    $apply_coupon_button_color = isset($branding) && is_array($branding) && isset($branding["apply_coupon_button_color"]) && !empty($branding["apply_coupon_button_color"]) ? $branding["apply_coupon_button_color"] : "";
                                    $button_color = $apply_coupon_button_color ? "background:" . $apply_coupon_button_color . ";" : "background:" . $theme_color . ";";
                                    $apply_coupon_button_text_color = isset($branding) && is_array($branding) && isset($branding["apply_coupon_button_text_color"]) && !empty($branding["apply_coupon_button_text_color"]) ? $branding["apply_coupon_button_text_color"] : "";
                                    $button_text_color = $apply_coupon_button_text_color ? "color:" . $apply_coupon_button_text_color . ";" : "";
                                    $apply_coupon_border_color = isset($branding) && is_array($branding) && isset($branding["apply_coupon_border_color"]) && !empty($branding["apply_coupon_border_color"]) ? $branding["apply_coupon_border_color"] : "";
                                    $coupon_border = $apply_coupon_border_color ? "border:1px dashed " . $apply_coupon_border_color . ";" : "";
                                    $coupon_copy_icon_color = $apply_coupon_border_color ? "background:" . $apply_coupon_border_color . ";" : "";
                                    $apply_coupon_background = isset($branding) && is_array($branding) && isset($branding["apply_coupon_background"]) && !empty($branding["apply_coupon_background"]) ? $branding["apply_coupon_background"] : "";
                                    $coupon_background = $apply_coupon_background ? "background:" . $apply_coupon_background . ";" : "";
                                    $css_class_name = 'wlr-button-reward-apply wlr-button wlr-button-action';
                                    ?>
                                    <div class="wlr-code" style="<?php echo esc_attr($coupon_border); ?>">
                                        <div class="wlr-coupon-code"
                                             style="<?php echo esc_attr($coupon_background); ?>">
                                            <p title="<?php esc_html_e('Coupon Code', 'wp-loyalty-rules'); ?>"
                                               onclick="wlr_jquery( 'body' ).trigger( 'wlr_copy_coupon',[ '<?php echo esc_js('#wlr-' . $u_reward->discount_code) ?>','<?php echo esc_js('#wlr-icon-' . $u_reward->discount_code) ?>'])">
                                                <span
                                                        style="<?php echo !empty($apply_coupon_border_color) ? esc_attr("color:" . $apply_coupon_border_color . ";") : ""; ?>"
                                                        id="<?php echo esc_attr('wlr-' . $u_reward->discount_code) ?>"><?php echo esc_html($u_reward->discount_code); ?></span>
                                            </p>
                                        </div>
                                        <div class="wlr-coupon-copy-icon"
                                             style="<?php echo esc_attr("color:" . $apply_coupon_border_color . ";" . $coupon_background); ?>">
                                            <i id="<?php echo esc_attr('wlr-icon-' . $u_reward->discount_code) ?>"
                                               class="wlr wlrf-copy wlr-icon"
                                               title="<?php esc_html_e('copy to clipboard', 'wp-loyalty-rules'); ?>"
                                               onclick="wlr_jquery( 'body' ).trigger( 'wlr_copy_coupon',[ '<?php echo esc_js('#wlr-' . $u_reward->discount_code) ?>','<?php echo esc_js('#wlr-icon-' . $u_reward->discount_code) ?>'])"
                                               style="font-size:20px;"></i>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <?php if (!empty($description) && !(isset($u_reward->discount_code) && !empty($u_reward->discount_code))): ?>
                                    <?php echo \Wlr\App\Helpers\Base::readMoreLessContent($description, $card_key, 90, __("Show more", "wp-loyalty-rules"), __("Show less", "wp-loyalty-rules"), 'card-my-reward-description', 'wlr-description wlr-pre-text wlr-text-color'); ?>

                                <?php endif; ?>
                            </div>
                            <?php if (isset($u_reward->discount_type) && $u_reward->discount_type == 'points_conversion' && $u_reward->reward_table != 'user_reward'): ?>
                                <div style="display: none;" class="wlr-point-conversion-section wlr-border-color"
                                     id="<?php echo esc_attr('wlr_point_conversion_div_' . $u_reward->id); ?>">
                                    <div><i class="wlrf-close wlr-cursor wlr-text-color"
                                            title="<?php _e('Close', 'wp-loyalty-rules'); ?>"
                                            onclick="wlr_jquery('<?php echo esc_js('#wlr_point_conversion_div_' . $u_reward->id); ?>').hide();wlr_jquery('<?php echo esc_js('#wlr-button-action-' . $card_key) ?>').show();">
                                        </i></div>
                                    <div style="display: flex;gap: 15%">
                                        <div class="wlr-input-point-section">
                                            <div class="wlr-input-point-conversion wlr-border-color">
                                                <input type="text" min="1" pattern="/^[0-9]+$/"
                                                       class="wlr-point-conversion-box wlr-text-color"
                                                       onkeypress="return wlr_jquery('body').trigger('wlr_validate_number');"
                                                       onchange="wlr_jquery('body').trigger('wlr_calculate_point_conversion',
                                                               ['<?php echo esc_js('wlr_point_conversion_' . $u_reward->id); ?>','<?php echo esc_js('wlr_point_conversion_' . $u_reward->id . '_value'); ?>']);"
                                                       onkeyup="wlr_jquery('body').trigger('wlr_calculate_point_conversion',
                                                               ['<?php echo esc_js('wlr_point_conversion_' . $u_reward->id); ?>','<?php echo esc_js('wlr_point_conversion_' . $u_reward->id . '_value'); ?>']);"
                                                       id="<?php echo esc_attr('wlr_point_conversion_' . $u_reward->id); ?>"
                                                       value="<?php echo esc_attr($u_reward->input_point); ?>"
                                                       oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');"
                                                       data-require-point="<?php echo esc_attr($u_reward->require_point); ?>"
                                                       data-discount-value="<?php echo esc_attr($woocommerce_helper->getCustomPrice($u_reward->discount_value, false)); ?>"
                                                       data-available-point="<?php echo esc_attr($u_reward->available_point); ?>"
                                                       data-cart-amount="<?php echo esc_attr($u_reward->cart_amount); ?>"
                                                ></div>
                                            <div class="wlr-point-label-content wlr-border-color">
                                                <p class="wlr-input-point-title wlr-text-color"><?php
                                                    $woocommerce_currency = $woocommerce_helper->getDisplayCurrency();
                                                    echo sprintf(__('=(%s)%s', 'wp-loyalty-rules'), $woocommerce_currency, $woocommerce_helper->getCurrencySymbols($woocommerce_currency)); ?>
                                                    &nbsp;<span
                                                            id="<?php echo esc_attr('wlr_point_conversion_' . $u_reward->id . '_value'); ?>"
                                                            class="wlr-point-conversion-discount-label">
                                                    <?php echo $u_reward->input_value; ?>
                                                </span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div
                                            id="<?php echo esc_attr('wlr_point_conversion_' . $u_reward->id . '_button'); ?>"
                                            class="wlr-button wlr-button-action"
                                            style="<?php echo esc_attr($button_color); ?>"
                                            onclick="wlr_jquery( 'body' ).trigger( 'wlr_apply_point_conversion_reward',['<?php echo esc_js($u_reward->id); ?>', '<?php echo esc_js($u_reward->reward_table); ?>', '<?php echo esc_js($u_reward->available_point); ?>','<?php echo esc_js('#wlr_point_conversion_' . $u_reward->id); ?>' ,'<?php echo esc_js('#wlr_point_conversion_' . $u_reward->id . '_button'); ?>'] );">
                                        <a class="wlr-action-text"
                                           style="<?php echo esc_attr($button_text_color); ?>"
                                           href="javascript:void(0);"><?php echo esc_html__('Redeem', 'wp-loyalty-rules'); ?></a>
                                    </div>
                                </div>
                                <div class="<?php echo esc_attr($css_class_name); ?>"
                                     style="<?php echo esc_attr($button_color); ?>"
                                     id="<?php echo esc_attr('wlr-button-action-' . $card_key) ?>"
                                     onclick="wlr_jquery('<?php echo esc_js('#wlr_point_conversion_div_' . $u_reward->id); ?>').show();wlr_jquery('<?php echo esc_js('#wlr-button-action-' . $card_key) ?>').hide();wlr_jquery('body').trigger('wlr_calculate_point_conversion',
                                             ['<?php echo esc_js('wlr_point_conversion_' . $u_reward->id); ?>','<?php echo esc_js('wlr_point_conversion_' . $u_reward->id . '_value'); ?>']);">
                                    <a class="wlr-action-text" style="<?php echo esc_attr($button_text_color); ?>"
                                       href="javascript:void(0);"><?php echo esc_html($button_text); ?></a>
                                </div>
                            <?php else: ?>
                                <div class="<?php echo esc_attr($css_class_name); ?> "
                                     id="<?php echo esc_attr('wlr-button-action-' . $card_key); ?>"
                                     style="<?php echo esc_attr($button_color); ?>"
                                     onclick="wlr_jquery( 'body' ).trigger( 'wlr_apply_reward_action', [ '<?php echo esc_js($u_reward->id); ?>', '<?php echo esc_js($u_reward->reward_table); ?>', '<?php echo esc_js('#wlr-button-action-' . $card_key); ?>'] )">
                                    <a class="wlr-action-text" style="<?php echo esc_attr($button_text_color); ?>"
                                       href="javascript:void(0);"><?php echo esc_html($button_text); ?></a>
                                </div>
                            <?php endif; ?>
                            <?php if (isset($u_reward->expiry_date) && !empty($u_reward->expiry_date) && !empty($u_reward->discount_code)): ?>
                                <p class="wlr-expire-date wlr-text-color">
                                    <?php echo esc_html(sprintf(__("Expires on %s", "wp-loyalty-rules"), $u_reward->expiry_date)); ?></p>
                            <?php endif; ?>
                            <?php if (!empty($revert_button) && ($u_reward->reward_type == 'redeem_point') && !empty($u_reward->discount_code) && isset($is_revert_enabled) && $is_revert_enabled): ?>
                                <div style="display: flex;gap: 3px;float: right">
                                    <div class="wlr-revert-tool"
                                         onclick="wlr_jquery( 'body' ).trigger('revertEnable',['<?php echo esc_js('#wlr-' . $u_reward->id . '-' . $u_reward->discount_code); ?>']);">
                                        <span
                                                class="wlr-text-color"><?php echo __('Options', 'wp-loyalty-rules'); ?></span>
                                        <i class="wlr-text-color wlrf-arrow_right"></i>
                                    </div>
                                </div>
                                <div class="wlr-revert"
                                     id="<?php echo esc_attr('wlr-' . $u_reward->id . '-' . $u_reward->discount_code); ?>"
                                     onclick="wlr_jquery( 'body' ).trigger('wlr_revoke_coupon',['<?php echo esc_js($u_reward->id); ?>','<?php echo esc_js($u_reward->discount_code); ?>']);">
                                    <a class="wlr-revert-reward wlr-theme-color-apply"><?php echo esc_html($revert_button); ?></a>
                                </div>
                            <?php endif; ?>

                        </div>
                    </div>
                    <?php $card_key++; endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
    <!--    customer rewards end here -->
    <?php do_action('wlr_after_customer_reward_cart_page_content'); ?>
</div>
