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
*/

include_once "route_slugs.php";
//routes
$r_admin = $custom_slug_array["admin"];

$route['default_controller'] = 'home_controller';
$route['404_override'] = 'home_controller/error_404';
$route['translate_uri_dashes'] = FALSE;
$route['error-404'] = 'home_controller/error_404';
$route['logout'] = 'auth_controller/logout';
$route['register'] = 'auth_controller/register';
$route['forgot-password'] = 'auth_controller/forgot_password';
$route['reset-password'] = 'auth_controller/reset_password';
$route['confirm'] = 'auth_controller/confirm_email';
$route['profile/(:any)'] = 'profile_controller/profile/$1';
$route['favorites/(:any)'] = 'profile_controller/favorites/$1';
$route['followers/(:any)'] = 'profile_controller/followers/$1';
$route['following/(:any)'] = 'profile_controller/following/$1';
$route['reviews/(:any)'] = 'profile_controller/reviews/$1';
/*settings*/
$route['settings'] = 'profile_controller/update_profile';
$route['settings/update-profile'] = 'profile_controller/update_profile';
$route['settings/contact-informations'] = 'profile_controller/contact_informations';
$route['settings/social-media'] = 'profile_controller/social_media';
$route['settings/preferences'] = 'profile_controller/preferences';
$route['settings/change-password'] = 'profile_controller/change_password';
$route['settings/shipping-address'] = 'profile_controller/shipping_address';

$route['contact'] = 'home_controller/contact';
$route['members'] = 'home_controller/members';
/*product routes*/
$route['sell-now'] = 'product_controller/add_product';
$route['sell-now/images'] = 'product_controller/add_product_images';
$route['update-product/(:num)'] = 'product_controller/update_product/$1';
$route['update-product/images/(:num)'] = 'product_controller/update_product_images/$1';
$route['filter-products'] = 'product_controller/filter_products';
$route['products'] = 'product_controller/products';
$route['pending-products'] = 'profile_controller/pending_products';
$route['hidden-products'] = 'profile_controller/hidden_products';
/*promote product routes*/
$route['promote-product/pricing/(:num)'] = 'promote_controller/pricing/$1';
$route['promote-product/payment-method'] = 'promote_controller/payment_method';
$route['promote-product/payment'] = 'promote_controller/payment';
$route['promote-product/completed'] = 'promote_controller/completed';
/*blog routes*/
$route['blog'] = 'home_controller/blog';
$route['blog/(:any)'] = 'home_controller/category/$1';
$route['blog/tag/(:any)'] = 'home_controller/tag/$1';
$route['blog/(:any)/(:any)'] = 'home_controller/post/$1/$2';

$route['category/(:any)'] = 'product_controller/category/$1';
$route['category/(:any)/(:any)'] = 'product_controller/subcategory/$1/$2';
$route['category/(:any)/(:any)/(:any)'] = 'product_controller/third_category/$1/$2/$3';

$route["messages"] = 'message_controller/messages';
$route["messages/message/(:num)"] = 'message_controller/message/$1';
/*paypal routes*/
$route["execute-paypal-payment"] = 'product_controller/execute_paypal_payment';

$route['cron/update-sitemap'] = 'cron_controller/update_sitemap';
$route["unsubscribe"] = 'home_controller/unsubscribe';
/*rss feeds*/
$route["rss-feeds"] = 'rss_controller/rss_feeds';
$route["rss/latest-products"] = 'rss_controller/latest_products';
$route["rss/promoted-products"] = 'rss_controller/promoted_products';
$route["rss/category/(:any)"] = 'rss_controller/rss_by_category/$1';
$route["rss/seller/(:any)"] = 'rss_controller/rss_by_seller/$1';
/*cart*/
$route["cart"] = 'cart_controller/cart';
$route["cart/shipping"] = 'cart_controller/shipping';
$route["cart/payment-method"] = 'cart_controller/payment_method';
$route["cart/payment"] = 'cart_controller/payment';
$route["add-to-cart"] = 'cart_controller/add_to_cart';
$route["order-completed/(:num)"] = 'cart_controller/order_completed/$1';
/*orders*/
$route["orders"] = 'order_controller/orders';
$route["orders/completed-orders"] = 'order_controller/completed_orders';
$route["order/(:num)"] = 'order_controller/order/$1';
/*sales*/
$route["sales"] = 'order_controller/sales';
$route["sales/completed-sales"] = 'order_controller/completed_sales';
$route["sale/(:num)"] = 'order_controller/sale/$1';
/*earnings*/
$route["earnings"] = 'earnings_controller/earnings';
$route["set-payout-account"] = 'earnings_controller/set_payout_account';
$route["payouts"] = 'earnings_controller/payouts';

