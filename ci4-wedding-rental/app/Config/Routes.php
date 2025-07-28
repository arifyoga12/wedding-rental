<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Home routes
$routes->get('/', 'Home::index');
$routes->get('/home', 'Home::index');
$routes->get('/about', 'Home::about');
$routes->get('/contact', 'Home::contact');
$routes->post('/contact', 'Home::submitContact');

// Authentication routes
$routes->get('/auth', 'Auth::index');
$routes->post('/auth/login', 'Auth::login');
$routes->post('/auth/register', 'Auth::register');
$routes->get('/auth/logout', 'Auth::logout');
$routes->get('/logout', 'Auth::logout');
$routes->get('/profile', 'Auth::profile');
$routes->post('/profile', 'Auth::updateProfile');

// Shop routes
$routes->get('/shop', 'Shop::index');
$routes->get('/shop/(:num)', 'Shop::detail/$1');
$routes->get('/product/(:num)', 'Shop::detail/$1');

// API routes
$routes->get('/api/search', 'Shop::api_search');
$routes->get('/api/categories', 'Shop::categories');

// Vendor routes
$routes->get('/vendors', 'Vendor::index');
$routes->get('/vendor/(:num)', 'Vendor::detail/$1');

// Cart routes (if implementing)
$routes->get('/cart', 'Cart::index');
$routes->post('/cart/add', 'Cart::add');
$routes->post('/cart/update', 'Cart::update');
$routes->post('/cart/remove', 'Cart::remove');
$routes->get('/cart/clear', 'Cart::clear');

// Order routes (if implementing)
$routes->get('/orders', 'Order::index');
$routes->get('/order/(:num)', 'Order::detail/$1');
$routes->post('/order/create', 'Order::create');

// Admin routes (if implementing)
$routes->group('admin', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'Admin::index');
    $routes->get('dashboard', 'Admin::index');
    $routes->get('products', 'Admin::products');
    $routes->get('orders', 'Admin::orders');
    $routes->get('users', 'Admin::users');
    $routes->get('vendors', 'Admin::vendors');
});

// Catch-all route for 404
$routes->set404Override('Errors::show404');
