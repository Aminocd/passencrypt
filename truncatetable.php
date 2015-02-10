<?

include "init.php";

$query="truncate TABLE uniquesalts";

$mysqli->query($query);

echo "success";
?>
