<?php
/**
 * @author      Wployalty (Alagesan)
 * @license     http://www.gnu.org/licenses/gpl-2.0.html
 * @link        https://www.wployalty.net
 * */

namespace Wlr\App\Premium\Controllers\Site;
defined('ABSPATH') or die();

use Wlr\App\Controllers\Base;
use Wlr\App\Models\Levels;
use Wlr\App\Premium\Helpers\PurchaseHistories;
use Wlr\App\Premium\Helpers\Subtotal;

class Main extends Base
{
    function isPro($status)
    {
        return true;
    }

    function proActionTypes($action_types)
    {
        if (empty($action_types)) {
            return $action_types;
        }
        $action_types['subtotal'] = __('Reward based on spending', 'wp-loyalty-rules');
        $action_types['purchase_histories'] = __('Order Goals', 'wp-loyalty-rules');
        $action_types['signup'] = __('Sign Up', 'wp-loyalty-rules');
        $action_types['product_review'] = __('Write a review', 'wp-loyalty-rules');
        $action_types['birthday'] = __('Birthday', 'wp-loyalty-rules');
        $action_types['facebook_share'] = __('Facebook Share', 'wp-loyalty-rules');
        $action_types['twitter_share'] = __('Twitter Share', 'wp-loyalty-rules');
        $action_types['whatsapp_share'] = __('WhatsApp Share', 'wp-loyalty-rules');
        $action_types['email_share'] = __('Email Share', 'wp-loyalty-rules');
        $action_types['referral'] = __('Referral', 'wp-loyalty-rules');
        $action_types['followup_share'] = __('Follow', 'wp-loyalty-rules');
        $action_types['achievement'] = __('Achievement', 'wp-loyalty-rules');
        return $action_types;
    }

    function addProCartActionList($action_list)
    {
        $action_list[] = 'subtotal';
        $action_list[] = 'purchase_histories';
        return $action_list;
    }

    function addProConditions($available_conditions)
    {
        if (file_exists(WLR_PLUGIN_PATH . 'App/Premium/Conditions/')) {
            $conditions_list = array_slice(scandir(WLR_PLUGIN_PATH . 'App/Premium/Conditions/'), 2);
            if (!empty($conditions_list)) {
                foreach ($conditions_list as $condition) {
                    $class_name = basename($condition, '.php');
                    if (!in_array($class_name, array('Base'))) {
                        $condition_class_name = 'Wlr\App\Premium\Conditions\\' . $class_name;
                        if (class_exists($condition_class_name)) {
                            $condition_object = new $condition_class_name();
                            if ($condition_object instanceof \Wlr\App\Conditions\Base) {
                                $rule_name = $condition_object->name();
                                if (!empty($rule_name)) {
                                    $available_conditions[$rule_name] = array(
                                        'object' => $condition_object,
                                        'label' => $condition_object->label,
                                        'group' => $condition_object->group,
                                        'extra_params' => $condition_object->extra_params,
                                    );
                                }
                            }
                        }
                    }
                }
            }
        }
        return $available_conditions;
    }

