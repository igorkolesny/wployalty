<?php
/**
 * @author      Wployalty (Alagesan)
 * @license     http://www.gnu.org/licenses/gpl-2.0.html
 * @link        https://www.wployalty.net
 * */

namespace Wlr\App\Controllers\Site;

use Wlr\App\Controllers\Base;
use Wlr\App\Helpers\EarnCampaign;
use Wlr\App\Models\Logs;
use Wlr\App\Models\Rewards;
use Wlr\App\Models\Users;

defined('ABSPATH') or die;

class CustomerPage extends Base
{
    function rewardPageData($page_type = '')
    {
        if (!$this->canShowRewardPage($page_type)) return array();
        $page_params = array();
        $user_email = self::$woocommerce->get_login_user_email();
        $is_guest_user = empty($user_email);
        $earn_campaign_helper = EarnCampaign::getInstance();
        $setting_option = self::$woocommerce->getOptions('wlr_settings');
        if ($page_type != 'cart') {
            $is_campaign_display = is_array($setting_option) && isset($setting_option['is_campaign_display']) && in_array($setting_option['is_campaign_display'], array('no', 'yes')) ? $setting_option['is_campaign_display'] : 'yes';
            if ($is_campaign_display === 'yes') $page_params['campaign_list'] = $this->getCampaignList($is_guest_user);
            $is_reward_display = is_array($setting_option) && isset($setting_option['is_reward_display']) && in_array($setting_option['is_reward_display'], array('no', 'yes')) ? $setting_option['is_reward_display'] : 'no';
            if ($is_reward_display === 'yes') $page_params['reward_list'] = $this->getRewardList($is_guest_user);
            $is_transaction_display = is_array($setting_option) && isset($setting_option['is_transaction_display']) && in_array($setting_option['is_transaction_display'], array('no', 'yes')) ? $setting_option['is_transaction_display'] : 'yes';
            if ($is_transaction_display === 'yes' && !$is_guest_user) $page_params['trans_details'] = $this->getTransactionDetails($user_email);
            $earn_campaign_model = new \Wlr\App\Models\EarnCampaign();
            $referral_campaigns = $earn_campaign_model->getCampaignByAction('referral');
            $page_params['is_referral_action_available'] = !$is_guest_user && !empty($referral_campaigns);
            if (!$is_guest_user && $page_params['is_referral_action_available']) {
                $page_params['referral_url'] = $earn_campaign_helper->getReferralUrl();
                if (!empty($page_params['referral_url'])) $page_params['social_share_list'] = $this->getSocialShareList($user_email, $page_params['referral_url']);
            }
        }
        if (!$is_guest_user) $page_params['user_rewards'] = $this->getPageUserRewards($user_email);
        $page_params['is_revert_enabled'] = false;
        if (isset($setting_option['is_revert_enabled']) && !empty($setting_option['is_revert_enabled']) && $setting_option['is_revert_enabled'] == 'yes') {
            $page_params['is_revert_enabled'] = true;
        }
        if (!$is_guest_user) {
            $page_params['user'] = $this->getPageUserDetails($user_email);
            $page_params['is_sent_email_display'] = is_array($setting_option) && isset($setting_option['is_sent_email_display']) && in_array($setting_option['is_sent_email_display'], array('no', 'yes')) ? $setting_option['is_sent_email_display'] : 'yes';
        }
        $page_params['branding'] = $this->getBrandingData();
        return apply_filters('wlr_myaccount_page_data', $page_params);
    }

    function canShowRewardPage($page_type)
    {
        $status = false;
        if (is_string($page_type) && !empty($page_type) && in_array($page_type, $this->getValidPageTypes())) {
            $status = true;
            $user_email = self::$woocommerce->get_login_user_email();
            if (empty($user_email) && $page_type == 'myaccount') {
                $status = false;
            }
        }
        return apply_filters('wlr_can_show_reward_page', $status, $page_type);
    }

    function getValidPageTypes()
    {
        $valid_page_types = array('myaccount', 'cart', 'page');
        return apply_filters('wlr_valid_customer_page_types', $valid_page_types);
    }

