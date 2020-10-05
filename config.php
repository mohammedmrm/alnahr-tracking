<?php
$config = [
   "Company_name"=>"شركة البرق",
   "Company_address"=>"بغداد-حي الجامعة",
   "Company_phone"=>"0784567890",
   "Company_reg"=>"الشركة مسجلة قانونياً",
   "Company_email"=>"nahar@nahar.com",
   "Company_logo"=>"img/logos/logo.png",
   "c_ad1"=>"اعلان 1",
   "c_ad2"=>"اعلان 2",
   "d_ad1"=>"اعلان 1",
   "d_ad2"=>"اعلان 2",
   "dev_b"=>5000,               //سعر توصيل بغدلد
   "dev_o"=>10000,                //سعر توصيل باقي المحافظات
   "driver_price"=>3000,                //اجرة المندوب
   "addOnOver500"=>2000,
   "weightPrice"=>1000

];
function phone_number_format($number) {
  // Allow only Digits, remove all other characters.
  $number = preg_replace("/[^\d]/","",$number);
  // get number length.
  $length = strlen($number);
  // if number = 10
 if($length == 11) {
  $number = preg_replace("/^1?(\d{4})(\d{3})(\d{4})$/", "$1-$2-$3", $number);
 }
  if($length == 10) {
  $number = preg_replace("/^1?(\d{3})(\d{3})(\d{4})$/", "$1-$2-$3", $number);
 }
  return $number;

}
require_once("script/dbconnection.php");
$sql = "select * from setting";
$setting = getData($con,$sql);
foreach($setting as $val){
  $config[$val['control']] =  $val['value'];
}
?>