<main class="dash-content-edit-cus container-fluid">
	<header class="row">
		<form id="edit-cuisine" class="row">
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
			</main>
		</form>
	</header>
</main>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-2.1.1.js"></script>
<script type="text/javascript">
	function loadDataAjax(dat,restId=0) {
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
	}
	function trash(elem) {
		$.ajax({
			type:'POST',
			url:"<?php Yii::app()->createUrl('dashboard/editCuisine')?>",
			data:'itemTrashId='+elem.prop("id"),
			success:function(data){
				var response = $.parseJSON(data);
				alert(response.msg);
				var dat = "restid="+$('#restaurant-option option:selected').prop('id');
				loadDataAjax(dat);
			},
			error:function(){
				alert("Sorry there have been some errors");
			}
		});
	}

	function edit(elem) {
		$.ajax({
			type:'POST',
			url:"<?php echo Yii::app()->createUrl('dashboard/editCuisine')?>",
			data:'itemEditId='+elem.prop("id"),
			success:function(data) {
				var response = $.parseJSON(data);
				window.location.href = response.url;
			},
			error:function() {
				alert("Sorry there have been some errors");
			}
		})
	}

	$('#restaurant-option').change(function(){
		if( $('#restaurant-option option:selected').val() != 'NULL') {
			var dat = "restid="+$('#restaurant-option option:selected').prop('id');
			loadDataAjax(dat);
			$('.dash-content-edit-cus main').removeClass('main-hide').addClass('main-show');
		} else {
			$('.dash-content-edit-cus main').removeClass('main-show').addClass('main-hide');
		}
	});
</script>