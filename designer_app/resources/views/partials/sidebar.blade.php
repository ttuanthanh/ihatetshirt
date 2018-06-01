<div class="page-sidebar navbar-collapse collapse">
    <ul class="page-sidebar-menu page-header-fixed" data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200" style="padding-top: 20px">

        <li class="sidebar-toggler-wrapper hide">
            <div class="sidebar-toggler">
                <span></span>
            </div>
        </li>

        <?php foreach(sidebar_menu() as $menu): ?>
        <li class="nav-item {{ $menu['class'] }}">
            <a href="<?php echo $menu['url']; ?>" class="nav-link nav-toggle">
                <i class="icon-<?php echo $menu['icon']; ?>"></i>
                <span class="title"><?php echo $menu['title']; ?></span>
                 <span class="selected"></span>
                 <?php if( $menu['sub_menu'] ): ?>
                 <span class="arrow"></span>
                <?php endif; ?>
            </a>

            <?php if( $menu['sub_menu'] ): ?>
            <ul class="sub-menu">
                <?php foreach($menu['sub_menu'] as $sub_menu): ?>
                <li class="nav-item {{ $sub_menu['class'] }}">
                    <a href="<?php echo $sub_menu['url']; ?>" class="nav-link ">
                        <span class="title"><?php echo $sub_menu['title']; ?></span>
                        <span class="selected"></span>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>
            <?php endif; ?>
            
        </li>
        <?php endforeach; ?>

        
    </ul>
</div>