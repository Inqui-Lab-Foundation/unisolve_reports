      <div id="page-container">

          <!-- Main Container -->
          <main id="main-container">
              <!-- Page Content -->
              <div class="bg-image"
                  style="background-image: url('<?=base_url();?>assets/media/photos/photo28@2x.jpg');">
                  <div class="row no-gutters bg-primary-dark-op">
                      <!-- Meta Info Section -->
                      <div class="hero-static col-lg-4 d-none d-lg-flex flex-column justify-content-center">
                          <div class="p-4 p-xl-5 flex-grow-1 d-flex align-items-center">
                              <div class="w-100">
                                  <a class="link-fx font-w600 font-size-h2 text-white" href="<?=base_url();?>">
                                      NGI <span class="font-w400">Payments</span>
                                  </a>
                                  <p class="text-white-75 mr-xl-8 mt-2">
                                      Welcome to NGI Official College Campus ERP app. Feel free to login and start
                                      managing your data.
                                  </p>
                              </div>
                          </div>
                          <div class="p-4 p-xl-5 d-xl-flex justify-content-between align-items-center font-size-sm">
                              <p class="font-w400 text-white mb-0">
                                  <strong>Campus 1.0</strong> &copy; <span data-toggle="year-copy"></span>
                              </p>
                              <p class="font-w500 text-white-50 mb-0">
                                  <a href="http://medhatech.in" class="font-w400 text-white mb-0">Developed by Medha
                                      Tech </a>
                              </p>
                          </div>
                      </div>
                      <!-- END Meta Info Section -->

                      <!-- Main Section -->
                      <div class="hero-static col-lg-8 d-flex flex-column align-items-center bg-white">
                          <div class="p-3 w-100 d-lg-none text-center">
                              <a class="link-fx font-w600 font-size-h3 text-dark" href="<?=base_url();?>">
                                  BMSCW <span class="font-w400">Campus</span>
                              </a>
                          </div>
                          <div class="p-4 w-100 flex-grow-1 d-flex align-items-center">
                              <div class="w-100">
                                  <!-- Header -->
                                  <div class="text-center mb-0">
                                      <!-- <p class="mb-3">
                                          <i class="fa fa-2x fa-circle-notch text-primary-light"></i>
                                      </p> -->
                                      <img src="<?=base_url();?>assets/img/ncms_logo.png" class="login_logo" />
                                      <h4 class="font-w700 mt-3">
                                          Login into Admin Account
                                      </h4>
                                  </div>
                                  <!-- END Header -->

                                  <!-- Sign In Form -->
                                  <!-- jQuery Validation (.js-validation-signin class is initialized in js/pages/op_auth_signin.min.js which was auto compiled from _js/pages/op_auth_signin.js) -->
                                  <!-- For more info and examples you can check out https://github.com/jzaefferer/jquery-validation -->
                                  <div class="row no-gutters justify-content-center">
                                      <div class="col-sm-8 col-xl-6">

                                          <p id="msg" class="<?=$cls;?>"><?=$msg;?></p>

                                          <?php echo form_open($action, 'class="js-validation-signin" method="POST"'); ?>
                                          <?php echo '<span class="text-danger mb-0">'.validation_errors().'</span>'; ?>
                                          <div class="form-group">
                                              <input type="text"
                                                  class="form-control form-control-lg form-control-alt py-4"
                                                  id="username" name="username" placeholder="Enter Username"
                                                  autocomplete="off">
                                          </div>
                                          <div class="form-group">
                                              <input type="password"
                                                  class="form-control form-control-lg form-control-alt py-4"
                                                  id="password" name="password" placeholder="Enter Password"
                                                  autocomplete="off">
                                          </div>
                                          <div class="form-group d-flex justify-content-between align-items-center">
                                              <div>
                                                  <!-- <a class="text-muted font-size-sm font-w500 d-block d-lg-inline-block mb-1"
                                                      href="<?=base_url();?>">
                                                      Forgot Password?
                                                  </a> -->
                                              </div>
                                              <div>
                                                  <button type="submit" class="btn btn-lg btn-alt-primary">
                                                      <i class="fa fa-fw fa-sign-in-alt mr-1 opacity-50"></i> Sign
                                                      In
                                                  </button>
                                              </div>
                                          </div>
                                          <?php echo form_close(); ?>
                                      </div>
                                  </div>
                                  <!-- END Sign In Form -->
                              </div>
                          </div>
                          <div
                              class="px-4 py-3 w-100 d-lg-none d-flex flex-column flex-sm-row justify-content-between font-size-sm text-center text-sm-left">
                              <p class="font-w500 text-black-50 py-2 mb-0">
                                  <strong>Campus 1.0</strong> &copy; <span data-toggle="year-copy"></span>
                              </p>
                              <p class="font-w500 text-black-50 py-2 mb-0">
                                  <a href="http://medhatech.in" class="font-w400 text-black mb-0">Developed by Medha
                                      Tech </a>
                              </p>
                          </div>
                      </div>
                      <!-- END Main Section -->
                  </div>
              </div>
              <!-- END Page Content -->
          </main>
          <!-- END Main Container -->
      </div>