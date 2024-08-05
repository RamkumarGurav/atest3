<?php
$page_module_name = "Employee";
?>
<?php
//$joining_date;
$joining_date_input = date('d-m-Y');
$termination_date_input = '';
$fax_no = $show_password = $mobile_no = $alt_mobile_no = $admin_user_name = $address1 = $address2 = $address3 = $email = $pincode = "";
$admin_user_id = 0;
$country_id = 0;
$state_id = 0;
$city_id = 0;
$admin_user_role_id = 0;
$designation_id = 0;
$data_view = $approval_access = 0;
$status = 1;
$record_action = "Add New Record";
$selected_user_role = array();
$selected_company = array();
if (!empty($admin_user_data)) {
	$record_action = "Update";
	$admin_user_id = $admin_user_data->admin_user_id;
	$admin_user_name = $admin_user_data->admin_user_name;
	$address1 = $admin_user_data->address1;
	$address3 = $admin_user_data->address3;
	$status = $admin_user_data->status;
	$country_id = $admin_user_data->country_id;
	$state_id = $admin_user_data->state_id;
	$city_id = $admin_user_data->city_id;
	$admin_user_role_id = $admin_user_data->admin_user_role_id;
	$designation_id = $admin_user_data->designation_id;
	$mobile_no = $admin_user_data->mobile_no;
	$alt_mobile_no = $admin_user_data->alt_mobile_no;
	$email = $admin_user_data->email;
	$pincode = $admin_user_data->pincode;
	$data_view = $admin_user_data->data_view;
	$approval_access = $admin_user_data->approval_access;
	$show_password = $admin_user_data->show_password;
	$fax_no = $admin_user_data->fax_no;
	$joining_date_input = date('d-m-Y', strtotime($admin_user_data->joining_date));
	//$joining_date = $admin_user_data->joining_date;

	if (!empty($admin_user_data->termination_date) && $admin_user_data->termination_date != '0000-00-00' && $admin_user_data->termination_date != '01-01-1970' && $admin_user_data->termination_date != '1970-01-01')
		$termination_date_input = date('d-m-Y', strtotime($admin_user_data->termination_date));
	else
		$termination_date_input = '';


	if (!empty($admin_user_data->roles)) {
		foreach ($admin_user_data->roles as $role) {
			$selected_user_role[] = $role->admin_user_role_id;
			$selected_company[] = $role->company_profile_id;
		}
	}
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
						<?php if (!empty($admin_user_data)) { ?>
							<li class="breadcrumb-item"><a
									href="<?php echo MAINSITE_Admin . $user_access->class_name . "/view/" . $admin_user_id ?>">View</a>
							</li>
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
						<h3 class="card-title"><?php echo $admin_user_name ?> <small><?php echo $record_action ?></small></h3>
					</div>
					<!-- /.card-header -->
					<?php
					if ($user_access->view_module == 1 || true) {
						?>
						<?php echo $this->session->flashdata('alert_message'); ?>
						<div class="card-body">

							<?php echo form_open(MAINSITE_Admin . "$user_access->class_name/do_edit", array('method' => 'post', 'id' => 'employee_form', "name" => "employee_form", 'style' => '', 'class' => 'form-horizontal', 'role' => 'form', 'enctype' => 'multipart/form-data')); ?>
							<input type="hidden" name="admin_user_id" id="admin_user_id" value="<?php echo $admin_user_id ?>" />
							<input type="hidden" name="redirect_type" id="redirect_type" value="" />
							<input type="hidden" name="approval_access" value="0">

							<div class="form-group row">
								<div class="col-lg-3 col-md-4 col-sm-6">
									<label for="inputEmail3" class="col-sm-12 label_content px-2 py-0">Role
										<span style="color:#f00;font-size: 22px;margin-top: 3px;">*</span></label>
									<div class="col-sm-12">
										<?php if (count($company_profile_data) > 1) { ?>
											<div class="">
												<table class="table table-hover text-nowrap" style="width:90%">
													<thead>
														<tr>
															<th style="width: 10px">#</th>
															<th>Company</th>
															<th>Role <span style="color:#f00;font-size: 11px;margin-top: 3px;">*</span></th>
														</tr>
													</thead>
													<tbody>
														<?php $c_count = 0;
														foreach ($company_profile_data as $cpd) {
															$c_count++; ?>
															<tr>
																<td><?php echo $c_count ?>.</td>
																<td><?php echo $cpd->company_unique_name ?></td>
																<td>
																	<input type="hidden" name="company_profile_id[]"
																		value="<?php echo $cpd->company_profile_id ?>">
																	<?php
																	$selected_role = "";
																	if (!empty($admin_user_data->roles)) {
																		foreach ($admin_user_data->roles as $role) {
																			if ($role->company_profile_id == $cpd->company_profile_id) {
																				$selected_role = $role->admin_user_role_id;
																			}
																		}
																	}
																	?>
																	<select type="text" class="form-control form-control-sm" id="admin_user_role_id"
																		name="admin_user_role_id[]">
																		<option value="">Select Role</option>
																		<?php foreach ($users_role_data as $urd) {
																			$selected = "";
																			if ($urd->admin_user_role_id == $selected_role) {
																				$selected = "selected";
																			}
																			?>
																			<option value="<?php echo $urd->admin_user_role_id ?>" <?php echo $selected ?>>
																				<?php echo $urd->admin_user_role_name ?>
																				<?php if ($urd->status != 1) {
																					echo " [Block]";
																				} ?>
																			</option>
																		<?php } ?>
																	</select>
																</td>
															</tr>
														<?php } ?>
													</tbody>
												</table>

											</div>
										<?php } else { ?>
											<?php $c_count = 0;
											foreach ($company_profile_data as $cpd) {
												$c_count++; ?>
												<input type="hidden" name="company_profile_id[]" value="<?php echo $cpd->company_profile_id ?>">
												<?php
												$selected_role = "";
												if (!empty($admin_user_data->roles)) {
													foreach ($admin_user_data->roles as $role) {
														if ($role->company_profile_id == $cpd->company_profile_id) {
															$selected_role = $role->admin_user_role_id;
														}
													}
												}
												?>
												<select required type="text" class="form-control form-control-sm" id="admin_user_role_id"
													name="admin_user_role_id[]">
													<option value="">Select Role <?php echo $selected_role ?></option>
													<?php foreach ($users_role_data as $urd) {
														$selected = "";
														if ($urd->admin_user_role_id == $selected_role) {
															$selected = "selected";
														}
														?>
														<option value="<?php echo $urd->admin_user_role_id ?>" <?php echo $selected ?>>
															<?php echo $urd->admin_user_role_name ?>
															<?php if ($urd->status != 1) {
																echo " [Block]";
															} ?>
														</option>
													<?php } ?>
												</select>
												<p class="help-block text-muted mb-0">
													<span style="color:red" class="error_span" id="user_role_error"></span>
												</p>
											<?php } ?>
										<?php } ?>
									</div>
								</div>

								<div class="col-lg-3 col-md-4 col-sm-6">
									<label for="inputEmail3" class="col-sm-12 label_content px-2 py-0">Designation <span
											style="color:#f00;font-size: 22px;margin-top: 3px;">*</span></label>
									<div class="col-sm-12">
										<select type="text" class="form-control form-control-sm" required id="designation_id"
											name="designation_id">
											<option value="">Select Designation</option>
											<?php foreach ($designation_data as $d) {
												$selected = "";
												if ($d->designation_id == $designation_id) {
													$selected = "selected";
												}
												?>
												<option value="<?php echo $d->designation_id ?>" <?php echo $selected ?>>
													<?php echo $d->designation_name ?>
													<?php if ($d->status != 1) {
														echo " [Block]";
													} ?>
												</option>
											<?php } ?>
										</select>

									</div>
								</div>

								<div class="col-lg-3 col-md-4 col-sm-6">
									<label for="inputEmail3" class="py-0 px-2 col-sm-12 col-form-label-lg label_content">Joining Date <span
											style="color:#f00;font-size: 22px;margin-top: 3px;">*</span></label>
									<div class="col-sm-12">
										<div class="input-group date joining_date_input" id="joining_date_input" data-target-input="nearest">
											<input type="text" readonly="readonly" value="<?php echo $joining_date_input ?>" name="joining_date"
												id="joining_date" placeholder="Joining Date" style="width: 100%;"
												class="form-control datetimepicker-input width100 form-control-sm"
												data-target="#joining_date_input" />
											<div class="input-group-append" data-target="#joining_date_input" data-toggle="datetimepicker">
												<div class="input-group-text"><i class="fa fa-calendar"></i></div>
											</div>

										</div>

									</div>
								</div>

								<div class="col-lg-3 col-md-4 col-sm-6">
									<label for="inputEmail3" class="py-0 px-2 col-sm-12 col-form-label-lg label_content">Termination Date
										<span style="color:#f00;font-size: 22px;margin-top: 3px;">*</span></label>
									<div class="col-sm-12">
										<div class="input-group date termination_date_input" id="termination_date_input"
											data-target-input="nearest">
											<input type="text" readonly="readonly" value="<?php echo $termination_date_input ?>"
												name="termination_date" id="termination_date" placeholder="Termination Date" style="width: 100%;"
												class="form-control datetimepicker-input width100 form-control-sm"
												data-target="#termination_date_input" />
											<div class="input-group-append" data-target="#termination_date_input" data-toggle="datetimepicker">
												<div class="input-group-text"><i class="fa fa-calendar"></i></div>
											</div>

										</div>

									</div>
								</div>

							</div>


							<div class="form-group row">
								<div class="col-lg-3 col-md-4 col-sm-6">
									<label for="inputEmail3" class="col-sm-12 label_content px-2 py-0">Name <span
											style="color:#f00;font-size: 22px;margin-top: 3px;">*</span></label>
									<div class="col-sm-12">
										<input type="text" class="form-control form-control-sm" required id="admin_user_name"
											name="admin_user_name" value="<?php echo $admin_user_name ?>" placeholder="Name">
									</div>
								</div>


								<div class="col-lg-3 col-md-4 col-sm-6">
									<label for="inputEmail3" class="col-sm-12 label_content px-2 py-0">Email <span
											style="color:#f00;font-size: 22px;margin-top: 3px;">*</span></label>
									<div class="col-sm-12">
										<input type="email" class="form-control form-control-sm" required id="email" name="email"
											value="<?php echo $email ?>" placeholder="Email">
									</div>
								</div>

								<div class="col-lg-3 col-md-4 col-sm-6">
									<label for="inputEmail3" class="col-sm-12 label_content px-2 py-0">Password <span
											style="color:#f00;font-size: 22px;margin-top: 3px;">*</span></label>
									<div class="col-sm-12">
										<input type="text" class="form-control form-control-sm" required id="show_password"
											name="show_password" value="<?php echo $show_password ?>" placeholder="Password">
									</div>
								</div>


							</div>
							<div class="form-group row">

								<div class="col-lg-3 col-md-4 col-sm-6">
									<label for="inputEmail3" class="col-sm-12 label_content px-2 py-0">Fax No. <span
											style="color:#f00;font-size: 22px;margin-top: 3px;"></span></label>
									<div class="col-sm-12">
										<input type="number" class="form-control form-control-sm" pattern="[0-9]{8,15}" id="fax_no"
											name="fax_no" value="<?php echo $fax_no ?>" placeholder="Fax No.">
									</div>
								</div>

								<div class="col-lg-3 col-md-4 col-sm-6">
									<label for="inputEmail3" class="col-sm-12 label_content px-2 py-0">Mobile No. <span
											style="color:#f00;font-size: 22px;margin-top: 3px;">*</span></label>
									<div class="col-sm-12">
										<input type="number" class="form-control form-control-sm" pattern="[0-9]{8,15}" required
											id="mobile_no" name="mobile_no" value="<?php echo $mobile_no ?>" placeholder="Mobile No.">
									</div>
								</div>

								<div class="col-lg-3 col-md-4 col-sm-6">
									<label for="inputEmail3" class="col-sm-12 label_content px-2 py-0">Alt Mobile No. <span
											style="color:#f00;font-size: 22px;margin-top: 3px;"></span></label>
									<div class="col-sm-12">
										<input type="number" class="form-control form-control-sm" pattern="[0-9]{8,15}" id="mobile_no"
											name="alt_mobile_no" value="<?php echo $alt_mobile_no ?>" placeholder="Alt Mobile No.">
									</div>
								</div>
							</div>
							<div class="form-group row">

								<div class="col-lg-3 col-md-4 col-sm-6">
									<label for="inputEmail3" class="col-sm-12 label_content px-2 py-0">Address 1 <span
											style="color:#f00;font-size: 22px;margin-top: 3px;">*</span></label>
									<div class="col-sm-12">
										<input type="text" class="form-control form-control-sm" required id="address1" name="address1"
											value="<?php echo $address1 ?>" placeholder="Address Line 1">
									</div>
								</div>

								<div class="col-lg-3 col-md-4 col-sm-6">
									<label for="inputEmail3" class="col-sm-12 label_content px-2 py-0">Address 2 <span
											style="color:#f00;font-size: 22px;margin-top: 3px;"></span></label>
									<div class="col-sm-12">
										<input type="text" class="form-control form-control-sm" id="address2" name="address2"
											value="<?php echo $address2 ?>" placeholder="Address Line 2">
									</div>
								</div>

								<div class="col-lg-3 col-md-4 col-sm-6">
									<label for="inputEmail3" class="col-sm-12 label_content px-2 py-0">Address 3 <span
											style="color:#f00;font-size: 22px;margin-top: 3px;"></span></label>
									<div class="col-sm-12">
										<input type="text" class="form-control form-control-sm" id="address3" name="address3"
											value="<?php echo $address3 ?>" placeholder="Address Line 3">
									</div>
								</div>


								<div class="col-lg-3 col-md-4 col-sm-6">
									<label for="inputEmail3" class="col-sm-12 label_content px-2 py-0">Pincode <span
											style="color:#f00;font-size: 22px;margin-top: 3px;"></span></label>
									<div class="col-sm-12">
										<input type="text" class="form-control form-control-sm" id="pincode" name="pincode"
											value="<?php echo $pincode ?>" placeholder="Pincode">
									</div>
								</div>
							</div>

							<div class="form-group row">
								<div class="col-lg-3 col-md-4 col-sm-6">
									<label for="inputEmail3" class="col-sm-12 label_content px-2 py-0">Country <span
											style="color:#f00;font-size: 22px;margin-top: 3px;">*</span></label>
									<div class="col-sm-12">
										<select type="text" class="form-control form-control-sm custom-select" required id="country_id"
											onchange="get_state(this.value ,0)" name="country_id">
											<option value="">Select Country</option>
											<?php foreach ($country_data as $cd) {
												$selected = "";
												if ($cd->country_id == $country_id) {
													$selected = "selected";
												}
												?>
												<option value="<?php echo $cd->country_id ?>" <?php echo $selected ?>>
													<?php echo $cd->country_name ?>
													<?php if ($cd->status != 1) {
														echo " [Block]";
													} ?>
												</option>
											<?php } ?>
										</select>
									</div>
								</div>

								<div class="col-lg-3 col-md-4 col-sm-6">
									<label for="inputEmail3" class="col-sm-12 label_content px-2 py-0">State <span
											style="color:#f00;font-size: 22px;margin-top: 3px;">*</span></label>
									<div class="col-sm-12">
										<select type="text" class="form-control form-control-sm custom-select" required id="state_id"
											name="state_id" onchange="get_city(this.value ,0)">
											<option value="">Select State</option>
										</select>
									</div>
								</div>

								<div class="col-lg-3 col-md-4 col-sm-6">
									<label for="inputEmail3" class="col-sm-12 label_content px-2 py-0">City <span
											style="color:#f00;font-size: 22px;margin-top: 3px;">*</span></label>
									<div class="col-sm-12">
										<select type="text" class="form-control form-control-sm custom-select" required id="city_id"
											name="city_id">
											<option value="">Select City</option>
										</select>
									</div>
								</div>
							</div>

							<div class="form-group row">
								<div class="col-lg-6 col-md-12 col-sm-6">
									<label for="asdf" class="col-sm-12 label_content px-2 py-0">Upload Files </label>

									<div class="card-body py-0 px-2">
										<table class="table table-sm">
											<thead>
												<tr>
													<th>#</th>
													<th width="25%">File Title</th>
													<th>File</th>
													<th></th>
												</tr>
											</thead>
											<tbody class="RFQDetailBody_auf">
												<? $this->load->view('secureRegions/human_resource/Admin_user_module/template/file_line_add_more_auf', $this->data); ?>
											</tbody>
											<tfoot>
												<tr>
													<td colspan="9"><button type="button" onclick="addNewRFQDeatilLine_auf(0)"
															class="btn btn-block btn-default">Add New Line</button>
													<td>
												</tr>
											</tfoot>
										</table>
										<?php if (!empty($admin_user_data->files)) { ?>
											<div class="card-body p-0 " style="width:90% !important">
												<table class="table table-sm" style="width:100%;">
													<thead style="width:100%;">
														<tr style="width:100%;">
															<th colspan="2" style="padding: 10px 5px;"><a data-target="#uploadImg_auf"
																	data-toggle="collapse" class="collapsed uploadImgClick">Uploaded Images <span
																		class="bg-primary fa fa-chevron-down"></span></a></th>
														</tr>
													</thead>
													<tbody class="collapse" id="uploadImg_auf">
														<?php foreach ($admin_user_data->files as $item) { ?>
															<tr id="gi<?= $item->admin_user_file_id ?>">
																<td><?= !empty($item->file_title) ? $item->file_title : "No Name" ?></td>
																<td><span class="">
																		<a target="_blank" href="<?= _uploaded_files_ . 'admin_user_file/' . $item->file_name ?>">
																			<img class="imageThumb "
																				src="<?= _uploaded_files_ . 'admin_user_file/' . $item->file_name ?>" />
																		</a>
																	</span></td>
																<td><button class=" btn btn-outline-danger btn-xs"
																		onclick="return del_auf('<?= $item->admin_user_file_id ?>')" title="remove"><i
																			class="fas fa-trash"></i></button></td>
															</tr>
														<?php } ?>
														<tr>
															<td colspan="2"></td>
														</tr>
													</tbody>
												</table>
											</div>
										<?php } ?>
									</div>

								</div>
								<!-- <div class="col-md-6 col-sm-6">
									<label for="inputEmail3" class="col-sm-12 label_content px-2 py-0">Upload Files <span
											style="color:#f00;font-size: 22px;margin-top: 3px;"></span></label>
									<div class="card-body py-0 px-2">
										<table class="table table-sm">
											<thead>
												<tr>
													<th>#</th>
													<th width="25%">File Title</th>
													<th>File</th>
													<th></th>
												</tr>
											</thead>
											<tbody class="RFQDetailBody_uekf">
												<? $this->load->view('secureRegions/human_resource/Admin_user_module/template/file_line_add_more_uekf', $this->data); ?>
											</tbody>
											<tfoot>
												<tr>
													<td colspan="9"><button type="button" onclick="addNewRFQDeatilLine_uekf(0)"
															class="btn btn-block btn-default">Add New Line</button>
													<td>
												</tr>
											</tfoot>
										</table>
										<?php if (!empty($admin_user_data->files)) { ?>
											<div class="card-body p-0 " style="width:90% !important">
												<table class="table table-sm">
													<thead>
														<tr>
															<th colspan="2" style="padding: 10px 5px;"><a data-target="#uploadImg_uekf"
																	data-toggle="collapse" class="collapsed uploadImgClick uploadImgClick2">Uploaded Files
																	<span class="bg-primary fa fa-chevron-down"></span></a></th>
														</tr>
													</thead>
													<tbody class="collapse" id="uploadImg_uekf">
														<?php foreach ($admin_user_data->files as $item) { ?>
															<tr id="gi<?= $item->admin_user_file_id ?>">
																<td><?= !empty($item->file_title) ? $item->file_title : "NO FILE NAME" ?></td>
																<td><span class="">
																		<a target="_blank" class="btn btn-outline-primary btn-sm"
																			href="<?= _uploaded_files_ . 'admin_user_file/' . $item->file_name ?>">
																			view
																		</a>
																	</span></td>
																<td><button class=" btn btn-outline-danger btn-xs"
																		onclick="return del_uekf('<?= $item->admin_user_file_id ?>')" title="remove"><i
																			class="fas fa-trash"></i></button></td>
															</tr>
														<?php } ?>
														<tr>
															<td colspan="2"></td>
														</tr>
													</tbody>
												</table>
											</div>
										<?php } ?>
									</div>
								</div> -->

								<div class="col-lg-2 col-md-4 col-sm-6">
									<label for="radioSuccess1" class="col-sm-12 label_content px-2 py-0">Status <span
											style="color:#f00;font-size: 22px;margin-top: 3px;"> </span></label>
									<div class="col-sm-12">
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
									<button type="submit" name="save" onclick=" redirect_type_func('');" value="1"
										class="btn btn-info">Save</button>
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<button type="submit" name="save-add-new" onclick=" redirect_type_func('save-add-new');" value="1"
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
	function validateForm() {
		event.preventDefault();
		//user_role admin_user_role_id

		$(".error_span").html("");
		var admin_user_role_id_arr = document.getElementsByName("admin_user_role_id[]");
		var i = 0;
		var user_role_check = true;
		for (i = 0; i < admin_user_role_id_arr.length; i++) {
			if (admin_user_role_id_arr[i].value != '') {
				user_role_check = false;
			}
		}
		if (user_role_check) {
			toastrDefaultErrorFunc("You Did Not Assign The Role To The User.");
			$("#user_role_error").html("You Did Not Assign The Role To The User.");
		}
		else {
			$('#employee_form').attr('onsubmit', '');
			$("#employee_form").submit();
		}
	}

	function redirect_type_func(data) {
		document.getElementById("redirect_type").value = data;
		return true;
	}

	function get_state(country_id, state_id = 0) {
		$("#state_id").html('');
		$("#city_id").html('');
		if (country_id > 0) {
			Pace.restart();
			$.ajax({
				url: "<?php echo MAINSITE_Admin . 'Ajax/get_state' ?>",
				type: 'post',
				dataType: "json",
				data: { 'country_id': country_id, 'state_id': state_id, "<?php echo $csrf['name'] ?>": "<?php echo $csrf['hash'] ?>" },
				success: function (response) {
					$("#state_id").html(response.state_html);
				},
				error: function (request, error) {
					toastrDefaultErrorFunc("Unknown Error. Please Try Again");
				}
			});
		}
	}

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
	window.addEventListener('load', function () {
		<?php if (!empty($country_id) && !empty($state_id)) { ?>
			get_state(<?php echo $country_id ?>, <?php echo $state_id ?>)
		<?php } ?>

		<?php if (!empty($city_id) && !empty($state_id)) { ?>
			get_city(<?php echo $state_id ?>, <?php echo $city_id ?>)
		<?php } ?>

		//setSearch();
		var dateFormat = "DD-MM-YYYY";
		var CurrDate = new Date();
		var MinDate = new Date();
		var MaxDate = new Date();

		dateCurr = moment(CurrDate, dateFormat);
		dateMin = moment(MinDate, dateFormat);
		dateMax = moment(MaxDate, dateFormat);
		$('#joining_date_input').datetimepicker({
			format: dateFormat,
			//maxDate: dateMax,
			ignoreReadonly: true
		});

		$('#termination_date_input').datetimepicker({
			format: dateFormat,
			//maxDate: dateMax,
			timepicker: false,
			ignoreReadonly: true
		});
		<?php if (!empty($termination_date)) { ?>
			$('#termination_date_input').val('');
		<?php } ?>

	})



</script>
<script>
	require(['bootstrap-multiselect'], function (purchase) {
		$('#mySelect').multiselect();
	});
</script>


<script>
	/*  >>> ADDING MORE tdate TEXT*/



	var append_id_uekf = 1;

	function addNewRFQDeatilLine_uekf(id_uekf = 0) {
		append_id_uekf++;
		Pace.restart();
		$.ajax({
			url: "<?= MAINSITE_Admin . $user_access->class_name . '/addNewLine_uekf' ?>",
			type: 'post',
			dataType: "json",
			data: { 'id_uekf': id_uekf, 'append_id_uekf': append_id_uekf, "<?= $csrf['name'] ?>": "<?= $csrf['hash'] ?>" },
			success: function (response) {
				$(".RFQDetailBody_uekf").append(response.template);
				set_qe_sub_table_count_uekf();
				set_qe_sub_table_remove_btn_uekf();
				// calculate_qe_sub_table_price_uekf();
				// set_input_element_functions_uekf();
				// Initialize Summernote



				//$('.summernote').summernote();
				$('.summernote').summernote({ <?= _summernote_ ?> });
				$('.document_file_uekf').on('change', function () {
					let fileName = Array.from(this.files).map(x => x.name).join(', ');
					$(this).siblings('.custom-file-label').addClass("selected").html(fileName);
				});
			},
			error: function (request, error) {
				toastrDefaultErrorFunc("Unknown Error. Please Try Again");
			}
		});
	}



	function set_qe_sub_table_count_uekf() {
		let count_uekf = 0;
		$('.qe_sub_table_count_uekf').each(function (index, value) {
			count_uekf++;
			$(this).html(count_uekf + '.');
		});
	}

	function set_qe_sub_table_remove_btn_uekf() {
		$('.qe_sub_table_remove_td_uekf').html('');
		let count_uekf = 0;
		$('.qe_sub_table_remove_td_uekf').each(function (index, value) {
			count_uekf++;
		});
		if (count_uekf > 1) {
			$('.qe_sub_table_remove_td_uekf').html('<button class="btn btn-outline-danger btn-xs" onclick="remove_qe_sub_table_row_uekf($(this))" title="remove"><i class="fas fa-trash"></i></button>');
		}
	}

	function remove_qe_sub_table_row_uekf(row) {
		row.closest('tr').remove();
		set_qe_sub_table_remove_btn_uekf();
		set_qe_sub_table_count_uekf();
	}


	function del_uekf($admin_user_file_id) {
		if (parseInt($admin_user_file_id) > 0) {
			var s = confirm('You want to delete this file?');
			if (s) {
				$.ajax({
					url: "<?= MAINSITE_Admin . 'Ajax/del_any_file' ?>",
					type: 'post',
					//dataType: "json",
					data: {
						"table_name": "admin_user_file",
						"id_column": "admin_user_file_id",
						'id': $admin_user_file_id,
						"folder_name": "admin_user_file",
						"<?= $csrf['name'] ?>": "<?= $csrf['hash'] ?>"
					},
					success: function (response) {
						toastrDefaultSuccessFunc("Record Deleted Successfully");
						window.location.reload();
						//alert(response);
						$("#quotation_enquiry_file_" + $uekf_id).hide();
					},
					error: function (request, error) {
						toastrDefaultErrorFunc("Unknown Error. Please Try Again");
					}
				});
			}
		}

		return false;
	}

	/* <<<< ADDING MORE uekf TEXT*/




	/*  >>> ADDING MORE Carusel FILES*/

	var append_id_auf = 1;

	function addNewRFQDeatilLine_auf(id_auf = 0) {
		append_id_auf++;
		Pace.restart();
		$.ajax({
			url: "<?= MAINSITE_Admin . $user_access->class_name . '/addNewLine_auf' ?>",
			type: 'post',
			dataType: "json",
			data: { 'id_auf': id_auf, 'append_id_auf': append_id_auf, "<?= $csrf['name'] ?>": "<?= $csrf['hash'] ?>" },
			success: function (response) {
				$(".RFQDetailBody_auf").append(response.template);
				set_qe_sub_table_count_auf();
				set_qe_sub_table_remove_btn_auf();
				calculate_qe_sub_table_price_auf();
				set_input_element_functions_auf();
				// Initialize Summernote
				$('.summernote').summernote({
					<?= _summernote_ ?>
				});
			},
			error: function (request, error) {
				toastrDefaultErrorFunc("Unknown Error. Please Try Again");
			}
		});
	}

	// Use event delegation for file input change event
	$(document).on('change', '.custom-file-input', function () {
		let fileName = Array.from(this.files).map(x => x.name).join(', ');
		$(this).siblings('.custom-file-label').addClass("selected").html(fileName);
	});

	function set_qe_sub_table_count_auf() {
		let count_auf = 0;
		$('.qe_sub_table_count_auf').each(function (index, value) {
			count_auf++;
			$(this).html(count_auf + '.');
		});
	}

	function set_qe_sub_table_remove_btn_auf() {
		$('.qe_sub_table_remove_td_auf').html('');
		let count_auf = 0;
		$('.qe_sub_table_remove_td_auf').each(function (index, value) {
			count_auf++;
		});
		if (count_auf > 1) {
			$('.qe_sub_table_remove_td_auf').html('<button class="btn btn-outline-danger btn-xs" onclick="remove_qe_sub_table_row_auf($(this))" title="remove"><i class="fas fa-trash"></i></button>');
		}
	}

	function remove_qe_sub_table_row_auf(row) {
		row.closest('tr').remove();
		set_qe_sub_table_remove_btn_auf();
		set_qe_sub_table_count_auf();
	}

	function del_auf($admin_user_file_id) {
		if (parseInt($admin_user_file_id) > 0) {
			var s = confirm('You want to delete this file?');
			if (s) {
				$.ajax({
					url: "<?= MAINSITE_Admin . 'Ajax/del_any_file' ?>",
					type: 'post',
					//dataType: "json",
					data: {
						"table_name": "admin_user_file",
						"id_column": "admin_user_file_id",
						'id': $admin_user_file_id,
						"folder_name": "admin_user_file",
						"<?= $csrf['name'] ?>": "<?= $csrf['hash'] ?>"
					},
					success: function (response) {
						toastrDefaultSuccessFunc("Record Deleted Successfully");
						window.location.reload();
						//alert(response);
						$("#quotation_enquiry_file_" + $id).hide();
					},
					error: function (request, error) {
						toastrDefaultErrorFunc("Unknown Error. Please Try Again");
					}
				});
			}
		}

		return false;
	}
	/* <<<< ADDING MORE FILES*/
</script>