    /**
     * Campaign list
     * @param $is_guest_user
     * @return array|mixed|null
     */
    function getCampaignList($is_guest_user)
    {
        if (!is_bool($is_guest_user)) return array();
        $campaign_reward = new \Wlr\App\Models\EarnCampaign();
        $campaign_list = $campaign_reward->getCurrentCampaignList();
        $setting_option = self::$woocommerce->getOptions('wlr_settings');
        $is_campaign_point_display = is_array($setting_option) && isset($setting_option['is_campaign_point_display']) && in_array($setting_option['is_campaign_point_display'], array('no', 'yes')) ? $setting_option['is_campaign_point_display'] : 'yes';
        if ($is_campaign_point_display == 'yes') {
            foreach ($campaign_list as &$active_campaigns) {
                $active_campaigns = $this->getCampaignPointReward($active_campaigns);
            }
        }
        return apply_filters('wlr_page_campaign_list', $campaign_list, $is_guest_user);
    }

    function getCampaignPointReward($active_campaigns)
    {
        $base_helper = new \Wlr\App\Helpers\Base();
        $reward_table = new \Wlr\App\Models\Rewards();
        if (!empty($active_campaigns) && is_object($active_campaigns)) {
            $campaign_point_rule = self::$woocommerce->isJson($active_campaigns->point_rule) ? json_decode($active_campaigns->point_rule) : new \stdClass();
            $active_campaigns->campaign_title_discount = "";
            $action_type = isset($active_campaigns->action_type) && !empty($active_campaigns->action_type) ? $active_campaigns->action_type : '';
            switch ($action_type) {
                case "referral":
                    $active_campaigns->campaign_title_discount .= $this->getReferralPointMessage($campaign_point_rule);
                    break;
                default:
                    $campaign_type = isset($active_campaigns->campaign_type) && !empty($active_campaigns->campaign_type) && $active_campaigns->campaign_type ? $active_campaigns->campaign_type : '';
                    if ($campaign_type == "point" && isset($campaign_point_rule->earn_point)) {
                        $active_campaigns->campaign_title_discount .= isset($active_campaigns->action_type) && ($active_campaigns->action_type == 'point_for_purchase') ?
                            sprintf(__('%d %s for each %s spent', 'wp-loyalty-rules'), $campaign_point_rule->earn_point, $base_helper->getPointLabel($campaign_point_rule->earn_point), self::$woocommerce->getCustomPrice($campaign_point_rule->wlr_point_earn_price)) :
                            sprintf('+%d %s', $campaign_point_rule->earn_point, $base_helper->getPointLabel($campaign_point_rule->earn_point));
                    } elseif ($campaign_type == "coupon" && isset($campaign_point_rule->earn_reward)) {
                        $reward = !empty($campaign_point_rule->earn_reward) ? $reward_table->findReward((int)$campaign_point_rule->earn_reward) : "";
                        $discount_type = isset($reward->discount_type) && !empty($reward->discount_type) ? $reward->discount_type : "";
                        $point_label = $this->getDiscountRewardLabel($discount_type, $reward);
                        $reward_label = $base_helper->getRewardLabel(1);
                        if (!empty($reward)) $active_campaigns->campaign_title_discount .= isset($reward->discount_value) && !empty($reward->discount_value) ? sprintf(__('%s %s', 'wp-loyalty-rules'), $point_label, $reward_label) : "";
                    }
            }
        }
        return apply_filters("wlr_alter_campaign_selected_data", $active_campaigns);
    }

    function getReferralPointMessage($campaign_point_rule)
    {
        if (!$this->checkBasicReferralCheck($campaign_point_rule)) {
            return '';
        }
        $discount_title = $this->getAdvocateDiscountMessage($campaign_point_rule);
        $discount_title .= " <br> " . $this->getFriendDiscountMessage($campaign_point_rule);
        return $discount_title;
    }

    function checkBasicReferralCheck($campaign_point_rule)
    {
        if (empty($campaign_point_rule) || !is_object($campaign_point_rule)) {
            return false;
        }
        return true;
    }

