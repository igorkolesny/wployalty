<?php
/**
 * @author      Wployalty (Alagesan)
 * @license     http://www.gnu.org/licenses/gpl-2.0.html
 * @link        https://www.wployalty.net
 * */

namespace Wlr\App\Models;
defined('ABSPATH') or die();

class UserRewards extends Base
{

    static $available_user_rewards;
    public static $user_reward_by_email = array();

    function __construct()
    {
        parent::__construct();
        $this->table = self::$db->prefix . 'wlr_user_rewards';
        $this->primary_key = 'id';
        $this->fields = array(
            'name' => '%s',
            'description' => '%s',
            'email' => '%s',
            'reward_id' => '%d',
            'campaign_id' => '%d',
            'reward_type' => '%s', // 'redeem_point','redeem_coupon'
            'action_type' => '%s', // 'point_for_purchase', 'subtotal_based', etc..
            'discount_type' => '%s', // 'free_product','free_shipping',etc..
            'discount_value' => '%s', // reward value - in cart created reward value
            'reward_currency' => '%s', // reward_value generate time, we must add current currency also
            'discount_code' => '%s', // generated discount code
            'discount_id' => '%d', // generated discount amount
            'display_name' => '%s',
            'require_point' => '%d', // required point for generate discount code
            'status' => '%s', // open -  reward still not active, but created(used for redeem_point type), active - reward created and active(user limit didn't reached), used - reward used(user limit reached),expired - reward expired
            'start_at' => '%s',
            'end_at' => '%s',
            'icon' => '%s',
            'expire_email_date' => '%s',
            'is_expire_email_send' => '%d',
            'usage_limits' => '%d',
            'conditions' => '%s',
            'condition_relationship' => '%s',
            'free_product' => '%s',
            'expire_after' => '%d',
            'expire_period' => '%s',
            'enable_expiry_email' => '%d',
            'expire_email' => '%d',
            'expire_email_period' => '%s',
            'minimum_point' => '%d',
            'maximum_point' => '%d',
            'created_at' => '%s',
            'modified_at' => '%s',
            'is_show_reward' => '%d',
        );
    }

    function beforeTableCreation()
    {
    }

