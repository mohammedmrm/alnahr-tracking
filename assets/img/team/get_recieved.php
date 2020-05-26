<?php
header('Content-type:application/json;charset=windows-1256');
require_once('dbconnection.php');
require_once('config.php');
error_reporting(0);
$phone=$_REQUEST['phone'];;
$start = trim($_REQUEST['start']);
$end = trim($_REQUEST['end']);
$page = trim($_REQUEST['page']);

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

if($page == 0){
  $page =1;
}
$limt = " limit ".($page-1) * 10 .",10";
$text = str_replace(" ","%",$text);

$query='select id from clients where phone=?';
$res= getData($con,$query,[$phone]);

if($res>0){
  $id=$res[0]['id'];
}
$query = 'select orders.* , count(id) as num_orders, sum(new_price) as price, DATE_FORMAT(date,"%Y-%m-%d") as date from orders where client_id=? and order_status=4 and date between "'.$start.'"  and "'.$end.'" GROUP BY DATE_FORMAT(date,"%Y-%m-%d")' ;
$data = getData($con,$query,[$id]);
$i=0;
foreach($data as $k=>$v){
        $total['income'] += $data[$i]['new_price'];
        $sql = "select * from client_dev_price where client_id=? and city_id=?";
        $dev_price  = getData($con,$sql,[$v['client_id'],$v['to_city']]);
        if(count($dev_price) > 0){
          $dev_p = $dev_price[0]['price'];
        }else{
          if($v['to_city'] == 1){
           $dev_p = $config['dev_b'];
          }else{
           $dev_p = $config['dev_o'];
          }
        }
        $data[$i]['dev_price'] =number_format($dev_p);
        $data[$i]['client_price'] =number_format(($data[$i]['new_price'] -  $dev_p) + $data[$i]['discount']);


/*  $total['discount'] += $data[$i]['discount'];
  $total['dev_price'] += $dev_p - $data[$i]['discount'];
  $total['client_price'] += $data[$i]['client_price'];*/
  $i++;
}
 $total['dev_price'] = number_format($total['dev_price']);
echo json_encode($data);
?>