<?php
ini_set("display_errors", 1);

/**
 * 买家的订单管理控制器
 *
 * @author Garbin
 *         @usage none
 */
class Seller_orderApp extends StoreadminbaseApp
{

    function __construct()
    {
        $GLOBALS["member"] = 1;
        parent::__construct();
    }

    function index()
    {
        /* 获取订单列表 */
        $this->_get_orders();
        
        /* 当前位置 */
        $this->_curlocal(LANG::get('member_center'), 'index.php?app=member', LANG::get('order_manage'), 'index.php?app=seller_order', LANG::get('order_list'));
        
        /* 当前用户中心菜单 */
        $type = (isset($_GET['type']) && $_GET['type'] != '') ? trim($_GET['type']) : 'all_orders';
        $this->_curitem('order_manage');
        $this->_curmenu($type);
        $this->_config_seo('title', Lang::get('member_center') . ' - ' . Lang::get('order_manage'));
        $this->import_resource(array(
            'script' => array(
                array(
                    'path' => 'dialog/dialog.js',
                    'attr' => 'id="dialog_js"'
                ),
                array(
                    'path' => 'jquery.ui/jquery.ui.js',
                    'attr' => ''
                ),
                array(
                    'path' => 'jquery.ui/i18n/' . i18n_code() . '.js',
                    'attr' => ''
                ),
                array(
                    'path' => 'jquery.plugins/jquery.validate.js',
                    'attr' => ''
                )
            ),
            'style' => 'jquery.ui/themes/ui-lightness/jquery.ui.css'
        ));
        /* 显示订单列表 */
        $this->display('seller_order.index.html');
    }

