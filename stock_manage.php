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
            <div class="pull-right">
                <button class="btn btn-success" id="show-add">Add New Stock</button>
            </div>
            <div id="link-edit" class="form-inline">
            </div>
            <div id="link-add" class="form-inline">
                <div class="form-group col-md-3">
                    <input type="text" name="stock_name" id="stock_name" placeholder="Stock Name" class="form-control" required />
                </div>
                <div class="form-group col-md-3">
                    <input type="text" name="stock_symbol" id="stock_symbol" placeholder="Stock Symbol" class="form-control" required/>
                </div>
                <div class="form-group col-md-3">
                    <input type="number" id="price" name="price" placeholder="price" class="form-control" required />
                </div>
                <div class="form-group col-md-3">
                    <button type="button" class="btn btn-primary" id="add" name="add">Add Record</button>
                    <button type="button" href="javascript:void(0);" class="btn btn-default" id="cancel" name="add" onclick="$('#link-edit').slideUp(400);$('#link-add').slideUp(400);$('#show-add').show(600);">Cancel</button>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div id="records_content"></div>
            <br>
            <div class="col-md-offset-1 col-md-10" id="table_content">
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

        // console.log(e.data);
        // alert( e.data );
    }
</script>
<script type="text/javascript">
    function delete_stock(id)
    {
        if(id !='')
        {
            socket.send(JSON.stringify({'status':'delete', 'id':id}));
        }
    }
    function update_stock(id,stock_name,stock_symbol,price)
    {
        socket.send(JSON.stringify({'status':'update', 'id':id, 'stock_name':stock_name,'stock_symbol':stock_symbol,'price':price}));
    }
    $(document).ready(function() {
        $.get("view.php", function(data) {
            $("#table_content").html(data);
        });

        $('#link-add').hide();

        $('#show-add').click(function() {
            $('#link-edit').hide();
            $('#link-add').slideDown(500);
            $('#show-add').hide();
        });

        $('#add').click(function() {
            var stock_name = $('#stock_name').val();
            var stock_symbol = $('#stock_symbol').val();
            var price = $('#price').val();

            $.ajax({
                url: "add.php",
                type: "POST",
                dataType: 'json',
                data: { stock_name: stock_name, stock_symbol: stock_symbol, price: price },
                success: function(data, status, xhr) {
                    if(data.status == 'success') {
                        socket.send(JSON.stringify(data.data));

                        $('#stock_name').val('');
                        $('#stock_symbol').val('');
                        $('#price').val('');
                        $.get("view.php", function (html) {
                            $("#table_content").html(html);
                        });
                        $('#records_content').fadeOut(1100).html(data.message);
                    }
                },
                error: function() {
                    $('#records_content').fadeIn(3000).html('<div class="text-center">error here</div>');
                },
                beforeSend: function() {
                    $('#records_content').fadeOut(700).html('<div class="text-center">Loading...</div>');
                },
                complete: function() {
                    $('#link-add').hide();
                    $('#show-add').show(700);
                }
            });
        });
    });
</script>
</body>

</html>