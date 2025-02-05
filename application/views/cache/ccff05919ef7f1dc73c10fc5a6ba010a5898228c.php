<div id="kt_header" class="header header-fixed">
	<div class="container-fluid d-flex align-items-stretch justify-content-between">
		<div class="header-menu-wrapper header-menu-wrapper-left" id="kt_header_menu_wrapper">
			<div class="header-logo">
				<?php if($ci->session->userdata('username')): ?>
				<a href="<?php echo e(base_url()); ?><?php echo e($ci->session->userdata('username')); ?>">
					<img alt="Logo" src="<?php echo e(base_url()); ?>assets/img/site/logo/logo-dark3.png" />
				</a>
					
				<?php else: ?>
				<a href="<?php echo e(base_url()); ?>">
					<img alt="Logo" src="<?php echo e(base_url()); ?>assets/img/site/logo/logo-dark3.png" />
				</a>
					
				<?php endif; ?>
			</div>
			<div id="kt_header_menu" class="header-menu header-menu-mobile header-menu-layout-default">
				<ul class="menu-nav">

					<?php if($ci->ion_auth->logged_in()): ?>
					
					
								
					<?php else: ?>
						
					<?php endif; ?>
					
					
					
					
					
					
				</ul>
				<!--end::Header Nav-->
			</div>
			<!--end::Header Menu-->
		</div>
		<!--end::Header Menu Wrapper-->
		<!--begin::Topbar-->
		<div class="topbar">
			<!--begin::Search-->
			<div class="dropdown" id="kt_quick_search_toggle">
				<!--begin::Toggle-->
				<div class="topbar-item" data-toggle="dropdown" data-offset="10px,0px">
					<div class="btn btn-icon btn-clean btn-lg btn-dropdown mr-1">
						<span class="svg-icon svg-icon-xl svg-icon-primary">
							<!--begin::Svg Icon | path:assets/media/svg/icons/General/Search.svg-->
							<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
								<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
									<rect x="0" y="0" width="24" height="24" />
									<path d="M14.2928932,16.7071068 C13.9023689,16.3165825 13.9023689,15.6834175 14.2928932,15.2928932 C14.6834175,14.9023689 15.3165825,14.9023689 15.7071068,15.2928932 L19.7071068,19.2928932 C20.0976311,19.6834175 20.0976311,20.3165825 19.7071068,20.7071068 C19.3165825,21.0976311 18.6834175,21.0976311 18.2928932,20.7071068 L14.2928932,16.7071068 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" />
									<path d="M11,16 C13.7614237,16 16,13.7614237 16,11 C16,8.23857625 13.7614237,6 11,6 C8.23857625,6 6,8.23857625 6,11 C6,13.7614237 8.23857625,16 11,16 Z M11,18 C7.13400675,18 4,14.8659932 4,11 C4,7.13400675 7.13400675,4 11,4 C14.8659932,4 18,7.13400675 18,11 C18,14.8659932 14.8659932,18 11,18 Z" fill="#000000" fill-rule="nonzero" />
								</g>
							</svg>
							<!--end::Svg Icon-->
						</span>
					</div>
				</div>
				<!--end::Toggle-->
				<!--begin::Dropdown-->
				<div class="dropdown-menu p-0 m-0 dropdown-menu-right dropdown-menu-anim-up dropdown-menu-lg">
					<div class="quick-search quick-search-dropdown" id="kt_quick_search_dropdown">
						<!--begin:Form-->
						<form method="get" class="quick-search-form">
							<div class="input-group">
								<div class="input-group-prepend">
									<span class="input-group-text">
										<span class="svg-icon svg-icon-lg">
											<!--begin::Svg Icon | path:assets/media/svg/icons/General/Search.svg-->
											<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
												<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
													<rect x="0" y="0" width="24" height="24" />
													<path d="M14.2928932,16.7071068 C13.9023689,16.3165825 13.9023689,15.6834175 14.2928932,15.2928932 C14.6834175,14.9023689 15.3165825,14.9023689 15.7071068,15.2928932 L19.7071068,19.2928932 C20.0976311,19.6834175 20.0976311,20.3165825 19.7071068,20.7071068 C19.3165825,21.0976311 18.6834175,21.0976311 18.2928932,20.7071068 L14.2928932,16.7071068 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" />
													<path d="M11,16 C13.7614237,16 16,13.7614237 16,11 C16,8.23857625 13.7614237,6 11,6 C8.23857625,6 6,8.23857625 6,11 C6,13.7614237 8.23857625,16 11,16 Z M11,18 C7.13400675,18 4,14.8659932 4,11 C4,7.13400675 7.13400675,4 11,4 C14.8659932,4 18,7.13400675 18,11 C18,14.8659932 14.8659932,18 11,18 Z" fill="#000000" fill-rule="nonzero" />
												</g>
											</svg>
											<!--end::Svg Icon-->
										</span>
									</span>
								</div>
								<input type="text" class="form-control" placeholder="Search..." />
								<div class="input-group-append">
									<span class="input-group-text">
										<i class="quick-search-close ki ki-close icon-sm text-muted"></i>
									</span>
								</div>
							</div>
						</form>
						<!--end::Form-->
						<!--begin::Scroll-->
						<div class="quick-search-wrapper scroll" data-scroll="true" data-height="325" data-mobile-height="200"></div>
						<!--end::Scroll-->
					</div>
				</div>
				<!--end::Dropdown-->
			</div>
			<!--end::Search-->
			<!--begin::Notifications-->
			
			<!--end::Notifications-->
			<!--begin::Quick Actions-->
			
			<!--end::Quick Actions-->
			<!--begin::Cart-->
			
			<!--end::Cart-->
			<!--begin::Quick panel-->
			
			<!--end::Quick panel-->
			<!--begin::Chat-->
			
			<!--end::Chat-->
			<!--begin::Languages-->
			
			<!--end::Languages-->
			<!--begin::User-->
			<div class="topbar-item">
				<?php if($ci->ion_auth->logged_in()): ?>
				<?php
				$user_id = $ci->session->userdata('user_id');
	            $user_now = $ci->ion_auth->user($user_id)->row();
				?>
				<div class="btn btn-icon btn-icon-mobile w-auto btn-clean d-flex align-items-center btn-lg px-2" id="kt_quick_user_toggle">
					<span class="text-muted font-weight-bold font-size-base d-none d-md-inline mr-1">Hi,</span>
					<span class="text-dark-50 font-weight-bolder font-size-base d-none d-md-inline mr-3"><?php echo e($user_now->first_name); ?> <?php echo e($user_now->last_name); ?></span>
					<span class="symbol symbol-lg-35 symbol-25 symbol-light-success">
						<span class="symbol-label font-size-h5 font-weight-bold"><?php echo e(substr($user_now->first_name, 0, 1)); ?> <?php echo e(substr($user_now->last_name, 0, 1)); ?></span>
					</span>
				</div>
				<?php else: ?>
				<?php
					echo anchor(base_url().'auth/login', 'Login', ['class' => 'btn btn-primary font-weight-bold']);
				?>
				<?php endif; ?>
			</div>
			<!--end::User-->
		</div>
		<!--end::Topbar-->
	</div>
	<!--end::Container-->
</div><?php /**PATH C:\xampp-7.3.33\htdocs\surveiku_spak_spkp\application\views/include_backend/partials_no_aside/_kt_header.blade.php ENDPATH**/ ?>