    /**
     * 导出excel格式订单
     */
    function export()
    {
        include_once (ROOT_PATH . "/includes/PHPExcel.php");
        include_once (ROOT_PATH . "/includes/PHPExcel/Writer/Excel2007.php");
        $db = & db();
        // var_dump("test");
        // $page = $this->_get_page();
        $model_order = & m('order');
        
        ! $_GET['type'] && $_GET['type'] = 'all_orders';
        $type = $_GET["type"];
        switch ($type) {
            case "all_orders":
                $type = "所有订单";
                break;
            case "pending":
                $type = "待付款";
                break;
            case "submitted":
                $type = "已提交";
                break;
            case "accepted":
                $type = "待发货";
                break;
            case "shipped":
                $type = "已发货";
                break;
            case "finished":
                $type = "已完成";
                break;
            case "canceled":
                $type = "已取消";
                break;
            default:
                $type = "所有订单";
        }
        $conditions = '';
        
        // 团购订单
        // if (!empty($_GET['group_id']) && intval($_GET['group_id']) > 0)
        // {
        // $groupbuy_mod = &m('groupbuy');
        // $order_ids = $groupbuy_mod->get_order_ids(intval($_GET['group_id']));
        // $order_ids && $conditions .= ' AND order_alias.order_id' . db_create_in($order_ids);
        // }
        
        $conditions .= $this->_get_query_conditions(array(
            array( // 按订单状态搜索
                'field' => 'status',
                'name' => 'type',
                'handler' => 'order_status_translator'
            ),
            array( // 按买家名称搜索
                'field' => 'buyer_name',
                'equal' => 'LIKE'
            ),
            array( // 按下单时间搜索,起始时间
                'field' => 'add_time',
                'name' => 'add_time_from',
                'equal' => '>=',
                'handler' => 'gmstr2time'
            ),
            array( // 按下单时间搜索,结束时间
                'field' => 'add_time',
                'name' => 'add_time_to',
                'equal' => '<=',
                'handler' => 'gmstr2time_end'
            ),
            array( // 按订单号
                'field' => 'order_sn'
            )
        ));
        if ($conditions == " AND status = '20'") // 如果用户点击待发货一栏，则将货到付款的商品也显示出来
{
            $conditions = " AND (status = '20' OR status = 50)";
        }
        /* 查找订单 */
        $orders = $model_order->findAll(array(
            'conditions' => "seller_id=" . $this->visitor->get('manage_store') . "{$conditions}",
            'count' => true,
            'join' => 'has_orderextm',
            
            // 'limit' => $page['limit'],
            'order' => 'add_time DESC',
            'include' => array(
                'has_ordergoods'
            )
        )); // 取出商品
        
        $orders = array_values($orders);
        if ($orders) {
            $excel = new PHPExcel();
            // $excel->getDefaultStyle()->getAlignment()>setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $result = array();
            foreach ($orders as $k => $v) {
                $goods_id = $v["goods_id"];
                $goods_sql = "select * from ecm_goods where goods_id = '$goods_id'";
                $goods_row = $db->query($goods_sql);
                while ($g_result = mysql_fetch_array($goods_row)) {
                    $goods_result = $g_result;
                }
                $goods_name = $goods_result["goods_name"];
                $order_id = $v["order_id"];
                $order_goods_sql = "select * from ecm_order_goods where order_id = '$order_id'";
                $order_goods_row = $db->query($order_goods_sql);
                while ($o_result = mysql_fetch_array($order_goods_row)) {
                    $order_goods_result = $o_result;
                }
                $order_shipping_sql = "select * from ecm_order_extm where order_id = '$order_id'";
                $order_shipping_row = $db->query($order_shipping_sql);
                while ($s_result = mysql_fetch_array($order_shipping_row)) {
                    $order_shipping_result = $s_result;
                }
                $consignee = $order_shipping_result["consignee"];
                $address = $order_shipping_result["region_name"] . $order_shipping_result["address"];
                $phone = $order_shipping_result["phone_tel"];
                $quantity = $order_goods_result["quantity"];
                // array_push($result[$k], $v["order_sn"]);
                $result[$k] = array(
                    $v["order_sn"]
                );
                array_push($result[$k], $v["order_amount"]);
                array_push($result[$k], $v["seller_name"]);
                
                array_push($result[$k], $goods_name);
                array_push($result[$k], $quantity);
                array_push($result[$k], date("Y-m-d H:i:s", $v["add_time"]));
                
                array_push($result[$k], $v["payment_name"]);
                array_push($result[$k], $v["out_trade_sn"]);
                if ($v["pay_time"]) {
                    array_push($result[$k], date("Y-m-d H:i:s", $v["pay_time"]));
                } else {
                    array_push($result[$k], "尚未支付");
                }
                // array_push($result[$k], $v["pay_message"]);
                array_push($result[$k], $consignee);
                array_push($result[$k], $address);
                array_push($result[$k], $phone);
                if ($v["ship_time"]) {
                    array_push($result[$k], date("Y-m-d H:i:s", $v["ship_time"]));
                } else {
                    array_push($result[$k], "尚未发货");
                }
                array_push($result[$k], $v["express_name"]);
                
                array_push($result[$k], $v["buyer_name"]);
            }
            $fileName = iconv("utf-8", "gb2312", "订单");
            $excel->getActiveSheet()
                ->getColumnDimension('A')
                ->setWidth(15);
            $excel->getActiveSheet()
                ->getColumnDimension('B')
                ->setWidth(10);
            $excel->getActiveSheet()
                ->getColumnDimension('C')
                ->setWidth(15);
            $excel->getActiveSheet()
                ->getColumnDimension('D')
                ->setWidth(20);
            $excel->getActiveSheet()
                ->getColumnDimension('E')
                ->setWidth(10);
            $excel->getActiveSheet()
                ->getColumnDimension('F')
                ->setWidth(10);
            $excel->getActiveSheet()
                ->getColumnDimension('G')
                ->setWidth(15);
            $excel->getActiveSheet()
                ->getColumnDimension('H')
                ->setWidth(20);
            $excel->getActiveSheet()
                ->getColumnDimension('I')
                ->setWidth(20);
            $excel->getActiveSheet()
                ->getColumnDimension('J')
                ->setWidth(10);
            $excel->getActiveSheet()
                ->getColumnDimension('K')
                ->setWidth(30);
            $excel->getActiveSheet()
                ->getColumnDimension('L')
                ->setWidth(15);
            $excel->getActiveSheet()
                ->getColumnDimension('M')
                ->setWidth(15);
            $excel->getActiveSheet()
                ->getColumnDimension('N')
                ->setWidth(15);
            $excel->getActiveSheet()
                ->getColumnDimension('O')
                ->setWidth(15);
            // $excel->getActiveSheet()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $excel->getActiveSheet()->setCellValue('A1', '订单号');
            $excel->getActiveSheet()->setCellValue('B1', '总价');
            $excel->getActiveSheet()->setCellValue('C1', '买家名称');
            $excel->getActiveSheet()->setCellValue('D1', '商品名称');
            $excel->getActiveSheet()->setCellValue('E1', '商品数量');
            $excel->getActiveSheet()->setCellValue('F1', '添加时间');
            $excel->getActiveSheet()->setCellValue('G1', '支付方式');
            $excel->getActiveSheet()->setCellValue('H1', '支付号');
            $excel->getActiveSheet()->setCellValue('I1', '支付时间');
            $excel->getActiveSheet()->setCellValue('J1', '收货人');
            $excel->getActiveSheet()->setCellValue('K1', '收货地址');
            $excel->getActiveSheet()->setCellValue('L1', '联系电话');
            $excel->getActiveSheet()->setCellValue('M1', '发货时间');
            $excel->getActiveSheet()->setCellValue('N1', '快递名称');
            $excel->getActiveSheet()->setCellValue('O1', '卖家名称');
            $excel->getActiveSheet()->fromArray($result, NULL, 'A2');
            $file_name = $type . date("YmdHi");
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8');
            header("Content-Disposition: attachment; filename=" . $file_name . ".xlsx");
            header('Cache-Control: max-age=0');
            $writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
            $writer->save('php://output');
        }
    }

