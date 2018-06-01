<?php

function sidebar_menu($type = '') {

  $menu[] = array(
        'title'    => 'Dashboard',
        'icon'     => 'home',
        'class'    => '',
        'url'      => URL::route('admin.general.dashboard'),
        'sub_menu' => array()
    );

    /* BEGIN PRODUCTS */
    $menu[2] = array(
        'title'    => 'Products',
        'icon'     => 'layers',
        'class'    => '',
        'url'      => '',
        'sub_menu' => array(
            array(
                'title'    => 'All Products',
                'icon'     => '',
                'class'    => '',
                'url'      => URL::route('admin.products.index'),
                'sub_menu' => array()
            )
        )
    );
    $menu[2]['sub_menu'][] = array(
        'title'    => 'Add New',
        'icon'     => '',
        'class'    => '',
        'url'      => URL::route('admin.products.add'),
        'sub_menu' => array()
    );
    $menu[2]['sub_menu'][] = array(
        'title'    => 'Categories',
        'icon'     => '',
        'class'    => '',
        'url'      => URL::route('admin.categories.index', ['post_type' => 'product-category']),
        'sub_menu' => array()
    );
    /* END PRODUCTS */

    $menu[3] = array(
        'title'    => 'Media',
        'icon'     => 'folder-alt',
        'class'    => '',
        'url'      => '',
        'sub_menu' => array()
    );
    $menu[3]['sub_menu'][] = array(
        'title'    => 'Library',
        'icon'     => '',
        'class'    => '',
        'url'      => URL::route('admin.media.index'),
        'sub_menu' => array()
    );
    $menu[3]['sub_menu'][] = array(
        'title'    => 'Add New',
        'icon'     => '',
        'class'    => '',
        'url'      => URL::route('admin.media.add'),
        'sub_menu' => array()
    );
    /* END PRODUCTS */

    /* BEGIN CLIPARTS */
    $menu[4] = array(
        'title'    => 'Cliparts',
        'icon'     => 'picture',
        'class'    => '',
        'url'      => '',
        'sub_menu' => array(
            array(
                'title'    => 'All Cliparts',
                'icon'     => '',
                'class'    => '',
                'url'      => URL::route('admin.cliparts.index'),
                'sub_menu' => array()
            )
        )
    );
    $menu[4]['sub_menu'][] = array(
        'title'    => 'Add New',
        'icon'     => '',
        'class'    => '',
        'url'      => URL::route('admin.cliparts.add'),
        'sub_menu' => array()
    );
    $menu[4]['sub_menu'][] = array(
        'title'    => 'Categories',
        'icon'     => '',
        'class'    => '',
        'url'      => URL::route('admin.categories.index', ['post_type' => 'clipart-category']),
        'sub_menu' => array()
    );
    /* END CLIPARTS */

    $menu[5] = array(
        'title'    => 'Orders',
        'icon'     => 'basket-loaded',
        'class'    => '',
        'url'      => URL::route('admin.orders.index'),
        'sub_menu' => array()
    );

    /* BEGIN COUPONS */
    $menu[6] = array(
        'title'    => 'Coupons',
        'icon'     => 'tag',
        'class'    => '',
        'url'      => '',
        'sub_menu' => array(
            array(
                'title'    => 'All Coupons',
                'icon'     => '',
                'class'    => '',
                'url'      => URL::route('admin.coupons.index'),
                'sub_menu' => array()
            )
        )
    );
    $menu[6]['sub_menu'][] = array(
        'title'    => 'Add New',
        'icon'     => '',
        'class'    => '',
        'url'      => URL::route('admin.coupons.add'),
        'sub_menu' => array()
    );
    /* END COUPONS */

    /* BEGIN USERS */    
    $menu[7] = array(
        'title'    => 'Users',
        'icon'     => 'users',
        'class'    => '',
        'url'      => '',
        'sub_menu' => array(
            array(
                'title'    => 'All Users',
                'icon'     => '',
                'class'    => '',
                'url'      => URL::route('admin.users.index'),
                'sub_menu' => array()
            )
        )
    );
    $menu[7]['sub_menu'][] = array(
        'title'    => 'Add New',
        'icon'     => '',
        'class'    => '',
        'url'      => URL::route('admin.users.add'),
        'sub_menu' => array()
    );
    /* END USERS */    

    /* BEGIN GROUPS */  
    $menu[8] = array(
        'title'    => 'Groups',
        'icon'     => 'users',
        'class'    => '',
        'url'      => '',
        'sub_menu' => array(
            array(
                'title'    => 'All Groups',
                'icon'     => '',
                'class'    => '',
                'url'      => URL::route('admin.groups.index'),
                'sub_menu' => array()
            )
        )
    );
    $menu[8]['sub_menu'][] = array(
        'title'    => 'Add New',
        'icon'     => '',
        'class'    => '',
        'url'      => URL::route('admin.groups.add'),
        'sub_menu' => array()
    );
    /* END GROUPS */    

    /* BEGIN CUSTOMERS */  
    $menu[9] = array(
        'title'    => 'Customers',
        'icon'     => 'users',
        'class'    => '',
        'url'      => '',
        'sub_menu' => array(
            array(
                'title'    => 'All Customers',
                'icon'     => '',
                'class'    => '',
                'url'      => URL::route('admin.customers.index'),
                'sub_menu' => array()
            )
        )
    );
    $menu[9]['sub_menu'][] = array(
        'title'    => 'Customer Designs',
        'icon'     => '',
        'class'    => '',
        'url'      => URL::route('admin.customers.designs.index'),
        'sub_menu' => array()
    );
    /* END CUSTOMERS */  

    /* BEGIN POSTS */ 
    $menu[10] = array(
        'title'    => 'Posts',
        'icon'     => 'pin',
        'class'    => '',
        'url'      => '',
        'sub_menu' => array(
            array(
                'title'    => 'All Posts',
                'icon'     => '',
                'class'    => '',
                'url'      => URL::route('admin.posts.index', ['post_type' => 'post']),
                'sub_menu' => array()
            )
        )
    );
    $menu[10]['sub_menu'][] = array(
        'title'    => 'Add New',
        'icon'     => '',
        'class'    => '',
        'url'      => URL::route('admin.posts.add', ['post_type' => 'post']),
        'sub_menu' => array()
    );
    $menu[10]['sub_menu'][] = array(
        'title'    => 'Categories',
        'icon'     => '',
        'class'    => '',
        'url'      => URL::route('admin.categories.index', ['post_type' => 'post-category']),
        'sub_menu' => array()
    );
    $menu[10]['sub_menu'][] = array(
        'title'    => 'Static Pages',
        'icon'     => '',
        'class'    => '',
        'url'      => URL::route('admin.posts.index', ['post_type' => 'page']),
        'sub_menu' => array()
    );
    /* END POSTS */  

    $menu[11] = array(
        'title'    => 'Subscribers',
        'icon'     => 'users',
        'class'    => '',
        'url'      => URL::route('admin.subscribers.index'),
        'sub_menu' => array()
    );

    $menu[12] = array(
        'title'    => 'Settings',
        'icon'     => 'settings',
        'class'    => '',
        'url'      => URL::route('admin.settings.general'),
        'sub_menu' => array()
    );

  return $menu;
}

