<?php
/**
 * @author      Wployalty (Alagesan)
 * @license     http://www.gnu.org/licenses/gpl-3.0.html
 * @link        https://www.wployalty.net
 * */

namespace Wlr\App\Premium\Controllers\Admin;

use Wlr\App\Controllers\Base;
use Wlr\App\Helpers\CsvHelper;
use Wlr\App\Helpers\Woocommerce;
use Wlr\App\Models\Users;
use Exception;

defined('ABSPATH') or die;

class Customers extends Base
{
    /**
     * Import preview page
     * @return void
     */
    function importPreview()
    {
        $wlr_nonce = (string)self::$input->post_get('wlr_nonce', '');
        $result = array();
        if (Woocommerce::hasAdminPrivilege() && Woocommerce::verify_nonce($wlr_nonce, 'wlr-user-nonce') && isset($_FILES['importfile']) && !empty($_FILES['importfile'])) {
            $file = $_FILES['importfile'];
            $process = false;
            if (isset($file['name'])) {
                $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                if ($ext == 'csv') {
                    $process = true;
                }
            }
            $redirect = false;
            if ((isset($file['error']) && $file['error'] > 0)) {
                $redirect = true;
            }
            if (!empty($file['tmp_name']) && $process) {
                $file_limit = (int)self::$input->post_get('limit', 5);
                $csv_helper = CsvHelper::getInstance();
                $total = $csv_helper->getTotalRecord($file['tmp_name']);
                if ($total > 0) {
                    $header = $csv_helper->getFirstValue($file['tmp_name']);
                    $filename = basename(preg_replace('/[^a-zA-Z0-9\.\-\s+]/', '', html_entity_decode($file ['name'], ENT_QUOTES, 'UTF-8')));
                    $file_path = WLR_PLUGIN_PATH . 'App/File';
                    $filepath = trim($file_path . '/' . $filename);
                    if (is_file($filepath)) {
                        if ($csv_helper->file_delete($filepath)) {
                            $csv_helper->file_upload($file ['tmp_name'], $filepath);
                        }
                    } else {
                        $csv_helper->file_upload($file ['tmp_name'], $filepath);
                    }
                    $need_update = (string)self::$input->post_get('need_update', 'no');
                    $file['holding_name'] = $file['tmp_name'];
                    $file['holding_name'] = str_replace('/tmp/', '/holding/', $file['holding_name']);
                    unset($file['tmp_name']);
                    $page_details = array(
                        'need_update' => $need_update,
                        'total_count' => $total,
                        'process_count' => 0,
                        'header' => $header,
                        'file' => $file,
                        'limit' => $file_limit,
                    );
                    $result['data'] = $page_details;
                    $result['success'] = 'completed';
                } else {
                    $redirect = true;
                }
            } else {
                $redirect = true;
            }
            if ($redirect) {
                $result['redirect'] = admin_url('admin.php?' . http_build_query(array('page' => WLR_PLUGIN_SLUG, 'view' => 'point_users')));
            }
        } else {
            $result['redirect'] = admin_url('admin.php?' . http_build_query(array('page' => WLR_PLUGIN_SLUG, 'view' => 'point_users')));
        }
        wp_send_json($result);
    }

    /**
     * process import page
     * @return void
     */
    function processImport()
    {
        $wlr_nonce = (string)self::$input->post_get('wlr_nonce', '');
        $data = array(
            'success' => false,
            'data' => array()
        );
        if (Woocommerce::hasAdminPrivilege() && Woocommerce::verify_nonce($wlr_nonce, 'wlr-user-nonce')) {
            $csv_helper = CsvHelper::getInstance();
            $file_data = array();
            $limit_start = (int)self::$input->post_get('limit_start', 0);
            $limit = (int)self::$input->post_get('limit', 5);
            $file_tmp_name = (string)self::$input->post_get('holding_name', '');
            $file_tmp_name = str_replace('/holding/', '/tmp/', $file_tmp_name);
            $original_file_name = (string)self::$input->post_get('name', '');
            $process = false;
            if (!empty($original_file_name)) {
                $ext = pathinfo($original_file_name, PATHINFO_EXTENSION);
                if ($ext == 'csv') {
                    $process = true;
                }
            }
            if ($process && !empty($file_tmp_name)) {
                $filename = basename(preg_replace('/[^a-zA-Z0-9\.\-\s+]/', '', html_entity_decode($original_file_name, ENT_QUOTES, 'UTF-8')));
                $file_path = WLR_PLUGIN_PATH . 'App/File';
                $filepath = trim($file_path . '/' . $filename);
                $file_data = $csv_helper->getCsvData($filepath, $limit_start, $limit);
            }

            if (!empty($file_data)) {
                $need_update = (string)self::$input->post_get('need_update', 'no');
                $update_type = (string)self::$input->post_get('update_type', 'equal');
                $csv_helper->save_user($file_data, $need_update, $update_type);
                if (count($file_data) < $limit) {
                    $limit = count($file_data);
                }
                $data['success'] = true;
                $data['data']['success'] = 'incomplete';
                $data['data']['limit_start'] = $limit_start + $limit;
                $data['data']['notification'] = sprintf(__('Insert/Update %s customer', 'wp-loyalty-rules'), count($file_data));
            }
            if (empty($data['data'])) {
                if (isset($filepath) && !empty($filepath)) {
                    $csv_helper->file_delete($filepath);
                }
                $data['success'] = true;
                $data['data']['success'] = 'completed';
                $data['data']['message'] = __('Import customer successfully', 'wp-loyalty-rules');
                $data['data']['redirect'] = admin_url('admin.php?' . http_build_query(array('page' => WLR_PLUGIN_SLUG, 'view' => 'point_users')));
            }
        } else {
            $data['success'] = false;
            $data['data']['success'] = 'error';
            $data['data']['message'] = __('Invalid request', 'wp-loyalty-rules');
        }
        wp_send_json($data);
    }

