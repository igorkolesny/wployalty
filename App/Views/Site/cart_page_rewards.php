<?php
/**
 * @author      Wployalty (Alagesan)
 * @license     http://www.gnu.org/licenses/gpl-3.0.html
 * @link        https://www.wployalty.net
 * */
defined('ABSPATH') or die;
$woocommerce_helper = new \Wlr\App\Helpers\Woocommerce();
$theme_color = isset($theme_color) && !empty($theme_color) ? $theme_color : "";
$heading_color = isset($heading_color) && !empty($heading_color) ? $heading_color : "";
?>
<style>
    .ajs-dialog .wlr-myaccount-page .wlr-heading {
    <?php echo !empty($heading_color) ? esc_attr("color:" . $heading_color . " !important;") : "";?><?php echo !empty($theme_color) ? esc_attr("border-left: 3px solid " . $theme_color . " !important;") : "";?>
    }

    .ajs-dialog .wlr-myaccount-page .wlr-theme-color-apply:before {
    <?php echo isset($theme_color) && !empty($theme_color) ?  esc_attr("color :".$theme_color." !important;") : "";?>;
    }
</style>
<div class="wlr-myaccount-page">
    <!-- cart points start here-->
    <?php
    $style = '';
    if (isset($page_type) && in_array($page_type, array('page')) && empty($user)) {
        $style = "style=\"display:none;\"";
    }
    $base_helper = new \Wlr\App\Helpers\Base();
    ?>
    <?php $level_check = isset($level_data) && !empty($level_data) && isset($level_data->current_level_name) && !empty($level_data->current_level_name); ?>
    <div class="wlr-user-details" <?php echo esc_attr($style); ?>>
        <div class="wlr-heading-container">
            <h3 class="wlr-heading"><?php echo esc_html(sprintf(__('My %s', 'wp-loyalty-rules'), $base_helper->getPointLabel(3))); ?></h3>
        </div>
        <div class="wlr-points-container" style="<?php echo !$level_check ? 'margin-bottom: 0px' : '' ?>">
            <?php if (isset($user) && isset($user->level_id) && $user->level_id > 0 && $level_check) {
            ?>
            <div id="<?php echo esc_attr(WLR_PLUGIN_PREFIX . '-levels') ?>" style="min-height: 190px">
                <div>
                    <?php if (isset($level_data->current_level_image) && !empty($level_data->current_level_image)): ?>
                        <?php echo \Wlr\App\Helpers\Base::setImageIcon($level_data->current_level_image, "", array("alt" => __("Level image", "wp-loyalty-rules"), "height" => 48, "width" => 48)); ?>
                    <?php endif; ?>
                </div>
                <div>
                    <p id="<?php echo esc_attr(WLR_PLUGIN_PREFIX . '-level-name') ?>" class="wlr-points-name">
                        <?php echo !empty($level_data) && !empty($level_data->current_level_name) ? esc_html($level_data->current_level_name) : '' ?>
                    </p>
                </div>
                <?php
                if (isset($level_data->current_level_start) && isset($level_data->next_level_start) && $level_data->next_level_start > 0) {
                    $css_width = (($user->earn_total_point - $level_data->current_level_start) / ($level_data->next_level_start - $level_data->current_level_start)) * 100;
                    $needed_point = $level_data->next_level_start - $user->earn_total_point;
                    ?>
                    <div class="level-points">
                        <div class="wlr-progress-bar">
                            <div class="wlr-progress-level"
                                 style="<?php echo esc_attr('width:' . $css_width . '%'); ?>">

                            </div>
                        </div>
                        <p class="wlr-progress-content">
                            <?php echo esc_html(sprintf(__('%d %s more needed to unlock next level', 'wp-loyalty-rules'), (int)$needed_point, $base_helper->getPointLabel($needed_point))); ?>
                        </p>
                    </div>
                    <?php
                } else {
                    ?>
                    <div class="level-points">
                        <p class="wlr-progress-content">
                            <?php echo esc_html__('Congratulations! You have reached the final level', 'wp-loyalty-rules'); ?>
                        </p>
                    </div>

                    <?php
                }
                }
                ?>
            </div>
            <div id="<?php echo esc_attr(WLR_PLUGIN_PREFIX . '-points') ?>"
                 style="<?php echo !$level_check ? 'display:flex; flex-direction:row; width:100%; box-shadow:none; padding:0px;' : 'min-height: 190px;' ?> ">
                <div id="<?php echo esc_attr(WLR_PLUGIN_PREFIX . '-available-points') ?>"
                     style="<?php echo !$level_check ? 'width:50%;border: 1px solid #E7E7EF;border-radius: 10px; padding: 10px' : ''; ?>">
                    <div>
                        <?php $img_icon = isset($level_data->available_point_icon) && !empty($level_data->available_point_icon) ? $level_data->available_point_icon : ""; ?>
                        <?php echo \Wlr\App\Helpers\Base::setImageIcon($img_icon, "", array("height" => 64, "width" => 64)); ?>
                    </div>
                    <div>
                            <span id="<?php echo esc_attr(WLR_PLUGIN_PREFIX . '-available-points-heading') ?>">
        <?php echo esc_html(sprintf(__('Available %s', 'wp-loyalty-rules'), $base_helper->getPointLabel(3))) ?></span>
                        <div id="<?php echo esc_attr(WLR_PLUGIN_PREFIX . '-available-point-value') ?>">
                            <?php echo (int)(isset($user) && !empty($user) && isset($user->points) && !empty($user->points) ? $user->points : 0) ?>
                        </div>
                    </div>
                </div>
                <div id="<?php echo esc_attr(WLR_PLUGIN_PREFIX . '-redeemed-points') ?>"
                     style="<?php echo !$level_check ? 'width:50%;border: 1px solid #E7E7EF;border-radius: 10px; padding: 10px' : '' ?>">
                    <div>
                        <?php $img_icon = isset($level_data->redeem_point_icon) && !empty($level_data->redeem_point_icon) ? $level_data->redeem_point_icon : ""; ?>
                        <?php echo \Wlr\App\Helpers\Base::setImageIcon($img_icon, "", array("height" => 64, "width" => 64)); ?>
                    </div>
                    <div>
                            <span id="<?php echo esc_attr(WLR_PLUGIN_PREFIX . '-redeemed-points-heading') ?>">
        <?php echo esc_html(sprintf(__('Redeemed %s', 'wp-loyalty-rules'), $base_helper->getPointLabel(3))) ?></span>
                        <div id="<?php echo esc_attr(WLR_PLUGIN_PREFIX . '-redeemed-point-value') ?>">
                            <?php echo (int)(isset($user) && !empty($user) && isset($user->used_total_points) && !empty($user->used_total_points) ? $user->used_total_points : 0) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- cart points end here -->

        <!-- cart referral start here -->
        <?php
        if ((isset($is_referral_action_available) && $is_referral_action_available === 'yes' && isset($referral_url) && !empty($referral_url))
            || (isset($is_social_share_available) && $is_social_share_available === 'yes' && isset($social_share_list) && !empty($social_share_list) && isset($referral_url) && !empty($referral_url))): ?>
            <div class="wlr-referral-blog">
                <div class="wlr-heading-container">
                    <h3 class="wlr-heading"><?php echo esc_html__('Referral link', 'wp-loyalty-rules'); ?></h3>
                </div>
                <?php if (isset($is_referral_action_available) && $is_referral_action_available === 'yes'): ?>
                    <div class="wlr-referral-box">
                        <input type="text" value="<?php echo esc_url($referral_url); ?>" id="wlr_referral_url_link"
                               class="wlr_referral_url" disabled/>
                        <div class="input-group-append"
                             onclick="wlr_jquery( 'body' ).trigger( 'wlr_copy_link',[ 'wlr_referral_url_link'])">
                    <span class="input-group-text"
                          style="<?php echo isset($theme_color) && !empty($theme_color) ? esc_attr("background:" . $theme_color . ";") : ""; ?>">
                        <i class="wlr wlrf-copy wlr-icon" title="copy to clipboard"
                           style="font-size:20px;margin-top:4px"></i>
                        <?php echo esc_html__('Copy Link', 'wp-loyalty-rules'); ?>
                    </span>
                        </div>
                    </div>
                <?php endif; ?>
                <?php if (isset($is_social_share_available) && $is_social_share_available === 'yes'
                    && isset($social_share_list) && !empty($social_share_list)): ?>
                    <div class="wlr-social-share">
                        <?php foreach ($social_share_list as $action => $social_share): ?>
                            <a class="wlr-icon-list"
                               onclick="wlr_jquery( 'body' ).trigger( 'wlr_apply_social_share', [ '<?php echo esc_js($social_share['url']); ?>','<?php echo esc_js($action); ?>' ] )"
                               target="_parent">
                                <?php $img_icon = isset($social_share['icon']) && !empty($social_share['icon']) ? $social_share['icon'] : "" ?>
                                <?php echo \Wlr\App\Helpers\Base::setImageIcon("social", $social_share['icon'], array()); ?>
                                <span class="wlr-social-text"><?php echo esc_html($social_share['name']); ?></span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        <!-- cart referral end here -->

        <!-- cart rewards start here -->
        <?php
        $base_helper = new \Wlr\App\Helpers\Base();
        if (isset($user_reward) && !empty($user_reward)): ?>
            <div class="wlr-your-reward">
                <div class="wlr-heading-container"><h3
                            class="wlr-heading"><?php esc_html_e('My rewards', 'wp-loyalty-rules') ?></h3>
                </div>
                <div class="wlr-customer-reward">
                    <?php $card_key = 1;
                    foreach ($user_reward as $u_reward): ?>
                        <?php
                        $button_text = isset($u_reward->redeem_button_text) && !empty($u_reward->redeem_button_text) ? __($u_reward->redeem_button_text, 'wp-loyalty-rules') : __('Redeem Now', 'wp-loyalty-rules');
                        $revert_button = sprintf(__('Revert to %s', 'wp-loyalty-rules'), $base_helper->getPointLabel(3));
                        $theme_color = isset($theme_color) && !empty($theme_color) ? $theme_color : "";
                        $button_color = isset($u_reward->redeem_button_color) && !empty($u_reward->redeem_button_color) ? "background:" . $u_reward->redeem_button_color . ";" : "background:" . $theme_color . ";";
                        $button_text_color = isset($u_reward->redeem_button_text_color) && !empty($u_reward->redeem_button_text_color) ? "color:" . $u_reward->redeem_button_text_color . ";" : "";
                        $css_class_name = 'wlr-button-reward wlr-button';
                        ?>
                        <div class="<?php echo (isset($u_reward->discount_code) && !empty($u_reward->discount_code)) ? 'wlr-coupon-card' : 'wlr-reward-card'; ?>">
                            <div style="margin-right: -12px;">
                                <p class="wlr-reward-type-name">
                                    <?php echo $u_reward->reward_type_name; ?>
                                    <?php $discount_value = isset($u_reward->discount_value) && !empty($u_reward->discount_value) && ($u_reward->discount_value != 0) ? ($u_reward->discount_value) : ''; ?>
                                    <?php echo $discount_value > 0 && isset($u_reward->discount_type) && ($u_reward->discount_type == 'percent') ? esc_html(" - " . round($discount_value) . "%") : " "; ?>
                                    <?php echo $discount_value > 0 && isset($u_reward->discount_type) && ($u_reward->discount_type == 'fixed_cart') ? " - " . $woocommerce_helper->getCustomPrice($discount_value) : ""; ?>
                                </p>
                            </div>
                            <div class="wlr-card-container">
                                <div class="wlr-card-icon-container">
                                    <div class="wlr-card-icon">
                                        <?php $discount_type = isset($u_reward->discount_type) && !empty($u_reward->discount_type) ? $u_reward->discount_type : "" ?>
                                        <?php $img_icon = isset($u_reward->icon) && !empty($u_reward->icon) ? $u_reward->icon : "" ?>
                                        <?php echo \Wlr\App\Helpers\Base::setImageIcon($img_icon, $discount_type, array()); ?>
                                    </div>
                                    <div style="display: flex;gap: 3px;">
                                        <?php if (!empty($revert_button) && ($u_reward->reward_type == 'redeem_point') && !empty($u_reward->discount_code)): ?>
                                            <div class="wlr-revert-tool"
                                                 onclick="wlr_jquery( 'body' ).trigger('revertEnable',['<?php echo esc_js('#wlr-' . $u_reward->id . '-' . $u_reward->discount_code); ?>']);">
                                                <img src="<?php echo isset($point_revert_icon) && !empty($point_revert_icon) ? esc_url($point_revert_icon) : '' ?>"/>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php if (!empty($revert_button) && ($u_reward->reward_type == 'redeem_point') && !empty($u_reward->discount_code)): ?>
                                    <div class="wlr-revert"
                                         id="wlr-<?php echo esc_attr($u_reward->id . '-' . $u_reward->discount_code); ?>"
                                         onclick="wlr_jquery( 'body' ).trigger('wlr_revoke_coupon',['<?php echo esc_js($u_reward->id); ?>','<?php echo esc_js($u_reward->discount_code); ?>']);">
                                        <a class="wlr-revert-reward"><?php echo esc_html($revert_button); ?></a>
                                    </div>
                                <?php endif; ?>
                                <div class="wlr-card-inner-container">
                                    <h4 class="wlr-name">
                                        <?php echo \Wlr\App\Helpers\Base::readMoreLessContent($u_reward->name, $card_key, 60, __("Show more", "wp-loyalty-rules"), __("Show less", "wp-loyalty-rules"), 'card-my-reward-name', 'wlr-name wlr-pre-text'); ?>
                                    </h4>
                                    <?php $description = apply_filters('wlr_my_account_reward_desc', $u_reward->description, $u_reward); ?>
                                    <?php if (isset($u_reward->discount_code) && !empty($u_reward->discount_code)): ?>
                                        <?php
                                        $button_text = isset($u_reward->apply_coupon_button_text) && !empty($u_reward->apply_coupon_button_text) ? __($u_reward->apply_coupon_button_text, 'wp-loyalty-rules') : __('Apply Coupon', 'wp-loyalty-rules');
                                        $button_color = isset($u_reward->apply_coupon_button_color) && !empty($u_reward->apply_coupon_button_color) ? "background:" . $u_reward->apply_coupon_button_color . ";" : "background:" . $theme_color . ";";
                                        $button_text_color = isset($u_reward->apply_coupon_button_text_color) && !empty($u_reward->apply_coupon_button_text_color) ? "color:" . $u_reward->apply_coupon_button_text_color . ";" : "";
                                        $coupon_border = isset($u_reward->apply_coupon_border_color) && !empty($u_reward->apply_coupon_border_color) ? "border:1px dashed " . $u_reward->apply_coupon_border_color . ";color:" . $u_reward->apply_coupon_border_color . ";" : "";
                                        $coupon_copy_icon_color = isset($u_reward->apply_coupon_border_color) && !empty($u_reward->apply_coupon_border_color) ? "background:" . $u_reward->apply_coupon_border_color . ";" : "";
                                        $coupon_background = isset($u_reward->apply_coupon_background) && !empty($u_reward->apply_coupon_background) ? "background:" . $u_reward->apply_coupon_background . ";" : "";
                                        $css_class_name = 'wlr-button-reward-apply wlr-button ';
                                        ?>
                                        <div class="wlr-code" style="<?php echo esc_attr($coupon_border); ?>">
                                            <div class="wlr-coupon-code"
                                                 style="<?php echo esc_attr($coupon_background); ?>">
                                                <p title="Coupon Code"
                                                   onclick="wlr_jquery( 'body' ).trigger( 'wlr_copy_coupon',[ '<?php echo esc_js('#wlr-' . $u_reward->discount_code) ?>','<?php echo esc_js('#wlr-icon-' . $u_reward->discount_code) ?>'])">
                                                    <span id="<?php echo esc_attr('wlr-' . $u_reward->discount_code) ?>"><?php echo esc_html($u_reward->discount_code) ?></span>
                                                </p>
                                            </div>
                                            <div class="wlr-coupon-copy-icon"
                                                 style="<?php echo esc_attr($coupon_copy_icon_color); ?>">
                                                <i id="<?php echo esc_attr('wlr-icon-' . $u_reward->discount_code) ?>"
                                                   class="wlr wlrf-copy wlr-icon" title="copy to clipboard"
                                                   onclick="wlr_jquery( 'body' ).trigger( 'wlr_copy_coupon',[ '<?php echo esc_js('#wlr-' . $u_reward->discount_code) ?>','<?php echo esc_js('#wlr-icon-' . $u_reward->discount_code) ?>'])"
                                                   style="font-size:20px;"></i>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    <?php if (!empty($description) && !(isset($u_reward->discount_code) && !empty($u_reward->discount_code))): ?>
                                        <?php echo \Wlr\App\Helpers\Base::readMoreLessContent($description, $card_key, 90, __("Show more", "wp-loyalty-rules"), __("Show less", "wp-loyalty-rules"), 'card-my-reward-description', 'wlr-description wlr-pre-text'); ?>

                                    <?php endif; ?>
                                </div>
                                <?php if (isset($u_reward->discount_type) && $u_reward->discount_type == 'points_conversion' && $u_reward->reward_table != 'user_reward'): ?>
                                    <?php $available_point = (isset($user) && isset($user->points) && !empty($user->points)) ? $user->points : 0; ?>
                                    <div class="<?php echo esc_attr($css_class_name); ?> "
                                         style="<?php echo esc_attr($button_color); ?>"
                                         onclick="wlr_jquery( 'body' ).trigger( 'wlr_apply_point_conversion_reward_action', [ '<?php echo esc_js($u_reward->id); ?>', '<?php echo esc_js($u_reward->reward_table); ?>', '<?php echo esc_js($available_point); ?>' ] )">
                                        <a class="wlr-action-text" style="<?php echo esc_attr($button_text_color); ?>"
                                           href="javascript:void(0);"><?php echo esc_html($button_text); ?></a>
                                    </div>
                                <?php else: ?>
                                    <div class="<?php echo esc_attr($css_class_name); ?> "
                                         style="<?php echo esc_attr($button_color); ?>"
                                         onclick="wlr_jquery( 'body' ).trigger( 'wlr_apply_reward_action', [ '<?php echo esc_js($u_reward->id); ?>', '<?php echo esc_js($u_reward->reward_table); ?>' ] )">
                                        <a class="wlr-action-text" style="<?php echo esc_attr($button_text_color); ?>"
                                           href="javascript:void(0);"><?php echo esc_html($button_text); ?></a>
                                    </div>
                                <?php endif; ?>
                                <?php if (isset($u_reward->expiry_date) && !empty($u_reward->expiry_date) && !empty($u_reward->discount_code)): ?>
                                    <p class="wlr-expire-date">
                                        <?php echo esc_html(sprintf(__("Expires on %s", "wp-loyalty-rules"), $u_reward->expiry_date)); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php $card_key++; endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
        <!-- cart rewards end here -->
    </div>