    /**
     * 查看订单详情
     *
     * @author Garbin
     * @return void
     */
    function view()
    {
        $order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
        
        $model_order = & m('order');
        $order_info = $model_order->findAll(array(
            'conditions' => "order_alias.order_id={$order_id} AND seller_id=" . $this->visitor->get('manage_store'),
            'join' => 'has_orderextm'
        ));
        $order_info = current($order_info);
        if (! $order_info) {
            $this->show_warning('no_such_order');
            
            return;
        }
        
        /* 团购信息 */
        if ($order_info['extension'] == 'groupbuy') {
            $groupbuy_mod = &m('groupbuy');
            $group = $groupbuy_mod->get(array(
                'join' => 'be_join',
                'conditions' => 'order_id=' . $order_id,
                'fields' => 'gb.group_id'
            ));
            $this->assign('group_id', $group['group_id']);
        }
        
        /* 当前位置 */
        $this->_curlocal(LANG::get('member_center'), 'index.php?app=member', LANG::get('order_manage'), 'index.php?app=seller_order', LANG::get('view_order'));
        
        /* 当前用户中心菜单 */
        $this->_curitem('order_manage');
        $this->_config_seo('title', Lang::get('member_center') . ' - ' . Lang::get('detail'));
        
        /* 调用相应的订单类型，获取整个订单详情数据 */
        $order_type = & ot($order_info['extension']);
        $order_detail = $order_type->get_order_detail($order_id, $order_info);
        $spec_ids = array();
        foreach ($order_detail['data']['goods_list'] as $key => $goods) {
            empty($goods['goods_image']) && $order_detail['data']['goods_list'][$key]['goods_image'] = Conf::get('default_goods_image');
            $spec_ids[] = $goods['spec_id'];
        }
        
        /* 查出最新的相应的货号 */
        $model_spec = & m('goodsspec');
        $spec_info = $model_spec->find(array(
            'conditions' => $spec_ids,
            'fields' => 'sku'
        ));
        foreach ($order_detail['data']['goods_list'] as $key => $goods) {
            $order_detail['data']['goods_list'][$key]['sku'] = $spec_info[$goods['spec_id']]['sku'];
        }
        
        $this->assign('order', $order_info);
        $this->assign($order_detail['data']);
        $this->display('seller_order.view.html');
    }

