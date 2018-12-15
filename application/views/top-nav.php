<!-- <nav class="navbar navbar-expand-sm navbar-light bg-light justify-content-between"> -->
<!-- <nav class="navbar navbar-default">
    <div class="container-fluid">
        
        <div class="navbar-header">
            <a class="navbar-brand" href="#"><img src="<?=base_url('assets/img/logo-ex-4.png');?>" width="50" height="50" alt=""></a>
        </div>
        <!-- <button type="button" id="sidebarCollapse" class="btn btn-primary navbar-btn">
            <i class="fas fa-bars"></i>
        </button> 
         -->
        <!-- <ul class="navbar-nav mr-auto">
            <li>
            <form class="form-inline">
                <input class="form-control form-control-sm" type="search" placeholder="Search" aria-label="Search">
            <button class="btn btn-outline-success btn-sm" type="submit"><i class="fas fa-search"></i></button>
        </form>
            </li>
        </ul> -->


        <!-- <ul class="nav navbar-nav navbar-right">

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
  <a class="navbar-brand" href="#"><img src="<?=base_url('assets/img/logo-ex-4.png');?>" width="50" height="50" alt=""></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">Link</a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Dropdown
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="#">Action</a>
          <a class="dropdown-item" href="#">Another action</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="#">Something else here</a>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link disabled" href="#">Disabled</a>
      </li>
    </ul>
    <form class="form-inline my-2 my-lg-0">
      <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
      <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
    </form>
  </div>
</nav>