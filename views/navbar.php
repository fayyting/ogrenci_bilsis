<nav class="navbar navbar-default navbar-fixed-top">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#coreNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>                        
      </button>
        <a class="navbar-brand" href="<?php echo BASE_URL; ?>"></a>
    </div>
    <div class="collapse navbar-collapse" id="coreNavbar">
      <ul class="nav navbar-nav navbar-left">
        <li>
              <a href="<?php echo BASE_URL."/user"; ?>"><span class="glyphicon glyphicon-user"></span> <?php $user = get_current_core_user(); echo $user->ID ? "$user->NAME": ""; ?></a>
            <a href="<?php echo BASE_URL."/logout"; ?>" id="logout"><span class="glyphicon glyphicon-log-out"></span> <?php echo _t(4); ?></a>
        </li>
        <li> 
            <a href="<?php echo BASE_URL; ?>"><span class="glyphicon glyphicon-home"></span> Head Office</a>
            <a href="<?php echo BASE_URL; ?>"><span class="glyphicon glyphicon-qrcode"></span> HO</a>
        </li>
        <?php if(get_current_core_user()->isRoot()) { ?>
          <li> 
            <a href="<?php echo BASE_URL."/table"; ?>"><span class="glyphicon glyphicon-list"></span> <?php echo _t(1); ?></a>
            <a href="<?php echo BASE_URL."/manage"; ?>"><span class="glyphicon glyphicon-cog"></span> <?php echo _t(2); ?></a>
          </li>
        <?php } ?>
      </ul>
      <ul class="nav navbar-nav navbar-right">
          <li>
              <div>34 Church Street, Dagenham, RM10 9UR</div>
              <div><span class="glyphicon glyphicon-phone-alt"></span> <a href="tel:020 8517 8005">020 8517 8005</a> / <a href="tel:020 8517 8007">020 8517 8007</a></div>
          </li>
      </ul>
    </div>
  </div>
</nav>