$route["login-with-twitter"] = 'auth_controller/login_with_twitter';

/*
 *
 * ADMIN ROUTES
 *
 */

/*navigation routes*/
$route[$r_admin . '/navigation'] = 'admin_controller/navigation';
/*slider routes*/
$route[$r_admin . '/add-slider-item'] = 'admin_controller/add_slider_item';
$route[$r_admin . '/slider-items'] = 'admin_controller/slider_items';
$route[$r_admin . '/update-slider-item/(:num)'] = 'admin_controller/update_slider_item/$1';
/*page routes*/
$route[$r_admin] = 'admin_controller/index';
$route[$r_admin . '/settings'] = 'admin_controller/settings';
$route[$r_admin . '/email-settings'] = 'admin_controller/email_settings';
$route[$r_admin . '/social-login'] = 'admin_controller/social_login_settings';

$route[$r_admin . '/add-page'] = 'page_controller/add_page';
$route[$r_admin . '/update-page'] = 'page_controller/update_page';
$route[$r_admin . '/pages'] = 'page_controller/pages';
$route[$r_admin . '/pages'] = 'page_controller/pages';
/*order routes*/
$route[$r_admin . '/orders'] = 'order_admin_controller/orders';
$route[$r_admin . '/order-details/(:num)'] = 'order_admin_controller/order_details/$1';
$route[$r_admin . '/transactions'] = 'order_admin_controller/transactions';
$route[$r_admin . '/order-bank-transfers'] = 'order_admin_controller/order_bank_transfers';
/*product routes*/
$route[$r_admin . '/products'] = 'product_admin_controller/products';
$route[$r_admin . '/pending-products'] = 'product_admin_controller/pending_products';
$route[$r_admin . '/hidden-products'] = 'product_admin_controller/hidden_products';
$route[$r_admin . '/sold-products'] = 'product_admin_controller/sold_products';
$route[$r_admin . '/deleted-products'] = 'product_admin_controller/deleted_products';
$route[$r_admin . '/product-details/(:num)'] = 'product_admin_controller/product_details/$1';
/*promoted product routes*/
$route[$r_admin . '/promoted-products'] = 'product_admin_controller/promoted_products';
$route[$r_admin . '/promoted-products-transactions'] = 'product_admin_controller/promoted_products_transactions';
$route[$r_admin . '/promoted-products-pricing'] = 'product_admin_controller/promoted_products_pricing';
/*page routes*/
$route[$r_admin . '/pages'] = 'page_controller/pages';
$route[$r_admin . '/update-page/(:num)'] = 'page_controller/update_page/$1';
/*category routes*/
$route[$r_admin . '/add-category'] = 'category_controller/add_category';
$route[$r_admin . '/categories'] = 'category_controller/categories';
$route[$r_admin . '/update-category/(:num)'] = 'category_controller/update_category/$1';
$route[$r_admin . '/update-subcategory/(:num)'] = 'category_controller/update_subcategory/$1';
$route[$r_admin . '/subcategories'] = 'category_controller/subcategories';
$route[$r_admin . '/add-subcategory'] = 'category_controller/add_subcategory';
/*custom fields*/
$route[$r_admin . '/add-custom-field'] = 'category_controller/add_custom_field';
$route[$r_admin . '/custom-fields'] = 'category_controller/custom_fields';
$route[$r_admin . '/update-custom-field/(:num)'] = 'category_controller/update_custom_field/$1';
/*earnings*/
$route[$r_admin . '/earnings'] = 'earnings_admin_controller/earnings';
$route[$r_admin . '/completed-payouts'] = 'earnings_admin_controller/completed_payouts';
$route[$r_admin . '/payout-requests'] = 'earnings_admin_controller/payout_requests';
$route[$r_admin . '/payout-settings'] = 'earnings_admin_controller/payout_settings';
$route[$r_admin . '/add-payout'] = 'earnings_admin_controller/add_payout';
$route[$r_admin . '/seller-balances'] = 'earnings_admin_controller/seller_balances';
$route[$r_admin . '/update-seller-balance/(:num)'] = 'earnings_admin_controller/update_seller_balance/$1';
/*blog routes*/
$route[$r_admin . '/blog-add-post'] = 'blog_controller/add_post';
$route[$r_admin . '/blog-posts'] = 'blog_controller/posts';
$route[$r_admin . '/update-blog-post/(:num)'] = 'blog_controller/update_post/$1';
$route[$r_admin . '/blog-categories'] = 'blog_controller/categories';
$route[$r_admin . '/update-blog-category/(:num)'] = 'blog_controller/update_category/$1';
/*comment routes*/
$route[$r_admin . '/product-comments'] = 'product_admin_controller/comments';
$route[$r_admin . '/blog-comments'] = 'blog_controller/comments';
/*review routes*/
$route[$r_admin . '/product-reviews'] = 'product_admin_controller/product_reviews';
$route[$r_admin . '/user-reviews'] = 'admin_controller/user_reviews';
/*ad spaces routes*/
$route[$r_admin . '/ad-spaces'] = 'admin_controller/ad_spaces';
/*seo tools routes*/
$route[$r_admin . '/seo-tools'] = 'admin_controller/seo_tools';
/*location*/
$route[$r_admin . '/location-settings'] = 'admin_controller/location_settings';
$route[$r_admin . '/countries'] = 'admin_controller/countries';
$route[$r_admin . '/states'] = 'admin_controller/states';
$route[$r_admin . '/add-country'] = 'admin_controller/add_country';
$route[$r_admin . '/update-country/(:num)'] = 'admin_controller/update_country/$1';
$route[$r_admin . '/add-state'] = 'admin_controller/add_state';
$route[$r_admin . '/update-state/(:num)'] = 'admin_controller/update_state/$1';
/*users routes*/
$route[$r_admin . '/members'] = 'admin_controller/members';
$route[$r_admin . '/administrators'] = 'admin_controller/administrators';
$route[$r_admin . '/add-administrator'] = 'admin_controller/add_administrator';

