<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Route::group(['prefix' => 'paypal'], function() {
	Route::post('direct', [
	    'as'   => 'paypal.direct', 
	    'uses' => 'PaypalController@direct'
	]);
	Route::post('pay', [
	    'as'   => 'paypal.pay', 
	    'uses' => 'PaypalController@pay'
	]);
	Route::get('status', [
	    'as'   => 'paypal.status', 
	    'uses' => 'PaypalController@status'
	]);
	Route::get('checkout', [
	    'as'   => 'paypal.checkout', 
	    'uses' => 'PaypalController@checkout'
	]);

});

Route::any('/', [
    'as'   => 'frontend.home', 
    'uses' => 'FrontendController@home'
]);

Route::post('contact', [
    'as'   => 'frontend.contact', 
    'uses' => 'FrontendController@contact'
]);

Route::any('products/{category?}/{product?}', [
    'as'   => 'frontend.products', 
    'uses' => 'FrontendController@products'
]);

Route::any('product/{name?}', [
    'as'   => 'frontend.product', 
    'uses' => 'FrontendController@product'
]);

Route::any('product/{name?}', [
    'as'   => 'frontend.product', 
    'uses' => 'FrontendController@product'
]);

Route::post('subscribers/add', [
    'as'   => 'admin.subscribers.add', 
    'uses' => 'SubscriberController@add'
]);



Route::group(['prefix' => 'checkout'], function() {
	Route::any('/', [
	    'as'   => 'frontend.checkout', 
	    'uses' => 'FrontendController@checkout'
	]);
	Route::any('paypal', [
	    'as'   => 'frontend.checkout.paypal', 
	    'uses' => 'FrontendController@checkout_paypal'
	]);
	Route::any('update', [
	    'as'   => 'frontend.checkout.update', 
	    'uses' => 'FrontendController@checkout_update'
	]);
	Route::any('completed', [
	    'as'   => 'frontend.checkout.completed', 
	    'uses' => 'FrontendController@checkout_completed'
	]);
	Route::any('mini-cart', [
	    'as'   => 'frontend.checkout.mini-cart', 
	    'uses' => 'FrontendController@checkout_minicart'
	]);
});

Route::any('get-quote', [
    'as'   => 'frontend.get-quote', 
    'uses' => 'ProductController@get_quote'
]);


Route::any('results', [
    'as'   => 'frontend.results', 
    'uses' => 'FrontendController@results'
]);


Route::group(['prefix' => 'account'], function() {
	Route::any('register', [
	    'as'   => 'frontend.account.register', 
	    'uses' => 'FrontendCustomerController@register'
	]);
	Route::any('login', [
	    'as'   => 'frontend.account.login', 
	    'uses' => 'FrontendCustomerController@login'
	]);
	Route::any('logout', [
	    'as'   => 'frontend.account.logout', 
	    'uses' => 'FrontendCustomerController@logout'
	]);
	Route::any('forgot-password/{token?}', [
	    'as'   => 'frontend.account.forgot-password', 
	    'uses' => 'FrontendCustomerController@forgot_password'
	]);
	Route::any('confirm/{token?}', [
	    'as'   => 'frontend.account.confirm', 
	    'uses' => 'FrontendCustomerController@confirm'
	]);
	Route::any('profile', [
	    'as'   => 'frontend.account.profile', 
	    'uses' => 'FrontendCustomerController@profile'
	]);
	Route::any('designs', [
	    'as'   => 'frontend.account.designs', 
	    'uses' => 'FrontendCustomerController@designs'
	]);
	Route::any('designs/{id?}', [
	    'as'   => 'frontend.account.view-design', 
	    'uses' => 'FrontendCustomerController@view_design'
	]);
	Route::any('change-password', [
	    'as'   => 'frontend.account.change-password', 
	    'uses' => 'FrontendCustomerController@change_password'
	]);
});

Route::group(['prefix' => 'auth'], function() {

	Route::any('login', [
	    'as'   => 'auth.login', 
	    'uses' => 'AuthController@login'
	]);

	Route::any('logout', [
	    'as'   => 'auth.logout', 
	    'uses' => 'AuthController@logout'
	]);

	Route::any('forgot-password/{token?}', [
	    'as'   => 'auth.forgot-password', 
	    'uses' => 'AuthController@forgotPassword'
	]);

});


