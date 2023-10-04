<?php
/**
 * @author      Wployalty (Ilaiyaraja)
 * @license     http://www.gnu.org/licenses/gpl-2.0.html
 * @link        https://www.wployalty.net
 * */

namespace Wll\V2\App\Controllers\Site;

use Wll\V2\App\Controllers\Base;
use Wll\V2\App\Controllers\Guest;
use Wll\V2\App\Controllers\Member;
use Wlr\App\Helpers\EarnCampaign;

defined('ABSPATH') or die();

class Site extends Base
{
    /**
     * Loading site scripts and styles
     * @return void
     */
    public function enqueueSiteAssets()
    {
        $suffix = '.min';
        if (defined('SCRIPT_DEBUG')) {
            $suffix = SCRIPT_DEBUG ? '' : '.min';
        }
        $cache_fix = apply_filters('wlr_load_asset_with_time', true);
        $add_cache_fix = ($cache_fix) ? '&t=' . time() : '';
        wp_register_style(WLL_PLUGIN_SLUG . '-wlr-font', WLR_PLUGIN_URL . 'Assets/Site/Css/wlr-fonts' . $suffix . '.css', array(), WLR_PLUGIN_VERSION . $add_cache_fix);
        wp_enqueue_style(WLL_PLUGIN_SLUG . '-wlr-font');
        wp_register_style(WLL_PLUGIN_SLUG . '-wlr-launcher', WLL_PLUGIN_URL . 'V2/Assets/Site/Css/launcher_site_ui.css', array(), WLR_PLUGIN_VERSION . $add_cache_fix);
        wp_enqueue_style(WLL_PLUGIN_SLUG . '-wlr-launcher');
        $common_path = WLL_PLUGIN_DIR . '/V2/launcher-site-ui/dist';
        $js_files = self::$woocommerce->getDirFileLists($common_path);
        $localize_name = "";
        foreach ($js_files as $file) {
            $path = str_replace(WLR_PLUGIN_PATH, '', $file);
            $js_file_name = str_replace($common_path . '/', '', $file);
            $js_name = WLR_PLUGIN_SLUG . '-react-ui-' . substr($js_file_name, 0, -3);
            $js_file_url = WLR_PLUGIN_URL . $path;
            if ($js_file_name == 'bundle.js') {
                $localize_name = $js_name;
                wp_register_script($js_name, $js_file_url, array('jquery'), WLR_PLUGIN_VERSION . $add_cache_fix);
                wp_enqueue_script($js_name);
            }
        }
        $localize = array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'render_page_nonce' => wp_create_nonce('render_page_nonce'),
            'wlr_redeem_nonce' => wp_create_nonce('wlr_redeem_nonce'),
            'wlr_reward_nonce' => wp_create_nonce('wlr_reward_nonce'),
            'apply_share_nonce' => wp_create_nonce('wlr_social_share_nonce'),
            'revoke_coupon_nonce' => wp_create_nonce('wlr_revoke_coupon_nonce'),

        );
        wp_localize_script($localize_name, 'wll_localize_data', $localize);
    }

    /**
     * @return void
     */
    public function getLauncherWidget()
    {
        $args = array(
            'style' => self::$template->setData(WLL_PLUGIN_DIR . '/V2/Assets/Site/Css/launcher_site.css', array())->render(),
        );
        $args = apply_filters("wll_before_launcher_site_page", $args);
        $path = WLL_PLUGIN_DIR . '/V2/App/Views/Site/main_site.php';
        echo apply_filters('wll_launcher_widget', self::$template->setData($path, $args)->render(), $args);
    }

    public function launcherWidgetData()
    {
        $response = array(
            'success' => false,
            'data' => array(),
        );
        if (!$this->getRenderPageNonceCheck()) {
            $response["data"]["message"] = __("Security check failed.", "wp-loyalty-rules");
            wp_send_json($response);
        }
        //design
        $design_settings = $this->getDesignSettings();
        //content admin side translated values fetch
        $guest_base = new Guest();
        $guest_content = $guest_base->getGuestContentData(false);
        $member_base = new Member();
        $member_content = $member_base->getMemberContentData(false);
        $content_settings = array('content' => array_merge($guest_content, $member_content));
        //popup button
        $popup_button_settings = $this->getLauncherButtonContentData(false);
        $settings = array_merge($design_settings, $content_settings, $popup_button_settings);
        $settings['is_member'] = !empty(self::$woocommerce->get_login_user_email());
        $earn_campaign_helper = EarnCampaign::getInstance();
        $settings['is_pro'] = $earn_campaign_helper->isPro();
        $user = $this->getUserDetails();
        $settings['available_point'] = (isset($user) && isset($user->points) && !empty($user->points)) ? $user->points : 0;
        $settings['labels'] = array(
            'footer' => array(
                "powered_by" => __("Powered by", 'wp-loyalty-rules'),
                'launcher_power_by_url' => 'https://wployalty.net/?utm_campaign=wployalty-link&utm_medium=launcher&utm_source=powered_by',
                "title" => __("WPLoyalty", "wp-loyalty-rules"),
            ),
            'reward_text' => __("Rewards", 'wp-loyalty-rules'),
            'coupon_text' => __("Coupons", 'wp-loyalty-rules'),
            'loading_text' => __("Loading...", 'wp-loyalty-rules'),
            'loading_timer_text' => __("If loading takes a while, please refresh the screen...!", 'wp-loyalty-rules'),
            'reward_opportunity_text' => __('Reward Opportunities', 'wp-loyalty-rules'),
            'my_rewards_text' => __('My Rewards', 'wp-loyalty-rules'),
            'apply_button_text' => __('Apply', 'wp-loyalty-rules'),
        );
        $response["success"] = true;
        $response["data"] = $settings;
        wp_send_json($response);
    }
}