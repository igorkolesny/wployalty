<?php
/**
 * @author      Wployalty (Alagesan)
 * @license     http://www.gnu.org/licenses/gpl-2.0.html
 * @link        https://www.wployalty.net
 * */

namespace Wlr\App\Premium\Helpers;
defined('ABSPATH') or die();

use Wlr\App\Helpers\Base;
use Wlr\App\Helpers\EarnCampaign;
use Wlr\App\Helpers\Woocommerce;

class WhatsAppShare extends Base
{
    public static $instance = null;

    public function __construct($config = array())
    {
        parent::__construct($config);
    }

    function getTotalEarnPoint($point, $rule, $data)
    {
        if (isset($rule->earn_campaign->point_rule) && !empty($rule->earn_campaign->point_rule)) {
            $point_rule = $rule->earn_campaign->point_rule;
            if (self::$woocommerce_helper->isJson($rule->earn_campaign->point_rule)) {
                $point_rule = json_decode($rule->earn_campaign->point_rule);
            }
            if (isset($rule->earn_campaign->id) && !empty($rule->earn_campaign->id)) {
                $data['campaign_id'] = $rule->earn_campaign->id;
            }
            $data['earn_type'] = 'point';
            $status = $this->checkSocialShare($data);
            if ($status && isset($point_rule->earn_point)) {
                $point = (int)$point_rule->earn_point;
            }
        }
        return $point;
    }

    function getTotalEarnReward($reward, $rule, $data)
    {
        if (isset($rule->earn_campaign->point_rule) && !empty($rule->earn_campaign->point_rule)) {
            $point_rule = $rule->earn_campaign->point_rule;
            if (self::$woocommerce_helper->isJson($rule->earn_campaign->point_rule)) {
                $point_rule = json_decode($rule->earn_campaign->point_rule);
            }
            if (isset($rule->earn_campaign->id) && !empty($rule->earn_campaign->id)) {
                $data['campaign_id'] = $rule->earn_campaign->id;
            }
            $data['earn_type'] = 'coupon';
            $status = $this->checkSocialShare($data);
            if ($status && isset($point_rule->earn_reward)) {
                $action_reward = $this->getRewardById($point_rule->earn_reward);
                $reward = !empty($action_reward) ? $action_reward : $reward;
            }
        }
        return $reward;
    }

    function applyEarnWhatsAppShare($action_data)
    {
        if (!is_array($action_data) || empty($action_data['user_email'])) {
            return false;
        }
        $status = false;
        $earn_campaign = EarnCampaign::getInstance();
        $cart_action_list = array('whatsapp_share');
        foreach ($cart_action_list as $action_type) {
            $variant_reward = $this->getTotalEarning($action_type, array(), $action_data);
            foreach ($variant_reward as $campaign_id => $v_reward) {
                if (isset($v_reward['point']) && !empty($v_reward['point']) && $v_reward['point'] > 0) {
                    $status = $earn_campaign->addEarnCampaignPoint($action_type, $v_reward['point'], $campaign_id, $action_data);
                }
                if (isset($v_reward['rewards']) && $v_reward['rewards']) {
                    foreach ($v_reward['rewards'] as $single_reward) {
                        $status = $earn_campaign->addEarnCampaignReward($action_type, $single_reward, $campaign_id, $action_data);
                    }
                }
            }
        }
        return $status;
    }

    public static function getInstance(array $config = array())
    {
        if (!self::$instance) {
            self::$instance = new self($config);
        }
        return self::$instance;
    }

    function processMessage($point_rule, $earning)
    {
        $message = '';
        if (isset($point_rule->share_message) && !empty($point_rule->share_message)) {
            $message = Woocommerce::getCleanHtml(__($point_rule->share_message, 'wp-loyalty-rules'));
        }
        $point = isset($earning['point']) && !empty($earning['point']) ? (int)$earning['point'] : 0;
        $rewards = isset($earning['rewards']) && !empty($earning['rewards']) ? (array)$earning['rewards'] : array();
        $available_rewards = '';
        foreach ($rewards as $single_reward) {
            if (is_object($single_reward) && isset($single_reward->display_name)) {
                $available_rewards .= __($single_reward->display_name, 'wp-loyalty-rules') . ',';
            }
        }
        $available_rewards = trim($available_rewards, ',');
        $reward_count = 0;
        if (!empty($available_rewards)) {
            $reward_count = count(explode(',', $available_rewards));
        }
        $display_message = '';
        if (($point > 0 || !empty($available_rewards)) && !empty($message)) {
            $point = $this->roundPoints($point);
            $short_code_list = array(
                '{wlr_points}' => $point > 0 ? number_format_i18n($point) : '',
                '{wlr_points_label}' => $this->getPointLabel($point),
                '{wlr_reward_label}' => $this->getRewardLabel($reward_count),
                '{wlr_rewards}' => $available_rewards,
                '{wlr_referral_url}' => $this->getReferralUrl()
            );
            $display_message = $this->processShortCodes($short_code_list, $message);
        }
        return $display_message;
    }
}