$route[$r_admin . "/cache-system"] = 'admin_controller/cache_system';
$route[$r_admin . '/storage'] = 'admin_controller/storage';
/*languages routes*/
$route[$r_admin . '/languages'] = 'language_controller/languages';
$route[$r_admin . '/update-language/(:num)'] = 'language_controller/update_language/$1';
$route[$r_admin . '/update-phrases/(:num)'] = 'language_controller/update_phrases/$1';
/*payment routes*/
$route[$r_admin . '/payment-settings'] = 'admin_controller/payment_settings';
$route[$r_admin . '/visual-settings'] = 'admin_controller/visual_settings';
$route[$r_admin . '/system-settings'] = 'admin_controller/system_settings';
/*currency*/
$route[$r_admin . '/currency-settings'] = 'admin_controller/currency_settings';
$route[$r_admin . '/update-currency/(:num)'] = 'admin_controller/update_currency/$1';
//newsletter
$route[$r_admin . "/send-email-subscribers"] = 'admin_controller/send_email_subscribers';
$route[$r_admin . "/subscribers"] = 'admin_controller/subscribers';

$route[$r_admin . '/contact_messages'] = 'admin_controller/contact_messages';
$route[$r_admin . '/preferences'] = 'admin_controller/preferences';


/*
*-------------------------------------------------------------------------------------------------
* DYNAMIC ROUTES
*-------------------------------------------------------------------------------------------------
*/
require_once(BASEPATH . 'database/DB.php');
$db =& DB();
$general_settings = $db->get('general_settings')->row();
$languages = $db->get('languages')->result();
foreach ($languages as $language) {
    if ($language->status == 1 && $general_settings->site_lang != $language->id) {
        $key = $language->short_form;

        $route[$key] = "home_controller/index";
        $route[$key . '/error-404'] = 'home_controller/error_404';
        $route[$key . '/logout'] = 'auth_controller/logout';
        $route[$key . '/register'] = 'auth_controller/register';
        $route[$key . '/forgot-password'] = 'auth_controller/forgot_password';
        $route[$key . '/reset-password'] = 'auth_controller/reset_password';
        $route[$key . '/confirm'] = 'auth_controller/confirm_email';
        $route[$key . '/profile/(:any)'] = 'profile_controller/profile/$1';
        $route[$key . '/favorites/(:any)'] = 'profile_controller/favorites/$1';
        $route[$key . '/followers/(:any)'] = 'profile_controller/followers/$1';
        $route[$key . '/following/(:any)'] = 'profile_controller/following/$1';
        $route[$key . '/reviews/(:any)'] = 'profile_controller/reviews/$1';
        /*settings*/
        $route[$key . '/settings'] = 'profile_controller/update_profile';
        $route[$key . '/settings/update-profile'] = 'profile_controller/update_profile';
        $route[$key . '/settings/contact-informations'] = 'profile_controller/contact_informations';
        $route[$key . '/settings/social-media'] = 'profile_controller/social_media';
        $route[$key . '/settings/preferences'] = 'profile_controller/preferences';
        $route[$key . '/settings/change-password'] = 'profile_controller/change_password';

        $route[$key . '/settings/shipping-address'] = 'profile_controller/shipping_address';
        $route[$key . '/contact'] = 'home_controller/contact';
        $route[$key . '/members'] = 'home_controller/members';
        /*product routes*/
        $route[$key . '/sell-now'] = 'product_controller/add_product';
        $route[$key . '/sell-now/images'] = 'product_controller/add_product_images';
        $route[$key . '/update-product/(:num)'] = 'product_controller/update_product/$1';
        $route[$key . '/update-product/images/(:num)'] = 'product_controller/update_product_images/$1';
        $route[$key . '/filter-products'] = 'product_controller/filter_products';
        $route[$key . '/products'] = 'product_controller/products';
        $route[$key . '/pending-products'] = 'profile_controller/pending_products';
        $route[$key . '/hidden-products'] = 'profile_controller/hidden_products';
        /*promote product routes*/
        $route[$key . '/promote-product/pricing/(:num)'] = 'promote_controller/pricing/$1';
        $route[$key . '/promote-product/payment-method'] = 'promote_controller/payment_method';
        $route[$key . '/promote-product/payment'] = 'promote_controller/payment';
        $route[$key . '/promote-product/completed'] = 'promote_controller/completed';
        /*blog routes*/
        $route[$key . '/blog'] = 'home_controller/blog';
        $route[$key . '/blog/(:any)'] = 'home_controller/category/$1';
        $route[$key . '/blog/tag/(:any)'] = 'home_controller/tag/$1';
        $route[$key . '/blog/(:any)/(:any)'] = 'home_controller/post/$1/$2';

        $route[$key . '/category/(:any)'] = 'product_controller/category/$1';
        $route[$key . '/category/(:any)/(:any)'] = 'product_controller/subcategory/$1/$2';
        $route[$key . '/category/(:any)/(:any)/(:any)'] = 'product_controller/third_category/$1/$2/$3';
        $route[$key . "/messages"] = 'message_controller/messages';
        $route[$key . "/messages/message/(:num)"] = 'message_controller/message/$1';
        /*paypal routes*/
        $route[$key . "/execute-paypal-payment"] = 'product_controller/execute_paypal_payment';

        $route[$key . '/cron/update-sitemap'] = 'cron_controller/update_sitemap';
        $route[$key . "/unsubscribe"] = 'home_controller/unsubscribe';
        /*rss feeds*/
        $route[$key . "/rss-feeds"] = 'rss_controller/rss_feeds';
        $route[$key . "/rss/latest-products"] = 'rss_controller/latest_products';
        $route[$key . "/rss/promoted-products"] = 'rss_controller/promoted_products';
        $route[$key . "/rss/category/(:any)"] = 'rss_controller/rss_by_category/$1';
        $route[$key . "/rss/seller/(:any)"] = 'rss_controller/rss_by_seller/$1';
        /*cart*/
        $route[$key . "/cart"] = 'cart_controller/cart';
        $route[$key . "/cart/shipping"] = 'cart_controller/shipping';
        $route[$key . "/cart/payment-method"] = 'cart_controller/payment_method';
        $route[$key . "/cart/payment"] = 'cart_controller/payment';
        $route[$key . "/add-to-cart"] = 'cart_controller/add_to_cart';
        $route[$key . "/order-completed/(:num)"] = 'cart_controller/order_completed/$1';
        /*orders*/
        $route[$key . "/orders"] = 'order_controller/orders';
        $route[$key . "/orders/completed-orders"] = 'order_controller/completed_orders';
        $route[$key . "/order/(:num)"] = 'order_controller/order/$1';
        /*sales*/
        $route[$key . "/sales"] = 'order_controller/sales';
        $route[$key . "/sales/completed-sales"] = 'order_controller/completed_sales';
        $route[$key . "/sale/(:num)"] = 'order_controller/sale/$1';
        /*earnings*/
        $route[$key . "/earnings"] = 'earnings_controller/earnings';
        $route[$key . "/set-payout-account"] = 'earnings_controller/set_payout_account';
        $route[$key . "/payouts"] = 'earnings_controller/payouts';
        $route[$key . '/(:any)'] = 'home_controller/any/$1';
    }
}
$route['(:any)'] = 'home_controller/any/$1';
