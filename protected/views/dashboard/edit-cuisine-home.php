<main class="dash-content-edit-cus container-fluid">
	<header class="row">
		<form id="edit-cuisine" class="row" method="post">
			<header class="colGLG-12 colGSM-12">
				<select id="restaurant-option" name="restaurant-option">
					<option value="NULL">Select Restaurant</option>
					<?php foreach ($restaurant as $res): ?>
						<option id="<?php echo $res->id;?>" value="<?php echo $res->id;?>"><?php echo $res->name;?>&nbsp;-&nbsp;<?php echo $res->location->name; ?></option>
					<?php endforeach; ?>
				</select>
			</header>
			<main class="main-hide colGLG-12">
				<table class="colGLG-12 colGSM-12">
					<thead class="row">
						<tr class="row">
							<td>Name</td>
							<td>Options</td>
						</tr>
					</thead>
					<tbody class="row" id="item-data">
					</tbody>
				</table>
		</form>
	</header>
</main>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-2.1.1.js"></script>
<script type="text/javascript">
	function trash(elem) {
		$.ajax({
			type:'POST',
			url:"<?php Yii::app()->createUrl('dashboard/editCuisine')?>",
			data:'itemTrashId='+elem.prop("id"),
			success:function(data){
				var response = $.parseJSON(data);
				alert(response.msg);
			},
			error:function(){
				alert("Errors");
			}
		});
	}

	function edit() {
		alert($('a.pencil').prop('id'));
	}

	$('#restaurant-option').change(function(){
		if( $('#restaurant-option option:selected').val() != 'NULL') {
			var dat = "restid="+$('#restaurant-option option:selected').prop('id');
			$.ajax({
				type:'POST',
				url:"<?php echo Yii::app()->createUrl('dashboard/editCuisine');?>",
				data:dat,
				success:function(data){
					var response = $.parseJSON(data);
					$("#item-data").empty();
					for (var i = 0; i < response.length; i++) {
						$("#item-data").append('<tr class="row"><td>'+response[i].name+'</td><td><a onClick="trash($(this))" class="trash" href="#" id="'+response[i].id+'"><i id="'+response[i].id+'" class="fa fa-trash"></i></a><a onClick="edit($(this))" class="pencil" href="javascript:edit()" id="'+response[i].id+'"><i id="'+response[i].id+'" class="fa fa-pencil"></i></a></td></tr>');
					}
				},
				error:function(){
					alert("No");
				}
			});
			$('.dash-content-edit-cus main').removeClass('main-hide').addClass('main-show');
		} else {
			$('.dash-content-edit-cus main').removeClass('main-show').addClass('main-hide');
		}
	});
</script>