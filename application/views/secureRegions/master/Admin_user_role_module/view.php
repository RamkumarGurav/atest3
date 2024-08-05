<?php

$page_module_name = "Role Master";

?>
<?
$name = "";
$admin_user_role_id = 0;
$status = 1;
$record_action = "Add New Record";
if (!empty($admin_user_role_data)) {
    // $record_action = "Update";
    // $admin_user_role_id = $admin_user_role_data->admin_user_role_id;
    // $name = $admin_user_role_data->name;
    // $status = $admin_user_role_data->status;

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
                    <h1 class="m-0 text-dark"><?php echo $page_module_name ?> <small>Details</small></h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo MAINSITE_Admin . "wam" ?>">Home</a></li>
                        <li class="breadcrumb-item"><a
                                href="<?php echo MAINSITE_Admin . $user_access->class_name . "/" . $user_access->function_name ?>"><?php echo $user_access->module_name ?>
                                List</a></li>
                        <li class="breadcrumb-item active">Details</li>
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
                        <h3 class="card-title"><?php echo $admin_user_role_data->admin_user_role_name ?></h3>
                        <div class="float-right">
                            <?php
                            if ($user_access->add_module == 1 && false) {
                                ?>
                                <a href="<?php echo MAINSITE_Admin . $user_access->class_name ?>/edit">
                                    <button type="button" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Add
                                        New</button></a>
                            <?php } ?>
                            <?php
                            if ($user_access->update_module == 1) {
                                ?>
                                <a
                                    href="<?php echo MAINSITE_Admin . $user_access->class_name ?>/edit/<?php echo $admin_user_role_data->admin_user_role_id ?>">
                                    <button type="button" class="btn btn-success btn-sm"><i class="fas fa-edit"></i>
                                        Update</button>
                                </a>
                            <?php } ?>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <?php
                    if ($user_access->view_module == 1) {
                        ?>
                        <div class="card-body">

                            <?php echo form_open(MAINSITE_Admin . "$user_access->class_name/userRole-doUpdateStatus", array('method' => 'post', 'id' => 'ptype_list_form', "name" => "ptype_list_form", 'style' => '', 'class' => 'form-horizontal', 'role' => 'form', 'enctype' => 'multipart/form-data')); ?>

                            <input type="hidden" name="task" id="task" value="" />
                            <?php echo $this->session->flashdata('alert_message'); ?>


                            <div class="form-group row table-responsive">
                                <table id="" class="table table-bordered table-hover " style="font-size:medium">
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
                                        <?
                                        $count = 0;

                                        foreach ($module_data as $md) {
                                            $count++;
                                            ?>

                                            <?
                                            $all_checked = $view_checked = $add_checked = $update_checked = $delete_checked = $approval_checked = $import_checked = $export_checked = '<button type="button" class="btn btn-sm btn-block btn-danger">No</button>';
                                            if (!empty($module_permission_data)) {
                                                foreach ($module_permission_data as $mpd) {
                                                    if ($md->module_id == $mpd->module_id) {
                                                        if (!empty($mpd->view_module)) {
                                                            $view_checked = '<button type="button" class="btn btn-sm btn-block btn-success">Yes</button> ';
                                                            $all_checked = 'checked';
                                                        }

                                                        if (!empty($mpd->add_module)) {
                                                            $add_checked = '<button type="button" class="btn btn-sm btn-block btn-success">Yes</button>';
                                                            $all_checked = 'checked';
                                                        }

                                                        if (!empty($mpd->update_module)) {
                                                            $update_checked = '<button type="button" class="btn btn-sm btn-block btn-success">Yes</button>';
                                                            $all_checked = 'checked';
                                                        }

                                                        if (!empty($mpd->delete_module)) {
                                                            $delete_checked = '<button type="button" class="btn btn-sm btn-block btn-success">Yes</button>';
                                                            $all_checked = 'checked';
                                                        }

                                                        if (!empty($mpd->approval_module)) {
                                                            $approval_checked = '<button type="button" class="btn btn-sm btn-block btn-success">Yes</button>';
                                                            $all_checked = 'checked';
                                                        }

                                                        if (!empty($mpd->import_data)) {
                                                            $import_checked = '<button type="button" class="btn btn-sm btn-block btn-success">Yes</button>';
                                                            $all_checked = 'checked';
                                                        }

                                                        if (!empty($mpd->export_data)) {
                                                            $export_checked = '<button type="button" class="btn btn-sm btn-block btn-success">Yes</button>';
                                                            $all_checked = 'checked';
                                                        }
                                                    }

                                                }
                                            }

                                            if ($all_checked == 'checked') {
                                                $all_checked = '<button type="button" class="btn btn-sm btn-block btn-success">Yes</button>';
                                            }


                                            ?>


                                            <tr>
                                                <td><?php echo $count ?>.</td>
                                                <td><?php echo $md->module_name ?> [
                                                    <?php echo $md->main_menu_name ?> ]
                                                </td>
                                                <td>
                                                    <?php echo $all_checked ?>
                                                </td>
                                                <td>
                                                    <?php echo $view_checked ?>
                                                </td>
                                                <td>
                                                    <?php echo $add_checked ?>
                                                </td>
                                                <td>
                                                    <?php echo $update_checked ?>
                                                </td>
                                                <?php   /* ?><td>
                                                                                                                                                          <?php   echo $delete_checked?>
                                                                                                                                                      </td>
                                                                                                                                                      <td>
                                                                                                                                                          <?php   echo $approval_checked?>
                                                                                                                                                      </td><?php   */ ?>
                                                <td>
                                                    <?php echo $import_checked ?>
                                                </td>
                                                <td>
                                                    <?php echo $export_checked ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>

                            <div class="row">
                                <table id="" class="table table-bordered table-hover myviewtable responsiveTableNewDesign">

                                    <tbody>

                                        <tr>
                                            <td>
                                                <strong class="full">Data Base Id</strong>
                                                <?php echo $admin_user_role_data->admin_user_role_id ?>
                                            </td>
                                            <td>
                                                <strong class="full">User Role Name</strong>
                                                <?php echo $admin_user_role_data->admin_user_role_name ?>
                                            </td>
                                            <td>
                                                <strong class="full">Added On</strong>
                                                <?php echo date("d-m-Y h:i:s A", strtotime($admin_user_role_data->added_on)) ?>
                                            </td>
                                            <td class="full">
                                                <strong class="full">Added By</strong>
                                                <?php echo $admin_user_role_data->added_by_name ?>
                                            </td>
                                            <td>
                                                <strong class="full">Updated On</strong>
                                                <?php if (!empty($admin_user_role_data->updated_on)) {
                                                    echo date("d-m-Y h:i:s A", strtotime($admin_user_role_data->updated_on));
                                                } else {
                                                    echo "-";
                                                } ?>
                                            </td>
                                            <td>
                                                <strong class="full">Updated By</strong>
                                                <?php if (!empty($admin_user_role_data->updated_by_name)) {
                                                    echo $admin_user_role_data->updated_by_name;
                                                } else {
                                                    echo "-";
                                                } ?>
                                            </td>
                                            <td>
                                                <strong class="full">Status</strong>
                                                <?php if ($admin_user_role_data->status == 1) { ?> Active <i
                                                        class="fas fa-check btn-success btn-sm "></i>
                                                <?php } else { ?> Block <i class="fas fa-ban btn-danger btn-sm "></i> Block
                                                <?php } ?></ td>

                                        </tr>

                                    </tbody>

                                </table>
                            </div>
                            <?php echo form_close() ?>
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

<script type="application/javascript">
    function check_uncheck_All_records() // done
    {
        var mainCheckBoxObj = document.getElementById("main_check");
        var checkBoxObj = document.getElementsByName("sel_recds[]");

        for (var i = 0; i < checkBoxObj.length; i++) {
            if (mainCheckBoxObj.checked)
                checkBoxObj[i].checked = true;
            else
                checkBoxObj[i].checked = false;
        }
    }

    function validateCheckedRecordsArray() // done
    {
        var checkBoxObj = document.getElementsByName("sel_recds[]");
        var count = true;

        for (var i = 0; i < checkBoxObj.length; i++) {
            if (checkBoxObj[i].checked) {
                count = false;
                break;
            }
        }

        return count;
    }

    function validateRecordsActivate() // done
    {
        if (validateCheckedRecordsArray()) {
            //alert("Please select any record to activate.");
            toastrDefaultErrorFunc("Please select any record to activate.");
            document.getElementById("sel_recds1").focus();
            return false;
        } else {
            document.ptype_list_form.task.value = 'active';
            document.ptype_list_form.submit();
        }
    }

    function validateRecordsBlock() // done
    {
        if (validateCheckedRecordsArray()) {
            //alert("Please select any record to block.");
            toastrDefaultErrorFunc("Please select any record to block.");
            document.getElementById("sel_recds1").focus();
            return false;
        } else {
            document.ptype_list_form.task.value = 'block';
            document.ptype_list_form.submit();
        }
    }
</script>