<?php
/**
 * @author      Wployalty (Alagesan)
 * @license     http://www.gnu.org/licenses/gpl-2.0.html
 * @link        https://www.wployalty.net
 * */

namespace Wlr\App\Premium\Conditions;

use Wlr\App\Conditions\Base;

defined('ABSPATH') or die();

class PurchaseHistory extends Base
{
    function __construct()
    {
        parent::__construct();
        $this->name = 'purchase_history';
        $this->label = __('Purchase History', 'wp-loyalty-rules');
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
            $purchase_count = $this->getPurchaseCount($data['user_email'], $order_status);
            $status = $this->doComparisionOperation($operator, $purchase_count, $value);
        }
        return $status;
    }

    protected function getPurchaseCount($email, $order_status)
    {
        $count = 0;
        if (empty($email) || empty($order_status)) {
            return $count;
        }
        $orders = wc_get_orders(array('billing_email' => $email, 'status' => $order_status, 'limit' => -1));
        return count($orders);
        /*  foreach ($order_status as &$or_status) {
              $or_status = preg_replace('/^wc-/', '', $or_status);
              $or_status = 'wc-' . $or_status;
          }
          global $wpdb;
          $query = "SELECT COUNT(DISTINCT order_table.ID) as total_count FROM {$wpdb->posts} as order_table
          INNER JOIN {$wpdb->postmeta} as order_email ON order_table.ID = order_email.post_id";
          $where = $wpdb->prepare("order_table.post_type = %s AND order_table.post_status IN('".implode("','",$order_status)."')", array('shop_order'));
          $where .= $wpdb->prepare(' AND order_email.meta_key = %s AND order_email.meta_value=%s', array('_billing_email', sanitize_email($email)));
          $query .= ' WHERE ' . $where;
          $list = $wpdb->get_row($query, OBJECT);
          if (isset($list->total_count) && $list->total_count > 0) {
              $count = $list->total_count;
          }
          return $count;*/
    }

}