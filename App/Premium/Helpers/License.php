<?php
/**
 * @author      Wployalty (Alagesan)
 * @license     http://www.gnu.org/licenses/gpl-2.0.html
 * @link        https://www.wployalty.net
 * */

namespace Wlr\App\Premium\Helpers;

defined('ABSPATH') or die();

class License
{
    public static $instance = null;
    /**
     * Settings URL.
     * @var string
     */
    private static $settings_url = 'admin.php?page=wp-loyalty-rules#/settings';

    public static function getInstance(array $config = array())
    {
        if (!self::$instance) {
            self::$instance = new self($config);
        }
        return self::$instance;
    }

    /**
     * Get License Status.
     * @return string|null status
     */
    public static function getStatus()
    {
        $license = self::getData();
        return $license['status'];
    }

    /**
     * Get License Data.
     * @return array|null data
     */
    public static function getData()
    {
        return get_option(WLR_PLUGIN_PREFIX . 'license', array(
            'key' => null,
            'status' => null,
            'expires' => null,
        ));
    }

    /**
     * Check if the license is active.
     * @return bool
     */
    public static function isActive()
    {
        $license = self::getData();
        return !empty($license['key']) && $license['status'] === 'valid';
    }

    /**
     * Get License detailed message from status.
     */
    public static function getMessage($status = '')
    {
        switch ($status) {
            case 'activated':
                $message = __('License activated successfully. Thank you!', 'wp-loyalty-rules');
                break;
            case 'deactivated':
                $message = __('License deactivated successfully.', 'wp-loyalty-rules');
                break;
            case 'valid':
                $message = __('License verified successfully.', 'wp-loyalty-rules');
                break;
            case 'invalid':
            case 'site_inactive':
                $message = __('Your license is not active for this URL.', 'wp-loyalty-rules');
                break;
            case 'revoked':
            case 'disabled':
                $message = __('Your license key has been disabled.', 'wp-loyalty-rules');
                break;
            case 'missing':
                $message = __('Invalid license key.', 'wp-loyalty-rules');
                break;
            case 'expired':
                if (self::getExpires()) {
                    /* translators: %s is replaced with expired date */
                    $message = sprintf(__('Your license key expired on %s.', 'wp-loyalty-rules'),
                        date_i18n(get_option('date_format'), strtotime(self::getExpires(), current_time('timestamp')))
                    );
                } else {
                    $message = __('Your license key has been expired.', 'wp-loyalty-rules');
                }
                break;
            case 'invalid_item_id':
            case 'item_name_mismatch':
                /* translators: %s is replaced with license item name */
                $message = sprintf(__('This appears to be an invalid license key for %s.', 'wp-loyalty-rules'), WLR_LICENSE_ITEM_NAME);
                break;
            case 'no_activations_left':
                $message = __('Your license key has reached its activation limit.', 'wp-loyalty-rules');
                break;
            case 'wp_error':
                $message = __('Unable to connect our server, please try again.', 'wp-loyalty-rules');
                break;
            case 'error':
                $message = __('An error occurred, please try again.', 'wp-loyalty-rules');
                break;
            default:
                $message = __('Something went wrong, please try again.', 'wp-loyalty-rules');
        }

        return $message;
    }

    /**
     * Get License Expires.
     * @return string|null datetime
     */
    public static function getExpires()
    {
        $license = self::getData();
        return $license['expires'];
    }

    /**
     * To display activation request message in plugins page.
     */
    public static function activationNotice()
    {
        add_action('admin_notices', function () {
            $html_prefix = '<div class="notice notice-warning">';
            $message = '<p><strong>' . esc_html(WLR_PLUGIN_NAME) . ' - </strong>';
            $message .= esc_html__("Make sure to activate your license to receive updates, support and security fixes!", 'wp-loyalty-rules') . '</p>';
            $message .= '<p>';
            $message .= '<a href="' . esc_url(self::$settings_url) . '" class="button-secondary">';
            $message .= esc_html__("Enter license key", 'wp-loyalty-rules') . '</a>';
            $message .= '<a href="' . esc_url(WLR_LICENSE_ITEM_URL) . '" target="_blank" class="button-primary" style="margin-left: 12px;">';
            $message .= esc_html__("Get License", 'wp-loyalty-rules') . '</a>';
            $message .= '</p>';
            $html_suffix = '</div>';
            echo $html_prefix . $message . $html_suffix;
        }, 1);
    }

