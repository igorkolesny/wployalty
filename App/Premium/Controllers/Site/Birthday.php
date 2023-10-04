<?php
/**
 * @author      Wployalty (Alagesan)
 * @license     http://www.gnu.org/licenses/gpl-2.0.html
 * @link        https://www.wployalty.net
 * */

namespace Wlr\App\Premium\Controllers\Site;

use Wlr\App\Controllers\Base;
use Wlr\App\Helpers\Woocommerce;
use Wlr\App\Models\Users;

defined('ABSPATH') or die;

class Birthday extends Base
{
    public static $birthday_campaigns = array();

    /* Birthday Start */
    function getPointBirthday($point, $rule, $data)
    {
        $birthday_helper = \Wlr\App\Premium\Helpers\Birthday::getInstance();
        return $birthday_helper->getTotalEarnPoint($point, $rule, $data);
    }

    function getCouponBirthday($point, $rule, $data)
    {
        $birthday_helper = \Wlr\App\Premium\Helpers\Birthday::getInstance();
        return $birthday_helper->getTotalEarnReward($point, $rule, $data);
    }

    function onBirthDayPoints()
    {
        global $wpdb;
        $user = new Users();
        $null_date = 0;
        $user_where = $wpdb->prepare('CASE WHEN birthday_date IS NOT NULL THEN MONTH(birthday_date) = %s AND DAY(birthday_date) = %s 
        WHEN birthday_date IS NULL THEN MONTH(from_unixtime(birth_date)) = %s AND DAY(from_unixtime(birth_date)) = %s AND birth_date > %s END', array(date('m'), date('d'), date('m'), date('d'), $null_date));
        //$user_where = $wpdb->prepare('MONTH(from_unixtime(birth_date)) = %s AND DAY(from_unixtime(birth_date)) = %s AND birth_date > %s', date('m'), date('d'), $null_date);
        $birth_users = $user->getWhere($user_where, '*', false);
        $birthday_helper = \Wlr\App\Premium\Helpers\Birthday::getInstance();
        foreach ($birth_users as $birth_user) {
            if (empty($birth_user->user_email)) {
                continue;
            }
            $action_data = array(
                'user_email' => $birth_user->user_email,
                'birthday_earn_type' => 'on_their_birthday'
            );
            $birthday_helper->applyEarnBirthday($action_data);
        }
    }

    function onUpdateBirthDay()
    {
        $wlr_nonce = (string)self::$input->post_get('wlr_nonce', '');
        if (!Woocommerce::verify_nonce($wlr_nonce, 'wlr_reward_nonce')) {
            $message = __('Invalid nonce', 'wp-loyalty-rules');
            $json = array(
                'success' => false,
                'message' => $message
            );
            wc_add_notice($message, 'error');
            wp_send_json_error($json);
        }
        $campaign_id = (int)self::$input->post_get('campaign_id', 0);
        if (empty($campaign_id)) {
            $message = __('Invalid Campaign', 'wp-loyalty-rules');
            $json = array(
                'success' => false,
                'message' => $message
            );
            wc_add_notice($message, 'error');
            wp_send_json_error($json);
        }
        $birthdate = (string)self::$input->post_get('birth_date', '');
        if (empty($birthdate)) {
            $message = __('Invalid Birthdate', 'wp-loyalty-rules');
            $json = array(
                'success' => false,
                'message' => $message
            );
            wc_add_notice($message, 'error');
            wp_send_json_error($json);
        }
        $user_email = self::$woocommerce->get_login_user_email();
        if (empty($user_email)) {
            $message = __('Invalid user', 'wp-loyalty-rules');
            $json = array(
                'success' => false,
                'message' => $message
            );
            wc_add_notice($message, 'error');
            wp_send_json_error($json);
        }
        $this->earnViaBirthdateUpdate($user_email, $birthdate, $campaign_id);
        $json = array(
            'success' => true,
        );
        wp_send_json_success($json);
    }
    /* Birthday End */

    /**
     * @param $user_email
     * @param $birthdate
     * @param $campaign_id
     * @return void
     */
    public function earnViaBirthdateUpdate($user_email, $birthdate, $campaign_id = 0)
    {
        if (empty($user_email) || empty($birthdate)) return;
        $user_table = new Users();
        $birthdate_helper = \Wlr\App\Premium\Helpers\Birthday::getInstance();
        $already_user = $birthdate_helper->getPointUserByEmail($user_email);
        if (empty($already_user)) {
            $id = 0;
            //Create New User
            $unique_refer_code = $birthdate_helper->get_unique_refer_code('', false, $user_email);
            $_data = array(
                'user_email' => sanitize_email($user_email),
                'points' => 0,
                'earn_total_point' => 0,
                'used_total_points' => 0,
                'refer_code' => $unique_refer_code,
                'birth_date' => self::$woocommerce->beforeSaveDate($birthdate, 'Y-m-d'),
                'birthday_date' => self::$woocommerce->convertDateFormat($birthdate),
                'created_date' => strtotime(date("Y-m-d h:i:s")),
            );
        } else {
            $id = $already_user->id;
            // update birthdate
            $_data = array(
                'birth_date' => self::$woocommerce->beforeSaveDate($birthdate, 'Y-m-d'),
                'birthday_date' => self::$woocommerce->convertDateFormat($birthdate),
                'level_id' => $already_user->level_id
            );
        }
        $user_table->insertOrUpdate($_data, $id);
        // add point or reward to that customer
        $action_data = array(
            'user_email' => $user_email,
            'birthday_earn_type' => 'update_birth_date',
            'campaign_id' => $campaign_id
        );
        $birthdate_helper->applyEarnBirthday($action_data);
    }

    function initProSchedule()
    {
        //every 3 hours
        $hook = 'wlr_birth_day_points';
        $timestamp = wp_next_scheduled($hook);
        if (false === $timestamp) {
            $scheduled_time = strtotime('+3 hours', current_time('timestamp'));
            wp_schedule_event($scheduled_time, 'hourly', $hook);
        }
    }

    /* Birthday End */

    function addCustomBirthdayField()
    {
        $options = self::$woocommerce->getOptions('wlr_settings', array());
        if (empty($options) || !is_array($options) || empty($options['birthday_display_place']) || !is_string($options['birthday_display_place'])) return;
        $display_places = explode(',', $options['birthday_display_place']);
        if (empty($display_places) || !is_array($display_places)) return;
        foreach ($display_places as $display_place) {
            switch ($display_place) {
                case 'checkout':
                    $this->addInCheckoutForm();
                    break;
                case 'registration':
                    $this->addInRegistrationForm();
                    break;
                case 'account_details':
                    $this->addInAccountDetailsPageForm();
                    break;
                default:
                    return;
            }
        }
        if (is_admin()) {
            add_action('show_user_profile', array($this, 'addBirthdayInProfile'));
        }

    }

    private function addInCheckoutForm()
    {
        /* check user email earned birthday point */
        $user_email = self::$woocommerce->get_login_user_email();
        $customer_page = new \Wlr\App\Controllers\Site\CustomerPage();
        $user = $customer_page->getPageUserDetails($user_email);
        $birthday_date = is_object($user) && isset($user->birthday_date) && !empty($user->birthday_date) && $user->birthday_date != '0000-00-00' ? $user->birthday_date : (is_object($user) && isset($user->birth_date) && !empty($user->birth_date) ? self::$woocommerce->beforeDisplayDate($user->birth_date, "Y-m-d") : '');
        if (empty($birthday_date) || $birthday_date == '0000-00-00') {
            /* Checkout form start*/
            add_action('woocommerce_after_checkout_billing_form', array($this, 'addBirthdayInCheckoutForm'), 10, 1);
            add_action('woocommerce_after_checkout_validation', array($this, 'validateBirthdayInCheckoutForm'), 10, 2);
            add_action('woocommerce_checkout_create_order', array($this, 'saveBirthdayInCheckoutForm'), 10, 2);
            /* Checkout form end*/
        }
    }

    private function addInRegistrationForm()
    {
        /* Registration form start */
        add_action('woocommerce_register_form', array($this, 'addBirthdayInRegisterForm'));
        add_action('woocommerce_register_post', array($this, 'validateBirthdayInRegisterForm'), 10, 3);
        add_action('woocommerce_created_customer', array($this, 'saveBirthdayInRegisterForm'), 10, 3);
        /* Registration form end */
    }

    private function addInAccountDetailsPageForm()
    {
        /* Account details form start */
        add_action('woocommerce_edit_account_form', array($this, 'addBirthdayInAccountDetailsForm'));
        add_action('woocommerce_save_account_details_errors', array($this, 'validateBirthdayInAccountDetailsForm'));
        add_action('woocommerce_save_account_details', array($this, 'saveBirthdayInAccountDetailsForm'));
        /* Account details form start */
    }

    function addBirthdayInProfile($user)
    {
        if (empty($user) || !is_object($user) || empty($user->ID)) return '';
        $user_model = new \Wlr\App\Models\Users();
        $user_data = $user_model->getByKey($user->ID);
        if (empty($user_data) || !is_object($user_data) || !isset($user_data->birth_date)) return '';
        $dob = !empty($user_data->birth_date) ? self::$woocommerce->beforeDisplayDate($user_data->birth_date, 'Y-m-d') : "";
        $validate_date = self::validateDateORNull($dob);
        if ($validate_date) {
            ?>
            <h3><?php _e('Extra account details', 'wp-loyalty-rules'); ?></h3>
            <table class="form-table">
                <tr>
                    <th><label for="birthday_date"><?php _e('Date of birth', 'wp-loyalty-rules'); ?></label></th>
                    <td>
                        <p><?php echo esc_html($dob); ?></p>
                    </td>
                </tr>
            </table>
            <?php
        }
    }

    static function validateDateORNull($value)
    {
        if (empty($value) || in_array($value, array('', null, 0, '-'))) {
            return true;
        }
        $is_date = false;
        if ($value instanceof \DateTime) {
            $is_date = true;
        } else {
            $is_date = (strtotime($value) !== false);
        }
        return $is_date;
    }

    function addBirthdayInRegisterForm()
    {
        woocommerce_form_field('wlr_dob', array(
            'label' => __('Birthday', 'wp-loyalty-rules'),
            'required' => false,
            'class' => array('form-row-wide'),
            'type' => 'date',
            'custom_attributes' => array(
                'max' => date('Y-m-d'),
            ),
        ));
    }

    function validateBirthdayInRegisterForm($username, $user_email, $errors)
    {
        if (empty($username) || empty($user_email)) return $errors;
        $dob = self::$input->post_get('wlr_dob', '');
        $validate_date = self::validateDateORNull($dob);
        if (!$validate_date) {
            $errors->add('dob_error', __('Birthday date is required!', 'wp-loyalty-rules'));
        }
        return $errors;
    }

    function saveBirthdayInRegisterForm($customer_id, $new_customer_data, $password_generated)
    {
        if (empty($customer_id)) return;
        $dob = self::$input->post_get('wlr_dob', '');
        $user_email = self::$input->post_get('email', '');
        $validate_date = self::validateDateORNull($dob);
        if (!empty($dob) && $validate_date && !empty($user_email) && $customer_id > 0) {
            $this->saveBirthdayDateAction($dob, $user_email, $customer_id);
        }
    }

    function saveBirthdayDateAction($dob, $user_email, $user_id)
    {
        $validate_date = self::validateDateORNull($dob);
        if ($user_id > 0 && !empty($user_email) && $validate_date === true) {
            $user = $this->getUserdata($user_email);
            if (!empty($user) && is_object($user) && isset($user->id) && $user->id > 0) {
                $user_modal = new Users();
                $user_modal->updateRow(array(
                    'birth_date' => self::$woocommerce->beforeSaveDate($dob, 'Y-m-d'),
                    'birthday_date' => self::$woocommerce->convertDateFormat($dob),
                ), array('user_email' => $user_email));
            }
            $this->earnBirthdayPoint($user_email, $dob);
            $dob = self::$woocommerce->convertDateFormat($dob);
            update_user_meta($user_id, 'wlr_dob', $dob);
        }
    }

    function getUserdata($user_email)
    {
        if (empty($user_email)) return new \stdClass();
        $user_model = new Users();
        global $wpdb;
        $where = $wpdb->prepare('user_email = %s', array(sanitize_email($user_email)));
        return $user_model->getWhere($where, '*', true);
    }

    /**
     * @param $user_email
     * @param $dob
     * @return void
     */
    public function earnBirthdayPoint($user_email, $dob)
    {
        if (empty($user_email) || empty($dob)) return;
        $birthday_helper = new \Wlr\App\Premium\Controllers\Site\Birthday();
        $birthday_campaigns = $this->getBirthdayCampaigns();
        if (empty($birthday_campaigns) || !is_array($birthday_campaigns)) return;
        foreach ($birthday_campaigns as $birthday_campaign) {
            $campaign_id = is_object($birthday_campaign) && isset($birthday_campaign->id) && $birthday_campaign->id > 0 ? $birthday_campaign->id : 0;
            $birthday_helper->earnViaBirthdateUpdate($user_email, $dob, $campaign_id);
        }
    }

    function getBirthdayCampaigns()
    {
        if (!empty(self::$birthday_campaigns)) return self::$birthday_campaigns;
        $earn_campaign_model = new \Wlr\App\Models\EarnCampaign();
        $campaigns = $earn_campaign_model->getCampaignByAction('birthday');
        $update_birthday_campaigns = array();
        if (empty($campaigns)) return self::$birthday_campaigns;
        foreach ($campaigns as $campaign) {
            if (empty($campaign) || !is_object($campaign) || empty($campaign->point_rule)) continue;
            $point_rule = self::$woocommerce->isJson($campaign->point_rule) ? json_decode($campaign->point_rule) : new \stdClass();
            if (isset($point_rule->birthday_earn_type) && !empty($point_rule->birthday_earn_type) && ($point_rule->birthday_earn_type === 'update_birth_date')) {
                $update_birthday_campaigns[] = $campaign;
            }
        }
        return self::$birthday_campaigns = $update_birthday_campaigns;
    }

    function addBirthdayInCheckoutForm($checkout)
    {
        if (empty($checkout) || !is_object($checkout)) return;
        $billing_email = $checkout->get_value('billing_email');
        $dob = $checkout->get_value('wlr_dob');
        $dob = $this->getUserBirthday($billing_email, $dob);
        woocommerce_form_field('wlr_dob', array(
            'type' => 'date',
            'required' => false,
            'class' => array('form-row-wide'),
            'label' => __('Birthday', 'wp-loyalty-rules'),
            'custom_attributes' => array(
                'max' => date('Y-m-d'),
            ),
        ), $dob);

    }

    /**
     * @param $billing_email
     * @param $dob
     * @return mixed
     */
    public function getUserBirthday($billing_email, $dob)
    {
        if (empty($billing_email)) {
            return $dob;
        }
        $user = $this->getUserdata($billing_email);
        if (!empty($user) && is_object($user) && isset($user->birthday_date) && ($user->birthday_date != '0000-00-00')) {
            return $user->birthday_date;
        }
        $user_data = get_user_by('email', $billing_email);
        if (is_object($user_data) && isset($user_data->ID)) {
            $user_dob = get_user_meta($user_data->ID, 'wlr_dob', true);
            return !empty($user_dob) ? $user_dob : $dob;
        }
        return $dob;
    }

    function validateBirthdayInCheckoutForm($fields, $errors)
    {
        $dob = self::$input->post_get('wlr_dob', '');
        $validate_date = self::validateDateORNull($dob);
        if (!$validate_date) {
            $errors->add('dob_error', __('Birthday date is required!', 'wp-loyalty-rules'));
        }
        return $errors;
    }

    function saveBirthdayInCheckoutForm($order, $data)
    {
        $dob = self::$input->post_get('wlr_dob', '');
        $validate_date = self::validateDateORNull($dob);
        $user_email = isset($data['billing_email']) && !empty($data['billing_email']) ? $data['billing_email'] : "";
        if (!empty($dob) && $validate_date === true && !empty($user_email)) {
            $user = get_user_by('email', $user_email);
            if (!empty($user) && is_object($user) && isset($user->ID) && $user->ID > 0) {
                $this->saveBirthdayDateAction($dob, $user_email, $user->ID);
            }
            $dob = self::$woocommerce->convertDateFormat($dob);
            $order->update_meta_data('wlr_dob', $dob);
        }
    }

    function addBirthdayInAccountDetailsForm()
    {
        $user_email = self::$woocommerce->get_login_user_email();
        $dob = $this->getUserBirthday($user_email, '');
        $validate_date = self::validateDateORNull($dob);
        if (!$validate_date) return;
        woocommerce_form_field('wlr_dob', array(
            'label' => __('Birthday', 'wp-loyalty-rules'),
            'type' => 'date',
            'required' => false,
            'class' => array('form-row-wide'),
            'custom_attributes' => array(
                'max' => date('Y-m-d'),
            ),
        ), $dob);
    }

    function validateBirthdayInAccountDetailsForm($args)
    {
        $dob = self::$input->post_get('wlr_dob', '');
        $validate_date = self::validateDateORNull($dob);
        if (!$validate_date) {
            $args->add('dob_error', __('Birthday date is required!', 'wp-loyalty-rules'));
        }
        return $args;
    }

    function saveBirthdayInAccountDetailsForm($user_id)
    {
        $dob = self::$input->post_get('wlr_dob', '');
        $user_email = self::$woocommerce->get_email_by_id($user_id);
        $validate_date = self::validateDateORNull($dob);
        if (!empty($dob) && $validate_date === true && !empty($user_email)) {
            $this->saveBirthdayDateAction($dob, $user_email, $user_id);
        }
    }
}