    /**
     * 收到货款
     *
     * @author Garbin
     * @param
     *            none
     * @return void
     */
    function received_pay()
    {
        list ($order_id, $order_info) = $this->_get_valid_order_info(ORDER_PENDING);
        if (! $order_id) {
            echo Lang::get('no_such_order');
            
            return;
        }
        if (! IS_POST) {
            header('Content-Type:text/html;charset=' . CHARSET);
            $this->assign('order', $order_info);
            $this->display('seller_order.received_pay.html');
        } else {
            $model_order = &  m('order');
            $model_order->edit(intval($order_id), array(
                'status' => ORDER_ACCEPTED,
                'pay_time' => gmtime()
            ));
            if ($model_order->has_error()) {
                $this->pop_warning($model_order->get_error());
                
                return;
            }
            
            // TODO 发邮件通知
            /* 记录订单操作日志 */
            $order_log = & m('orderlog');
            $order_log->add(array(
                'order_id' => $order_id,
                'operator' => addslashes($this->visitor->get('user_name')),
                'order_status' => order_status($order_info['status']),
                'changed_status' => order_status(ORDER_ACCEPTED),
                'remark' => $_POST['remark'],
                'log_time' => gmtime()
            ));
            
            /* 发送给买家邮件，提示等待安排发货 */
            $model_member = & m('member');
            $buyer_info = $model_member->get($order_info['buyer_id']);
            $mail = get_mail('tobuyer_offline_pay_success_notify', array(
                'order' => $order_info
            ));
            $this->_mailto($buyer_info['email'], addslashes($mail['subject']), addslashes($mail['message']));
            
            $new_data = array(
                'status' => Lang::get('order_accepted'),
                'actions' => array(
                    'cancel',
                    'shipped'
                )
            ); // 可以取消可以发货
            
            $this->pop_warning('ok');
        }
    }

    /**
     * 货到付款的订单的确认操作
     *
     * @author Garbin
     * @param
     *            none
     * @return void
     */
    function confirm_order()
    {
        list ($order_id, $order_info) = $this->_get_valid_order_info(ORDER_SUBMITTED);
        if (! $order_id) {
            echo Lang::get('no_such_order');
            
            return;
        }
        if (! IS_POST) {
            header('Content-Type:text/html;charset=' . CHARSET);
            $this->assign('order', $order_info);
            $this->display('seller_order.confirm.html');
        } else {
            $model_order = &  m('order');
            $model_order->edit($order_id, array(
                'status' => ORDER_ACCEPTED
            ));
            if ($model_order->has_error()) {
                $this->pop_warning($model_order->get_error());
                
                return;
            }
            
            /* 记录订单操作日志 */
            $order_log = & m('orderlog');
            $order_log->add(array(
                'order_id' => $order_id,
                'operator' => addslashes($this->visitor->get('user_name')),
                'order_status' => order_status($order_info['status']),
                'changed_status' => order_status(ORDER_ACCEPTED),
                'remark' => $_POST['remark'],
                'log_time' => gmtime()
            ));
            
            /* 发送给买家邮件，订单已确认，等待安排发货 */
            $model_member = & m('member');
            $buyer_info = $model_member->get($order_info['buyer_id']);
            $mail = get_mail('tobuyer_confirm_cod_order_notify', array(
                'order' => $order_info
            ));
            $this->_mailto($buyer_info['email'], addslashes($mail['subject']), addslashes($mail['message']));
            
            $new_data = array(
                'status' => Lang::get('order_accepted'),
                'actions' => array(
                    'cancel',
                    'shipped'
                )
            ); // 可以取消可以发货
            
            $this->pop_warning('ok');
            ;
        }
    }