    function addProActionAcceptConditions($conditions)
    {
        $conditions['subtotal'] = array(
            'Common' => array(
                'label' => __('Common', 'wp-loyalty-rules'),
                'options' => array(
                    'user_role' => __('User Role', 'wp-loyalty-rules'),
                    'user_point' => __('Customer Points', 'wp-loyalty-rules'),
                    'customer' => __('Customer', 'wp-loyalty-rules'),
                    'language' => __('Language', 'wp-loyalty-rules'),
                    'currency' => __('Currency', 'wp-loyalty-rules'),
                    'usage_limits' => __('Campaign usage limit per customer', 'wp-loyalty-rules'),
                )
            ),
            'Cart' => array(
                'label' => __('Cart', 'wp-loyalty-rules'),
                'options' => array(
                    'cart_subtotal' => __('Cart Subtotal', 'wp-loyalty-rules'),
                    'cart_line_items_count' => __('Line Item Count', 'wp-loyalty-rules'),
                    'cart_weights' => __('Cart Weight', 'wp-loyalty-rules')
                )
            ),
            'Product' => array(
                'label' => __('Product', 'wp-loyalty-rules'),
                'options' => array(
                    'products' => __('Products', 'wp-loyalty-rules'),
                    'product_attributes' => __('Product Attributes', 'wp-loyalty-rules'),
                    'product_category' => __('Product Category', 'wp-loyalty-rules'),
                    'product_sku' => __('Product SKU', 'wp-loyalty-rules'),
                    //'product_onsale' => __('On sale products', 'wp-loyalty-rules'),
                    'product_tags' => __('Tags', 'wp-loyalty-rules'),
                )
            ),
            'Order' => array(
                'label' => __('Order', 'wp-loyalty-rules'),
                'options' => array(
                    'payment_method' => __('Payment Method', 'wp-loyalty-rules'),
                    'order_status' => __('Order Status', 'wp-loyalty-rules'),
                    'purchase_history' => __('Purchase History', 'wp-loyalty-rules'),
                    'purchase_history_qty' => __('Purchase History Quantity', 'wp-loyalty-rules'),
                    'life_time_sale_value' => __('Life Time Sale value', 'wp-loyalty-rules')
                )
            ),
            'PurchaseHistory' => array(
                'label' => __('Purchase History', 'wp-loyalty-rules'),
                'options' => array(
                    'purchase_first_order' => __('First Order', 'wp-loyalty-rules'),
                    'purchase_last_order' => __('Last Order', 'wp-loyalty-rules'),
                    'purchase_last_order_amount' => __('Last order amount', 'wp-loyalty-rules'),
                    'purchase_previous_orders' => __('Number of orders made', 'wp-loyalty-rules'),
                    'purchase_previous_orders_for_specific_product' => __('Number of orders made with following products', 'wp-loyalty-rules'),
                    'purchase_quantities_for_specific_product' => __('Number of quantities made with following products', 'wp-loyalty-rules'),
                    'purchase_spent' => __('Total spent', 'wp-loyalty-rules')
                )
            )
        );
        $conditions['purchase_histories'] = array(
            'Common' => array(
                'label' => __('Common', 'wp-loyalty-rules'),
                'options' => array(
                    'user_role' => __('User Role', 'wp-loyalty-rules'),
                    'user_point' => __('Customer Points', 'wp-loyalty-rules'),
                    'customer' => __('Customer', 'wp-loyalty-rules'),
                    'language' => __('language', 'wp-loyalty-rules'),
                    'currency' => __('Currency', 'wp-loyalty-rules'),
                    'usage_limits' => __('Campaign usage limit per customer', 'wp-loyalty-rules'),
                )
            ),
            'Product' => array(
                'label' => __('Product', 'wp-loyalty-rules'),
                'options' => array(
                    'products' => __('Products', 'wp-loyalty-rules'),
                    'product_attributes' => __('Product Attributes', 'wp-loyalty-rules'),
                    'product_category' => __('Product Category', 'wp-loyalty-rules'),
                    'product_sku' => __('Product SKU', 'wp-loyalty-rules'),
                    //'product_onsale' => __('On sale products', 'wp-loyalty-rules'),
                    'product_tags' => __('Tags', 'wp-loyalty-rules'),
                )
            ),
            'Order' => array(
                'label' => __('Order', 'wp-loyalty-rules'),
                'options' => array(
                    //'payment_method' => __('Payment Method', 'wp-loyalty-rules'),
                    //'order_status' => __('Order Status', 'wp-loyalty-rules'),
                    'purchase_history' => __('Purchase History', 'wp-loyalty-rules'),
                    'purchase_history_qty' => __('Purchase History Quantity', 'wp-loyalty-rules'),
                    'life_time_sale_value' => __('Life Time Sale value', 'wp-loyalty-rules')
                )
            ),
            'PurchaseHistory' => array(
                'label' => __('Purchase History', 'wp-loyalty-rules'),
                'options' => array(
                    'purchase_first_order' => __('First Order', 'wp-loyalty-rules'),
                    'purchase_last_order' => __('Last Order', 'wp-loyalty-rules'),
                    'purchase_last_order_amount' => __('Last order amount', 'wp-loyalty-rules'),
                    'purchase_previous_orders' => __('Number of orders made', 'wp-loyalty-rules'),
                    'purchase_previous_orders_for_specific_product' => __('Number of orders made with following products', 'wp-loyalty-rules'),
                    'purchase_quantities_for_specific_product' => __('Number of quantities made with following products', 'wp-loyalty-rules'),
                    'purchase_spent' => __('Total spent', 'wp-loyalty-rules')
                )
            )
        );
        $conditions['signup'] = array(
            'Common' => array(
                'label' => __('Common', 'wp-loyalty-rules'),
                'options' => array(
                    //'user_role' => __('User Role', 'wp-loyalty-rules'),
                    'language' => __('language', 'wp-loyalty-rules'),
                    'currency' => __('Currency', 'wp-loyalty-rules'),
                )
            ),
        );
        $conditions['product_review'] = array(
            'Common' => array(
                'label' => __('Common', 'wp-loyalty-rules'),
                'options' => array(
                    'user_role' => __('User Role', 'wp-loyalty-rules'),
                    'user_point' => __('Customer Points', 'wp-loyalty-rules'),
                    'customer' => __('Customer', 'wp-loyalty-rules'),
                    'language' => __('language', 'wp-loyalty-rules'),
                    'currency' => __('Currency', 'wp-loyalty-rules'),
                )
            ),
            'Product' => array(
                'label' => __('Product', 'wp-loyalty-rules'),
                'options' => array(
                    'products' => __('Products', 'wp-loyalty-rules'),
                    'product_attributes' => __('Product Attributes', 'wp-loyalty-rules'),
                    'product_category' => __('Product Category', 'wp-loyalty-rules'),
                    'product_sku' => __('Product SKU', 'wp-loyalty-rules'),
                    'product_onsale' => __('On sale products', 'wp-loyalty-rules'),
                    'product_tags' => __('Tags', 'wp-loyalty-rules'),
                )
            ),
            'Order' => array(
                'label' => __('Order', 'wp-loyalty-rules'),
                'options' => array(
                    //'payment_method' => __('Payment Method', 'wp-loyalty-rules'),
                    //'order_status' => __('Order Status', 'wp-loyalty-rules'),
                    'purchase_history' => __('Purchase History', 'wp-loyalty-rules'),
                    'life_time_sale_value' => __('Life Time Sale value', 'wp-loyalty-rules'),
                )
            )
        );
        $conditions['birthday'] = array(
            'Common' => array(
                'label' => __('Common', 'wp-loyalty-rules'),
                'options' => array(
                    'user_role' => __('User Role', 'wp-loyalty-rules'),
                    'user_point' => __('Customer Points', 'wp-loyalty-rules'),
                    'customer' => __('Customer', 'wp-loyalty-rules'),
                    'language' => __('language', 'wp-loyalty-rules'),
                    'currency' => __('Currency', 'wp-loyalty-rules'),
                )
            ),
            'Order' => array(
                'label' => __('Order', 'wp-loyalty-rules'),
                'options' => array(
                    //'payment_method' => __('Payment Method', 'wp-loyalty-rules'),
                    //'order_status' => __('Order Status', 'wp-loyalty-rules'),
                    'purchase_history' => __('Purchase History', 'wp-loyalty-rules'),
                    'life_time_sale_value' => __('Life Time Sale value', 'wp-loyalty-rules')
                )
            )
        );
        $conditions['facebook_share'] = array(
            'Common' => array(
                'label' => __('Common', 'wp-loyalty-rules'),
                'options' => array(
                    'user_role' => __('User Role', 'wp-loyalty-rules'),
                    'user_point' => __('Customer Points', 'wp-loyalty-rules'),
                    'customer' => __('Customer', 'wp-loyalty-rules'),
                    'language' => __('language', 'wp-loyalty-rules'),
                    'currency' => __('Currency', 'wp-loyalty-rules'),
                )
            ),
            'Order' => array(
                'label' => __('Order', 'wp-loyalty-rules'),
                'options' => array(
                    //'payment_method' => __('Payment Method', 'wp-loyalty-rules'),
                    //'order_status' => __('Order Status', 'wp-loyalty-rules'),
                    'purchase_history' => __('Purchase History', 'wp-loyalty-rules'),
                    'life_time_sale_value' => __('Life Time Sale value', 'wp-loyalty-rules'),
                )
            )
        );
        $conditions['twitter_share'] = array(
            'Common' => array(
                'label' => __('Common', 'wp-loyalty-rules'),
                'options' => array(
                    'user_role' => __('User Role', 'wp-loyalty-rules'),
                    'user_point' => __('Customer Points', 'wp-loyalty-rules'),
                    'customer' => __('Customer', 'wp-loyalty-rules'),
                    'language' => __('language', 'wp-loyalty-rules'),
                    'currency' => __('Currency', 'wp-loyalty-rules'),
                )
            ),
            'Order' => array(
                'label' => __('Order', 'wp-loyalty-rules'),
                'options' => array(
                    //'payment_method' => __('Payment Method', 'wp-loyalty-rules'),
                    //'order_status' => __('Order Status', 'wp-loyalty-rules'),
                    'purchase_history' => __('Purchase History', 'wp-loyalty-rules'),
                    'life_time_sale_value' => __('Life Time Sale value', 'wp-loyalty-rules'),
                )
            )
        );
        $conditions['whatsapp_share'] = array(
            'Common' => array(
                'label' => __('Common', 'wp-loyalty-rules'),
                'options' => array(
                    'user_role' => __('User Role', 'wp-loyalty-rules'),
                    'user_point' => __('Customer Points', 'wp-loyalty-rules'),
                    'customer' => __('Customer', 'wp-loyalty-rules'),
                    'language' => __('language', 'wp-loyalty-rules'),
                    'currency' => __('Currency', 'wp-loyalty-rules'),
                )
            ),
            'Order' => array(
                'label' => __('Order', 'wp-loyalty-rules'),
                'options' => array(
                    //'payment_method' => __('Payment Method', 'wp-loyalty-rules'),
                    //'order_status' => __('Order Status', 'wp-loyalty-rules'),
                    'purchase_history' => __('Purchase History', 'wp-loyalty-rules'),
                    'life_time_sale_value' => __('Life Time Sale value', 'wp-loyalty-rules'),
                )
            )
        );
        $conditions['email_share'] = array(
            'Common' => array(
                'label' => __('Common', 'wp-loyalty-rules'),
                'options' => array(
                    'user_role' => __('User Role', 'wp-loyalty-rules'),
                    'user_point' => __('Customer Points', 'wp-loyalty-rules'),
                    'customer' => __('Customer', 'wp-loyalty-rules'),
                    'language' => __('language', 'wp-loyalty-rules'),
                    'currency' => __('Currency', 'wp-loyalty-rules'),
                )
            ),
            'Order' => array(
                'label' => __('Order', 'wp-loyalty-rules'),
                'options' => array(
                    //'payment_method' => __('Payment Method', 'wp-loyalty-rules'),
                    //'order_status' => __('Order Status', 'wp-loyalty-rules'),
                    'purchase_history' => __('Purchase History', 'wp-loyalty-rules'),
                    'life_time_sale_value' => __('Life Time Sale value', 'wp-loyalty-rules'),
                )
            )
        );
        $conditions['referral'] = array(
            'Common' => array(
                'label' => __('Common', 'wp-loyalty-rules'),
                'options' => array(
                    //'user_role' => __('User Role', 'wp-loyalty-rules'),
                    //'customer' => __('Customer', 'wp-loyalty-rules'),
                    'language' => __('language', 'wp-loyalty-rules'),
                    'currency' => __('Currency', 'wp-loyalty-rules'),
                )
            ),
            /*'Order' => array(
                'label' => __('Order', 'wp-loyalty-rules'),
                'options' => array(
                    'purchase_history' => __('Purchase History', 'wp-loyalty-rules'),
                    'life_time_sale_value' => __('Life Time Sale value', 'wp-loyalty-rules'),
                )
            )*/
        );
        $conditions['followup_share'] = array(
            'Common' => array(
                'label' => __('Common', 'wp-loyalty-rules'),
                'options' => array(
                    'user_role' => __('User Role', 'wp-loyalty-rules'),
                    'user_point' => __('Customer Points', 'wp-loyalty-rules'),
                    'customer' => __('Customer', 'wp-loyalty-rules'),
                    'language' => __('language', 'wp-loyalty-rules'),
                    'currency' => __('Currency', 'wp-loyalty-rules'),
                )
            ),
            'Order' => array(
                'label' => __('Order', 'wp-loyalty-rules'),
                'options' => array(
                    //'payment_method' => __('Payment Method', 'wp-loyalty-rules'),
                    //'order_status' => __('Order Status', 'wp-loyalty-rules'),
                    'purchase_history' => __('Purchase History', 'wp-loyalty-rules'),
                    'life_time_sale_value' => __('Life Time Sale value', 'wp-loyalty-rules'),
                )
            )
        );
        $conditions['achievement'] = array(
            'Common' => array(
                'label' => __('Common', 'wp-loyalty-rules'),
                'options' => array(
                    'user_role' => __('User Role', 'wp-loyalty-rules'),
                    'user_point' => __('Customer Points', 'wp-loyalty-rules'),
                    'customer' => __('Customer', 'wp-loyalty-rules'),
                    'language' => __('Language', 'wp-loyalty-rules'),
                    'currency' => __('Currency', 'wp-loyalty-rules'),
                    'usage_limits' => __('Campaign usage limit per customer', 'wp-loyalty-rules'),
                )
            ),
            /* 'Cart' => array(
                 'label' => __('Cart', 'wp-loyalty-rules'),
                 'options' => array(
                     'cart_subtotal' => __('Cart Subtotal', 'wp-loyalty-rules'),
                     'cart_line_items_count' => __('Line Item Count', 'wp-loyalty-rules'),
                     'cart_weights' => __('Cart Weight', 'wp-loyalty-rules')
                 )
             ),
             'Product' => array(
                 'label' => __('Product', 'wp-loyalty-rules'),
                 'options' => array(
                     'products' => __('Products', 'wp-loyalty-rules'),
                     'product_attributes' => __('Product Attributes', 'wp-loyalty-rules'),
                     'product_category' => __('Product Category', 'wp-loyalty-rules'),
                     'product_sku' => __('Product SKU', 'wp-loyalty-rules'),
                     'product_onsale' => __('On sale products', 'wp-loyalty-rules'),
                     'product_tags' => __('Tags', 'wp-loyalty-rules'),
                 )
             ),
            'Order' => array(
                'label' => __('Order', 'wp-loyalty-rules'),
                'options' => array(
                    'payment_method' => __('Payment Method', 'wp-loyalty-rules'),
                    'order_status' => __('Order Status', 'wp-loyalty-rules'),
                    'purchase_history' => __('Purchase History', 'wp-loyalty-rules'),
                    'purchase_history_qty' => __('Purchase History Quantity', 'wp-loyalty-rules'),
                    'life_time_sale_value' => __('Life Time Sale value', 'wp-loyalty-rules')
                )
            ),*/
           /* 'PurchaseHistory' => array(
                'label' => __('Purchase History', 'wp-loyalty-rules'),
                'options' => array(
                    //'purchase_first_order' => __('First Order', 'wp-loyalty-rules'),
                    //'purchase_last_order' => __('Last Order', 'wp-loyalty-rules'),
                    //'purchase_last_order_amount' => __('Last order amount', 'wp-loyalty-rules'),
                    'purchase_previous_orders' => __('Number of orders made', 'wp-loyalty-rules'),
                    'purchase_previous_orders_for_specific_product' => __('Number of orders made with following products', 'wp-loyalty-rules'),
                    'purchase_quantities_for_specific_product' => __('Number of quantities made with following products', 'wp-loyalty-rules'),
                    'purchase_spent' => __('Total spent', 'wp-loyalty-rules')
                )
            )*/
        );
        //
        return apply_filters('wlr_pro_conditions', $conditions);
    }

