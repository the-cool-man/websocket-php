<?php

require_once('conn.php');
global $con;

$id = $_POST['id'];
$stock_name = $_POST['stock_name'];
$stock_symbol = $_POST['stock_symbol'];
$price = $_POST['price'];

if(!empty($stock_name) && !empty($stock_symbol) && !empty($price) && !empty($id))
{
$query = "UPDATE stock_list SET stock_name='$stock_name', stock_symbol='$stock_symbol', price='$price' WHERE id='$id'";
if (!$result = mysqli_query($con, $query)) {
exit(mysqli_error($con));
}
echo '<div class="col-md-offset-4 col-md-5 text-center alert alert-success">1 Record updated!</div>';
}
else
{
echo '<div class="col-md-offset-4 col-md-5 text-center alert alert-danger">error while updating record</div>';
}
