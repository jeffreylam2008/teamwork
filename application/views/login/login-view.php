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
                <option name="<?=$v['shop_code']?>">
                  <?=$v['name']?>
                </option>
                <?php endforeach;?>
              </select>
            </div>
            <div class="form-check">
              <label class="form-check-label">
                <input type="checkbox" class="form-check-input">
                <small>Remember Me</small>
              </label>
              <button type="submit" name="i-submit" class="btn btn-primary float-right">Submit</button>
            </div>
          </form>
          <div class="copy-text"><a href="">link here</a></div>
        </div>
    


        <div class="col-md-8">
          <!-- <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
               <ol class="carousel-indicators">
                  <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                  <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                  <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                </ol>
                      <div class="carousel-inner" role="listbox">-->
                                  <!-- <div class="carousel-item active"> -->
                                    <img class=" img-fluid" src="<?=base_url('assets/img/sa-hotel.jpg');?>" alt="First slide" width="100%" />
                                  
                                  <!-- </div> -->
                            <!--
                                  <div class="carousel-item">
                                    <img class="d-block img-fluid" src="https://images.pexels.com/photos/7097/people-coffee-tea-meeting.jpg" alt="First slide">
                                    <div class="carousel-caption d-none d-md-block">
                                      <div class="banner-text">
                                          <h2>This is Heaven</h2>
                                          <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation</p>
                                      </div>	
                                    </div>
                                  </div>

                                  <div class="carousel-item">
                                    <img class="d-block img-fluid" src="https://images.pexels.com/photos/872957/pexels-photo-872957.jpeg" alt="First slide">
                                    <div class="carousel-caption d-none d-md-block">
                                      <div class="banner-text">
                                          <h2>This is Heaven</h2>
                                          <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation</p>
                                      </div>	
                                    </div>
                                  </div>
-->
                      <!--</div>-->
            <!--</div>-->
        </div>
    <!-- container 
    </div>-->
</section>