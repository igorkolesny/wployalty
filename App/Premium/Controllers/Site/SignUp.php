<?php
/**
 * @author      Wployalty (Alagesan)
 * @license     http://www.gnu.org/licenses/gpl-2.0.html
 * @link        https://www.wployalty.net
 * */

namespace Wlr\App\Premium\Controllers\Site;

use Wlr\App\Controllers\Base;
use Wlr\App\Helpers\EarnCampaign;
use Wlr\App\Premium\Helpers\Achievement;
use Wlr\App\Premium\Helpers\Referral;

defined('ABSPATH') or die;

class SignUp extends Base
{
    function wooSignUpMessage($checkout)
    {
        $this->registerForm();
    }

    function registerForm()
    {
        $earn_campaign = EarnCampaign::getInstance();
        $cart_action_list = array(
            'signup'
        );
        $extra = array('user_email' => self::$woocommerce->get_login_user_email(), 'is_message' => true);
        $reward_list = $earn_campaign->getActionEarning($cart_action_list, $extra);
        $message = '';
        foreach ($reward_list as $rewards) {
            foreach ($rewards as $reward) {
                if (isset($reward['messages']) && !empty($reward['messages'])) {
                    $message .= $reward['messages'] . "<br/>";
                }
            }
        }
        echo $message;
    }

    public function createAccountAction($user_id)
    {
        if (empty($user_id)) {
            return;
        }
        $user = get_user_by('id', $user_id);
        $userEmail = '';
        if (!empty($user)) {
            $userEmail = $user->user_email;
        }
        if (!empty($userEmail)) {
            $sign_up_helper = \Wlr\App\Premium\Helpers\SignUp::getInstance();
            $action_data = array(
                'user_email' => $userEmail
            );
            $sign_up_helper->applyEarnSignUp($action_data);
            $referral_helper = new Referral();
            $referral_helper->doReferralCheck($action_data);
        }
    }

    function getPointSignUp($point, $rule, $data)
    {
        $point_for_purchase = \Wlr\App\Premium\Helpers\SignUp::getInstance();
        return $point_for_purchase->getTotalEarnPoint($point, $rule, $data);
    }

    function getCouponSignUp($point, $rule, $data)
    {
        $point_for_purchase = \Wlr\App\Premium\Helpers\SignUp::getInstance();
        return $point_for_purchase->getTotalEarnReward($point, $rule, $data);
    }

}