    function getAdvocateDiscountMessage($campaign_point_rule)
    {
        if (!$this->checkBasicReferralCheck($campaign_point_rule)) {
            return '';
        }
        $base_helper = new \Wlr\App\Helpers\Base();
        $reward_table = new \Wlr\App\Models\Rewards();
        $discount_title = "";
        $advocate_type = isset($campaign_point_rule->advocate) && isset($campaign_point_rule->advocate->campaign_type) && !empty($campaign_point_rule->advocate->campaign_type) ? $campaign_point_rule->advocate->campaign_type : '';
        if ($advocate_type == "point") {
            $earn_point = isset($campaign_point_rule->advocate->earn_point) && !empty($campaign_point_rule->advocate->earn_point) ? $campaign_point_rule->advocate->earn_point : 0;
            $point_label = isset($campaign_point_rule->advocate->earn_type) && ($campaign_point_rule->advocate->earn_type == 'subtotal_percentage') && !empty($earn_point) ? round($earn_point) . "%" : $earn_point;
            $discount_title = !empty($earn_point) ? sprintf(__('You get %s : %s', 'wp-loyalty-rules'), $base_helper->getPointLabel($campaign_point_rule->advocate->earn_point), $point_label) : "";
        } elseif ($advocate_type == "coupon") {
            $advocate_reward = isset($campaign_point_rule->advocate->earn_reward) && !empty($campaign_point_rule->advocate->earn_reward) ? $reward_table->findReward((int)$campaign_point_rule->advocate->earn_reward) : "";
            $discount_type = isset($advocate_reward->discount_type) && !empty($advocate_reward->discount_type) ? $advocate_reward->discount_type : '';
            $point_label = $this->getDiscountRewardLabel($discount_type, $advocate_reward);
            $reward_label = $base_helper->getRewardLabel(1);
            if (!empty($advocate_reward)) $discount_title = !empty($point_label) ? sprintf(__('You get %s: %s discount', 'wp-loyalty-rules'), $reward_label, $point_label) : "";
        }
        return $discount_title;
    }

    /**
     * @param $discount_type
     * @param $reward
     * @return string|null
     */
    public function getDiscountRewardLabel($discount_type, $reward)
    {
        $point_label = "";
        switch ($discount_type) {
            case "percent":
                $point_label = round($reward->discount_value) . "%";
                break;
            case 'fixed_cart':
                $point_label = self::$woocommerce->getCustomPrice($reward->discount_value);
                break;
            case 'free_shipping':
                $point_label = __("Free Shipping", "wp-loyalty-rules");
                break;
            case 'free_product':
                $point_label = __("Free Product", "wp-loyalty-rules");
                break;
        }
        return $point_label;
    }

    function getFriendDiscountMessage($campaign_point_rule)
    {
        if (!$this->checkBasicReferralCheck($campaign_point_rule)) {
            return '';
        }
        $base_helper = new \Wlr\App\Helpers\Base();
        $reward_table = new \Wlr\App\Models\Rewards();
        $discount_title = "";
        $friend_type = isset($campaign_point_rule->friend) && isset($campaign_point_rule->friend->campaign_type) && !empty($campaign_point_rule->friend->campaign_type) ? $campaign_point_rule->friend->campaign_type : "";
        if ($friend_type == "point") {
            $earn_point = isset($campaign_point_rule->friend->earn_point) && !empty($campaign_point_rule->friend->earn_point) ? $campaign_point_rule->friend->earn_point : 0;
            $point_label = isset($campaign_point_rule->friend->earn_type) && ($campaign_point_rule->friend->earn_type == 'subtotal_percentage') && !empty($earn_point) ? round($earn_point) . "%" : $earn_point;
            $discount_title = !empty($earn_point) ? sprintf(__('Your friend gets %s : %s', 'wp-loyalty-rules'), $base_helper->getPointLabel($campaign_point_rule->friend->earn_point), $point_label) : "";
        } elseif ($friend_type == "coupon") {
            $friend_reward = isset($campaign_point_rule->friend->earn_reward) && !empty($campaign_point_rule->friend->earn_reward) ? $reward_table->findReward((int)$campaign_point_rule->friend->earn_reward) : "";
            $discount_type = isset($friend_reward->discount_type) && !empty($friend_reward->discount_type) ? $friend_reward->discount_type : '';
            $point_label = $this->getDiscountRewardLabel($discount_type, $friend_reward);
            $reward_label = $base_helper->getRewardLabel(1);
            if (!empty($friend_reward)) $discount_title = !empty($point_label) ? sprintf(__('Your friend gets %s: %s discount', 'wp-loyalty-rules'), $reward_label, $point_label) : "";
        }
        return $discount_title;
    }

