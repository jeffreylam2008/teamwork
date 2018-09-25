<nav class="navbar navbar-expand-sm navbar-light bg-light">
    <a class="navbar-brand" href="#"><img src="<?=base_url('assets/img/logo-ex-4.png');?>" width="70" height="10" alt=""></a>
    <button type="button" id="sidebarCollapse" class="btn btn-primary navbar-btn">
        <i class="fas fa-bars"></i>

    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Link 2</a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link" href="#">Link3</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Link 4</a>
            </li>
            <li>
            <form class="form-inline">
                <input class="form-control form-control-sm" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-success btn-sm" type="submit"><i class="fas fa-search"></i></button>
            </form>
            </li>
        </ul>
        <a class="nav-link" href="#">Profile</a>
        <?php if($topNav["isLogin"] && isset($topNav["isLogin"])): ?>
        
        <a class="nav-link" href="#">Login</a>
        <?php endif; ?>
    </div>
</nav>