    /**
     * 调整费用
     *
     * @author Garbin
     * @return void
     */
    function adjust_fee()
    {
        list ($order_id, $order_info) = $this->_get_valid_order_info(array(
            ORDER_SUBMITTED,
            ORDER_PENDING
        ));
        if (! $order_id) {
            echo Lang::get('no_such_order');
            
            return;
        }
        $model_order = &  m('order');
        $model_orderextm = & m('orderextm');
        $shipping_info = $model_orderextm->get($order_id);
        if (! IS_POST) {
            header('Content-Type:text/html;charset=' . CHARSET);
            $this->assign('order', $order_info);
            $this->assign('shipping', $shipping_info);
            $this->display('seller_order.adjust_fee.html');
        } else {
            /* 配送费用 */
            $shipping_fee = isset($_POST['shipping_fee']) ? abs(floatval($_POST['shipping_fee'])) : 0;
            /* 折扣金额 */
            $goods_amount = isset($_POST['goods_amount']) ? abs(floatval($_POST['goods_amount'])) : 0;
            /* 订单实际总金额 */
            $order_amount = round($goods_amount + $shipping_fee, 2);
            if ($order_amount <= 0) {
                /* 若商品总价＋配送费用扣队折扣小于等于0，则不是一个有效的数据 */
                $this->pop_warning('invalid_fee');
                
                return;
            }
            $data = array(
                'goods_amount' => $goods_amount, // 修改商品总价
                'order_amount' => $order_amount, // 修改订单实际总金额
                'pay_alter' => 1
            ); // 支付变更
            
            if ($shipping_fee != $shipping_info['shipping_fee']) {
                /* 若运费有变，则修改运费 */
                
                $model_extm = & m('orderextm');
                $model_extm->edit($order_id, array(
                    'shipping_fee' => $shipping_fee
                ));
            }
            $model_order->edit($order_id, $data);
            
            if ($model_order->has_error()) {
                $this->pop_warning($model_order->get_error());
                
                return;
            }
            /* 记录订单操作日志 */
            $order_log = & m('orderlog');
            $order_log->add(array(
                'order_id' => $order_id,
                'operator' => addslashes($this->visitor->get('user_name')),
                'order_status' => order_status($order_info['status']),
                'changed_status' => order_status($order_info['status']),
                'remark' => Lang::get('adjust_fee'),
                'log_time' => gmtime()
            ));
            
            /* 发送给买家邮件通知，订单金额已改变，等待付款 */
            $model_member = & m('member');
            $buyer_info = $model_member->get($order_info['buyer_id']);
            $mail = get_mail('tobuyer_adjust_fee_notify', array(
                'order' => $order_info
            ));
            $this->_mailto($buyer_info['email'], addslashes($mail['subject']), addslashes($mail['message']));
            
            $new_data = array(
                'order_amount' => price_format($order_amount)
            );
            
            $this->pop_warning('ok');
        }
    }

    /**
     * 待发货的订单发货
     *
     * @author Garbin
     * @return void
     */
    function shipped()
    {
        list ($order_id, $order_info) = $this->_get_valid_order_info(array(
            ORDER_ACCEPTED,
            ORDER_SHIPPED,
            COD_ORDER
        ));
        if (! $order_id) {
            echo Lang::get('no_such_order');
            
            return;
        }
        $model_order = &  m('order');
        if (! IS_POST) {
            /* 显示发货表单 */
            header('Content-Type:text/html;charset=' . CHARSET);
            $this->assign('order', $order_info);
            $this->display('seller_order.shipped.html');
        } else {
            if (! $_POST['invoice_no']) {
                $this->pop_warning('invoice_no_empty');
                
                return;
            }
            $order = $_POST['invoice_no'];
            $express_name = json_decode($this->getcontent("http://www.kuaidi100.com/autonumber/auto?num={$order}"), true);
            $express_name = $express_name[0]['comCode'];
            
            $edit_data = array(
                'status' => ORDER_SHIPPED,
                'invoice_no' => $_POST['invoice_no'],
                'express_name' => $express_name
            );
            $is_edit = true;
            if (empty($order_info['invoice_no'])) {
                /* 不是修改发货单号 */
                $edit_data['ship_time'] = gmtime();
                $is_edit = false;
            }
            $model_order->edit(intval($order_id), $edit_data);
            if ($model_order->has_error()) {
                $this->pop_warning($model_order->get_error());
                
                return;
            }
            
            // TODO 发邮件通知
            /* 记录订单操作日志 */
            $order_log = & m('orderlog');
            $order_log->add(array(
                'order_id' => $order_id,
                'operator' => addslashes($this->visitor->get('user_name')),
                'order_status' => order_status($order_info['status']),
                'changed_status' => order_status(ORDER_SHIPPED),
                'remark' => $_POST['remark'],
                'log_time' => gmtime()
            ));
            
            /* 发送给买家订单已发货通知 */
            $model_member = & m('member');
            $buyer_info = $model_member->get($order_info['buyer_id']);
            $order_info['invoice_no'] = $edit_data['invoice_no'];
            $mail = get_mail('tobuyer_shipped_notify', array(
                'order' => $order_info
            ));
            $this->_mailto($buyer_info['email'], addslashes($mail['subject']), addslashes($mail['message']));
            
            $new_data = array(
                'status' => Lang::get('order_shipped'),
                'actions' => array(
                    'cancel',
                    'edit_invoice_no'
                )
            ); // 可以取消可以发货
            
            if ($order_info['payment_code'] == 'cod') {
                $new_data['actions'][] = 'finish';
            }
            
            $this->pop_warning('ok');
        }
    }

