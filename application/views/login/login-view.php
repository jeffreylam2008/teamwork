<section class="login-block">
   <!-- <div class="container">-->
      <div class="row">
        <div class="col-md-4 login-sec">
          <h2 class="text-center"><img class="d-block img-fluid" src="<?=base_url('assets/img/logo-white.png');?>" alt="First slide" width="98%" height="100%"/></h2>
          <form class="login-form" action="<?=$submit?>" method="POST">
            <div class="form-group">
              <label for="" class="text-uppercase">Username</label>
              <input type="text" name="i-username" class="form-control" placeholder="">
            </div>
            <div class="form-group">
              <label for="" class="text-uppercase">Password</label>
              <input type="password" name="i-password" class="form-control" placeholder="">
            </div>
            <div class="form-group">
              <label for="" class="text-uppercase">Companies</label>
              <select name="i-shops" class="form-control">
                <?php foreach($shop as $k => $v): ?>
                <option value="<?=$v['shop_code']?>">
                  <?=$v['name']?>
                </option>
                <?php endforeach;?>
              </select>
            </div>
            <div class="form-check">
              <label class="form-check-label">
                <input type="checkbox" name="i-rememberme" class="form-check-input">
                <small>Remember Me</small>
              </label>
              <button type="submit" name="i-submit" class="btn btn-primary float-right">Submit</button>
            </div>
          </form>
          <div class="copy-text"><a href="">link here</a></div>
        </div>

        <div class="col-md-8">
          <img class=" img-fluid" src="<?=base_url('assets/img/sa-hotel.jpg');?>" alt="First slide" width="100%" />
        </div>
    <!-- container
    </div>-->
</section>