Route::group(['prefix' => 'designer'], function() {
	Route::any('online', [
	    'as'   => 'frontend.designer.index', 
	    'uses' => 'DesignerController@designer'
	]);
	Route::any('settings', [
	    'as'   => 'frontend.designer.settings', 
	    'uses' => 'DesignerController@settings'
	]);
	Route::any('publish', [
	    'as'   => 'frontend.designer.publish', 
	    'uses' => 'DesignerController@publish'
	]);
	Route::any('fonts', [
	    'as'   => 'frontend.designer.fonts', 
	    'uses' => 'DesignerController@fonts'
	]);
	Route::any('products/{id?}', [
	    'as'   => 'frontend.designer.products', 
	    'uses' => 'DesignerController@products'
	]);
	Route::any('product/{id?}', [
	    'as'   => 'frontend.designer.product', 
	    'uses' => 'DesignerController@product'
	]);
	Route::any('cliparts/{category?}', [
	    'as'   => 'frontend.designer.cliparts', 
	    'uses' => 'DesignerController@cliparts'
	]);
	Route::any('clipart/{id?}', [
	    'as'   => 'frontend.designer.clipart', 
	    'uses' => 'DesignerController@clipart'
	]);
	Route::any('attributes/{id?}', [
	    'as'   => 'frontend.designer.attributes', 
	    'uses' => 'DesignerController@attributes'
	]);
	Route::any('upload', [
	    'as'   => 'frontend.designer.upload', 
	    'uses' => 'DesignerController@upload'
	]);
	Route::any('repaint', [
	    'as'   => 'frontend.designer.repaint', 
	    'uses' => 'DesignerController@repaint'
	]);
	Route::any('repaint/clear', [
	    'as'   => 'frontend.designer.clear-repaint', 
	    'uses' => 'DesignerController@clear_repaint'
	]);
});




