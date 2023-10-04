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
    .wlr-myaccount-page .wlr-heading {
    <?php echo !empty($heading_color) ? esc_attr("color:" . $heading_color . " !important;") : "";?><?php echo !empty($theme_color) ? esc_attr("border-left: 3px solid " . $theme_color . " !important;") : "";?>
    }

    .wlr-myaccount-page .wlr-theme-color-apply:before {
    <?php echo isset($theme_color) && !empty($theme_color) ?  esc_attr("color :".$theme_color." !important;") : "";?>;
    }
</style>
<div class="wlr-myaccount-page">
    <!--    customer points start here -->
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
                        <?php $user_points = (int)(isset($user) && !empty($user) && isset($user->points) && !empty($user->points) ? $user->points : 0); ?>
                        <span id="<?php echo esc_attr(WLR_PLUGIN_PREFIX . '-available-points-heading') ?>">
        <?php echo esc_html(sprintf(__('Available %s', 'wp-loyalty-rules'), $base_helper->getPointLabel($user_points))) ?></span>
                        <div id="<?php echo esc_attr(WLR_PLUGIN_PREFIX . '-available-point-value') ?>">
                            <?php echo $user_points; ?>
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
                        <?php $user_total_points = (int)(isset($user) && !empty($user) && isset($user->used_total_points) && !empty($user->used_total_points) ? $user->used_total_points : 0); ?>
                        <span id="<?php echo esc_attr(WLR_PLUGIN_PREFIX . '-redeemed-points-heading') ?>">
        <?php echo esc_html(sprintf(__('Redeemed %s', 'wp-loyalty-rules'), $base_helper->getPointLabel($user_total_points))) ?></span>
                        <div id="<?php echo esc_attr(WLR_PLUGIN_PREFIX . '-redeemed-point-value') ?>">
                            <?php echo $user_total_points; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--    customer points end here -->

        <!--    customer referral start here -->
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
                        <i class="wlr wlrf-copy wlr-icon"
                           title="<?php esc_html_e("copy to clipboard", 'wp-loyalty-rules') ?>"
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
        <!--    customer referral end here -->

        <!--    customer rewards start here -->
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
                                        <?php if (!empty($revert_button) && ($u_reward->reward_type == 'redeem_point') && !empty($u_reward->discount_code) && isset($is_revert_enabled) && $is_revert_enabled): ?>
                                            <div class="wlr-revert-tool"
                                                 onclick="wlr_jquery( 'body' ).trigger('revertEnable',['<?php echo esc_js('#wlr-' . $u_reward->id . '-' . $u_reward->discount_code); ?>']);">
                                                <img src="<?php echo isset($point_revert_icon) && !empty($point_revert_icon) ? esc_url($point_revert_icon) : '' ?>"/>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php if (!empty($revert_button) && ($u_reward->reward_type == 'redeem_point') && !empty($u_reward->discount_code) && isset($is_revert_enabled) && $is_revert_enabled): ?>
                                    <div class="wlr-revert"
                                         id="<?php echo esc_attr('wlr-' . $u_reward->id . '-' . $u_reward->discount_code); ?>"
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
                                                <p title="<?php esc_html_e('Coupon Code', 'wp-loyalty-rules'); ?>"
                                                   onclick="wlr_jquery( 'body' ).trigger( 'wlr_copy_coupon',[ '<?php echo esc_js('#wlr-' . $u_reward->discount_code) ?>','<?php echo esc_js('#wlr-icon-' . $u_reward->discount_code) ?>'])">
                                                    <span id="<?php echo esc_attr('wlr-' . $u_reward->discount_code) ?>"><?php echo esc_html($u_reward->discount_code) ?></span>
                                                </p>
                                            </div>
                                            <div class="wlr-coupon-copy-icon"
                                                 style="<?php echo esc_attr($coupon_copy_icon_color); ?>">
                                                <i id="<?php echo esc_attr('wlr-icon-' . $u_reward->discount_code); ?>"
                                                   class="wlr wlrf-copy wlr-icon"
                                                   title="<?php esc_html_e('copy to clipboard', 'wp-loyalty-rules'); ?>"
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
        <!--    customer rewards end here -->

        <!--    customer transactions start here -->
        <?php
        $earn_helper = \Wlr\App\Helpers\EarnCampaign::getInstance();
        if (isset($transactions) && !empty($transactions)): ?>
            <div class="wlr-transaction-blog"
                 id="<?php echo esc_attr(WLR_PLUGIN_PREFIX . '-transaction-details-table') ?>">
                <div class="wlr-heading-container">
                    <h3 class="wlr-heading"><?php echo esc_html__('Recent Activities', 'wp-loyalty-rules'); ?></h3>
                </div>
                <div id="<?php echo esc_attr(WLR_PLUGIN_PREFIX . '-transaction-table') ?>">
                    <table>
                        <thead id="<?php echo esc_attr(WLR_PLUGIN_PREFIX . '-transaction-table-header') ?>">
                        <tr>
                            <th class="set-center"><?php echo esc_html__('Order No.', 'wp-loyalty-rules') ?></th>
                            <th><?php echo esc_html__('Action Type', 'wp-loyalty-rules') ?></th>
                            <th><?php echo esc_html__('Message', 'wp-loyalty-rules') ?></th>
                            <th class="set-center"><?php echo esc_html__($earn_helper->getPointLabel(3), 'wp-loyalty-rules') ?></th>
                            <th><?php echo esc_html__('Rewards', 'wp-loyalty-rules') ?></th>
                        </tr>
                        </thead>

                        <?php foreach ($transactions as $transaction): ?>
                            <tr>
                                <td class="<?php echo esc_attr(WLR_PLUGIN_PREFIX . '-transaction-table-body') ?> set-center">
                                    <?php if ($transaction->order_id > 0):
                                        $order = wc_get_order($transaction->order_id);
                                        if (isset($order) && is_object($order) && method_exists($order, 'get_view_order_url')): ?>
                                            <?php if ($transaction->action_type != 'referral'): ?>
                                                <a href="<?php echo esc_url($order->get_view_order_url()); ?>">
                                                    <?php echo '#' . $order->get_order_number(); ?>
                                                </a>
                                            <?php else: ?>
                                                <?php echo '#' . $order->get_order_number(); ?>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <?php echo esc_html('#' . $transaction->order_id); ?>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                                <td class="<?php echo esc_attr(WLR_PLUGIN_PREFIX . '-transaction-table-body') ?>"><?php echo esc_html($earn_helper->getActionName($transaction->action_type)); ?></td>
                                <td class="<?php echo esc_attr(WLR_PLUGIN_PREFIX . '-transaction-table-body') ?>">
                                    <?php echo esc_html(isset($transaction->processed_custom_note) && !empty($transaction->processed_custom_note) ? $transaction->processed_custom_note : $transaction->customer_note);
                                    ?></td>
                                <td class="<?php echo esc_attr(WLR_PLUGIN_PREFIX . '-transaction-table-body') ?> set-center"><?php echo ($transaction->points == 0) ? "-" : (int)$transaction->points; ?></td>
                                <td class="<?php echo esc_attr(WLR_PLUGIN_PREFIX . '-transaction-table-body') ?>"><?php echo esc_html(!empty($transaction->reward_display_name) ? $transaction->reward_display_name : '-'); ?></td>
                            </tr>

                        <?php endforeach; ?>
                    </table>
                    <?php if (isset($transaction_total) && $transaction_total > 0): ?>
                        <div style="text-align: right">
                            <?php if (isset($offset) && 1 !== (int)$offset) : ?>
                                <a class="woocommerce-button woocommerce-button--previous woocommerce-Button"
                                   id="<?php echo esc_attr(WLR_PLUGIN_PREFIX . '-prev-button') ?>"
                                   href="<?php echo esc_url(wc_get_endpoint_url('loyalty_reward', $offset - 1) . '#wlr-transaction-details-table'); ?>">
                                    <?php esc_html_e('Prev', 'wp-loyalty-rules'); ?>
                                </a>
                            <?php endif; ?>
                            <?php if (isset($current_trans_count) && intval($current_trans_count) < $transaction_total) : ?>
                                <a class="woocommerce-button woocommerce-button--next woocommerce-Button "
                                   id="<?php echo esc_attr(WLR_PLUGIN_PREFIX . '-next-button') ?>"
                                   href="<?php echo esc_url(wc_get_endpoint_url('loyalty_reward', $offset + 1) . '#wlr-transaction-details-table'); ?>">
                                    <?php esc_html_e('Next', 'wp-loyalty-rules'); ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
        <!--    customer transactions end here -->

        <!--    campaign list start here -->
        <?php $woocommerce_helper = new \Wlr\App\Helpers\Woocommerce();
        if (isset($campaign_list) && !empty($campaign_list) && isset($is_campaign_list_available) && $is_campaign_list_available === 'yes') : ?>
            <div class="wlr-earning-options">
                <div class="wlr-heading-container">
                    <h3 class="wlr-heading"><?php esc_html_e('Ways to earn rewards', 'wp-loyalty-rules') ?></h3>
                </div>
                <div class="wlr-campaign-container">
                    <?php $card_key = 1;
                    foreach ($campaign_list as $campaign) : ?>
                        <?php if (isset($campaign->is_show_way_to_earn) && $campaign->is_show_way_to_earn == 1): ?>
                            <div class="wlr-card wlr-earning-option " <?php echo ((isset($user) && $user->id > 0 || get_current_user_id() > 0) && isset($campaign->action_type) && $campaign->action_type == 'followup_share' && isset($campaign->share_url)) ?
                                'style="cursor:pointer;"
                                onclick="wlr_jquery( \'body\' ).trigger( \'wlr_apply_followup_share\', [ \'' . esc_js($campaign->id) . '\',\'' . esc_js($campaign->share_url) . '\',\'' . esc_js($campaign->action_type) . '\' ] )"' : "" ?> >
                                <?php $action_type = isset($campaign->action_type) && !empty($campaign->action_type) ? $campaign->action_type : ""; ?>
                                <?php $img_icon = isset($campaign->icon) && !empty($campaign->icon) ? $campaign->icon : ""; ?>
                                <?php echo \Wlr\App\Helpers\Base::setImageIcon($img_icon, $action_type, array()); ?>
                                <h4 class="wlr-name">
                                    <?php echo \Wlr\App\Helpers\Base::readMoreLessContent($campaign->name, $card_key, 60, __("Show more", "wp-loyalty-rules"), __("Show less", "wp-loyalty-rules"), 'card-campaign-name', 'wlr-name wlr-pre-text'); ?>
                                </h4>
                                <?php if (isset($campaign->campaign_title_discount) && !empty($campaign->campaign_title_discount) && isset($campaign_point_display) && !empty($campaign_point_display) && $campaign_point_display == 'yes') : ?>
                                    <p class="wlr-discount-point"><?php esc_html_e($campaign->campaign_title_discount, 'wp-loyalty-rules'); ?></p>
                                <?php endif; ?>
                                <?php if (isset($campaign->action_type) && $campaign->action_type == 'birthday') : ?>
                                    <?php $birth_date = isset($user->birthday_date) && !empty($user->birthday_date) && $user->birthday_date != '0000-00-00' ? $user->birthday_date : (isset($user->birth_date) && !empty($user->birth_date) ? $woocommerce_helper->beforeDisplayDate($user->birth_date, "Y-m-d") : '');
                                    $can_edit_birthdate = (isset($user) && isset($user->id) && $user->id > 0);
                                    ?>
                                    <?php if ($can_edit_birthdate): ?>
                                        <div class="wlr-date">
                                        <span id="<?php echo esc_attr('wlr-birth-date-' . $campaign->id); ?>">
                                            <?php echo esc_html($birth_date); ?>
                                        </span>
                                            <a style="<?php echo !empty($theme_color) ? esc_attr("background:" . $theme_color . ";") : ""; ?>"
                                               onclick="jQuery('<?php echo esc_js('#wlr-birth-date-input-' . $campaign->id); ?>').toggle();">
                                            <span class="wlr">
                                                <?php echo !empty($birth_date) ? esc_html__('Edit Birthday', 'wp-loyalty-rules') : esc_html__('Set Birthday', 'wp-loyalty-rules'); ?>
                                            </span>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($can_edit_birthdate): ?>
                                        <div class="wlr-date-editor"
                                             id="<?php echo esc_attr('wlr-birth-date-input-' . $campaign->id); ?>"
                                             style="display: none;">
                                            <div class="wlr-date-editor-layer"></div>
                                            <i class="wlrf-close"
                                               style="float:right;margin-top:10px; margin-right:10px;color:white;font-weight:bold;font-size: 30px;"
                                               onclick="jQuery('<?php echo esc_js('#wlr-birth-date-input-' . $campaign->id); ?>').toggle();">
                                            </i>
                                            <div class="wlr-date-editor-container">
                                                <input class="wlr-input-date" type="date"
                                                       max="<?php echo esc_html(date("Y-m-d")) ?>"
                                                       value="<?php echo esc_attr($birth_date); ?>"
                                                       id="<?php echo esc_attr('wlr-customer-birth-date-' . $campaign->id); ?>">
                                                <a class="wlr-date-action wlr-update-birthday"
                                                   style="<?php echo !empty($theme_color) ? esc_attr("background:" . $theme_color . ";") : ""; ?>"
                                                   onclick="wlr_jquery( 'body' ).trigger( 'wlr_update_birthday_action', [ '<?php echo esc_js($campaign->id); ?>','<?php echo esc_js('#wlr-customer-birth-date-' . $campaign->id); ?>', 'update' ] )">
                                                    <?php esc_html_e('Update Birthday', 'wp-loyalty-rules') ?>
                                                </a>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <?php if (is_object($campaign) && isset($campaign->description) && !empty($campaign->description) && $campaign->description != 'null') : ?>
                                    <?php echo \Wlr\App\Helpers\Base::readMoreLessContent($campaign->description, $card_key, 90, __("Show more", "wp-loyalty-rules"), __("Show less", "wp-loyalty-rules"), 'card-campaign-description', 'wlr-description wlr-pre-text'); ?>
                                <?php endif; ?>
                                <?php if ((isset($user) && is_object($user) && $user->id > 0 || get_current_user_id() > 0) && isset($campaign->action_type) && $campaign->action_type == 'followup_share' && isset($campaign->share_url)) : ?>
                                    <div class="wlr-date" style="position:relative;float: right;">
                                        <a style="<?php echo !empty($theme_color) ? esc_attr("background:" . $theme_color . ";") : ""; ?>"
                                           onclick="wlr_jquery( 'body' ).trigger( 'wlr_apply_followup_share', [ '<?php echo esc_js($campaign->id); ?>','<?php echo esc_js($campaign->share_url); ?>','<?php echo esc_js($campaign->action_type); ?>' ] )">
                                            <span class="wlr">
                                                <?php echo esc_html__('Follow', 'wp-loyalty-rules'); ?>
                                            </span>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <?php $card_key++; endif; endforeach; ?>
                </div>

            </div>
        <?php endif; ?>
        <!--    campaign list end here -->

        <!--    rewards list start here -->
        <?php if (isset($reward_list) && !empty($reward_list) && isset($is_reward_list_available) && $is_reward_list_available === 'yes') : ?>
            <div class="wlr-earning-options">
                <div class="wlr-heading-container">
                    <h3 class="wlr-heading"><?php esc_html_e('Reward opportunities', 'wp-loyalty-rules') ?></h3>
                </div>
                <div class="wlr-campaign-container">
                    <?php $card_key = 1;
                    foreach ($reward_list as $reward) : ?>
                        <?php if (isset($reward->is_show_reward) && $reward->is_show_reward == 1): ?>
                            <div class="wlr-card wlr-earning-option ">
                                <?php $discount_type = isset($reward->discount_type) && !empty($reward->discount_type) ? $reward->discount_type : "" ?>
                                <?php $img_icon = isset($reward->icon) && !empty($reward->icon) ? $reward->icon : "" ?>
                                <?php echo \Wlr\App\Helpers\Base::setImageIcon($img_icon, $discount_type, array()); ?>
                                <h4 class="wlr-name">
                                    <?php echo \Wlr\App\Helpers\Base::readMoreLessContent($reward->name, $card_key, 60, __("Show more", "wp-loyalty-rules"), __("Show less", "wp-loyalty-rules"), 'card-ways-to-earn-name', 'wlr-name wlr-pre-text'); ?>
                                </h4>
                                <?php if (isset($reward->description) && !empty($reward->description) && $reward->description != 'null') : ?>
                                    <?php echo \Wlr\App\Helpers\Base::readMoreLessContent($reward->description, $card_key, 90, __("Show more", "wp-loyalty-rules"), __("Show less", "wp-loyalty-rules"), 'card-ways-to-earn-description', 'wlr-description wlr-pre-text'); ?>
                                <?php endif; ?>
                            </div>
                            <?php $card_key++; endif; endforeach; ?>
                </div>

            </div>
        <?php endif; ?>
        <!--    rewards list end here -->
    </div>