    /**
     * export preview page
     * @return void
     */
    function exportPreview()
    {
        $wlr_nonce = (string)self::$input->post_get('wlr_nonce', '');
        $data = array();
        if (Woocommerce::hasAdminPrivilege() && Woocommerce::verify_nonce($wlr_nonce, 'wlr-user-nonce')) {
            $path = WLR_PLUGIN_PATH . 'App/File';
            $file_name = 'customer_export_*.*';
            $delete_file_path = trim($path . '/' . $file_name);
            foreach (glob($delete_file_path) as $file_path) {
                if (file_exists($file_path)) {
                    wp_delete_file($file_path);
                }
            }
            $base_url = admin_url('admin.php?' . http_build_query(array('page' => WLR_PLUGIN_SLUG, 'view' => 'point_users')));
            $user_point_table = new Users();
            $total_count = $user_point_table->getQueryData(array('id' => array('operator' => '>', 'value' => 0)), 'COUNT( DISTINCT id) as total_count', array(), false, true);
            $data['success'] = true;
            $data['data'] = array(
                'base_url' => $base_url,
                'total_count' => (int)$total_count->total_count,
                'process_count' => 0,
                'limit_start' => 0,
                'limit' => 5
            );
        } else {
            $data['success'] = false;
            $data['data'] = array(
                'message' => __('Export preview nonce failed', 'wp-loyalty-rules')
            );
        }
        wp_send_json($data);
    }

    /**
     * export process page
     * @return void
     */
    function exportProcess()
    {
        $wlr_nonce = (string)self::$input->post_get('wlr_nonce', '');
        $data = array(
            'data' => array()
        );
        if (Woocommerce::hasAdminPrivilege() && Woocommerce::verify_nonce($wlr_nonce, 'wlr-user-nonce')) {
            $limit_start = (int)self::$input->post_get('limit_start', 0);
            $limit = (int)self::$input->post_get('limit', 5);
            $total_count = (int)self::$input->post_get('total_count', 0);
            if ($total_count > $limit_start) {
                $path = WLR_PLUGIN_PATH . 'App/File';
                $file_name = 'customer_export_';
                $file_count = 0;
                if ($limit_start >= 499) {
                    $file_count = round(($limit_start / 499));
                }
                $file_path = trim($path . '/' . $file_name . $file_count . '.csv');
                $point_table = new Users();
                /*$point_query = array(
                    'id' => array('operator' => '>', 'value' => 0),
                    'filter_order' => 'id',
                    'filter_order_dir' => 'ASC',
                    'limit' => $limit,
                    'offset' => $limit_start
                );
                $file_data = $point_table->getQueryData($point_query,'user_email,points,refer_code',array(),true,false);*/
                global $wpdb;
                $table = $point_table->getTableName();
                $where = $wpdb->prepare('id > 0 ORDER BY id ASC LIMIT %d OFFSET %d;', array($limit, $limit_start));
                $query = "SELECT user_email,points,refer_code  FROM {$table} WHERE {$where}";
                $file_data = $wpdb->get_results($query, ARRAY_A);
                if (!empty($file_data)) {
                    foreach ($file_data as &$single_file_data) {
                        if (isset($single_file_data['user_email'])) {
                            $single_file_data['email'] = $single_file_data['user_email'];
                        }
                        if (isset($single_file_data['refer_code'])) {
                            $single_file_data['referral_code'] = $single_file_data['refer_code'];
                        }
                    }
                    $csv_helper = new CsvHelper();
                    $csv_helper->setCsvData($file_path, $file_data);
                }
                $data['success'] = true;
                $data['data']['success'] = 'incomplete';
                $limit_start = $limit_start + $limit;
                if ($limit_start >= $total_count) {
                    $limit_start = $total_count;
                }
                $data['data']['limit_start'] = $limit_start;
                $data['data']['notification'] = sprintf(__('Exported %s customer', 'wp-loyalty-rules'), $limit_start);
            } else {
                $data['success'] = true;
                $data['data']['success'] = 'completed';
                $data['data']['message'] = __('Export customer successfully', 'wp-loyalty-rules');
                $data['data']['redirect'] = admin_url('admin.php?' . http_build_query(array('page' => WLR_PLUGIN_SLUG, 'view' => 'point_users')));
            }
        } else {
            $data['success'] = false;
            $data['data']['success'] = 'error';
            $data['data']['message'] = __('Invalid request', 'wp-loyalty-rules');
        }
        wp_send_json($data);
    }