    function getPointSubtotal($point, $rule, $data)
    {
        $point_for_purchase = Subtotal::getInstance();
        return $point_for_purchase->getTotalEarnPoint($point, $rule, $data);
    }

    function getCouponSubtotal($point, $rule, $data)
    {
        $point_for_purchase = Subtotal::getInstance();
        return $point_for_purchase->getTotalEarnReward($point, $rule, $data);
    }

    function getPointPurchaseHistories($point, $rule, $data)
    {
        $point_for_purchase = PurchaseHistories::getInstance();
        return $point_for_purchase->getTotalEarnPoint($point, $rule, $data);
    }

    function getCouponPurchaseHistories($point, $rule, $data)
    {
        $point_for_purchase = PurchaseHistories::getInstance();
        return $point_for_purchase->getTotalEarnReward($point, $rule, $data);
    }


    function addLevelCondition($conditions)
    {
        if (empty($conditions)) {
            return $conditions;
        }
        $allowed_conditions = array(
            'point_for_purchase',
            'subtotal',
            'purchase_histories',
            'product_review',
            'birthday',
            'facebook_share',
            'twitter_share',
            'whatsapp_share',
            'email_share',
            'followup_share'
        );
        foreach ($conditions as $key => &$condition) {
            if (isset($key) && in_array($key, $allowed_conditions)) {
                if (isset($condition['Common']) && isset($condition['Common']['options'])) {
                    $condition['Common']['options']['user_level'] = __('Customer Level', 'wp-loyalty-rules');
                }
            }
        }
        return $conditions;
    }
}