    function runTableCreation()
    {
        $create_table_query = "CREATE TABLE IF NOT EXISTS {$this->table} (
				 `{$this->getPrimaryKey()}` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                 `name` varchar(180) DEFAULT NULL,
                 `description` TEXT DEFAULT NULL,
                 `email` varchar(180) DEFAULT NULL,
                 `reward_id` BIGINT SIGNED DEFAULT 0,
                 `campaign_id` BIGINT SIGNED DEFAULT 0,
                 `reward_type` enum('redeem_point','redeem_coupon') DEFAULT 'redeem_point',
                 `action_type` varchar(180) DEFAULT NULL,
                 `discount_type` varchar(180) DEFAULT NULL,
                 `discount_value` decimal(12,4) DEFAULT 0,
                 `reward_currency` varchar(180) DEFAULT NULL,
                 `discount_code` varchar(180) DEFAULT NULL,
                 `discount_id` BIGINT SIGNED DEFAULT 0,
                 `display_name` varchar(180) DEFAULT NULL,
                 `require_point` int(11) DEFAULT 0,
                 `status` enum('open','active','used','expired') DEFAULT 'open',
                 `start_at`  BIGINT DEFAULT 0,
                 `end_at`  BIGINT DEFAULT 0,
                 `icon` varchar(180) DEFAULT NULL,
                 `expire_email_date`  BIGINT DEFAULT 0,
                 `is_expire_email_send` int(3) DEFAULT 0,
                 `usage_limits` int(11) DEFAULT 0,
                 `condition_relationship` enum('and','or') DEFAULT 'and',
                 `conditions` LONGTEXT DEFAULT NULL,
                 `free_product` TEXT DEFAULT NULL,
                 `expire_after` int(11) DEFAULT 0,
                 `expire_period` enum('day','week','month','year') DEFAULT 'day',
                 `enable_expiry_email` int(4) DEFAULT 1,
                 `expire_email` int(11) DEFAULT 0,
                 `expire_email_period` enum('day','week','month','year') DEFAULT 'day',
                 `minimum_point` int(11) DEFAULT 0,
                 `maximum_point` int(11) DEFAULT 0,
                 `created_at` BIGINT DEFAULT 0,
                 `modified_at` BIGINT DEFAULT 0,
                 `is_show_reward` smallint DEFAULT 1,
                 PRIMARY KEY (`{$this->getPrimaryKey()}`)
			)";
        $this->createTable($create_table_query);
    }

    function afterTableCreation()
    {
        if ($this->checkTableExists()) {
            $existing_columns = $this->getTableFields();
            if (!in_array('enable_expiry_email', $existing_columns)) {
                self::$db->query(
                    "ALTER TABLE `{$this->table}` ADD COLUMN enable_expiry_email INT(4) DEFAULT 0"
                );
            }
            if (!in_array('minimum_point', $existing_columns)) {
                self::$db->query(
                    "ALTER TABLE `{$this->table}` ADD COLUMN minimum_point int(11) DEFAULT 0"
                );
            }
            if (!in_array('maximum_point', $existing_columns)) {
                self::$db->query(
                    "ALTER TABLE `{$this->table}` ADD COLUMN maximum_point int(11) DEFAULT 0"
                );
            }
            if (!in_array('icon', $existing_columns)) {
                self::$db->query(
                    "ALTER TABLE `{$this->table}` ADD COLUMN icon varchar(180) DEFAULT NULL"
                );
            }
            if (!in_array('is_show_reward', $existing_columns)) {
                self::$db->query(
                    "ALTER TABLE `{$this->table}` ADD COLUMN is_show_reward smallint DEFAULT 1"
                );
            }
        }
        $index_fields = array('email', 'discount_code', 'status', 'end_at', 'is_expire_email_send', 'expire_email_date', 'created_at',);
        $this->insertIndex($index_fields);
    }

    function getExpireEmailList()
    {
        $current_date = date('Y-m-d H:i:s');
        $where = self::$db->prepare('expire_email_date < %s AND expire_email_date != %s AND is_expire_email_send = %d AND status NOT IN("%s","%s")', array(strtotime($current_date), 0, 0, 'used', 'expired'));
        return $this->getWhere($where, '*', false);
    }

    function getExpireStatusNeedToChangeList()
    {
        $current_date = date("Y-m-d h:i:s");
        $where = self::$db->prepare('end_at < %s AND end_at != %d AND status NOT IN("%s","%s")', array(strtotime($current_date), 0, 'used', 'expired'));
        return $this->getWhere($where, '*', false);
    }

    function getUserRewardByEmail($user_email)
    {
        if (empty($user_email)) {
            return array();
        }
        $current = date('Y-m-d H:i:s');
        if (!isset(self::$user_reward_by_email[$user_email]) || !isset(self::$user_reward_by_email[$user_email][$current])) {
            if (!isset(self::$user_reward_by_email[$user_email])) {
                self::$user_reward_by_email[$user_email] = array();
            }
            $where = self::$db->prepare('email = %s AND status NOT IN("%s","%s") AND (end_at >= %s OR end_at = 0)', array(sanitize_email($user_email), 'used', 'expired', strtotime($current)));
            $filter_order = 'discount_code';
            $filter_order_dir = 'DESC';
            $order_by_sql = sanitize_sql_orderby("{$filter_order} {$filter_order_dir}");
            $order_by = '';
            if (!empty($order_by_sql)) {
                $order_by = " ORDER BY {$order_by_sql}";
            }
            if (!empty($order_by)) {
                $where .= $order_by;
            }
            self::$user_reward_by_email[$user_email][$current] = $this->getWhere($where, '*', false);
        }
        return self::$user_reward_by_email[$user_email][$current];
    }

    function checkRewardUsedCount($user_email, $reward_id, $reward_type = 'redeem_point')
    {
        if (empty($user_email) || $reward_id <= 0 || empty($reward_type)) {
            return 0;
        }
        $user_reward_transaction = new UserRewards();
        global $wpdb;
        $where = $wpdb->prepare('reward_type = %s AND email = %s AND reward_id = %s', array($reward_type, $user_email, $reward_id));
        $user_reward_count = $user_reward_transaction->getWhere($where, 'COUNT(*) as total_count', true);
        return !empty($user_reward_count) ? $user_reward_count->total_count : 0;
    }
}