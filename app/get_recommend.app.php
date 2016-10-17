<?php

/**
 * 
 * 首页推荐商品接口调用方法
 * @author feng
 *
 */
class Get_recommendApp extends MallbaseApp
{

    function index()
    {
        $recommend_id = intval($_GET['recommend_id']);
        $number = isset($_GET['number']) ? intval($_GET['number']) : 4;
        $index = isset($_GET['index']) ? intval($_GET['index']) : 0;
        $db = & db();
        $sql = "select * from ecm_recommend where recom_id = '$recommend_id'";
        $row = $db->query($sql);
        while ($t_result = mysql_fetch_assoc($row)) {
            $recommend_result = $t_result;
        }
        $recommend_name = $recommend_result['recom_name'];
        $result["recommend_name"] = $recommend_name;
        $recommend_goods_sql = "select * from ecm_recommended_goods where recom_id = '$recommend_id'";
        $recommend_goods_row = $db->query($recommend_goods_sql);
        while ($t_result = mysql_fetch_assoc($recommend_goods_row)) {
            $recommend_goods_result[] = $t_result;
        }
        foreach ($recommend_goods_result as $k => $v) {
            $goods_id = $v['goods_id'];
            $goods_sql = "select goods_id, goods_name, default_image, price from ecm_goods where goods_id = '$goods_id' and if_show = 1 and closed = 0";
            $goods_row = $db->query($goods_sql);
            while ($te_result = mysql_fetch_assoc($goods_row)) {
                $goods_result = $te_result;
            }
            $recommend_goods_result[$k] = $goods_result;
        }
        $goods_size = sizeof($recommend_goods_result);
        $page = ($goods_size == 0) ? $page = 0 : (ceil($goods_size / $number) - 1);
        $result['page'] = $page;
        $k = ($index) * $number;
        $result["goods"] = array();
        for ($i = $k; $i < $number + $k; $i ++) {
            if ($recommend_goods_result[$i]) {
                $result['goods'][] = $recommend_goods_result[$i];
            } else {
                break;
            }
        }
        echo json_encode($result);
    }

    function main()
    {
        $recommend_id = intval($_GET['recommend_id']);
        $number = isset($_GET['number']) ? intval($_GET['number']) : 4;
        $index = isset($_GET['index']) ? intval($_GET['index']) : 0;
        $db = & db();
        $sql = "select * from ecm_recommend where recom_id = '$recommend_id'";
        $row = $db->query($sql);
        while ($t_result = mysql_fetch_assoc($row)) {
            $recommend_result = $t_result;
        }
        $recommend_name = $recommend_result['recom_name'];
        // $result["recommend_name"] = $recommend_name;
        $recommend_goods_sql = "select * from ecm_recommended_goods where recom_id = '$recommend_id'";
        $recommend_goods_row = $db->query($recommend_goods_sql);
        while ($t_result = mysql_fetch_assoc($recommend_goods_row)) {
            $recommend_goods_result[] = $t_result;
        }
        foreach ($recommend_goods_result as $k => $v) {
            $goods_result = null;
            $goods_id = $v['goods_id'];
            $goods_sql = "select goods_id, goods_name, default_image, if_credit, price from ecm_goods where goods_id = '$goods_id' and if_show = 1 and closed = 0";
            $goods_row = $db->query($goods_sql);
            while ($te_result = mysql_fetch_assoc($goods_row)) {
                $goods_result = $te_result;
            }
            
            if ($goods_result) {
                $result[] = $goods_result;
            }
        }
        $size = sizeof($result);
        $index = $size > $number ? $number : $size;
        // $final_result["credit_rate"] = CREDIT_RATE;
        for ($i = 0; $i < $index; $i ++) {
            if (! $result[$i]["default_image"]) {
                $result[$i]["default_image"] = "http://127.0.0.1/ecmalltest/data/system/default_goods_image.gif";
            }
            $final_result[] = $result[$i];
        }
        echo json_encode($final_result);
    }
}