    function getRewardList($is_guest_user)
    {
        if (!is_bool($is_guest_user)) return array();
        $reward_model = new Rewards();
        $reward_list = $reward_model->getCurrentRewardList();
        return apply_filters('wlr_page_reward_list', $reward_list, $is_guest_user);
    }

    function getTransactionDetails($user_email)
    {
        if (empty($user_email)) {
            return array();
        }
        $logs = new Logs();
        $offset = (int)self::$input->post_get('transaction_page', 1);
        $limit = 5;
        $start = ($offset - 1) * $limit;
        return apply_filters('wlr_page_transaction_details', array(
            'transactions' => $logs->getUserLogTransactions($user_email, $limit, $start),
            'transaction_total' => (int)$logs->getUserLogTransactionsCount($user_email),
            'offset' => $offset,
            'current_trans_count' => (int)($offset * $limit)
        ));
    }

    function getSocialShareList($user_email, $url)
    {
        if (empty($user_email) || empty($url)) return array();
        $social_share_list = array();
        $reward_list = $this->getSocialRewardList($user_email);
        foreach ($reward_list as $key => $social_share) {
            if (empty($social_share)) {
                continue;
            }
            $social_share_message = $this->getSocialShareMessage($key, $social_share, $social_share_list);
            $share_subject = $this->getSocialMailSubject($key, $social_share, $social_share_list);
            $share_body = $this->getSocialMailBody($key, $social_share, $social_share_list);
            $image_icon = $this->getCampaignIcon($key, $social_share, $social_share_list);
            $social_share_list[$key] = array(
                'icon' => 'wlr wlrf-' . $key,
                'share_content' => $social_share_message,
                'url' => '',
                'image_icon' => $image_icon
            );
            switch ($key) {
                case 'twitter_share':
                    $social_share_list[$key]['name'] = __('Twitter', 'wp-loyalty-rules');
                    $social_share_list[$key]['url'] = 'https://twitter.com/intent/tweet?text=' . urlencode($social_share_message);
                    break;
                case 'facebook_share':
                    $social_share_list[$key]['name'] = __('Facebook', 'wp-loyalty-rules');
                    $social_share_list[$key]['url'] = "https://www.facebook.com/sharer/sharer.php?quote=" . urlencode($social_share_message) . "&u=" . urlencode($url) . "&display=page";
                    break;
                case 'whatsapp_share':
                    $social_share_list[$key]['name'] = __('WhatsApp', 'wp-loyalty-rules');
                    $social_share_list[$key]['url'] = 'https://api.whatsapp.com/send?text=' . urlencode($social_share_message);
                    break;
                case 'email_share':
                    $social_share_list[$key]['name'] = __('E-mail', 'wp-loyalty-rules');
                    $social_share_list[$key]['url'] = "mailto:?subject=" . rawurlencode($share_subject) . "&amp;body=" . rawurlencode($share_body);
                    $social_share_list[$key]['share_subject'] = $share_subject;
                    $social_share_list[$key]['share_body'] = $share_body;
                    break;
            }
        }
        return apply_filters('wlr_page_social_share_list', $social_share_list);
    }

    function getSocialRewardList($user_email)
    {
        if (empty($user_email)) return array();
        $earn_campaign = EarnCampaign::getInstance();
        $social_extra = array(
            'user_email' => $user_email, 'cart' => WC()->cart, 'is_calculate_based' => 'cart', 'is_message' => true
        );
        $cart_action_list = $earn_campaign->getSocialActionList();
        $reward_list = $earn_campaign->getActionEarning($cart_action_list, $social_extra);
        return apply_filters('wlr_social_reward_list', $reward_list, $user_email);
    }

    function getSocialShareMessage($action, $social_share, $social_share_list)
    {
        if (empty($action) || $action == 'email_share' || !is_array($social_share) || empty($social_share)) return '';
        $social_share_message = is_array($social_share_list) && isset($social_share_list[$action]) && is_array($social_share_list[$action]) && isset($social_share_list[$action]['share_content']) && !empty($social_share_list[$action]['share_content']) ? $social_share_list[$action]['share_content'] : '';
        foreach ($social_share as $share_list) {
            if (!empty($share_list['messages'])) {
                $social_share_message .= $share_list['messages'] . ' ';
                break;
            }
        }
        return trim($social_share_message);
    }