//----------------------------------------------------------------

function top_nav_menu() {

  $menu = array(
    array(
      'title' => 'View Site',
      'icon' => 'home',
      'class' => '',
      'url'   => URL::route('frontend.home'),
      'sub_menu' => array()
    ),
    array(
      'title' => 'My Profile',
      'icon' => 'user',
      'class' => '',
      'url'   => URL::route('admin.users.profile'),
      'sub_menu' => array()
    )
  );

  return $menu;
}

//----------------------------------------------------------------

function site_header_menu() {

    $menu = array(
        array(
            'title' => 'Home',
            'icon' => '',
            'class' => '',
            'url'   => URL::route('frontend.home'),
            'sub_menu' => array()
        ),
        array(
            'title' => 'About',
            'icon' => '',
            'class' => '',
            'url'   => URL::route('frontend.post', 'about-us'),
            'sub_menu' => array()
        ),
        array(
            'title' => 'Products',
            'icon' => '',
            'class' => '',
            'url'   => URL::route('frontend.products'),
            'sub_menu' => array()
        ),
        array(
            'title' => 'How to order',
            'icon' => '',
            'class' => '',
            'url'   => URL::route('frontend.post', 'how-to-order'),
            'sub_menu' => array()
        ),
        array(
            'title' => 'Our Services',
            'icon' => '',
            'class' => '',
            'url'   => URL::route('frontend.post', 'services'),
            'sub_menu' => array()
        ),
        array(
            'title' => 'Design Online',
            'icon' => '',
            'class' => '',
            'url'   => URL::route('frontend.designer.index'),
            'sub_menu' => array()
        ),
        array(
            'title' => 'Contact Us',
            'icon' => '',
            'class' => '',
            'url'   => URL::route('frontend.post', 'contact-us'),
            'sub_menu' => array()
        ),
    );

  return $menu;
}

//----------------------------------------------------------------