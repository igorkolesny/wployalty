<?php
/**
 * @author      Wployalty (Alagesan)
 * @license     http://www.gnu.org/licenses/gpl-3.0.html
 * @link        https://www.wployalty.net
 * */

namespace Wlr\App\Premium\Controllers\Admin;
defined('ABSPATH') or die;

use Wlr\App\Controllers\Base;
use Wlr\App\Helpers\CsvHelper;
use Wlr\App\Helpers\Validation;
use Wlr\App\Helpers\Woocommerce;
use Wlr\App\Models\Levels;
use Wlr\App\Models\Users;
use Wlr\App\Premium\Helpers\License;

class Main extends Base
{
    function proLocalData($localize)
    {
        $available_payment_methods = \Wlr\App\Helpers\Woocommerce::getInstance()->getPaymentMethod();
        $payment_method_list = array();
        foreach ($available_payment_methods as $key => $value) {
            $payment_method_list[] = array(
                'label' => $value['text'],
                'value' => $value['id']
            );
        }
        $localize['payment_method'] = array(
            'payment_method_list' => $payment_method_list,
        );

        $language_list = array(
            'en_US'
        );
        $available_language = get_available_languages();
        foreach ($available_language as $langu) {
            $language_list[] = $langu;
        }
        $localize['language'] = array(
            'language_list' => $language_list
        );
        $currency_list = get_woocommerce_currencies();
        $localize['currency'] = array(
            'currency_list' => $currency_list
        );
        $order_status = \Wlr\App\Helpers\Woocommerce::getInstance()->get_order_statuses();
        $order_status_list = array();
        foreach ($order_status as $key => $value) {
            $order_status_list[] = array(
                'label' => $value,
                'value' => $key
            );
        }


        $localize['order_status'] = array(
            'order_status_list' => $order_status_list,
        );
        $localize['apps'] = array(
            'wlr_app_nonce' => Woocommerce::create_nonce('wlr_app_nonce'),
        );
        $level_model = new Levels();
        $where = 'active=1';
        $levels = $level_model->getWhere($where, '*', false);
        $level_list = array();
        foreach ($levels as $level) {
            $level_list[] = array(
                'label' => $level->name,
                'value' => $level->id
            );
        }
        $localize['level'] = array(
            'level_list' => $level_list,
        );
        $localize['levels'] = array(
            'levels_nonce' => Woocommerce::create_nonce('levels_nonce'),
            'level_popup_nonce' => Woocommerce::create_nonce('level_popup_nonce'),
            'level_save_nonce' => Woocommerce::create_nonce('level_save_level'),
            'level_delete_title' => __('Delete Level', 'wp-loyalty-rules'),
            'level_delete_content' => __('Are you sure ?', 'wp-loyalty-rules'),
            'level_delete_none' => Woocommerce::create_nonce('level_delete_none'),
            'level_update_nonce' => Woocommerce::create_nonce('level_update_nonce'),
            'level_multi_delete_nonce' => Woocommerce::create_nonce('level_multi_delete_nonce'),
            'level_active_nonce' => Woocommerce::create_nonce('level_active_nonce'),
        );
        return $localize;
    }

    /**
     * Validate the license key.
     */
    public function validateLicense()
    {
        $wlr_nonce = (string)self::$input->post_get('wlr_nonce', '');
        $message = __('Invalid license key.', 'wp-loyalty-rules');
        if (Woocommerce::hasAdminPrivilege() && Woocommerce::verify_nonce($wlr_nonce, 'wlr_common_user_nonce')) {
            $license_key = sanitize_text_field($_POST['license']);
            $license = License::getInstance();
            $status = $license->activateLicense($license_key);
            $message = $license->getMessage($status);
            if (in_array($status, ['valid', 'activated'])) {
                wp_send_json_success(['message' => $message]);
            }
        }
        wp_send_json_error(['message' => $message]);
    }

    public function isValidLicense($is_valid, $data)
    {
        $wlr_nonce = (string)self::$input->post_get('wlr_nonce', '');
        if (Woocommerce::hasAdminPrivilege() && Woocommerce::verify_nonce($wlr_nonce, 'wlr_setting_nonce')) {
            $license_key = isset($data['license_key']) && !empty($data['license_key']) ? $data['license_key'] : '';
            $license = License::getInstance();
            $licens_data = get_option(WLR_PLUGIN_PREFIX . 'license', array(
                'key' => null,
                'status' => null,
                'expires' => null,
            ));
            $licens_data['key'] = $license_key;
            update_option(WLR_PLUGIN_PREFIX . 'license', $licens_data);
            $status = $license->checkLicense();
            if ($status === 'valid') {
                $is_valid = true;
            } else {
                $is_valid = false;
            }
        }
        return $is_valid;
    }

    public function changeSettingData($data)
    {
        $wlr_nonce = (string)self::$input->post_get('wlr_nonce', '');
        if (Woocommerce::hasAdminPrivilege() && Woocommerce::verify_nonce($wlr_nonce, 'wlr_setting_nonce')) {
            $data['license_key'] = License::getKey();
            $data['license_key_valid'] = License::isActive();
        }
        return $data;
    }

    public function getAppView()
    {
        $wlr_nonce = (string)self::$input->post_get('wlr_nonce', '');
        $app_data = array();
        /* $app_data['unique_key'] = array(
             'name' => __('App Title'),
             'description' => __('Description'),
             'app_version' => '1.0.0',
             'app_image' => 'image path',
             'activate_url' => 'url',
             'deactivate_url' => 'url',
             'page_url' => 'page url'
         );*/
        if (Woocommerce::hasAdminPrivilege() && Woocommerce::verify_nonce($wlr_nonce, 'wlr_app_nonce')) {
            $app_data = apply_filters('wlr_app_view_data', $app_data);
        }
        wp_send_json($app_data);
    }

    /* levels */

}