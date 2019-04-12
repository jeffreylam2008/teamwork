<!-- <nav class="navbar navbar-expand-sm navbar-light bg-light justify-content-between"> -->
<!-- <nav class="navbar navbar-default">
    <div class="container-fluid">
        
        <div class="navbar-header">
            <a class="navbar-brand" href="#"><img src="<?=base_url('assets/img/logo-ex-4.png');?>" width="50" height="50" alt=""></a>
        </div>
 <button type="button" id="sidebarCollapse" class="btn btn-primary navbar-btn">
            <i class="fas fa-bars"></i>
        </button> 

     <ul class="navbar-nav mr-auto">
            <li>
            <form class="form-inline">
                <input class="form-control form-control-sm" type="search" placeholder="Search" aria-label="Search">
            <button class="btn btn-outline-success btn-sm" type="submit"><i class="fas fa-search"></i></button>
        </form>
            </li>
        </ul> 


         <ul class="nav navbar-nav navbar-right">

            <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
            <span class="caret"></span>
                <i class="far fa-user-circle fa-2x"></i>
            </a>
            <ul class="dropdown-menu">
                <li><a href="#">Login</a></li>

                <li role="separator" class="divider"></li>
                <li><a href="#">Separated link</a></li>
            </ul>
            </li>
        </ul>
    
    </div>
</nav> --> 

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="navbar-collapse collapse w-100 order-1 order-md-0 dual-collapse2">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="#"><img src="<?=base_url('assets/img/logo-ex-4.png');?>" width="30" height="30" alt=""></a>
            </li>
        </ul>
    </div>

    <div class="mx-auto order-0">
        <a class="navbar-brand mx-auto" href="#"> </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target=".dual-collapse2"></button>
    </div>

    <div class="navbar-collapse collapse w-100 order-2 dual-collapse2">
        <ul class="navbar-nav ml-auto">
           
            <li class="nav-item">
            <div class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="far fa-user-circle fa-2x"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                    <span class="dropdown-header"><?=$topNav['username']?></span>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#">Profile</a>
                    <a class="dropdown-item" href="#">Logout</a>
                </div>
            </div>
            </li>
        </ul>
    </div>

  </div>
</nav>