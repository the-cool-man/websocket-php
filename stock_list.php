<?php
require_once('conn.php');
global $con;
$query = $con->prepare("SELECT * FROM stock_list order by id DESC");
$query->execute();
mysqli_stmt_bind_result($query, $id, $stock_name, $stock_symbol, $price);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Stock List</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

</head>

<body>
<div class="container-fluid" style="padding: 0px; margin: 0px;">
    <div class="jumbotron">
        <h1 class="text-center">Stock List</h1>
    </div>
</div>
<div class="container" style="padding-top: 25px;">
    <div class="row">
        <div class="col-md-12">
            <div class="col-md-offset-1 col-md-10" id="table_content">
            <table class="table table-bordered">
                <tr class="info">
                    <th>ID</th>
                    <th>Stock Name</th>
                    <th>Stock Symbol</th>
                    <th>Price</th>
                </tr>
                <tbody id="tbody_data">
            <?php
                while(mysqli_stmt_fetch($query))
                {
                    echo '
                    <tr id="row_'.$id.'">
                    <td id="id_'.$id.'">'.$id.'</td>
                    <td id="stock_name_'.$id.'">'.$stock_name.'</td>
                    <td id="stock_symbol_'.$id.'">'.$stock_symbol.'</td>
                    <td id="price_'.$id.'">'.$price.'</td>
                    </tr>';
                }
                echo '<tbody></table>';
            ?>
            </div>
        </div>
    </div>
</div>
<script>
    // Create a new WebSocket.
    var socket  = new WebSocket('ws://localhost:8080', 'echo-protocol');
    // Define the
    var message = document.getElementById('message');
    var user_id = document.getElementById('user_id');
    function transmitMessage() {
        var messageJSON = {
            user_id: user_id.value,
            message: message.value
        };
        socket.send(JSON.stringify(messageJSON));
        // socket.send( message.value );
    }
    socket.onmessage = function(e) {
        let data = JSON.parse(e.data);
        if(data.status == 'add') {
            if (data.id != '' && data.stock_name != '' && data.stock_symbol != '' && data.price != '') {
                let str = '<tr id="row_'+data.id+'"><td id="id_'+data.id+'">'+data.id+'</td><td id="stock_name_'+data.id+'">'+data.stock_name+'</td><td id="stock_symbol_'+data.id+'">'+data.stock_symbol+'</td><td id="price_'+data.id+'">'+data.price+'</td></tr>';
                $("#tbody_data").prepend(str);
            }
        }
        else if(data.status == 'update') {
            if (data.id != '' && data.stock_name != '' && data.stock_symbol != '' && data.price != '') {
                let str = '<td id="id_'+data.id+'">'+data.id+'</td><td id="stock_name_'+data.id+'">'+data.stock_name+'</td><td id="stock_symbol_'+data.id+'">'+data.stock_symbol+'</td><td id="price_'+data.id+'">'+data.price+'</td>';
                $("#row_"+data.id).html(str);
            }
        }
        else if(data.status =='delete' && data.id !='')
        {
            $("#row_" + data.id).remove();
        }
        //alert(data.id);
        console.log(e.data);
        //alert( e.data );
    }
</script>
</body>
</html>