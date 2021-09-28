<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
| Exeplie : 
|
|
|
*/
$route['default_controller'] = 'Dushboard';
$route['404_override'] = 'error_404';
$route['translate_uri_dashes'] = FALSE;

// items
$route['products/items/page/(:any)'] = 'items/index/$1';
$route['products/items/page/(:any)/show/(:any)'] = 'items/index/$1/$2';
$route['products/items/edit/(:any)'] = 'items/edit/$1';
$route['products/items/edit/save/(:any)'] = 'items/saveedit/$1';
$route['products/items/delete/(:any)'] = 'items/delete/$1';
$route['products/items/delete/confirmed/(:any)'] = 'items/savedel/$1';
$route['products/items'] = 'items/index';
$route['items'] = 'error_404';
$route['items/page/(:any)'] = 'error_404';
$route['products/items/new'] = 'items/create';
$route['products/items/save'] = 'items/savecreate';

// categories
$route['products/categories/page/(:any)'] = 'categories/index/$1';
$route['products/categories/page/(:any)/show/(:any)'] = 'categories/index/$1/$2';
$route['products/categories/edit/(:any)'] = 'categories/edit/$1';
$route['products/categories/delete/(:any)'] = 'categories/delete/$1';
$route['products/categories/delete/confirmed/(:any)'] = 'categories/savedel/$1';
$route['products/categories'] = 'categories/index';
$route['categories'] = 'error_404';
$route['categories/page/(:any)'] = 'error_404';
$route['products/categories/new'] = 'categories/create';
$route['products/categories/save'] = 'categories/savecreate';
$route['products/categories/edit/save/(:any)'] = 'categories/saveedit/$1';

// invocies
$route['invoices'] = 'error_404';
$route['invoices/create'] = 'error_404';
$route['invoices/create/(:any)'] = 'invoices/create/$1';
$route['invoices/create/(:any)/(:any)'] = 'invoices/create/$1/$2';
$route['invoices/list'] = 'invoices/invlist/1';
$route['invoices/list/page/(:any)'] = 'invoices/invlist/$1';
$route['invoices/edit'] = 'error_404';
$route['invoices/edit/(:any)'] = 'invoices/edit/$1';
$route['invoices/list/edit'] = 'error_404';
$route['invoices/void/confirmed/(:any)'] = 'invoices/savevoid/$1';
$route['invoices/copy/(:any)'] = 'invoices/docopy/$1';

// quotations
$route['quotations'] = 'error_404';
$route['quotations/create'] = 'error_404';
$route['quotations/create/(:any)'] = 'quotations/create/$1';
$route['quotations/list'] = 'quotations/qualist/1';
$route['quotations/list/page/(:any)'] = 'quotations/qualist/$1';
$route['quotations/edit'] = 'error_404';
$route['quotations/edit/(:any)'] = 'quotations/edit/$1';
$route['quotations/list/edit'] = 'error_404';
$route['quotations/void/confirmed/(:any)'] = 'quotations/savevoid/$1';
$route['quotations/copy/(:any)'] = 'quotations/docopy/$1';

// Shops
$route['administration/shops'] = 'shops/index';
$route['administration/shops/page/(:any)'] = 'shops/index/$1';
$route['administration/shops/edit/(:any)'] = 'shops/edit/$1';
$route['administration/shops/edit/save/(:any)'] = 'shops/saveedit/$1';
$route['administration/shops/edit'] = 'error_404';

// Employees
$route['administration/employees'] = 'employees/index';
$route['administration/employees/page/(:any)'] = 'employees/index/$1';
$route['administration/employees/edit/(:any)'] = 'employees/edit/$1';
$route['administration/employees/save'] = 'employees/save';
$route['administration/employees/edit/save/(:any)'] = 'employees/saveedit/$1';

// Payment Method
$route['administration/payments/method'] = 'payments/paymentmethod';
$route['administration/payments/method/page/(:any)'] = 'payments/paymentmethod/$1';
$route['administration/payments/method/edit/(:any)'] = 'payments/paymentmethodedit/$1';
$route['administration/payments/method/edit/save/(:any)'] = 'payments/paymentmethodsaveedit/$1';
$route['administration/payments/method/save'] = 'payments/paymentmethodsave';

// Payment Term
$route['administration/payments/term'] = 'payments/paymentterm';
$route['administration/payments/term/page/(:any)'] = 'payments/paymentterm/$1';
$route['administration/payments/term/edit/(:any)'] = 'payments/paymenttermedit/$1';
$route['administration/payments/term/edit/save/(:any)'] = 'payments/paymenttermsaveedit/$1';
$route['administration/payments/term/save'] = 'payments/paymenttermsave';

