<?php
$page_module_name = "Location";
?>
<?php
$location_name = "";
$pincode = "";
$location_id = 0;
$country_id = 1;
$state_id = 0;
$city_id = 0;
$is_display = 1;
$status = 1;
$record_action = "Add New Record";
if (!empty($location_data)) {
	$record_action = "Update";
	$location_id = $location_data->location_id;
	$location_name = $location_data->location_name;
	$pincode = $location_data->pincode;
	$status = $location_data->status;
	$country_id = 1;
	$state_id = $location_data->state_id;
	$city_id = $location_data->city_id;
	$is_display = $location_data->is_display;

}
?>
<!-- /.navbar -->

<!-- Main Sidebar Container -->


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1 class="m-0 text-dark"><?php echo $page_module_name ?> </small></h1>
				</div><!-- /.col -->
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="<?php echo MAINSITE_Admin . "wam" ?>">Home</a></li>
						<li class="breadcrumb-item"><a
								href="<?php echo MAINSITE_Admin . $user_access->class_name . "/" . $user_access->function_name ?>"><?php echo $user_access->module_name ?>
								List</a></li>
						<?php if (!empty($location_data)) { ?>
							<li class="breadcrumb-item"><a
									href="<?php echo MAINSITE_Admin . $user_access->class_name . "/view/" . $location_id ?>">View</a></li>
						<?php } ?>
						<li class="breadcrumb-item"><?php echo $record_action ?></li>
					</ol>
				</div><!-- /.col -->
			</div><!-- /.row -->
		</div><!-- /.container-fluid -->
	</div>
	<!-- /.content-header -->

	<!-- Main content -->
	<?php ?>

	<section class="content">
		<div class="row">
			<div class="col-12">

				<div class="card">

					<div class="card-header">
						<h3 class="card-title"><?php echo $location_name ?> <small><?php echo $record_action ?></small></h3>
					</div>
					<!-- /.card-header -->
					<?php
					if ($user_access->view_module == 1 || true) {
						?>
						<?php echo $this->session->flashdata('alert_message'); ?>
						<div class="card-body">
							<?php echo form_open(MAINSITE_Admin . "$user_access->class_name/do_edit", array('method' => 'post', 'id' => '', "name" => "ptype_list_form", 'style' => '', 'class' => 'form-horizontal', 'role' => 'form')); ?>
							<input type="hidden" name="location_id" id="location_id" value="<?php echo $location_id ?>" />
							<input type="hidden" name="redirect_type" id="redirect_type" value="" />

							<div class="form-group row">
								<div class="col-lg-3 col-md-4 col-sm-6">
									<label for="inputEmail3" class="col-sm-12 label_content px-2 py-0">State <span
											style="color:#f00;font-size: 22px;margin-top: 3px;">*</span></label>
									<div class="col-sm-10">
										<select type="text" class="form-control form-control-sm" required id="state_id"
											onchange="get_city(this.value ,0)" name="state_id">
											<option value="">Select State</option>
											<?php foreach ($state_data as $cd) {
												$selected = "";
												if ($cd->state_id == $state_id) {
													$selected = "selected";
												}
												?>
												<option value="<?php echo $cd->state_id ?>" <?php echo $selected ?>>
													<?php echo $cd->state_name ?>
													<?php if ($cd->status != 1) {
														echo " [Block]";
													} ?>
												</option>
											<?php } ?>
										</select>
									</div>
								</div>

								<!-- <div class="col-lg-3 col-md-4 col-sm-6">
									<label for="inputEmail3" class="col-sm-12 label_content px-2 py-0">State <span
											style="color:#f00;font-size: 22px;margin-top: 3px;">*</span></label>
									<div class="col-sm-10">
										<select type="text" class="form-control form-control-sm" required id="state_id" name="state_id">
											<option value="">Select State</option>
										</select>
									</div>
								</div> -->

								<div class="col-md-3 col-sm-6">
									<label for="inputEmail3" class="col-sm-12 label_content px-2 py-0">City <span
											style="color:#f00;font-size: 22px;margin-top: 3px;">*</span></label>
									<div class="col-sm-12">
										<select type="text" class="form-control form-control-sm custom-select" required id="city_id"
											name="city_id">
											<option value="">Select City</option>
										</select>
									</div>
								</div>

								<div class="col-lg-6 col-md-4 col-sm-6">
									<label for="inputEmail3" class="col-sm-12 label_content px-2 py-0">Location <span
											style="color:#f00;font-size: 22px;margin-top: 3px;">*</span></label>
									<div class="col-sm-10">
										<input type="text" class="form-control form-control-sm" required id="location_name"
											name="location_name" value="<?php echo $location_name ?>" placeholder="Location">

									</div>
								</div>

							</div>
							<div class="form-group row">
								<div class="col-lg-3 col-md-4 col-sm-6">
									<label for="inputEmail3" class="col-sm-12 label_content px-2 py-0">Pincode <span
											style="color:#f00;font-size: 22px;margin-top: 3px;">*</span></label>
									<div class="col-sm-10">
										<input type="number" class="form-control form-control-sm" id="pincode" required name="pincode"
											value="<?php echo $pincode ?>" placeholder="Pincode">
									</div>
								</div>
								<div class="col-lg-3 col-md-4 col-sm-6">
									<label for="radioSuccess1" class="col-sm-12 label_content px-2 py-0">Is Display?</label>
									<div class="col-sm-10">
										<div class="form-check" style="margin-top:12px">
											<div class="form-group clearfix">
												<div class="icheck-success d-inline">
													<input type="radio" name="is_display" <?php if ($is_display == 1) {
														echo "checked";
													} ?> value="1"
														id="is_displaySuccess1">
													<label for="is_displaySuccess1"> Yes
													</label>
												</div>
												&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
												<div class="icheck-danger d-inline">
													<input type="radio" name="is_display" <?php if ($is_display != 1) {
														echo "checked";
													} ?> value="0" id="is_displaySuccess2">
													<label for="is_displaySuccess2"> No
													</label>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-lg-3 col-md-4 col-sm-6">
									<label for="radioSuccess1" class="col-sm-12 label_content px-2 py-0">Status</label>
									<div class="col-sm-10">
										<div class="form-check" style="margin-top:12px">
											<div class="form-group clearfix">
												<div class="icheck-success d-inline">
													<input type="radio" name="status" <?php if ($status == 1) {
														echo "checked";
													} ?> value="1"
														id="radioSuccess1">
													<label for="radioSuccess1"> Active
													</label>
												</div>
												&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
												<div class="icheck-danger d-inline">
													<input type="radio" name="status" <?php if ($status != 1) {
														echo "checked";
													} ?> value="0"
														id="radioSuccess2">
													<label for="radioSuccess2"> Block
													</label>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<!-- /.card-body -->
							<div class="card-footer">
								<center>
									<button type="submit" name="save" onclick="return redirect_type_func('')" value="1"
										class="btn btn-info">Save</button>
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<button type="submit" name="save-add-new" onclick="return redirect_type_func('save-add-new')" value="1"
										class="btn btn-default ">Save And Add New</button>
								</center>
							</div>
							<!-- /.card-footer -->

							<?php echo form_close() ?>
							</table>
						</div>
					<?php } else {
						$this->data['no_access_flash_message'] = "You Dont Have Access To View " . $page_module_name;
						$this->load->view('secureRegions/template/access_denied', $this->data);
					} ?>
					<!-- /.card-body -->
				</div>
			</div>
		</div>


	</section>
	<?php ?>

