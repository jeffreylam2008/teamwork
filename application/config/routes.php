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

// Login
$route['login'] = 'login/index';
$route['login/process'] = "login/dologin";

// dushboard
$route['dushboard'] = 'dushboard/index';

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

// // invocies
$route['invoices'] = 'error_404';
$route['invoices/create'] = 'error_404';
$route['invoices/create/(:any)'] = 'invoices/create/$1';
$route['invoices/create/(:any)/(:any)'] = 'invoices/create/$1/$2';
$route['invoices/process/(:any)'] = 'invoices/process/$1';
$route['invoices/list'] = 'invoices/index';
$route['invoices/edit'] = 'error_404';
$route['invoices/edit/(:any)'] = 'invoices/edit/$1';
$route['invoices/list/edit'] = 'error_404';
$route['invoices/void/confirmed/(:any)'] = 'invoices/savevoid/$1';
$route['invoices/copy/(:any)'] = 'invoices/docopy/$1';

// // quotations
$route['quotations'] = 'error_404';
$route['quotations/create'] = 'error_404';
$route['quotations/create/(:any)/(:any)'] = 'quotations/create/$1/$2';
$route['quotations/list'] = 'quotations/index';
$route['quotations/list/edit'] = 'error_404';
$route['quotations/edit'] = 'error_404';
$route['quotations/edit/(:any)'] = 'quotations/edit/$1';
$route['quotations/void/confirmed/(:any)'] = 'quotations/savevoid/$1';
$route['quotations/copy/(:any)'] = 'quotations/docopy/$1';
$route['quotations/tender/(:any)'] = 'quotations/tender/$1';

// general configure
$route['administration/general'] = 'administration/index';
$route['administration/save'] = 'administration/save';

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
$route['purchases/order/create/(:any)/(:any)'] = 'purchases/create/$1/$2';  
$route['purchases/order/process/(:any)'] = 'purchases/confirm/$1';
$route['purchases/order/save/(:any)'] = 'purchases/save/$1';
$route['purchases/order/discard'] = 'purchases/discard';
$route['purchases/order/saveedit/(:any)'] = 'purchases/saveedit/$1';
$route['purchases/order/edit/(:any)/(:any)'] = 'purchases/edit/$1/$2';
$route['purchases/order/void/(:any)/(:any)'] = 'purchases/void/$1/$2';
$route['purchases/order/confirmed/void/(:any)'] = 'purchases/savevoid/$1';
$route['purchases/order/copy/(:any)'] = 'purchases/docopy/$1';
$route['purchases/order/togrn/(:any)'] = 'purchases/togrn/$1';
$route['purchases/order/settlement/(:any)/(:any)'] = 'purchases/settlement/$1/$2';
$route['purchases/order/settlement/save/(:any)/(:any)'] = 'purchases/savesettlement/$1/$2';

// stocks
$route['stocks'] = 'stocks/index';
// $route['stocks/process'] = 'stocks/grn_confirm';

// stocks -> grn
$route['stocks/grn/edit/(:any)/(:any)'] = 'GoodReceivedNote/edit/$1/$2';
$route['stocks/grn/create/(:any)/(:any)'] = 'GoodReceivedNote/create/$1/$2';
$route['stocks/grn/create/(:any)/(:any)/(:any)'] = 'GoodReceivedNote/create/$1/$2/$3';
$route['stocks/grn/process/(:any)'] = 'GoodReceivedNote/process/$1';
$route['stocks/grn/save/(:any)'] = 'GoodReceivedNote/save/$1';

// stocks -> dn
$route['stocks/dn/edit/(:any)/(:any)'] = 'DeliveryNote/edit/$1/$2';
$route['stocks/dn/create/(:any)/(:any)'] = 'DeliveryNote/create/$1/$2';
$route['stocks/dn/create/(:any)/(:any)/(:any)'] = 'DeliveryNote/create/$1/$2/$3';
$route['stocks/dn/process/(:any)'] = 'DeliveryNote/process/$1';
$route['stocks/dn/save/(:any)'] = 'DeliveryNote/save/$1';

// // stocks -> adjustment
$route['stocks/adj/create/(:any)/(:any)'] = 'Adjustments/create/$1/$2';
$route['stocks/adj/create/(:any)/(:any)/(:any)'] = 'Adjustments/create/$1/$2/$3';
$route['stocks/adj/process/(:any)'] = 'Adjustments/process/$1';
$route['stocks/adj/edit/(:any)/(:any)'] = 'Adjustments/edit/$1/$2';
$route['stocks/adj/save/(:any)'] = 'Adjustments/save/$1';

// stocks -> stocktake
$route['stocks/stocktake/create/(:any)/(:any)'] = 'Stocktake/create/$1/$2';
$route['stocks/stocktake/process/(:any)'] = 'Stocktake/process/$1';
$route['stocks/stocktake/edit/(:any)/(:any)'] = 'Stocktake/edit/$1/$2';
$route['stocks/stocktake/save/(:any)'] = 'Stocktake/save/$1';
$route['stocks/stocktake/saveedit/(:any)'] = 'Stocktake/saveedit/$1';
$route['stocks/stocktake/delete/(:any)/(:any)'] = 'Stocktake/delete/$1/$2';
$route['stocks/stocktake/confirmed/delete/(:any)'] = 'Stocktake/savedelete/$1';
$route['stocks/stocktake/adjust/(:any)/(:any)'] = 'Stocktake/adjust/$1/$2';
$route['stocks/stocktake/confirmed/adjust/(:any)'] = 'Stocktake/saveadjust/$1';
// // stocks -> stocktake
// // $route['stocks/stocktake/create/(:any)'] = 'stocks/stocktake/$1';
// // $route['stocks/stocktake/process'] = 'stocks/stocktake_process';
// // $route['stocks/stocktake/detail/(:any)'] = 'stocks/stocktake_detail/$1';
// // $route['stocks/stocktake/save'] = 'stocks/stocktake_save';
// // $route['stocks/stocktake/discard/(:any)'] = 'stocks/stocktake_discard/$1';
// // $route['stocks/stocktake/discard/confirmed/(:any)'] = 'stocks/stocktake_save_discard/$1';
// // $route['stocks/stocktake/adjust/(:any)'] = 'stocks/stocktake_adjust/$1';
// // $route['stocks/stocktake/adjust/confirmed/(:any)'] = 'stocks/stocktake_save_adjust/$1';

// // Master file load
// $route['master'] = 'master/index';

// // testbed load
 $route['TestBed'] = 'TestBed/index';

// // Reports -> index
// $route['reports'] = 'reports/index';
// $route['reports/(:any)'] = 'reports/reports/$1';

// // Systems
$route['systems/backuprestore'] = 'systems/backuprestore';


// $route['reroute/quotations/create/(:any)'] = 'reroute/quotations/create/$1';