<?php
include "config.php";
function loadClass($c)
{
    include "classes/$c.class.php";
}
spl_autoload_register("loadClass");

if (!isset($_SESSION['admin']) ) {exit;echo 0;}
$mssv = isset($_POST['mssv'])?$_POST['mssv']:'';
//$code = md5($code);
//if (str_len($code)<6) exit;
$sv = new SinhVien();
echo $sv->insertDiemDanh($mssv);

?>