Route::group(['prefix' => 'admin', 'middleware' => ['auth']], function() {

	Route::any('dashboard', [
	    'as'   => 'admin.general.dashboard', 
	    'uses' => 'GeneralController@dashboard'
	]);

	Route::group(['prefix' => 'subscribers'], function() {
		Route::any('/', [
		    'as'   => 'admin.subscribers.index', 
		    'uses' => 'SubscriberController@index'
		]);
		Route::any('delete/{id?}', [
		    'as'   => 'admin.subscribers.delete', 
		    'uses' => 'SubscriberController@delete'
		]);
		Route::any('restore/{id?}', [
		    'as'   => 'admin.subscribers.restore', 
		    'uses' => 'SubscriberController@restore'
		]);
		Route::any('destroy/{id?}', [
		    'as'   => 'admin.subscribers.destroy', 
		    'uses' => 'SubscriberController@destroy'
		]);
		Route::any('export', [
		    'as'   => 'admin.subscribers.export', 
		    'uses' => 'SubscriberController@export'
		]);
	});


	/* BEGIN PRODUCTS */
	Route::group(['prefix' => 'products'], function() {
		Route::any('/', [
		    'as'   => 'admin.products.index', 
		    'uses' => 'ProductController@index'
		]);
		Route::any('add', [
		    'as'   => 'admin.products.add', 
		    'uses' => 'ProductController@add'
		]);
		Route::any('edit/{id?}', [
		    'as'   => 'admin.products.edit', 
		    'uses' => 'ProductController@edit'
		]);
		Route::any('delete/{id?}', [
		    'as'   => 'admin.products.delete', 
		    'uses' => 'ProductController@delete'
		]);
		Route::any('restore/{id?}', [
		    'as'   => 'admin.products.restore', 
		    'uses' => 'ProductController@restore'
		]);
		Route::any('destroy/{id?}', [
		    'as'   => 'admin.products.destroy', 
		    'uses' => 'ProductController@destroy'
		]);
	});
	/* END PRODUCTS */

	/* BEGIN CATEGORIES */
	Route::group(['prefix' => 'categories'], function() {
		Route::any('/', [
		    'as'   => 'admin.categories.index', 
		    'uses' => 'CategoryController@index'
		]);
		Route::any('add', [
		    'as'   => 'admin.categories.add', 
		    'uses' => 'CategoryController@add'
		]);
		Route::any('edit/{id?}', [
		    'as'   => 'admin.categories.edit', 
		    'uses' => 'CategoryController@edit'
		]);
		Route::any('delete/{id?}', [
		    'as'   => 'admin.categories.delete', 
		    'uses' => 'CategoryController@delete'
		]);
		Route::any('restore/{id?}', [
		    'as'   => 'admin.categories.restore', 
		    'uses' => 'CategoryController@restore'
		]);
		Route::any('destroy/{id?}', [
		    'as'   => 'admin.categories.destroy', 
		    'uses' => 'CategoryController@destroy'
		]);
	});
	/* END CATEGORIES */

	/* BEGIN MEDIA */
	Route::group(['prefix' => 'media'], function() {
		Route::any('/', [
		    'as'   => 'admin.media.index', 
		    'uses' => 'MediaController@index'
		]);
		Route::any('add', [
		    'as'   => 'admin.media.add', 
		    'uses' => 'MediaController@add'
		]);
		Route::any('upload', [
		    'as'   => 'admin.media.upload', 
		    'uses' => 'MediaController@upload'
		]);
		Route::any('delete', [
		    'as'   => 'admin.media.delete', 
		    'uses' => 'MediaController@delete'
		]);
		Route::post('unlink', [
		    'as'   => 'admin.media.unlink', 
		    'uses' => 'MediaController@unlink'
		]);
		Route::any('mkdir', [
		    'as'   => 'admin.media.mkdir', 
		    'uses' => 'MediaController@mkdir'
		]);
	});
	/* END MEDIA */

	/* BEGIN CLIPARTS */
	Route::group(['prefix' => 'cliparts'], function() {
		Route::any('/', [
		    'as'   => 'admin.cliparts.index', 
		    'uses' => 'ClipartController@index'
		]);
		Route::any('add', [
		    'as'   => 'admin.cliparts.add', 
		    'uses' => 'ClipartController@add'
		]);
		Route::any('edit/{id?}', [
		    'as'   => 'admin.cliparts.edit', 
		    'uses' => 'ClipartController@edit'
		]);
		Route::any('delete/{id?}', [
		    'as'   => 'admin.cliparts.delete', 
		    'uses' => 'ClipartController@delete'
		]);
		Route::any('restore/{id?}', [
		    'as'   => 'admin.cliparts.restore', 
		    'uses' => 'ClipartController@restore'
		]);
		Route::any('destroy/{id?}', [
		    'as'   => 'admin.cliparts.destroy', 
		    'uses' => 'ClipartController@destroy'
		]);
	});
	/* END CLIPARTS */

	/* BEGIN ORDERS */
	Route::group(['prefix' => 'orders'], function() {
		Route::any('/', [
		    'as'   => 'admin.orders.index', 
		    'uses' => 'OrderController@index'
		]);
		Route::any('add-custom', [
		    'as'   => 'admin.orders.add-custom', 
		    'uses' => 'OrderController@add_custom'
		]);
		Route::any('remove-order', [
		    'as'   => 'admin.orders.remove-order', 
		    'uses' => 'OrderController@remove_order'
		]);
		Route::any('update-product', [
		    'as'   => 'admin.orders.update-product', 
		    'uses' => 'OrderController@update_product'
		]);
		Route::any('update-order', [
		    'as'   => 'admin.orders.update-order', 
		    'uses' => 'OrderController@update_order'
		]);
		Route::any('add', [
		    'as'   => 'admin.orders.add', 
		    'uses' => 'OrderController@add'
		]);
		Route::any('view/{id?}', [
		    'as'   => 'admin.orders.edit', 
		    'uses' => 'OrderController@edit'
		]);
		Route::any('delete/{id?}', [
		    'as'   => 'admin.orders.delete', 
		    'uses' => 'OrderController@delete'
		]);
		Route::any('restore/{id?}', [
		    'as'   => 'admin.orders.restore', 
		    'uses' => 'OrderController@restore'
		]);
		Route::any('destroy/{id?}', [
		    'as'   => 'admin.orders.destroy', 
		    'uses' => 'OrderController@destroy'
		]);
	});
	/* END ORDERS */

	/* BEGIN COUPONS */
	Route::group(['prefix' => 'coupons'], function() {
		Route::any('/', [
		    'as'   => 'admin.coupons.index', 
		    'uses' => 'CouponController@index'
		]);
		Route::any('add', [
		    'as'   => 'admin.coupons.add', 
		    'uses' => 'CouponController@add'
		]);
		Route::any('edit/{id?}', [
		    'as'   => 'admin.coupons.edit', 
		    'uses' => 'CouponController@edit'
		]);
		Route::any('delete/{id?}', [
		    'as'   => 'admin.coupons.delete', 
		    'uses' => 'CouponController@delete'
		]);
		Route::any('restore/{id?}', [
		    'as'   => 'admin.coupons.restore', 
		    'uses' => 'CouponController@restore'
		]);
		Route::any('destroy/{id?}', [
		    'as'   => 'admin.coupons.destroy', 
		    'uses' => 'CouponController@destroy'
		]);
	});
	/* END COUPONS */

	/* BEGIN USERS */
	Route::group(['prefix' => 'users'], function() {
		Route::any('/', [
		    'as'   => 'admin.users.index', 
		    'uses' => 'UserController@index'
		]);
		Route::any('add', [
		    'as'   => 'admin.users.add', 
		    'uses' => 'UserController@add'
		]);
		Route::any('edit/{id?}', [
		    'as'   => 'admin.users.edit', 
		    'uses' => 'UserController@edit'
		]);
		Route::any('delete/{id?}', [
		    'as'   => 'admin.users.delete', 
		    'uses' => 'UserController@delete'
		]);
		Route::any('restore/{id?}', [
		    'as'   => 'admin.users.restore', 
		    'uses' => 'UserController@restore'
		]);
		Route::any('destroy/{id?}', [
		    'as'   => 'admin.users.destroy', 
		    'uses' => 'UserController@destroy'
		]);
		Route::any('profile', [
		    'as'   => 'admin.users.profile', 
		    'uses' => 'UserController@profile'
		]);
		Route::any('login/{id?}', [
		    'as'   => 'admin.users.login', 
		    'uses' => 'UserController@login'
		]);
	});
	/* END USERS */

	/* BEGIN GROUPS */
	Route::group(['prefix' => 'groups'], function() {
		Route::any('/', [
		    'as'   => 'admin.groups.index', 
		    'uses' => 'GroupController@index'
		]);
		Route::any('add', [
		    'as'   => 'admin.groups.add', 
		    'uses' => 'GroupController@add'
		]);
		Route::any('edit/{id?}', [
		    'as'   => 'admin.groups.edit', 
		    'uses' => 'GroupController@edit'
		]);
		Route::any('delete/{id?}', [
		    'as'   => 'admin.groups.delete', 
		    'uses' => 'GroupController@delete'
		]);
		Route::any('restore/{id?}', [
		    'as'   => 'admin.groups.restore', 
		    'uses' => 'GroupController@restore'
		]);
		Route::any('destroy/{id?}', [
		    'as'   => 'admin.groups.destroy', 
		    'uses' => 'GroupController@destroy'
		]);
	});
	/* END GROUPS */

	/* BEGIN CUSTOMERS */
	Route::group(['prefix' => 'customers'], function() {
		Route::any('/', [
		    'as'   => 'admin.customers.index', 
		    'uses' => 'CustomerController@index'
		]);
		Route::any('view/{id?}', [
		    'as'   => 'admin.customers.view', 
		    'uses' => 'CustomerController@view'
		]);
		Route::any('delete/{id?}', [
		    'as'   => 'admin.customers.delete', 
		    'uses' => 'CustomerController@delete'
		]);
		Route::any('restore/{id?}', [
		    'as'   => 'admin.customers.restore', 
		    'uses' => 'CustomerController@restore'
		]);
		Route::any('destroy/{id?}', [
		    'as'   => 'admin.customers.destroy', 
		    'uses' => 'CustomerController@destroy'
		]);

		/* BEGIN CUSTOMER DESIGNS */
		Route::group(['prefix' => 'designs'], function() {
			Route::any('/', [
			    'as'   => 'admin.customers.designs.index', 
			    'uses' => 'CustomerDesignController@index'
			]);
			Route::any('view/{id?}', [
			    'as'   => 'admin.customers.designs.view', 
			    'uses' => 'CustomerDesignController@view'
			]);
			Route::any('delete/{id?}', [
			    'as'   => 'admin.customers.designs.delete', 
			    'uses' => 'CustomerDesignController@delete'
			]);
			Route::any('restore/{id?}', [
			    'as'   => 'admin.customers.designs.restore', 
			    'uses' => 'CustomerDesignController@restore'
			]);
			Route::any('destroy/{id?}', [
			    'as'   => 'admin.customers.designs.destroy', 
			    'uses' => 'CustomerDesignController@destroy'
			]);
		});
		/* END CUSTOMER DESIGNS */

	});
	/* END CUSTOMERS */

	/* BEGIN POSTS */
	Route::group(['prefix' => 'posts'], function() {
		Route::any('/', [
		    'as'   => 'admin.posts.index', 
		    'uses' => 'PostController@index'
		]);
		Route::any('add', [
		    'as'   => 'admin.posts.add', 
		    'uses' => 'PostController@add'
		]);
		Route::any('edit/{id?}', [
		    'as'   => 'admin.posts.edit', 
		    'uses' => 'PostController@edit'
		]);
		Route::any('delete/{id?}', [
		    'as'   => 'admin.posts.delete', 
		    'uses' => 'PostController@delete'
		]);
		Route::any('restore/{id?}', [
		    'as'   => 'admin.posts.restore', 
		    'uses' => 'PostController@restore'
		]);
		Route::any('destroy/{id?}', [
		    'as'   => 'admin.posts.destroy', 
		    'uses' => 'PostController@destroy'
		]);
	});
	/* END POSTS */


	/* BEGIN SETTINGS */
	Route::group(['prefix' => 'settings'], function() {
		Route::any('general', [
		    'as'   => 'admin.settings.general', 
		    'uses' => 'SettingController@general'
		]);
		Route::any('price-table', [
		    'as'   => 'admin.settings.price-table', 
		    'uses' => 'SettingController@price_table'
		]);		
		Route::any('designer', [
		    'as'   => 'admin.settings.designer', 
		    'uses' => 'SettingController@designer'
		]);
		Route::any('shop', [
		    'as'   => 'admin.settings.shop', 
		    'uses' => 'SettingController@shop'
		]);
		Route::any('payments', [
		    'as'   => 'admin.settings.payments', 
		    'uses' => 'SettingController@payments'
		]);
		Route::any('emails', [
		    'as'   => 'admin.settings.emails', 
		    'uses' => 'SettingController@emails'
		]);
	});
	/* END SETTINGS */

	/* BEGIN SHIPPING METHODS */
	Route::group(['prefix' => 'shipping-methods'], function() {
		Route::any('/', [
		    'as'   => 'admin.shipping-methods.index', 
		    'uses' => 'ShippingMethodController@index'
		]);
		Route::any('add', [
		    'as'   => 'admin.shipping-methods.add', 
		    'uses' => 'ShippingMethodController@add'
		]);
		Route::any('edit/{id?}', [
		    'as'   => 'admin.shipping-methods.edit', 
		    'uses' => 'ShippingMethodController@edit'
		]);
		Route::any('delete/{id?}', [
		    'as'   => 'admin.shipping-methods.delete', 
		    'uses' => 'ShippingMethodController@delete'
		]);
		Route::any('restore/{id?}', [
		    'as'   => 'admin.shipping-methods.restore', 
		    'uses' => 'ShippingMethodController@restore'
		]);
		Route::any('destroy/{id?}', [
		    'as'   => 'admin.shipping-methods.destroy', 
		    'uses' => 'ShippingMethodController@destroy'
		]);
	});
	/* END SHIPPING METHODS */

	/* BEGIN COLORS */
	Route::group(['prefix' => 'colors'], function() {
		Route::any('/', [
		    'as'   => 'admin.colors.index', 
		    'uses' => 'ColorController@index'
		]);
		Route::any('add', [
		    'as'   => 'admin.colors.add', 
		    'uses' => 'ColorController@add'
		]);
		Route::any('edit/{id?}', [
		    'as'   => 'admin.colors.edit', 
		    'uses' => 'ColorController@edit'
		]);
		Route::any('delete/{id?}', [
		    'as'   => 'admin.colors.delete', 
		    'uses' => 'ColorController@delete'
		]);
		Route::any('restore/{id?}', [
		    'as'   => 'admin.colors.restore', 
		    'uses' => 'ColorController@restore'
		]);
		Route::any('destroy/{id?}', [
		    'as'   => 'admin.colors.destroy', 
		    'uses' => 'ColorController@destroy'
		]);
	});
	/* END COLORS */
});



/* BEGIN BLOG POSTS */
Route::any('{categories?}/{slug?}', [
    'as'   => 'frontend.post', 
    'uses' => 'PostController@post'
]);
/* END BLOG POSTS */