    /**
     * Activates the license key.
     *
     * @param string $key
     * @return string status
     */
    public function activateLicense($key)
    {
        // Data to send in our API request
        $api_params = array(
            'edd_action' => 'activate_license',
            'license' => $key,
            'item_id' => WLR_LICENSE_ITEM_ID,
            'item_name' => rawurlencode(WLR_LICENSE_ITEM_NAME),
            'url' => home_url(),
            'environment' => function_exists('wp_get_environment_type') ? wp_get_environment_type() : 'production',
        );

        // Call the custom API.
        $response = wp_remote_post(
            WLR_LICENSE_STORE_URL,
            array(
                'timeout' => 15,
                'sslverify' => false,
                'body' => $api_params,
            )
        );

        // Make sure the response came back okay
        if (is_wp_error($response) || 200 !== wp_remote_retrieve_response_code($response)) {
            if (is_wp_error($response)) {
                $message = 'wp_error';
            } else {
                $message = 'error';
            }
        } else {
            $license_data = json_decode(wp_remote_retrieve_body($response));
            if (false === $license_data->success) {
                $message = $license_data->error;
            }
        }

        $status = isset($license_data->license) ? $license_data->license : null;
        $expires = isset($license_data->expires) ? $license_data->expires : null;

        // Check if anything passed on a message constituting a failure
        if (!empty($message)) {
            update_option(WLR_PLUGIN_PREFIX . 'license', array(
                'key' => null,
                'status' => $status,
                'expires' => $expires,
            ));
            return $message;
        }

        if ('valid' === $license_data->license) {
            update_option(WLR_PLUGIN_PREFIX . 'license', array(
                'key' => $key,
                'status' => $status,
                'expires' => $expires,
            ));
            return 'activated';
        }

        return 'failed';
    }

    /**
     * Checks if a license key is still valid.
     *
     * @return string valid|invalid|wp_error
     */
    public function checkLicense()
    {
        // Data to send in our API request
        $api_params = array(
            'edd_action' => 'check_license',
            'license' => self::getKey(),
            'item_id' => WLR_LICENSE_ITEM_ID,
            'item_name' => rawurlencode(WLR_LICENSE_ITEM_NAME),
            'url' => home_url(),
            'environment' => function_exists('wp_get_environment_type') ? wp_get_environment_type() : 'production',
        );

        // Call the custom API.
        $response = wp_remote_post(
            WLR_LICENSE_STORE_URL,
            array(
                'timeout' => 15,
                'sslverify' => false,
                'body' => $api_params,
            )
        );

        if (is_wp_error($response)) {
            return 'wp_error';
        }

        $license_data = json_decode(wp_remote_retrieve_body($response));
        if ('valid' === $license_data->license) {
            return 'valid';
        }

        return 'invalid';
    }

    /**
     * Get License Key.
     * @return string|null key
     */
    public static function getKey()
    {
        $license = self::getData();
        return $license['key'];
    }

    /**
     * Deactivates the license key.
     * This will decrease the site count.
     *
     * @return string status
     */
    public function deactivateLicense()
    {
        // Data to send in our API request
        $api_params = array(
            'edd_action' => 'deactivate_license',
            'license' => self::getKey(),
            'item_id' => WLR_LICENSE_ITEM_ID,
            'item_name' => rawurlencode(WLR_LICENSE_ITEM_NAME),
            'url' => home_url(),
            'environment' => function_exists('wp_get_environment_type') ? wp_get_environment_type() : 'production',
        );

        // Call the custom API.
        $response = wp_remote_post(
            WLR_LICENSE_STORE_URL,
            array(
                'timeout' => 15,
                'sslverify' => false,
                'body' => $api_params,
            )
        );

        // Make sure the response came back okay
        if (is_wp_error($response) || 200 !== wp_remote_retrieve_response_code($response)) {
            if (is_wp_error($response)) {
                return 'wp_error';
            } else {
                return 'error';
            }
        }

        $license_data = json_decode(wp_remote_retrieve_body($response));

        // $license_data->license will be either "deactivated" or "failed"
        if ('deactivated' === $license_data->license) {
            $license = self::getData();
            update_option(WLR_PLUGIN_PREFIX . 'license', array(
                'key' => $license['key'],
                'status' => 'deactivated',
                'expires' => $license['expires'],
            ));
            return 'deactivated';
        }

        return 'failed';
    }

    /**
     * Initialize the updater.
     */
    public function initializeUpdater()
    {
        // To support auto-updates, this needs to run during the wp_version_check cron job for privileged users.
        $doing_cron = defined('DOING_CRON') && DOING_CRON;
        if (!current_user_can('manage_options') && !$doing_cron) {
            return;
        }

        // Set up the updater.
        $updater = new Update(
            WLR_LICENSE_STORE_URL,
            WLR_PLUGIN_FILE,
            array(
                'version' => WLR_PLUGIN_VERSION,
                'license' => self::getKey(),
                'item_id' => WLR_LICENSE_ITEM_ID,
                'author' => 'Flycart',
                'beta' => false,
            )
        );
    }
}
