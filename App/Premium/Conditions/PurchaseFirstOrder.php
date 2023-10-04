<?php
/**
 * @author      Wployalty (Alagesan)
 * @license     http://www.gnu.org/licenses/gpl-2.0.html
 * @link        https://www.wployalty.net
 * */

namespace Wlr\App\Premium\Conditions;
defined('ABSPATH') or die();

use Wlr\App\Conditions\Base;

class PurchaseFirstOrder extends Base
{
    protected static $cache_order_count = array();

    public function __construct()
    {
        parent::__construct();
        $this->name = 'purchase_first_order';
        $this->label = __('First order', 'wp-loyalty-rules');
        $this->group = __('Purchase History', 'wp-loyalty-rules');
    }

    public function isProductValid($options, $data)
    {
        return $this->check($options, $data);
    }

    function check($options, $data)
    {
        $conditions = array();
        $billing_email = isset($data['user_email']) && !empty($data['user_email']) ? $data['user_email'] : '';
        if (!empty($billing_email)) {
            $conditions = array(
                array('key' => '_billing_email', 'value' => $billing_email, 'compare' => '=')
            );
        }
        if (!empty($conditions)) {
            $cache_key = $this->generateBase64Encode($options);
            if (isset(self::$cache_order_count[$cache_key])) {
                $orders = self::$cache_order_count[$cache_key];
            } else {
                $args = array(
                    'posts_per_page' => 2,
                    'meta_query' => $conditions,
                );
                $orders = self::$woocommerce_helper->getOrdersThroughWPQuery($args);
            }

            $first_order = (int)isset($options->value) ? $options->value : 1;
            $is_calculate_base = isset($data['is_calculate_based']) && !empty($data['is_calculate_based']) ? $data['is_calculate_based'] : '';
            if ($is_calculate_base === 'cart' && isset($data[$is_calculate_base]) && !empty($data[$is_calculate_base])) {
                if ($first_order) {
                    return empty($orders);
                } else {
                    return !empty($orders);
                }
            } elseif ($is_calculate_base === 'order' && isset($data[$is_calculate_base]) && !empty($data[$is_calculate_base])) {
                if ($first_order) {
                    return count($orders) <= 1;
                } else {
                    return count($orders) > 1;
                }
            }
        }
        return false;
    }
}