<?php
require_once('conn.php');
global $con;
header('Content-Type: application/json; charset=utf-8');

$stock_name = $_POST['stock_name'];
$stock_symbol = $_POST['stock_symbol'];
$price = $_POST['price'];
$data = [
    'status'=>'error',
    'message'=>"Some error occured",
];
if(!empty($stock_name) && !empty($stock_symbol) && !empty($price)) {
    $query = $con->prepare("INSERT into stock_list (stock_name, stock_symbol, price) VALUES (?,?,?)");
    $query->bind_param('sss',$stock_name, $stock_symbol, $price);
    $result = $query->execute();
    if ($result) {
        $data = [
            'status'=>'success',
            'message'=>'<div class="col-md-offset-4 col-md-5 text-center alert alert-success">Data inserted successfully</div>',
            'data'=>[
                'id'=>$query->insert_id,
                'stock_name'=>$stock_name,
                'stock_symbol'=>$stock_symbol,
                'price'=>$price,
                'status'=>'add'
            ]
        ];
    } else {
        $data = [
            'status'=>'error',
            'message'=>mysqli_error($con),
            'data'=>[
            ]
        ];
    }
}
echo json_encode($data);