    function getSocialMailSubject($action, $social_share, $social_share_list)
    {
        if (empty($action) || $action != 'email_share' || !is_array($social_share) || empty($social_share)) return '';
        $share_subject = is_array($social_share_list) && isset($social_share_list[$action]) && is_array($social_share_list[$action]) && isset($social_share_list[$action]['share_subject']) && !empty($social_share_list[$action]['share_subject']) ? $social_share_list[$action]['share_subject'] : '';
        foreach ($social_share as $share_list) {
            if (isset($share_list['messages']) && !empty($share_list['messages'])) {
                if (isset($share_list['messages']['subject']) && !empty($share_list['messages']['subject'])) {
                    $share_subject .= $share_list['messages']['subject'] . ' ';
                    break;
                }
            }
        }
        return trim($share_subject, ' ');
    }

    function getSocialMailBody($action, $social_share, $social_share_list)
    {
        if (empty($action) || $action != 'email_share' || !is_array($social_share) || empty($social_share)) return '';
        $share_subject = is_array($social_share_list) && isset($social_share_list[$action]) && is_array($social_share_list[$action]) && isset($social_share_list[$action]['share_body']) && !empty($social_share_list[$action]['share_body']) ? $social_share_list[$action]['share_body'] : '';
        foreach ($social_share as $share_list) {
            if (isset($share_list['messages']) && !empty($share_list['messages'])) {
                if (isset($share_list['messages']['body']) && !empty($share_list['messages']['body'])) {
                    $share_subject .= $share_list['messages']['body'] . ' ';
                    break;
                }
            }
        }
        return trim($share_subject, ' ');
    }

    function getCampaignIcon($action, $social_share, $social_share_list)
    {
        if (empty($action) || !is_array($social_share) || empty($social_share)) return '';
        $image_icon = is_array($social_share_list) && isset($social_share_list[$action]) && is_array($social_share_list[$action]) && isset($social_share_list[$action]['image_icon']) && !empty($social_share_list[$action]['image_icon']) ? $social_share_list[$action]['image_icon'] : '';
        if (empty($image_icon)) {
            foreach ($social_share as $share_list) {
                if (isset($share_list['icon']) && !empty($share_list['icon'])) {
                    $image_icon = $share_list['icon'];
                    break;
                }
            }
        }
        return $image_icon;
    }

    function getPageUserRewards($user_email)
    {
        if (empty($user_email)) return array();
        $reward_helper = \Wlr\App\Helpers\Rewards::getInstance();
        $allowed_conditions = apply_filters('wlr_page_allowed_conditions', array(
            'user_role', 'customer', 'user_point', 'currency', 'language'
        ));
        $extra = array(
            'user_email' => $user_email, 'cart' => WC()->cart, 'is_calculate_based' => 'cart', 'allowed_condition' => $allowed_conditions
        );
        $user_reward = $reward_helper->getUserRewards($user_email, $extra);
        $point_rewards = $reward_helper->getPointRewards($user_email, $extra);
        $user_reward_list = array_merge($user_reward, $point_rewards);
        //$user = $this->getPageUserDetails($user_email);
        $user_model = new Users();
        $user = $user_model->getQueryData(array('user_email' => array('operator' => '=', 'value' => $user_email,),), '*', array(), false);
        if (!empty($user_reward_list)) {
            $reward_types = self::$woocommerce->getRewardDiscountTypes();
            foreach ($user_reward_list as &$user_reward_data) {
                $user_reward_data->reward_type_name = isset($user_reward_data->discount_type) && !empty($user_reward_data->discount_type) && isset($reward_types[$user_reward_data->discount_type]) && $reward_types[$user_reward_data->discount_type] ? $reward_types[$user_reward_data->discount_type] : '';
                $user_reward_data->expiry_date = (isset($user_reward_data->end_at) && !empty($user_reward_data->end_at) && $user_reward_data->end_at >= 0) ? self::$woocommerce->beforeDisplayDate($user_reward_data->end_at, "d/m/Y") : '';
                if (isset($user_reward_data->discount_type) && !empty($user_reward_data->discount_type) && ($user_reward_data->discount_type == 'points_conversion')
                    && $user_reward_data->reward_table != 'user_reward') {
                    $this->getPointConversionRedeemData($user_reward_data, $user);
                }
            }
        }
        return apply_filters('wlr_page_user_reward_list', $user_reward_list);
    }

