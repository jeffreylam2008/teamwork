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
$route['products/categories/page/(:any)'] = 'categories/index/page/$1';
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
$route['invoices/print'] = 'theprint/invoices'; 
$route['invoices'] = 'error_404';
$route['invoices/create'] = 'error_404';
$route['invoices/create/(:any)'] = 'invoices/create/$1';
$route['invoices/create/(:any)/(:any)'] = 'invoices/create/$1/$2';
$route['invoices/list'] = 'invoices/invlist/1';
$route['invoices/list/page/(:any)'] = 'invoices/invlist/$1';
$route['invoices/edit/(:any)'] = 'invoices/edit/$1';
$route['invoices/list/edit'] = 'error_404';
$route['invoices/edit'] = 'error_404';

// quotations
$route['quotations'] = 'error_404';
$route['quotations/create'] = 'error_404';
$route['quotations/create/(:any)'] = 'quotations/create/$1';
$route['quotations/list'] = 'quotations/qualist/1';
$route['quotations/list/page/(:any)'] = 'quotations/qualist/$1';
$route['quotations/edit/(:any)'] = 'quotations/edit/$1';
$route['quotations/list/edit'] = 'error_404';
$route['quotations/edit'] = 'error_404';

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
$route['administration/payments/term'] = 'payments/paymentterm';

// customers
$route['customers/customers'] = 'customers/index';
$route['customers/customers/page/(:any)'] = 'customers/index/$1';
$route['customers/customers/save'] = 'customers/save';
$route['customers/customers/edit'] = 'error_404';
$route['customers/customers/edit/(:any)'] = 'customers/edit/$1';
$route['customers/customers/edit/save/(:any)'] = 'customers/saveedit/$1';
$route['customers/customers/detail/(:any)'] = 'customers/detail/$1';
$route['customers/customers/delete/(:any)'] = 'customers/delete/$1';
$route['customers/customers/delete/confirmed/(:any)'] = 'customers/savedel/$1';

// Login
$route['login'] = 'login/index';
$route['login/process'] = "login/dologin";

// Master file load
$route['master'] = 'master/index';


// Master file load
$route['testbed'] = 'testbed/index';


