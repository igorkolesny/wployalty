<?php
/**
 * @author      Wployalty (Alagesan)
 * @license     http://www.gnu.org/licenses/gpl-2.0.html
 * @link        https://www.wployalty.net
 * */

namespace Wlr\App\Premium\Conditions;

use Wlr\App\Conditions\Base;

defined('ABSPATH') or die();

class PurchaseHistoryQty extends Base
{
    function __construct()
    {
        parent::__construct();
        $this->name = 'purchase_history_qty';
        $this->label = __('Purchase History Quantity', 'wp-loyalty-rules');
        $this->group = __('Cart', 'wp-loyalty-rules');
    }

    public function isProductValid($options, $data)
    {
        return $this->check($options, $data);
    }

    public function check($options, $data)
    {
        $status = false;
        if (isset($options->operator) && isset($options->value) && isset($options->order_status)
            && isset($options->product_action_type) && isset($options->purchase_before) && isset($options->condition)) {
            $order_status = !empty($options->order_status) ? $options->order_status : array();//completed
            $product_action_type = !empty($options->product_action_type) ? $options->product_action_type : '';//products
            $value = !empty($options->value) ? $options->value : array();// produtc_id
            $operator = sanitize_text_field($options->operator);// in_list
            $purchase_before = !empty($options->purchase_before) ? $options->purchase_before : 'all_time';//
            $condition = !empty($options->condition) ? $options->condition : 'greater_than_or_equal';
            $product_count = !empty($options->qty) ? $options->qty : '';//product count
            $purchase_order_ids = $this->getPurchaseOrderIds($data['user_email'], $order_status, $purchase_before);
            if (empty($purchase_order_ids)) {
                return false;
            }
            $all_product_ids = $this->getOrderProductIds($product_action_type, $value);
            if (empty($all_product_ids)) {
                return false;
            }
            if ($purchase_order_ids && $all_product_ids) {
                $order_ids = implode(',', $purchase_order_ids);
                $product_ids = implode(',', $all_product_ids);
                global $wpdb;
                /*$query = "SELECT {$wpdb->prefix}woocommerce_order_items.order_item_id FROM {$wpdb->prefix}woocommerce_order_items INNER JOIN {$wpdb->prefix}woocommerce_order_itemmeta ON {$wpdb->prefix}woocommerce_order_items.order_item_id = {$wpdb->prefix}woocommerce_order_itemmeta.order_item_id
WHERE ({$wpdb->prefix}woocommerce_order_items.order_id IN({$order_ids}) AND {$wpdb->prefix}woocommerce_order_items.order_item_type = 'line_item') AND ({$wpdb->prefix}woocommerce_order_itemmeta.meta_key = '_product_id' AND {$wpdb->prefix}woocommerce_order_itemmeta.meta_value IN({$product_ids}))";*/
                $query = "SELECT SUM(order_item_meta.meta_value) as product_qty FROM {$wpdb->prefix}woocommerce_order_itemmeta as order_item_meta WHERE (order_item_meta.meta_key = '_qty' ) AND order_item_meta.order_item_id IN (SELECT {$wpdb->prefix}woocommerce_order_items.order_item_id FROM {$wpdb->prefix}woocommerce_order_items INNER JOIN {$wpdb->prefix}woocommerce_order_itemmeta ON {$wpdb->prefix}woocommerce_order_items.order_item_id = {$wpdb->prefix}woocommerce_order_itemmeta.order_item_id
WHERE ({$wpdb->prefix}woocommerce_order_items.order_id IN({$order_ids}) AND {$wpdb->prefix}woocommerce_order_items.order_item_type = 'line_item')";
                if ($operator == 'in_list') {
                    $query .= " AND ({$wpdb->prefix}woocommerce_order_itemmeta.meta_key = '_product_id' AND {$wpdb->prefix}woocommerce_order_itemmeta.meta_value IN({$product_ids})))";
                } else {
                    $query .= " AND ({$wpdb->prefix}woocommerce_order_itemmeta.meta_key = '_product_id' AND {$wpdb->prefix}woocommerce_order_itemmeta.meta_value NOT IN({$product_ids})))";
                }
                $item = $wpdb->get_row($query);
                if (empty($item)) {
                    return false;
                }
                $status = $this->comparsion($condition, $product_count, $item->product_qty);
            }
        }
        return $status;
    }