    protected function getPointConversionRedeemData(&$user_reward_data, $user)
    {
        // case 1: if cart amount available, convert cart amount to required point
        // case 2: if cart required point greater than available point, then change required point to available point
        // case 3: if cart required point less than available point, then use required point
        // case 4: if user enter 10 point, need to display this conversion value
        $earn_campaign_helper = new EarnCampaign();
        $available_point = (isset($user) && is_object($user) && isset($user->points) && !empty($user->points)) ? $user->points : 0;
        $cart_amount = self::$woocommerce->getCartSubtotal();
        $cart_amount = self::$woocommerce->getCustomPrice($cart_amount, false);
        $cart_required_point = 0;
        $discount_value = self::$woocommerce->getCustomPrice($user_reward_data->discount_value, false);
        if ($cart_amount > 0) {
            $cart_required_point = ($user_reward_data->require_point / $discount_value) * $cart_amount;
        }
        $woocommerce_currency = self::$woocommerce->getDisplayCurrency();
        $input_point = ($cart_required_point > 0 && $cart_required_point < $available_point) ? $cart_required_point : $available_point;
        $input_point = $earn_campaign_helper->roundPoints($input_point);
        $input_value = ($input_point / $user_reward_data->require_point) * $discount_value;
        $data = apply_filters('wlr_user_reward_point_conversion_redeem_data', array(
            'reward_type_name' => ($cart_amount > 0) ? sprintf(__("%s %s =%s", "wp-loyalty-rules"), $user_reward_data->require_point, $earn_campaign_helper->getPointLabel($user_reward_data->require_point), $discount_value) : $user_reward_data->reward_type_name,
            'input_point' => $input_point,
            'input_value' => number_format($input_value, 2),
            'available_point' => $available_point,
            'cart_amount' => $cart_amount,
            'conversion_price_format' => sprintf(__('=(%s) %s', 'wp-loyalty-rules'), $woocommerce_currency, self::$woocommerce->getCurrencySymbols($woocommerce_currency)),
        ));
        $user_reward_data->reward_type_name = $data['reward_type_name'];
        $user_reward_data->input_point = $data['input_point'];
        $user_reward_data->input_value = $data['input_value'];
        $user_reward_data->available_point = $data['available_point'];
        $user_reward_data->cart_amount = $data['cart_amount'];
        $user_reward_data->conversion_price_format = $data['conversion_price_format'];
    }

    function getPageUserDetails($user_email)
    {
        $earn_campaign_helper = EarnCampaign::getInstance();
        //$user = $earn_campaign_helper->getPointUserByEmail($user_email);
        $user_point_table = new Users();
        $conditions = array('user_email' => array('operator' => '=', 'value' => sanitize_email($user_email)));
        $user = $user_point_table->getQueryData($conditions, '*', array(), false, true);
        if (is_object($user) && isset($user->id) && $user->id > 0 && isset($user->level_id)) {
            $user_point_table->insertOrUpdate(array('level_id' => $user->level_id), $user->id);
            $user = $user_point_table->getByKey($user->id);
            if ($user->level_id > 0) {
                $level_data = $earn_campaign_helper->getLevel($user->level_id);
                $level_data->current_level_name = isset($level_data->name) && !empty($level_data->name) ? $level_data->name : '';
                $level_data->current_level_image = isset($level_data->badge) && !empty($level_data->badge) ? $level_data->badge : WLR_PLUGIN_URL . "Assets/Site/image/default-level.png";
                $level_data->current_level_start = isset($current_level->from_points) && !empty($current_level->from_points) ? $current_level->from_points : 0;
                if (isset($level_data->to_points) && $level_data->to_points > 0) {
                    $next_level_data = $earn_campaign_helper->getNextLevel($level_data->to_points);
                    if (!empty($next_level_data) && isset($next_level_data->from_points) && $next_level_data->from_points > 0) {
                        $level_data->next_level_name = isset($next_level_data->name) && !empty($next_level_data->name) ? $next_level_data->name : 0;
                        $level_data->next_level_start = !empty($next_level_data->from_points) ? $next_level_data->from_points : 0;
                    }
                }
                $user->level_data = $level_data;
            }
        }
        return apply_filters('wlr_page_user_details', $user);
    }

