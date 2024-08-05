<?php
// Initialize $id to 1
$id_auf = 1;

// Check if $append_id is not empty
if (!empty($append_id_auf)) {
  // If $append_id is not empty, assign its value to $id
  $id_auf = $append_id_auf;
}
?>

<!-- Start of a new table row -->
<tr class="  qe_sub_table_tr_auf">
  <!-- Table cell for the row count (this will be updated dynamically) -->
  <td class=" qe_sub_table_count_auf">1.</td>

  <!-- Table cell for the file title input -->
  <td>
    <!-- Input field for the file title with a dynamic ID -->
    <input type="text" name="file_title_auf[]" id="file_title_auf_<?= $id_auf ?>" placeholder="File Title"
      class="form-control search-code form-control-sm" />
    <!-- Hidden input field for the quotation enquiry detail ID with a dynamic ID -->
    <!-- <input type="hidden" name="quotation_enquiry_detail_id[]" id="quotation_enquiry_detail_id<?= $id_auf ?>" value="" /> -->
  </td>

  <!-- Table cell for the file input -->
  <td>
    <div class="input-group">
      <div class="custom-file">
        <!-- Hidden input field to store the file name (initially empty) -->
        <!-- <input type="hidden" name="file_name[]" value="" /> -->
        <!-- File input field for selecting a file -->
        <input type="file" name="file_name_auf[]" class="custom-file-input" id="file_name_auf_<?= $id_auf ?>"
          onchange="previewImage_auf(<?= $id_auf ?>)">
        <!-- Label for the file input, initially empty -->
        <label class="custom-file-label form-control-sm" for="files">Choose file</label>
      </div>

    </div>
  </td>

  <!-- Table cell for the remove button (this will be updated dynamically) -->
  <td class=" qe_sub_table_remove_td_auf"></td>
</tr>