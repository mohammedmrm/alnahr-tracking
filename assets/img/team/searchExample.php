<?php
header('Content-type:application/json;charset=windows-1256');
require_once('dbconnection.php');
error_reporting(0);
$phone=$_REQUEST['phone'];
$text=$_REQUEST['text'];
$start = trim($_REQUEST['start']);
$end = trim($_REQUEST['end']);
$page = trim($_REQUEST['page']);

if(empty($text)){
  $txt='1';
} else{
  $txt='';
}
//****************************************//
if(empty($page)){
  $page = 1;
}
//***************************************//

if(empty($end)) {
  $end = date('Y-m-d h:i:s', strtotime($end. ' + 1 day'));
}else{
   $end =date('Y-m-d', strtotime($end. ' + 1 day'));
   $end .=" 00:00:00";
}
if(empty($start)) {
   $start = date('Y-m-d 00:00:00');
}else{
   $start .=" 00:00:00";
}

$query='select id from clients where phone=?';
$res= getData($con,$query,[$phone]);

if($res>0){
  $id=$res[0]['id'];
}

$limit = " limit ".($page-1) * 10 .",10";

$text = str_replace(" ","%",$text);
$sql ="SELECT orders.*, towns.name as town_name, cites.name as city_name, clients.name as client_name, staff.name as staff_name,
     branches.name as branch_name,order_status.status as status, order_status.id as status_id FROM orders
    left join towns on towns.id = orders.to_town left join cites on cites.id=orders.to_city left join staff on staff.id=orders.driver_id
     left join branches on branches.id=orders.from_branch left join order_status on orders.order_status=order_status.id
     left join clients on clients.id= orders.client_id
     where client_id=? and orders.date between '".$start."' and '".$end."' and
    ( orders.order_no like '%".$text."%' or
     orders.customer_phone like '%".$text."%' or
     orders.customer_name like '%".$text."%')
     ";
     $sql .= $limit;
        $result = getData($con,$sql,[$id]);

echo json_encode($result);
?>