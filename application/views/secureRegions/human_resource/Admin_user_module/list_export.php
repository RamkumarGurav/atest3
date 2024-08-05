<?php


$filename = "Employee-List-" . date('d-m-Y') . ".xls";
header("Content-Disposition: attachment; filename=\"$filename\"");

header("Content-Type: application/vnd.ms-excel");
//print_r($admin_user_data);

?>

<!DOCTYPE html
	PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

	<title>Employee List</title>

</head>



<body>
	<?php
	$colspan = 8;
	?>
	<table width="100%" border="1" align="center" cellpadding="0" cellspacing="0">
		<thead>
			<?php if (!empty($start_date) || !empty($end_date)) { ?>
				<tr>
					<th colspan="<?php echo $colspan ?>" style="background-color:#CCC" width="*"><br />

						Search Record :
						<?php if (!empty($start_date)) {
							echo "From : " . date('d-m-Y', strtotime($start_date));
						} ?>

						<?php if (!empty($end_date)) {
							echo " &nbsp;&nbsp;&nbsp;&nbsp;	 To : " . date('d-m-Y', strtotime($end_date));
						} ?>
						<br />&nbsp;

					</th>

				</tr>
			<?php } ?>

			<tr>
				<th style="background-color:#999" width="*">Sl. No.</th>
				<th style="background-color:#999" width="*">Employee Name</th>
				<th style="background-color:#999" width="*" colspan="3">Role</th>
				<th style="background-color:#999" width="*">Designation</th>
				<th style="background-color:#999" width="*">Email Id</th>
				<th style="background-color:#999" width="*">Mobile No</th>
				<th style="background-color:#999" width="*">Alt Mobile No</th>
				<th style="background-color:#999" width="*">Fax No</th>
				<th style="background-color:#999" width="*">Address 1</th>
				<th style="background-color:#999" width="*">Address 2</th>
				<th style="background-color:#999" width="*">Address 3</th>
				<th style="background-color:#999" width="*">Pincode</th>
				<th style="background-color:#999" width="*">City</th>
				<th style="background-color:#999" width="*">State</th>
				<th style="background-color:#999" width="*">Country</th>
				<th style="background-color:#999" width="*">Country Dial Code</th>
				<th style="background-color:#999" width="*">Data View</th>
				<th style="background-color:#999" width="*">Data View</th>
				<th style="background-color:#999" width="*">Added On</th>
				<th style="background-color:#999" width="*">Added By</th>
				<th style="background-color:#999" width="*">Updated On</th>
				<th style="background-color:#999" width="*">Updated By</th>
				<th style="background-color:#999" width="*">Status</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$count = 0;
			// echo "count : ".count($ptype_list)." <br>";
			if (!empty($admin_user_data)) { //print_r($ptype_list);
				foreach ($admin_user_data as $item) {
					//if($item->in_hand > 0 || $item->total_purchase > 0 || $item->total_sold > 0 || $item->total_returned > 0 || $item->to_receive > 0 )
					{
						$count++;


						?>
						<tr>
							<td width="*"><?php echo $count; ?></td>
							<td width="*"><?php echo $item->name; ?></td>
							<td width="*" colspan="3">
								<?php if (!empty($item->roles)) { ?>
									<table border="1" align="center" cellpadding="0" cellspacing="0" style="width:90%">
										<thead>
											<tr>
												<th style="width: 10px">#</th>
												<th>Company</th>
												<th>Role </th>
											</tr>
										</thead>
										<tbody>
											<?php $c_count = 0;
											foreach ($item->roles as $role) {
												$c_count++; ?>
												<tr>
													<td><?php echo $c_count ?>.</td>
													<td><?php echo $role->company_unique_name ?></td>
													<td><?php echo $role->user_role_name ?></td>
												</tr>
											<?php } ?>
										</tbody>
									</table><?php  //echo $item->role; ?>
								<?php } ?>
							</td>
							<td width="*"><?php echo $item->designation_name; ?></td>




							<td width="*"><?php echo $item->email; ?></td>
							<td width="*"><?php echo $item->mobile_no; ?></td>
							<td width="*"><?php echo $item->alt_mobile_no; ?></td>
							<td width="*"><?php echo $item->fax_no; ?></td>
							<td width="*"><?php echo $item->address1; ?></td>
							<td width="*"><?php echo $item->address2; ?></td>
							<td width="*"><?php echo $item->address3; ?></td>
							<td width="*"><?php echo $item->pincode; ?></td>
							<td width="*"><?php echo $item->city_name; ?></td>
							<td width="*"><?php echo $item->state_name; ?></td>
							<td width="*"><?php echo $item->country_name; ?></td>
							<td width="*"><?php echo $item->dial_code; ?></td>
							<td width="*">
								<?php if ($item->data_view == 1) { ?> Yes
								<?php } else { ?>No
								<?php } ?>
							</td>
							<td width="*">
								<?php if ($item->approval_access == 1) { ?> Yes
								<?php } else { ?>No
								<?php } ?>
							</td>
							<td width="*"> <?php echo date('d-m-Y h:i:s A', strtotime($item->added_on)); ?> &nbsp;</td>
							<td width="*"><?php echo $item->added_by_name; ?></td>
							<td width="*">
								<?php if (!empty($item->updated_on)) {
									echo date('d-m-Y h:i:s A', strtotime($item->updated_on));
								} ?> &nbsp;</td>
							<td width="*"><?php if (!empty($item->updated_by_name)) {
								echo $item->updated_by_name;
							} ?></td>
							<td width="*">
								<?php if ($item->status == 1) { ?> Active
								<?php } else { ?>Block
								<?php } ?>
							</td>

						</tr>
					<?php }
				} ?>


			<?php } else { ?>
				<tr>
					<th colspan="<?php echo $colspan ?>">No records to display...</th>
				</tr>

			<?php } ?>
		</tbody>

	</table>



</body>

</html>