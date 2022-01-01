<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
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
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/
$route['default_controller'] = "home";
/**
 * from 16-06-2015 all 404 are first redirected to product urlDecode and will check if url is related 
 * product category or product detail or search page if it is then taken to particular page other redirected to 
 * default "my404" controller page. 
 */
$route['404_override'] = "products/urlDecodeAndRedirect";	//'site/index';//'my404';
$route['err'] = 'home/index';	//'my404';


$route['admin'] = "admin/lgs";
//$route['p/(:any)'] = "home/article";

// $route['products'] = "products/newProducts";
// $route['products/clearance'] = "products/readyToShip";

/**
 * new main category routes added on 23-03-2015
 */
$route['vegetables/(:any)'] = "products/urlDecodeAndRedirect";
$route['fruits/(:any)'] = "products/urlDecodeAndRedirect";
$route['grocery/(:any)'] = "products/urlDecodeAndRedirect";
//$route['women/(:any)'] = "products/urlDecodeAndRedirect";
//$route['men/(:any)'] = "products/urlDecodeAndRedirect";
$route['special-offers(:any)'] = "products/urlDecodeAndRedirect";
$route['veges-fruit(:any)'] = "products/urlDecodeAndRedirect";
$route['fashion(:any)'] = "products/urlDecodeAndRedirect";
$route['jewellery(:any)'] = "products/urlDecodeAndRedirect";
$route['electronics(:any)'] = "products/urlDecodeAndRedirect";
$route['home-decore(:any)'] = "products/urlDecodeAndRedirect";
$route['home-appliances(:any)'] = "products/urlDecodeAndRedirect";
$route['synthetic-diamonds(:any)'] = "products/urlDecodeAndRedirect";
$route['new-arrivals(:any)'] = "products/urlDecodeAndRedirect";

// $route['products'] = "products/newProducts";
$route['search/(:any)'] = "products/search";
$route['search'] = "products/search";
//$route['productstest/s/(:any)'] = "productstest/search";

$route['wishlist'] = "cart/wishlist";

// $route['products/ready-to-ship'] = "products/readyToShip";
// $route['products/ready-to-ship(:any)'] = "products/readyToShip";

$route['about-us'] = "home/aboutUs";
$route['draw'] = "home/dro";
$route['privacy-policy'] = "home/privacyPolicy";
$route['terms-conditions'] = "home/termsConditions";
$route['return-policy'] = "home/returnPolicy";
$route['faqs'] = "home/faqs";
$route['contact-us'] = "home/contactUs";

//$route['products/valentine-gifts'] = "products/valentineGifts";
//$route['products/valentine-gifts(:any)'] = "products/valentineGifts";
$route['activateAccount'] = "login/activateAccount";
$route['unsubscribe'] = "home/unsubscribe";

$route['account/edit-account'] = "account/editAccount";
$route['account/address-books'] = "account/addressBooks";
$route['account/change-password'] = "account/changePassword";
$route['account/order-history'] = "account/orderHistory";
$route['account/order-returns'] = "account/orderReturns";
$route['account/address-books'] = "account/addressBook";
$route['account/edit-address'] = "account/displayAddress";
$route['account/add-address'] = "account/displayAddress";
$route['account/save-address'] = "account/saveAddress";
$route['account/order-tracking'] = "account/orderTracking";
$route['account/invite-friends'] = "account/inviteFriends";
$route['logout'] = "login/logout";
$route['register'] = "login/register";
//$route['p/(:any)'] = "home/article";

/**
 * admin CMS
 */
$route['admin/random'] = 'admin/lgs';
$route['admin/random/logout'] = 'admin/lgs/logout';

//$route['products/(:any)/(:any)/(:any)'] = "products/$1/$2/$3";
//$route['admin/diamond_type'] = "admin/localisation/diamond_type";

/* End of file routes.php */
/* Location: ./application/config/routes.php */