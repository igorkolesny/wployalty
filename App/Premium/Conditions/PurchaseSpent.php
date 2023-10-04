<?php
/**
 * @author      Wployalty (Alagesan)
 * @license     http://www.gnu.org/licenses/gpl-2.0.html
 * @link        https://www.wployalty.net
 * */

namespace Wlr\App\Premium\Conditions;
defined('ABSPATH') or die();

use Wlr\App\Conditions\Base;

class PurchaseSpent extends Base
{
    protected static $cache_order_count = array();

    public function __construct()
    {
        parent::__construct();
        $this->name = 'purchase_spent';
        $this->label = __('Total spent', 'wp-loyalty-rules');
        $this->group = __('Purchase History', 'wp-loyalty-rules');
    }

    public function isProductValid($options, $data)
    {
        return $this->check($options, $data);
    }

    function check($options, $data)
    {
        if (isset($options->operator) && isset($options->time) && isset($options->value) && $options->value >= 0) {
            $conditions = array();
            $billing_email = isset($data['user_email']) && !empty($data['user_email']) ? $data['user_email'] : '';
            if (!empty($billing_email)) {
                $conditions = array(
                    array('key' => '_billing_email', 'value' => $billing_email, 'compare' => '=')
                );
            }
            if (!empty($conditions)) {
                $is_calculate_base = isset($data['is_calculate_based']) && !empty($data['is_calculate_based']) ? $data['is_calculate_based'] : '';
                $cache_key = $this->generateBase64Encode($options);
                $total_spent = 0;
                if (isset(self::$cache_order_count[$cache_key])) {
                    $total_spent = self::$cache_order_count[$cache_key];
                } else if (!empty($is_calculate_base) && isset($data[$is_calculate_base])) {
                    $args = array(
                        'meta_query' => $conditions
                    );
                    if (isset($options->status) && is_array($options->status) && !empty($options->status)) {
                        $args['post_status'] = self::$woocommerce_helper->changeToQueryStatus($options->status);
                    }
                    if ($options->time != "all_time") {
                        $args['date_query'] = array('after' => $this->getDateByString($options->time, 'Y-m-d') . ' 00:00:00');
                    }
                    if ($is_calculate_base === 'order' && !empty($data[$is_calculate_base])) {
                        $current_order = self::$woocommerce_helper->getOrder($data[$is_calculate_base]);
                        $args['post__not_in'] = array(self::$woocommerce_helper->getOrderId($current_order));
                    }
                    $orders = apply_filters('wlr_purchase_spent_orders', self::$woocommerce_helper->getOrdersThroughWPQuery($args), $args);
                    if (!empty($orders)) {
                        foreach ($orders as $order) {
                            if (!empty($order) && isset($order->ID)) {
                                $order_obj = self::$woocommerce_helper->getOrder($order->ID);
                                $total_spent += apply_filters('wlr_purchase_spent_order_total', self::$woocommerce_helper->getOrderTotal($order_obj), $order_obj);
                            }
                        }
                    }
                    self::$cache_order_count[$cache_key] = $total_spent;
                }
                return $this->doComparisionOperation($options->operator, $total_spent, $options->value);
            }
        }
        return false;
    }
}