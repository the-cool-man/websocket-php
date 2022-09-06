<?php

require_once('conn.php');
global $con;

$query = $con->prepare("SELECT * FROM stock_list order by id DESC");
$query->execute();
mysqli_stmt_bind_result($query, $id, $stock_name, $stock_symbol, $price);

?>
<table class="table table-bordered">
<tr class="info">
<th>ID</th>
<th>Stock Name</th>
<th>Stock Symbol</th>
<th>Price</th>
    <th>Action</th>
</tr>
<?php

while(mysqli_stmt_fetch($query))
{
echo '
<tr>
<td>'.$id.'</td>
<td>'.$stock_name.'</td>
<td>'.$stock_symbol.'</td>
<td>'.$price.'</td>
<td>
    <button id="'.$id.'" class="edit btn btn-info">Edit</button>
    <button class="del btn btn-danger" id="'.$id.'">Delete</button></td>
</tr>';
}
echo '</table>';

?>
<script type="text/javascript">
$('.del').click(function() {
    var id = $(this).attr('id');
    $.ajax({
        url : "delete.php",
        type: "POST",
        data : { id: id },
        success: function(data)
        {
            delete_stock(id);
            $('#records_content').fadeIn(10).fadeOut(1100).html(data);
            $.get("view.php", function(data)
            {
                $("#table_content").html(data);
            });
        }
    });
}); // delete close

$('.edit').click(function() {

    var id = $(this).attr('id');
    $('#show-add').hide();
    $.ajax({
        url : 'edit.php',
        type: 'POST',
        data : { id: id },
        success: function(data)
        {
            $("#link-edit").html(data);
            $('#link-edit').show();
            $('#link-add').hide();
        }
    });
});
</script>