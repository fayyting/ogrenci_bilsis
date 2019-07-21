<?php function echo_mainpage(MainpageController $controller) { ?>

<div class="container-fluid text-center content">    
  <div class="row content">
    <div class="col-sm-3"></div>
    <div class="col-sm-6">
        <!-- <?php if(get_current_core_user()->isRoot()){ ?>
        <li><a href="<?php echo BASE_URL."/table"; ?>"><?php echo _t(1); ?></a></li>
        <?php } ?>
        <?php if(get_current_core_user()->isRoot()){ ?>
        <li><a href="<?php echo BASE_URL."/manage"; ?>"><span class="glyphicon glyphicon-cog"></span> <?php echo _t(2); ?></a></li>
        <?php } ?> -->
        
        <?php if(get_current_core_user()->isRoot()){ ?>
            <div class="col-sm-4">
                <div class="card">
                    <a href="<?php echo BASE_URL."/table"; ?>">
                        <div class="card-container">
                            <span class="glyphicon glyphicon-th-list"></span><?php echo _t(1); ?>
                        </div>
                    </a>   
                </div>            
            </div>
            <div class="col-sm-4">
                <div class="card">
                    <a href="<?php echo BASE_URL."/manage"; ?>">
                        <div class="card-container">
                            <span class="glyphicon glyphicon-cog"></span><?php echo _t(2); ?>
                        </div>
                    </a>   
                </div>            
            </div>
            <br clear="all"/>
            <hr/>
        <?php } ?>
        <div class="col-sm-4">
            <div class="card">
                <a href="<?php echo BASE_URL."/properties"; ?>">
                    <div class="card-container">
                        <span class="glyphicon glyphicon-home"></span><?php echo _t(127); ?>
                    </div>
                </a>   
            </div>            
        </div>
        <div class="col-sm-4">
            <div class="card">
                <a href="#">
                    <div class="card-container">
                        <span class="glyphicon glyphicon-tasks"></span><?php echo _t(128); ?>
                    </div>
                </a>   
            </div>            
        </div>
        <div class="col-sm-4">
            <div class="card">
                <a href="#">
                    <div class="card-container">
                        <span class="glyphicon glyphicon-tree-conifer"></span><?php echo _t(129); ?>
                    </div>
                </a>   
            </div>            
        </div>
        <div class="col-sm-4">
            <div class="card">
                <a href="#">
                    <div class="card-container">
                        <span class="glyphicon glyphicon-envelope"></span><?php echo _t(123); ?>
                    </div>
                </a>   
            </div>            
        </div>
        <div class="col-sm-4">
            <div class="card">
                <a href="#">
                    <div class="card-container">
                        <span class="glyphicon glyphicon-envelope"></span><?php echo _t(130); ?>
                    </div>
                </a>   
            </div>            
        </div>
        <div class="col-sm-4">
            <div class="card">
                <a href="#">
                    <div class="card-container">
                        <span class="glyphicon glyphicon-phone-alt"></span><?php echo _t(131); ?>
                    </div>
                </a>   
            </div>            
        </div>
    </div>
  </div>  
</div>

<?php }