    /**
     * Add New customer view
     * @return void
     */
    function addNewCustomer()
    {
        $wlr_nonce = (string)self::$input->post_get('wlr_nonce', '');
        $data = array();
        if (Woocommerce::hasAdminPrivilege() && Woocommerce::verify_nonce($wlr_nonce, 'wlr-user-nonce')) {
            $user_email = (string)self::$input->post_get('user_email', '');
            $points = (int)self::$input->post_get('points', 0);
            if (filter_var($user_email, FILTER_VALIDATE_EMAIL) !== false && $points >= 0) {
                try {
                    $point_helper = new \Wlr\App\Helpers\Base();
                    $user_points = $point_helper->getPointUserByEmail($user_email);
                    if (empty($user_points)) {
                        $action_data = array(
                            'user_email' => sanitize_email($user_email),
                            'points' => $points,
                            'action_type' => 'new_user_add',
                            'action_process_type' => 'earn_point',
                            'customer_note' => sprintf(__('Added %d %s by site admin', 'wp-loyalty-rules'), $points, $point_helper->getPointLabel($points)),
                            'note' => sprintf(__('%s customer added with %d %s by admin(%s)', 'wp-loyalty-rules'), sanitize_email($user_email), $points, $point_helper->getPointLabel($points), self::$woocommerce->get_email_by_id(get_current_user_id())),
                        );
                        $status = $point_helper->addExtraPointAction('new_user_add', $points, $action_data, 'credit', false, true);
                        if ($status) {
                            $customer_note = (string)self::$input->post_get('comments', '');
                            if ($customer_note) {
                                $created_at = strtotime(date("Y-m-d h:i:s"));
                                $log_data = array(
                                    'user_email' => sanitize_email($user_email),
                                    'action_type' => 'custom_note',
                                    'earn_campaign_id' => 0,
                                    'campaign_id' => 0,
                                    'note' => __('Add new user command:', 'wp-loyalty-rules') . ' ' . $customer_note,
                                    'customer_note' => __('Add new user command:', 'wp-loyalty-rules') . ' ' . $customer_note,
                                    'order_id' => 0,
                                    'product_id' => 0,
                                    'admin_id' => 0,
                                    'created_at' => $created_at,
                                    'modified_at' => 0,
                                    'points' => 0,
                                    'action_process_type' => 0,
                                    'referral_type' => '',
                                    'reward_id' => 0,
                                    'user_reward_id' => 0,
                                    'expire_email_date' => 0,
                                    'expire_date' => 0,
                                    'reward_display_name' => null,
                                    'required_points' => 0,
                                    'discount_code' => null,
                                );
                                $point_helper->add_note($log_data);
                            }
                            $data['success'] = true;
                            $data['data'] = array(
                                'message' => __('New Customer added successfully', 'wp-loyalty-rules'),
                                'redirect' => admin_url('admin.php?' . http_build_query(array('page' => WLR_PLUGIN_SLUG, 'view' => 'point_users')))
                            );
                        } else {
                            $data['success'] = false;
                            $data['data'] = array(
                                'message' => __('Add New Customer failed', 'wp-loyalty-rules'),
                            );
                        }

                    } else {
                        $data['success'] = false;
                        $data['data'] = array(
                            'message' => __('Customer already exist!', 'wp-loyalty-rules'),
                        );
                    }
                } catch (Exception $e) {
                    $data['success'] = false;
                    $data['data'] = array(
                        'message' => __('Invalid customer data', 'wp-loyalty-rules')
                    );
                }
            } else {
                $data['success'] = false;
                $data['data'] = array(
                    'message' => __('Invalid customer email address', 'wp-loyalty-rules'),
                );
            }
        } else {
            $data['success'] = false;
            $data['data'] = array(
                'message' => __("Invalid nonce", 'wp-loyalty-rules')
            );
        }
        wp_send_json($data);
    }
}