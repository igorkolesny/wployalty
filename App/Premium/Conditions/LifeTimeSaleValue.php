<?php
/**
 * @author      Wployalty (Alagesan)
 * @license     http://www.gnu.org/licenses/gpl-2.0.html
 * @link        https://www.wployalty.net
 * */

namespace Wlr\App\Premium\Conditions;

use Wlr\App\Conditions\Base;

defined('ABSPATH') or die();

class LifeTimeSaleValue extends Base
{
    function __construct()
    {
        parent::__construct();
        $this->name = 'life_time_sale_value';
        $this->label = __('Life Time Sale value', 'wp-loyalty-rules');
        $this->group = __('Cart', 'wp-loyalty-rules');
    }

    public function isProductValid($options, $data)
    {
        return $this->check($options, $data);
    }

    public function check($options, $data)
    {
        $status = false;
        if (isset($options->operator) && isset($options->value)) {
            $operator = sanitize_text_field($options->operator);
            $order_status = isset($options->order_status) && !empty($options->order_status) ? $options->order_status : array();
            $value = $options->value;
            $purchase_total_value = $this->getLifeTimeSaleValue($data['user_email'], $order_status);
            $status = $this->doComparisionOperation($operator, $purchase_total_value, $value);
        }
        return $status;
    }

    protected function getLifeTimeSaleValue($email, $order_status)
    {
        $total = 0;
        if (empty($email) || empty($order_status)) {
            return $total;
        }
        $orders = wc_get_orders(array('billing_email' => $email, 'status' => $order_status, 'limit' => -1));
        foreach ($orders as $order) {
            $total += apply_filters('wlr_life_time_sale_value_order_total', self::$woocommerce_helper->getOrderTotal($order), $order);
        }
        return $total;
    }

}