</div>

<aside class="control-sidebar control-sidebar-dark">
	<!-- Control sidebar content goes here -->
</aside>
<script>
	function redirect_type_func(data) {
		document.getElementById("redirect_type").value = data;
		return true;
	}

	// function get_city(state_id, city_id = 0) {
	// 	$('#loader1').show();
	// 	$("#city_id").html('');
	// 	if (state_id > 0) {
	// 		Pace.restart();
	// 		$.ajax({
	// 			url: "<?php echo MAINSITE_Admin . 'Ajax/get_city' ?>",
	// 			type: 'post',
	// 			dataType: "json",
	// 			data: { 'state_id': state_id, 'city_id': city_id, "<?php echo $csrf['name'] ?>": "<?php echo $csrf['hash'] ?>" },
	// 			success: function (response) {
	// 				$("#city_id").html(response.state_html);
	// 			},
	// 			error: function (request, error) {
	// 				toastrDefaultErrorFunc("Unknown Error. Please Try Again");
	// 				$("#quick_view_model").html('Unknown Error. Please Try Again');
	// 			}
	// 		});
	// 	}

	// }



	function get_city(state_id, city_id = 0) {
		$("#city_id").html('');
		if (state_id > 0) {
			Pace.restart();
			$.ajax({
				url: "<?php echo MAINSITE_Admin . 'Ajax/get_city' ?>",
				type: 'post',
				dataType: "json",
				data: { 'city_id': city_id, 'state_id': state_id, "<?php echo $csrf['name'] ?>": "<?php echo $csrf['hash'] ?>" },
				success: function (response) {
					$("#city_id").html(response.city_html);
				},
				error: function (request, error) {
					toastrDefaultErrorFunc("Unknown Error. Please Try Again");
				}
			});
		}
	}
	<?php if (!empty($state_id) && !empty($city_id)) { ?>
		window.addEventListener('load', function () {
			get_city(<?php echo $state_id ?>, <?php echo $city_id ?>)
		})
	<?php } ?>

</script>