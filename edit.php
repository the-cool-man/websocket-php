<?php

require_once('conn.php');
global $con;
$id = $_POST['id'];

if(empty($id))
{
?><div class="text-center">no records found under this selection <a href="#" onclick="$('#link-add').hide();$('#show-add').show(700);">Hide this</a></div>
<?php
die();
}

$query = "SELECT * FROM stock_list where id='$id'";
if (!$result = mysqli_query($con, $query)) {
exit(mysqli_error($con));
}
while($row = mysqli_fetch_assoc($result))
{
?>
<div class="form-inline" id="edit-data">
<div class="form-group col-md-3">
<input type="text" name="stock_name" id="up_stock_name" value="<?php echo $row['stock_name']; ?>" placeholder="Stock Name" class="form-control" required />
</div>
<div class="form-group col-md-3">
<input type="text" name="stock_symbol" id="up_stock_symbol" placeholder="Stock Symbol" class="form-control" value="<?php echo $row['stock_symbol']; ?>" required/>
</div>
<div class="form-group col-md-3">
<input type="number" id="up_price" name="price" placeholder="Price" class="form-control" value="<?php echo $row['price']; ?>" required />
</div>
<div class="form-group col-md-3">
<button type="button" class="btn btn-primary update" id="<?php echo $row['id']; ?>" name="update">Update Record</button>
<button type="button" href="javascript:void(0);" class="btn btn-default" id="cancel" name="add" onclick="$('#link-edit').slideUp(400);$('#link-add').slideUp(400);$('#show-add').show(700);">Cancel</button>
</div>
<?php
}
?>

<script type="text/javascript">
$('.update').click(function() {
var id = $(this).attr('id');
var stock_name = $('#up_stock_name').val();
var stock_symbol = $('#up_stock_symbol').val();
var price = $('#up_price').val();

$.ajax({
url: "update.php",
type: "POST",
data: { id: id, stock_name: stock_name, stock_symbol: stock_symbol, price: price },
success: function(data, status, xhr) {
    update_stock(id,stock_name,stock_symbol,price);
    $('#up_stock_name').val('');
    $('#up_stock_symbol').val('');
    $('#up_price').val('');
    $('#records_content').fadeIn(10).fadeOut(1100).html(data);

$.get("view.php", function(html) {
$("#table_content").html(html);
});
    $('#records_content').fadeIn(10).fadeOut(1100).html(data);
},
complete: function() {
$('#link-add').hide();
$('#link-edit').hide();
$('#show-add').show(700);
}
});
}); // update close
</script>