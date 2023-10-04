<?php
/**
 * @author      Wployalty (Alagesan)
 * @license     http://www.gnu.org/licenses/gpl-3.0.html
 * @link        https://www.wployalty.net
 * */

namespace Wlr\App\Premium\Controllers\Site;

use Wlr\App\Controllers\Base;
use Wlr\App\Premium\Helpers\EmailShare;
use Wlr\App\Premium\Helpers\FacebookShare;
use Wlr\App\Premium\Helpers\FollowUpShare;
use Wlr\App\Premium\Helpers\Referral;
use Wlr\App\Premium\Helpers\TwitterShare;
use Wlr\App\Premium\Helpers\WhatsAppShare;
use Wlr\App\Helpers\Woocommerce;

defined('ABSPATH') or die;

class SocialShare extends Base
{
    /* Twitter Share Start */
    function getPointTwitterShare($point, $rule, $data)
    {
        $twitter_share_helper = TwitterShare::getInstance();
        return $twitter_share_helper->getTotalEarnPoint($point, $rule, $data);
    }

    function getCouponTwitterShare($point, $rule, $data)
    {
        $twitter_share_helper = TwitterShare::getInstance();
        return $twitter_share_helper->getTotalEarnReward($point, $rule, $data);
    }

    function updateTwitterReward()
    {
        $wlr_nonce = (string)self::$input->post_get('wlr_nonce', '');
        if (!Woocommerce::verify_nonce($wlr_nonce, 'wlr_social_share_nonce')) {
            wp_send_json_error();
        }
        $user_email = self::$woocommerce->get_login_user_email();
        if (!empty($user_email)) {
            $twitter_share_helper = TwitterShare::getInstance();
            $action_data = array(
                'user_email' => $user_email
            );
            $twitter_share_helper->applyEarnTwitterShare($action_data);
            $referral_helper = new Referral();
            $referral_helper->doReferralCheck($action_data);
        }
        wp_send_json_success();
    }
    /* Twitter Share End */

    /* Facebook Share Start */
    function getPointFacebookShare($point, $rule, $data)
    {
        $facebook_share_helper = FacebookShare::getInstance();
        return $facebook_share_helper->getTotalEarnPoint($point, $rule, $data);
    }

    function getCouponFacebookShare($point, $rule, $data)
    {
        $facebook_share_helper = FacebookShare::getInstance();
        return $facebook_share_helper->getTotalEarnReward($point, $rule, $data);
    }

    function updateFacebookReward()
    {
        $wlr_nonce = (string)self::$input->post_get('wlr_nonce', '');
        if (!Woocommerce::verify_nonce($wlr_nonce, 'wlr_social_share_nonce')) {
            wp_send_json_error();
        }
        $user_email = self::$woocommerce->get_login_user_email();
        if (!empty($user_email)) {
            $facebook_share_helper = FacebookShare::getInstance();
            $action_data = array(
                'user_email' => $user_email
            );
            $facebook_share_helper->applyEarnFacebookShare($action_data);
            $referral_helper = new Referral();
            $referral_helper->doReferralCheck($action_data);
        }
        wp_send_json_success();
    }
    /* Facebook Share End */

    /* Email Share Start */
    function getPointEmailShare($point, $rule, $data)
    {
        $email_share_helper = EmailShare::getInstance();
        return $email_share_helper->getTotalEarnPoint($point, $rule, $data);
    }

    function getCouponEmailShare($point, $rule, $data)
    {
        $email_share_helper = EmailShare::getInstance();
        return $email_share_helper->getTotalEarnReward($point, $rule, $data);
    }

    function updateEmailReward()
    {
        $wlr_nonce = (string)self::$input->post_get('wlr_nonce', '');
        if (!Woocommerce::verify_nonce($wlr_nonce, 'wlr_social_share_nonce')) {
            wp_send_json_error();
        }
        $user_email = self::$woocommerce->get_login_user_email();
        if (!empty($user_email)) {
            $email_share_helper = EmailShare::getInstance();
            $action_data = array(
                'user_email' => $user_email
            );
            $email_share_helper->applyEarnEmailShare($action_data);
            $referral_helper = new Referral();
            $referral_helper->doReferralCheck($action_data);
        }
        wp_send_json_success();
    }
    /* Email Share End */

    /* Whats App Share Start */
    function getPointWhatsAppShare($point, $rule, $data)
    {
        $whats_app_share_helper = WhatsAppShare::getInstance();
        return $whats_app_share_helper->getTotalEarnPoint($point, $rule, $data);
    }

    function getCouponWhatsAppShare($point, $rule, $data)
    {
        $whats_app_share_helper = WhatsAppShare::getInstance();
        return $whats_app_share_helper->getTotalEarnReward($point, $rule, $data);
    }

    function updateWhatsAppReward()
    {
        $wlr_nonce = (string)self::$input->post_get('wlr_nonce', '');
        if (!Woocommerce::verify_nonce($wlr_nonce, 'wlr_social_share_nonce')) {
            wp_send_json_error();
        }
        $user_email = self::$woocommerce->get_login_user_email();
        if (!empty($user_email)) {
            $whats_app_share_helper = WhatsAppShare::getInstance();
            $action_data = array(
                'user_email' => $user_email
            );
            $whats_app_share_helper->applyEarnWhatsAppShare($action_data);
            $referral_helper = new Referral();
            $referral_helper->doReferralCheck($action_data);
        }
        wp_send_json_success();
    }
    /* Whats App Share End */

    /* Follow up Share*/
    function updateFollowUpReward()
    {
        $wlr_nonce = (string)self::$input->post_get('wlr_nonce', '');
        if (!Woocommerce::verify_nonce($wlr_nonce, 'wlr_social_share_nonce')) {
            wp_send_json_error();
        }
        $user_email = self::$woocommerce->get_login_user_email();
        if (!empty($user_email)) {
            $whats_app_share_helper = FollowUpShare::getInstance();
            $action_data = array(
                'user_email' => $user_email,
                'campaign_id' => self::$input->post_get('id', 0)
            );
            $whats_app_share_helper->applyEarnFollowUpShare($action_data);
            $referral_helper = new Referral();
            $referral_helper->doReferralCheck($action_data);
        }
        wp_send_json_success();
    }

    function getPointFollowUpShare($point, $rule, $data)
    {
        $whats_app_share_helper = FollowUpShare::getInstance();
        return $whats_app_share_helper->getTotalEarnPoint($point, $rule, $data);
    }

    function getCouponFollowUpShare($point, $rule, $data)
    {
        $whats_app_share_helper = FollowUpShare::getInstance();
        return $whats_app_share_helper->getTotalEarnReward($point, $rule, $data);
    }
    /* Follow up Share End*/
}