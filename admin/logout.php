<?php// filepath: C:/wamp64/www/site/admin/logout.php?>
<?php
session_start();
session_unset();
session_destroy();
header('Location: index.php');
exit();
?>