    /**
     * 取消订单
     *
     * @author Garbin
     * @return void
     */
    function cancel_order()
    {
        /* 取消的和完成的订单不能再取消 */
        // list($order_id, $order_info) = $this->_get_valid_order_info(array(ORDER_SUBMITTED, ORDER_PENDING, ORDER_ACCEPTED, ORDER_SHIPPED));
        $order_id = isset($_GET['order_id']) ? trim($_GET['order_id']) : '';
        if (! $order_id) {
            echo Lang::get('no_such_order');
        }
        $status = array(
            ORDER_SUBMITTED,
            ORDER_PENDING,
            ORDER_ACCEPTED,
            ORDER_SHIPPED
        );
        $order_ids = explode(',', $order_id);
        if ($ext) {
            $ext = ' AND ' . $ext;
        }
        
        $model_order = &  m('order');
        /* 只有已发货的货到付款订单可以收货 */
        $order_info = $model_order->find(array(
            'conditions' => "order_id" . db_create_in($order_ids) . " AND seller_id=" . $this->visitor->get('manage_store') . " AND status " . db_create_in($status) . $ext
        ));
        $ids = array_keys($order_info);
        if (! $order_info) {
            echo Lang::get('no_such_order');
            
            return;
        }
        if (! IS_POST) {
            header('Content-Type:text/html;charset=' . CHARSET);
            $this->assign('orders', $order_info);
            $this->assign('order_id', count($ids) == 1 ? current($ids) : implode(',', $ids));
            $this->display('seller_order.cancel.html');
        } else {
            $model_order = &  m('order');
            foreach ($ids as $val) {
                $id = intval($val);
                $model_order->edit($id, array(
                    'status' => ORDER_CANCELED
                ));
                if ($model_order->has_error()) {
                    // $_erros = $model_order->get_error();
                    // $error = current($_errors);
                    // $this->json_error(Lang::get($error['msg']));
                    // return;
                    continue;
                }
                
                /* 加回订单商品库存 */
                $model_order->change_stock('+', $id);
                $cancel_reason = (! empty($_POST['remark'])) ? $_POST['remark'] : $_POST['cancel_reason'];
                /* 记录订单操作日志 */
                $order_log = & m('orderlog');
                $order_log->add(array(
                    'order_id' => $id,
                    'operator' => addslashes($this->visitor->get('user_name')),
                    'order_status' => order_status($order_info[$id]['status']),
                    'changed_status' => order_status(ORDER_CANCELED),
                    'remark' => $cancel_reason,
                    'log_time' => gmtime()
                ));
                
                /* 发送给买家订单取消通知 */
                $model_member = & m('member');
                $buyer_info = $model_member->get($order_info[$id]['buyer_id']);
                $mail = get_mail('tobuyer_cancel_order_notify', array(
                    'order' => $order_info[$id],
                    'reason' => $_POST['remark']
                ));
                $this->_mailto($buyer_info['email'], addslashes($mail['subject']), addslashes($mail['message']));
                
                $new_data = array(
                    'status' => Lang::get('order_canceled'),
                    'actions' => array()
                ); // 取消订单后就不能做任何操作了
            }
            $this->pop_warning('ok', 'seller_order_cancel_order');
        }
    }

