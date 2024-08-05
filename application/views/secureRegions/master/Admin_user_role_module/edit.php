<?php
$page_module_name = "Role Master";
?>
<?php
$admin_user_role_name = "";
$admin_user_role_id = 0;
$status = 1;
$record_action = "Add New Record";
if (!empty($admin_user_role_data)) {
	$record_action = "Update";
	$admin_user_role_id = $admin_user_role_data->admin_user_role_id;
	$admin_user_role_name = $admin_user_role_data->admin_user_role_name;
	$status = $admin_user_role_data->status;

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
					<h1 class="m-0 text-dark">
						<?php echo $page_module_name ?> </small>
					</h1>
				</div><!-- /.col -->
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="<?php echo MAINSITE_Admin . "wam" ?>">Home</a></li>
						<li class="breadcrumb-item"><a
								href="<?php echo MAINSITE_Admin . $user_access->class_name . "/" . $user_access->function_name ?>">
								<?php echo $user_access->module_name ?>
								List
							</a></li>
						<?php if (!empty($admin_user_role_data)) { ?>
							<li class="breadcrumb-item"><a
									href="<?php echo MAINSITE_Admin . $user_access->class_name . "/view/" . $admin_user_role_id ?>">View</a>
							</li>
						<?php } ?>
						<li class="breadcrumb-item">
							<?php echo $record_action ?>
						</li>
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
						<h3 class="card-title">
							<?php echo $admin_user_role_name ?> <small>
								<?php echo $record_action ?>
						</h3>
					</div>
					<!-- /.card-header -->
					<?php
					if ($user_access->view_module == 1 || true) {
						?>
						<?php echo $this->session->flashdata('alert_message'); ?>
						<div class="card-body">

							<?php echo form_open(MAINSITE_Admin . "$user_access->class_name/do_edit", array('method' => 'post', 'id' => 'ptype_list_form', "name" => "ptype_list_form", 'style' => '', 'class' => 'form-horizontal', 'role' => 'form', 'enctype' => 'multipart/form-data')); ?>

							<input type="hidden" name="admin_user_role_id" id="admin_user_role_id"
								value="<?php echo $admin_user_role_id ?>" />
							<input type="hidden" name="redirect_type" id="redirect_type" value="" />

							<div class="card-body">
								<div class="form-group row">
									<label for="inputEmail3" class="col-sm-2 col-form-label-lg">User Role Name </label>
									<div class="col-sm-10">
										<input type="text" class="form-control" required id="admin_user_role_name" name="admin_user_role_name"
											value="<?php echo $admin_user_role_name ?>" placeholder="User Role Name">
										<span style="color:#f00;font-size: 22px;margin-top: 3px;">*</span>
									</div>
								</div>
								<div class="form-group row ">
									<table id="" class="table table-bordered table-hover" style="font-size:medium">
										<thead>
											<tr>
												<th>#</th>
												<th>Role</th>
												<th>All</th>
												<th>View</th>
												<th>Add</th>
												<th>Update</th>
												<?php   /* ?><th>Delete</th>
																																																																																																																																																																																																							<th>Approval</th><?php   */ ?>
												<th>Import</th>
												<th>Export</th>
											</tr>
										</thead>
										<tbody>
											<?php
											$count = 0;

											foreach ($module_data as $md) {
												$count++;
												?>

												<?php
												$all_checked = $view_checked = $add_checked = $update_checked = $delete_checked = $approval_checked = $import_checked = $export_checked = '';
												if (!empty($module_permission_data)) {
													foreach ($module_permission_data as $mpd) {
														if ($md->module_id == $mpd->module_id) {
															if (!empty($mpd->view_module)) {
																$view_checked = 'checked ';
																$all_checked = 'checked';
															}

															if (!empty($mpd->add_module)) {
																$add_checked = 'checked';
																$all_checked = 'checked';
															}

															if (!empty($mpd->update_module)) {
																$update_checked = 'checked';
																$all_checked = 'checked';
															}

															if (!empty($mpd->delete_module)) {
																$delete_checked = 'checked';
																$all_checked = 'checked';
															}

															if (!empty($mpd->approval_module)) {
																$approval_checked = 'checked';
																$all_checked = 'checked';
															}

															if (!empty($mpd->import_data)) {
																$import_checked = 'checked';
																$all_checked = 'checked';
															}

															if (!empty($mpd->export_data)) {
																$export_checked = 'checked';
																$all_checked = 'checked';
															}
														}

													}
												}




												?>


												<tr>
													<td>
														<?php echo $count ?>.
													</td>
													<td>
														<?php echo $md->module_name ?> [
														<?php echo $md->main_menu_name ?> ]
													</td>
													<td>
														<!-- <div
															class="bootstrap-switch bootstrap-switch-wrapper bootstrap-switch-focused bootstrap-switch-animate bootstrap-switch-on"
															style="width: 82.125px;">
															<div class="bootstrap-switch-container" style="width: 120.25px; margin-left: 0px;"><span
																	class="bootstrap-switch-handle-on bootstrap-switch-success"
																	style="width: 40px;">Yes</span><span class="bootstrap-switch-label"
																	style="width: 40px;">&nbsp;</span><span
																	class="bootstrap-switch-handle-off bootstrap-switch-danger"
																	style="width: 40px;">No</span><input type="checkbox" value="1" name="module_ids[]"
																	class="module_all m_check_all_1" data-module_id="1" checked="" data-bootstrap-switch=""
																	data-off-color="danger" data-on-color="success" data-on-text="Yes" data-off-text="No">
															</div>
														</div> -->
														<input type="checkbox" value="<?php echo $md->module_id ?>" name="module_ids[]"
															class="module_all m_check_all_<?php echo $md->module_id ?>"
															data-module_id="<?php echo $md->module_id ?>" <?php echo $all_checked ?> data-bootstrap-switch
															data-off-color="danger" data-on-color="success" data-on-text="Yes" data-off-text="No">
													</td>
													<td>
														<input type="checkbox" value="1" name="view_<?php echo $md->module_id ?>"
															class="module_field m_check_field_<?php echo $md->module_id ?>"
															data-module_id="<?php echo $md->module_id ?>" <?php echo $view_checked ?>
															data-bootstrap-switch data-off-color="danger" data-on-color="success" data-on-text="Yes"
															data-off-text="No">
													</td>
													<td>
														<input type="checkbox" value="1" name="add_<?php echo $md->module_id ?>"
															class="module_field m_check_field_<?php echo $md->module_id ?>"
															data-module_id="<?php echo $md->module_id ?>" <?php echo $add_checked ?> data-bootstrap-switch
															data-off-color="danger" data-on-color="success" data-on-text="Yes" data-off-text="No">
													</td>
													<td>
														<input type="checkbox" value="1" name="update_<?php echo $md->module_id ?>"
															class="module_field m_check_field_<?php echo $md->module_id ?>"
															data-module_id="<?php echo $md->module_id ?>" <?php echo $update_checked ?>
															data-bootstrap-switch data-off-color="danger" data-on-color="success" data-on-text="Yes"
															data-off-text="No">
													</td>

													<td>
														<input type="checkbox" value="1" name="import_<?php echo $md->module_id ?>"
															class="module_field m_check_field_<?php echo $md->module_id ?>"
															data-module_id="<?php echo $md->module_id ?>" <?php echo $import_checked ?>
															data-bootstrap-switch data-off-color="danger" data-on-color="success" data-on-text="Yes"
															data-off-text="No">
													</td>
													<td>
														<input type="checkbox" value="1" name="export_<?php echo $md->module_id ?>"
															class="module_field m_check_field_<?php echo $md->module_id ?>"
															data-module_id="<?php echo $md->module_id ?>" <?php echo $export_checked ?>
															data-bootstrap-switch data-off-color="danger" data-on-color="success" data-on-text="Yes"
															data-off-text="No">
													</td>
												</tr>
											<?php } ?>
										</tbody>
									</table>
								</div>


								<div class="form-group row">
									<label for="radioSuccess1" class="col-sm-2 col-form-label-lg">Status</label>
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
	window.addEventListener('load', function () {
		var approve_all = false;
		var approve_field = false;
		$('.module_all').on('switchChange.bootstrapSwitch', function (event, state) {
			if (approve_field) { return false; }
			console.log(state);
			var module_id = $(this).attr("data-module_id");
			approve_all = true;
			$(".m_check_field_" + module_id).each(function (index) {
				$(this).bootstrapSwitch('state', state);
			});
			approve_all = false;
		});

		$('.module_field').on('switchChange.bootstrapSwitch', function (event, state) {//alert('dgf');
			if (approve_all) { return false; }
			approve_field = true;
			var module_id = $(this).attr("data-module_id");
			var status = false;
			var total_count = 0;
			var true_count = 0;
			var false_count = 0;
			$(".m_check_field_" + module_id).each(function (index) {
				total_count++;
				if ($(this).bootstrapSwitch('state')) { true_count++; }
				else { false_count++; }
			});
			if (true_count == total_count) {
				$(".m_check_all_" + module_id).bootstrapSwitch('state', true);
			}

			else if (false_count == total_count) {
				$(".m_check_all_" + module_id).bootstrapSwitch('state', false);
			}
			else {
				$(".m_check_all_" + module_id).bootstrapSwitch('state', true);
			}
			//	console.log(true_count+' : '+false_count+' : '+total_count);
			approve_field = false;
		});
	})
</script>