    function getBrandingData()
    {
        $setting_option = self::$woocommerce->getOptions('wlr_settings');
        return apply_filters('wlr_page_branding_details', array(
            "theme_color" => is_array($setting_option) && isset($setting_option["theme_color"]) && !empty($setting_option["theme_color"]) ? $setting_option["theme_color"] : "#4F47EB",
            "border_color" => is_array($setting_option) && isset($setting_option["border_color"]) && !empty($setting_option["border_color"]) ? $setting_option["border_color"] : "#CFCFCF",
            "background_color" => is_array($setting_option) && isset($setting_option["background_color"]) && !empty($setting_option["background_color"]) ? $setting_option["background_color"] : "#ffffff",
            "button_text_color" => is_array($setting_option) && isset($setting_option["button_text_color"]) && !empty($setting_option["button_text_color"]) ? $setting_option["button_text_color"] : "#ffffff",
            "heading_color" => is_array($setting_option) && isset($setting_option["heading_color"]) && !empty($setting_option["heading_color"]) ? $setting_option["heading_color"] : "#1D2327",
            "redeem_point_icon" => is_array($setting_option) && isset($setting_option["redeem_point_icon"]) ? $setting_option["redeem_point_icon"] : "",
            "available_point_icon" => is_array($setting_option) && isset($setting_option["available_point_icon"]) ? $setting_option["available_point_icon"] : "",
            "redeem_button_text" => is_array($setting_option) && isset($setting_option["redeem_button_text"]) && !empty($setting_option["redeem_button_text"]) ? __($setting_option["redeem_button_text"], "wp-loyalty-rules") : __("Redeem Now", "wp-loyalty-rules"),
            "redeem_button_color" => is_array($setting_option) && isset($setting_option["redeem_button_color"]) && !empty($setting_option["redeem_button_color"]) ? $setting_option["redeem_button_color"] : "#4F47EB",
            "redeem_button_text_color" => is_array($setting_option) && isset($setting_option["redeem_button_text_color"]) && !empty($setting_option["redeem_button_text_color"]) ? $setting_option["redeem_button_text_color"] : "#ffffff",
            "apply_coupon_border_color" => is_array($setting_option) && isset($setting_option["apply_coupon_border_color"]) && !empty($setting_option["apply_coupon_border_color"]) ? $setting_option["apply_coupon_border_color"] : "#FF8E3D",
            "apply_coupon_button_text_color" => is_array($setting_option) && isset($setting_option["apply_coupon_button_text_color"]) && !empty($setting_option["apply_coupon_button_text_color"]) ? $setting_option["apply_coupon_button_text_color"] : "#ffffff",
            "apply_coupon_button_color" => is_array($setting_option) && isset($setting_option["apply_coupon_button_color"]) && !empty($setting_option["apply_coupon_button_color"]) ? $setting_option["apply_coupon_button_color"] : "#4F47EB",
            "apply_coupon_button_text" => is_array($setting_option) && isset($setting_option["apply_coupon_button_text"]) && !empty($setting_option["apply_coupon_button_text"]) ? __($setting_option["apply_coupon_button_text"], "wp-loyalty-rules") : __("Apply Coupon", "wp-loyalty-rules"),
            "apply_coupon_background" => is_array($setting_option) && isset($setting_option["apply_coupon_background"]) && !empty($setting_option["apply_coupon_background"]) ? $setting_option["apply_coupon_background"] : "#FFF8F3",
        ));
    }

    function isReferralActionAvailable($campaign_list)
    {
        if (!is_array($campaign_list)) {
            return false;
        }
        foreach ($campaign_list as $campaign) {
            if (isset($campaign->action_type) && $campaign->action_type == 'referral') {
                return true;
            }
        }
        return false;
    }
}