    /**
     * 完成交易(货到付款的订单)
     *
     * @author Garbin
     * @return void
     */
    function finished()
    {
        list ($order_id, $order_info) = $this->_get_valid_order_info(ORDER_SHIPPED, 'payment_code=\'cod\'');
        if (! $order_id) {
            echo Lang::get('no_such_order');
            
            return;
        }
        if (! IS_POST) {
            header('Content-Type:text/html;charset=' . CHARSET);
            /* 当前用户中心菜单 */
            $this->_curitem('seller_order');
            /* 当前所处子菜单 */
            $this->_curmenu('finished');
            $this->assign('_curmenu', 'finished');
            $this->assign('order', $order_info);
            $this->display('seller_order.finished.html');
        } else {
            $now = gmtime();
            $model_order = &  m('order');
            $model_order->edit($order_id, array(
                'status' => ORDER_FINISHED,
                'pay_time' => $now,
                'finished_time' => $now
            ));
            if ($model_order->has_error()) {
                $this->pop_warning($model_order->get_error());
                
                return;
            }
            
            /* 记录订单操作日志 */
            $order_log = & m('orderlog');
            $order_log->add(array(
                'order_id' => $order_id,
                'operator' => addslashes($this->visitor->get('user_name')),
                'order_status' => order_status($order_info['status']),
                'changed_status' => order_status(ORDER_FINISHED),
                'remark' => $_POST['remark'],
                'log_time' => gmtime()
            ));
            
            /* 更新累计销售件数 */
            $model_goodsstatistics = & m('goodsstatistics');
            $model_ordergoods = & m('ordergoods');
            $order_goods = $model_ordergoods->find("order_id={$order_id}");
            foreach ($order_goods as $goods) {
                $model_goodsstatistics->edit($goods['goods_id'], "sales=sales+{$goods['quantity']}");
            }
            
            /* 发送给买家交易完成通知，提示评论 */
            $model_member = & m('member');
            $buyer_info = $model_member->get($order_info['buyer_id']);
            $mail = get_mail('tobuyer_cod_order_finish_notify', array(
                'order' => $order_info
            ));
            $this->_mailto($buyer_info['email'], addslashes($mail['subject']), addslashes($mail['message']));
            
            $new_data = array(
                'status' => Lang::get('order_finished'),
                'actions' => array()
            ); // 完成订单后就不能做任何操作了
            
            $this->pop_warning('ok');
        }
    }

    /**
     * 获取有效的订单信息
     *
     * @author Garbin
     * @param array $status            
     * @param string $ext            
     * @return array
     */
    function _get_valid_order_info($status, $ext = '')
    {
        $order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
        if (! $order_id) {
            
            return array();
        }
        if (! is_array($status)) {
            $status = array(
                $status
            );
        }
        
        if ($ext) {
            $ext = ' AND ' . $ext;
        }
        
        $model_order = &  m('order');
        /* 只有已发货的货到付款订单可以收货 */
        $order_info = $model_order->get(array(
            'conditions' => "order_id={$order_id} AND seller_id=" . $this->visitor->get('manage_store') . " AND status " . db_create_in($status) . $ext
        ));
        if (empty($order_info)) {
            
            return array();
        }
        
        return array(
            $order_id,
            $order_info
        );
    }

