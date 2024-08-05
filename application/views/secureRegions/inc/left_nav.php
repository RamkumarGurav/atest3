<style>
	.left_nav_icon_img {
		width: 15px;
	}

	.nav-icon {
		font-size: 10px !important
	}
</style>

<aside class="main-sidebar sidebar-dark-primary elevation-4">
	<a href="<?php echo MAINSITE_Admin . 'wam' ?>" class="brand-link">
		<img src="<?php echo _lte_files_ ?>dist/img/AdminLTELogo.png" alt="AdminLTE Logo"
			class="brand-image img-circle elevation-3" style="opacity: .8">
		<span class="brand-text font-weight-light"><?php echo _brand_name_ ?></span>
	</a>

	<div class="sidebar">
		<!-- Sidebar user panel (optional) -->
		<!-- <?php echo form_open("", array('method' => 'get', 'id' => 'sidebar-form', "name" => "sidebar-form", 'style' => '', 'class' => 'form-horizontal', 'role' => 'form', 'onsubmit' => 'return false', 'autocomplete' => 'on')); ?>
		<div class="input-group">
			<input type="text" name="q" class="form-control" autocomplete="off" placeholder="Search..." id="search-input">

		</div>
		<?php echo form_close() ?> -->
		<!-- Sidebar Menu -->
		<nav class="mt-2">
			<ul class="nav nav-pills nav-sidebar flex-column sidebar-menu tree" data-widget="treeview" role="menu"
				data-accordion="false">


				<?php if (!empty($module_list_data)): ?>


					<?php foreach ($module_list_data as $item): ?>
						<!--Company Profile-->
						<?php
						if (!empty($item)) {
							$is_open = "";
							$active = "";
							if (!empty($page_is_master)) {
								if ($page_is_master == $item->is_master) {
									$is_open = "menu-open";
									$active = "active";
								}
							}
							?>
							<li class="nav-item has-treeview <?php echo $is_open ?>">
								<a href="#" class="nav-link <?php echo $active ?>"><i class="nav-icon fas fa-th"></i>
									<p><?= $item->main_menu_name ?><i class="fas fa-angle-left right"></i></p>
								</a>
								<ul class="nav nav-treeview"><?php echo $item->module_data ?></ul>
							</li>
							<?php
						}
						?>
					<?php endforeach; ?>
				<?php endif; ?>


			</ul>
		</nav>
		<!-- /.sidebar-menu -->
	</div>
</aside>