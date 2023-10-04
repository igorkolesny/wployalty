<?php
/**
 * @author      Wployalty (Alagesan)
 * @license     http://www.gnu.org/licenses/gpl-2.0.html
 * @link        https://www.wployalty.net
 * */

namespace Wlr\App\Premium\Conditions;
defined('ABSPATH') or die();

use Wlr\App\Conditions\Base;

class PurchasePreviousOrders extends Base
{
    protected static $cache_order_count = array();

    public function __construct()
    {
        parent::__construct();
        $this->name = 'purchase_previous_orders';
        $this->label = __('Number of orders made', 'wp-loyalty-rules');
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
            $is_calculate_base = isset($data['is_calculate_based']) && !empty($data['is_calculate_based']) ? $data['is_calculate_based'] : '';
            if (!empty($conditions)) {
                $cache_key = $this->generateBase64Encode($options);
                $order_count = 0;
                if (isset(self::$cache_order_count[$cache_key])) {
                    $order_count = self::$cache_order_count[$cache_key];
                } else if (!empty($is_calculate_base) && isset($data[$is_calculate_base])) {
                    $args = array(
                        'meta_query' => $conditions,
                    );
                    if ($is_calculate_base === 'order' && !empty($data[$is_calculate_base])) {
                        $current_order = self::$woocommerce_helper->getOrder($data[$is_calculate_base]);
                        $args['post__not_in'] = array(self::$woocommerce_helper->getOrderId($current_order));
                    }
                    if (isset($options->status) && is_array($options->status) && !empty($options->status)) {
                        $args['post_status'] = self::$woocommerce_helper->changeToQueryStatus($options->status);
                    }
                    if ($options->time != "all_time") {
                        $args['date_query'] = array('after' => $this->getDateByString($options->time, 'Y-m-d') . ' 00:00:00');
                    }
                    $orders = self::$woocommerce_helper->getOrdersThroughWPQuery($args);
                    $order_count = self::$cache_order_count[$cache_key] = count($orders);
                }
                return $this->doComparisionOperation($options->operator, $order_count, $options->value);
            }
        }
        return false;
    }
}