    protected function getPurchaseOrderIds($email, $order_status, $purchase_before)
    {
        if (empty($email) || empty($order_status) || empty($purchase_before)) {
            return false;
        }
        foreach ($order_status as &$o_status) {
            $o_status = 'wc-' . $o_status;
        }

        $args = array(
            'meta_query' => array(array('key' => '_billing_email', 'value' => $email, 'compare' => '='))
        );

        $args['post_status'] = $order_status;
        if ($purchase_before != "all_time") {
            $args['date_query'] = array('after' => $this->getDateByString($purchase_before, 'Y-m-d') . ' 00:00:00');
        }

        $default_args = array(
            'posts_per_page' => -1,
            'post_type' => self::$woocommerce_helper->getOrderPostType(),
            'post_status' => array_keys(self::$woocommerce_helper->getOrderStatusList()),
            'orderby' => 'ID',
            'order' => 'DESC'
        );
        $args = array_merge($default_args, $args);
        $query = new \WP_Query($args);
        $orders = $query->get_posts();
        if (empty($orders)) {
            return false;
        }
        return array_column($orders, 'ID');
    }

    function getDateByString($value, $format = 'Y-m-d H:i:s')
    {
        if (!empty($value)) {
            $value = str_replace('_', ' ', $value);
            try {
                $date = new \DateTime(current_time('mysql'));
                $date->modify($value);
                return $date->format($format);
            } catch (\Exception $e) {
            }
        }
        return false;
    }

    protected function getOrderProductIds($product_action_type, $value)
    {
        if (empty($product_action_type) || empty($value)) {
            return array();
        }
        $product_ids = array();
        switch ($product_action_type) {
            case 'products':
                foreach ($value as $product) {
                    $product_ids[] = $product->value;
                }
                break;
            case 'productCategory':
                $cat_slugs = array();
                foreach ($value as $category) {
                    $cat_slug = get_term((int)$category->value)->slug;
                    if (!empty($cat_slug)) {
                        $cat_slugs[] = $cat_slug;
                    }
                }
                if (empty($cat_slugs)) {
                    return $product_ids;
                }
                $args = array(
                    'category' => $cat_slugs,
                    'limit' => -1
                );
                $products = wc_get_products($args);
                foreach ($products as $product) {
                    $product_ids[] = $product->get_id();
                }
                break;
            case 'productSku':
                foreach ($value as $pro_sku) {
                    $product_id = wc_get_product_id_by_sku($pro_sku->value);
                    if (!empty($product_id)) {
                        $product_ids[] = $product_id;
                    }
                }
                break;
            case 'productTags':
                $product_tags = array();
                foreach ($value as $tag) {
                    $tag_slug = get_term((int)$tag->value)->slug;
                    if (!empty($tag_slug)) {
                        $product_tags[] = $tag_slug;
                    }
                }
                if (empty($product_tags)) {
                    return $product_ids;
                }
                $args = array(
                    'tag' => $product_tags,
                    'limit' => -1
                );
                $products = wc_get_products($args);
                foreach ($products as $product) {
                    $product_ids[] = $product->get_id();
                }
                break;
        }
        return $product_ids;
    }

    function comparsion($comparision_operator, $comparision_quantity, $quantity)
    {
        $compare_list = array();
        switch ($comparision_operator) {
            case 'less_than':
                if ($quantity < $comparision_quantity && $quantity > 0) {
                    $compare_list[] = 'yes';
                } else {
                    $compare_list[] = 'no';
                }
                break;
            case 'greater_than_or_equal':
                if ($quantity >= $comparision_quantity && $quantity > 0) {
                    $compare_list[] = 'yes';
                } else {
                    $compare_list[] = 'no';
                }
                break;
            case 'greater_than':
                if ($quantity > $comparision_quantity && $quantity > 0) {
                    $compare_list[] = 'yes';
                } else {
                    $compare_list[] = 'no';
                }
                break;
            default:
            case 'less_than_or_equal':
                if ($quantity <= $comparision_quantity && $quantity > 0) {
                    $compare_list[] = 'yes';
                } else {
                    $compare_list[] = 'no';
                }
                break;
        }
        if (!empty($compare_list) && in_array('no', $compare_list)) {
            return false;
        } else if ((!empty($compare_list) && in_array('yes', $compare_list))) {
            return true;
        }
        return false;
    }

    function getOrderItemsQty($order)
    {
        $order_items = self::$woocommerce_helper->getOrderItems($order);
        $productIds = array();
        if (!empty($order_items)) {
            foreach ($order_items as $item) {
                $product_id = $item->get_product_id();
                $variant_id = $item->get_variation_id();
                $quantity = $item->get_quantity();
                if ($variant_id) {
                    $productId = $variant_id;
                } else {
                    $productId = $product_id;
                }
                if (isset($productIds[$productId])) {
                    $productIds[$productId] = $productIds[$productId] + $quantity;
                } else {
                    $productIds[$productId] = $quantity;
                }
            }
        }

        return $productIds;
    }
}