    /**
     * 获取订单列表
     *
     * @author Garbin
     * @return void
     */
    function _get_orders()
    {
        $page = $this->_get_page();
        $model_order = & m('order');
        
        ! $_GET['type'] && $_GET['type'] = 'all_orders';
        
        $conditions = '';
        
        // 团购订单
        if (! empty($_GET['group_id']) && intval($_GET['group_id']) > 0) {
            $groupbuy_mod = &m('groupbuy');
            $order_ids = $groupbuy_mod->get_order_ids(intval($_GET['group_id']));
            $order_ids && $conditions .= ' AND order_alias.order_id' . db_create_in($order_ids);
        }
        
        $conditions .= $this->_get_query_conditions(array(
            array( // 按订单状态搜索
                'field' => 'status',
                'name' => 'type',
                'handler' => 'order_status_translator'
            ),
            array( // 按买家名称搜索
                'field' => 'buyer_name',
                'equal' => 'LIKE'
            ),
            array( // 按下单时间搜索,起始时间
                'field' => 'add_time',
                'name' => 'add_time_from',
                'equal' => '>=',
                'handler' => 'gmstr2time'
            ),
            array( // 按下单时间搜索,结束时间
                'field' => 'add_time',
                'name' => 'add_time_to',
                'equal' => '<=',
                'handler' => 'gmstr2time_end'
            ),
            array( // 按订单号
                'field' => 'order_sn'
            )
        ));
        // 如果用户点击待发货一栏，则将货到付款的商品也显示出来
        if ($conditions == " AND status = '20'") {
            $conditions = " AND (status = '20' OR status = 50)";
        }
        /* 查找订单 */
        $orders = $model_order->findAll(array(
            'conditions' => "seller_id=" . $this->visitor->get('manage_store') . "{$conditions}",
            'count' => true,
            'join' => 'has_orderextm',
            'limit' => $page['limit'],
            'order' => 'add_time DESC',
            'include' => array(
                'has_ordergoods'
            )
        )); // 取出商品
            
        // psmb
        $member_mod = & m('member');
        $model_spec = & m('goodsspec');
        $model_goods = & m('goods');
        $model_shipping = & m('shipping');
        
        foreach ($orders as $key1 => $order) {
            foreach ($order['order_goods'] as $key2 => $goods) {
                empty($goods['goods_image']) && $orders[$key1]['order_goods'][$key2]['goods_image'] = Conf::get('default_goods_image');
                
                $spec = $model_spec->get(array(
                    'conditions' => 'spec_id=' . $goods['spec_id'],
                    'fields' => 'sku'
                ));
                $orders[$key1]['order_goods'][$key2]['sku'] = $spec['sku'];
            }
            // psmb
            $orders[$key1]['goods_quantities'] = count($order['order_goods']);
            $orders[$key1]['buyer_info'] = $member_mod->get(array(
                'conditions' => 'user_id=' . $order['buyer_id'],
                'fields' => 'real_name,im_qq,im_aliww,im_msn'
            ));
            $daofu_info = $model_goods->get(array(
                'conditions' => 'goods_id=' . $order['goods_id'],
                'fields' => 'postage_daofu'
            ));
            if (! empty($daofu_info['postage_daofu'])) {
                $shipping_price = $model_shipping->get(array(
                    'conditions' => 'shipping_id=' . $order['shipping_id'],
                    'fields' => 'first_price'
                ));
                $orders[$key1]["shipping_fee"] = $shipping_price['first_price'] . '（到付）';
            }
        }
        $page['item_count'] = $model_order->getCount();
        $this->_format_page($page);
        $this->assign('types', array(
            'all' => Lang::get('all_orders'),
            'pending' => Lang::get('pending_orders'),
            'submitted' => Lang::get('submitted_orders'),
            'accepted' => Lang::get('accepted_orders'),
            'shipped' => Lang::get('shipped_orders'),
            'finished' => Lang::get('finished_orders'),
            'canceled' => Lang::get('canceled_orders')
        ));
        $this->assign('type', $_GET['type']);
        $this->assign('orders', $orders);
        $this->assign('page_info', $page);
    }
    /* 三级菜单 */
    function _get_member_submenu()
    {
        $array = array(
            array(
                'name' => 'all_orders',
                'url' => 'index.php?app=seller_order&amp;type=all_orders'
            ),
            array(
                'name' => 'pending',
                'url' => 'index.php?app=seller_order&amp;type=pending'
            ),
            array(
                'name' => 'submitted',
                'url' => 'index.php?app=seller_order&amp;type=submitted'
            ),
            array(
                'name' => 'accepted',
                'url' => 'index.php?app=seller_order&amp;type=accepted'
            ),
            array(
                'name' => 'shipped',
                'url' => 'index.php?app=seller_order&amp;type=shipped'
            ),
            array(
                'name' => 'finished',
                'url' => 'index.php?app=seller_order&amp;type=finished'
            ),
            array(
                'name' => 'canceled',
                'url' => 'index.php?app=seller_order&amp;type=canceled'
            )
        );
        return $array;
    }

    private function getcontent($url)
    {
        if (function_exists("file_get_contents")) {
            $file_contents = file_get_contents($url);
        } else {
            $ch = curl_init();
            $timeout = 5;
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            $file_contents = curl_exec($ch);
            curl_close($ch);
        }
        return $file_contents;
    }
}

?>