// customers
$route['customers'] = 'customers/index';
$route['customers/save'] = 'customers/save';
$route['customers/edit'] = 'error_404';
$route['customers/edit/(:any)'] = 'customers/edit/$1';
$route['customers/edit/save/(:any)'] = 'customers/saveedit/$1';
$route['customers/detail/(:any)'] = 'customers/detail/$1';
$route['customers/delete/(:any)'] = 'customers/delete/$1';
$route['customers/delete/confirmed/(:any)'] = 'customers/savedel/$1';

// suppliers
$route['suppliers'] = 'suppliers/index';
$route['suppliers/save'] = 'suppliers/save';
$route['suppliers/edit'] = 'error_404';
$route['suppliers/edit/(:any)'] = 'suppliers/edit/$1';
$route['suppliers/edit/save/(:any)'] = 'suppliers/saveedit/$1';
$route['suppliers/detail/(:any)'] = 'suppliers/detail/$1';
$route['suppliers/delete/(:any)'] = 'suppliers/delete/$1';
$route['suppliers/delete/confirmed/(:any)'] = 'suppliers/savedel/$1';

// purchases
$route['purchases/order'] = 'purchases/index';
$route['purchases/order/donew'] = 'purchases/donew';
$route['purchases/order/create/(:any)'] = 'purchases/create/$1';  
$route['purchases/order/process'] = 'purchases/confirm';
$route['purchases/order/save'] = 'purchases/save';
$route['purchases/order/saveedit'] = 'purchases/saveedit';
$route['purchases/order/edit/(:any)'] = 'purchases/edit/$1';
$route['purchases/order/void/(:any)'] = 'purchases/void/$1';
$route['purchases/order/void/confirmed/(:any)'] = 'purchases/savevoid/$1';
$route['purchases/order/togrn/(:any)'] = 'purchases/to_grn/$1';
$route['purchases/order/settlement/(:any)'] = 'purchases/settlement/$1';
$route['purchases/order/settlement/save/(:any)'] = 'purchases/savesettlement/$1';

// stocks
$route['stocks'] = 'stocks/index';
$route['stocks/process'] = 'stocks/grn_confirm';

// stocks -> grn
$route['stocks/grn/donew'] = 'stocks/donewgrn';
$route['stocks/grn/detail/(:any)'] = 'stocks/grn_detail/$1';
$route['stocks/grn/create/(:any)'] = 'stocks/grn/$1';
$route['stocks/grn/create/(:any)/(:any)'] = 'stocks/grn/$1/$2';
$route['stocks/grn/save'] = 'stocks/grn_save';

// stocks -> dn
$route['stocks/dn/detail/(:any)'] = 'DeliveryNote/dn_detail/$1';
$route['stocks/dn/donew'] = 'DeliveryNote/donew';
$route['stocks/dn/create/(:any)'] = 'DeliveryNote/create/$1';
$route['stocks/dn/save'] = 'DeliveryNote/save';

// stocks -> adj
$route['stocks/adj/create/(:any)'] = 'stocks/adjust/$1';
$route['stocks/adj/create/(:any)/(:any)'] = 'stocks/adjust/$1/$2';
$route['stocks/adj/process'] = 'stocks/adj_confirm';
$route['stocks/adj/detail/(:any)'] = 'stocks/adj_detail/$1';
$route['stocks/adj/save'] = 'stocks/adj_save';

// stocks -> stocktake
$route['stocks/stocktake/create/(:any)'] = 'stocks/stocktake/$1';
$route['stocks/stocktake/process'] = 'stocks/stocktake_process';
$route['stocks/stocktake/detail/(:any)'] = 'stocks/stocktake_detail/$1';
$route['stocks/stocktake/save'] = 'stocks/stocktake_save';
$route['stocks/stocktake/discard/(:any)'] = 'stocks/stocktake_discard/$1';
$route['stocks/stocktake/discard/confirmed/(:any)'] = 'stocks/stocktake_save_discard/$1';
$route['stocks/stocktake/adjust/(:any)'] = 'stocks/stocktake_adjust/$1';
$route['stocks/stocktake/adjust/confirmed/(:any)'] = 'stocks/stocktake_save_adjust/$1';

// Login
$route['login'] = 'login/index';
$route['login/process'] = "login/dologin";

// Master file load
$route['master'] = 'master/index';


// Master file load
$route['TestBed'] = 'TestBed/index';

// Reports -> index
$route['reports'] = 'reports/index';
$route['reports/(:any)'] = 'reports/reports/$1';

// Systems
$route['systems/backup'] = 'systems/index';