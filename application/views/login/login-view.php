<section class="login-block">
   <!-- <div class="container">-->
      <div class="row">
        <div class="col-md-4 login-sec">
          <h2 class="text-center"><img class="d-block img-fluid" src="<?=base_url('assets/img/just-logo.png');?>" alt="First slide" width="98%" height="100%"/></h2>
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
            <?php 
              if(isset($e_code) && !empty($e_code)):
            ?>
            <div class="input-group mb-5">
                <label for="" class="text-uppercase text-danger"><?=$e_code?> - <?=$e_msg?> </label>
            </div>
            <?php
              endif;
            ?>
            <?php 
              if(isset($s_status) && !empty($s_status)):
            ?>
              DEBUG MODE:
            <div class="input-group mb-5">
              <?php
              var_dump($s_status);
              ?>
            </div>
            <?php
              endif;
            ?>
            
          </form>
          
          <div class="copy-text"><a href="">link here</a></div>
        </div>

        <div class="col-md-8">
          <img class=" img-fluid" src="<?=base_url('assets/img/just-bg.png');?>" alt="First slide" width="100%" />
        </div>
    <!-- container
    </div>-->
</section>