<?php
// phone , msg ,date 

$var_msg = var_export($_GET['msg'], true);
$var_phone = var_export($_GET['phone'], true);
date_default_timezone_set('Asia/Calcutta');
$date = date('Y/m/d H:i:s', time());
$var_date = var_export($date, true);
$var = "<?php\n\n\$phone = $var_phone;\n\n \n\n\$msg = $var_msg;\n\n \n\n\$date = $var_date;\n\n    ?>";
file_put_contents('db/db2.php', $var);

include 'db/db2.php';
echo $msg;
echo $phone;
echo $date;