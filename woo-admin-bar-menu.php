<?php
/**
 * Plugin Name:     Admin Bar Menu for WooCommerce
 * Description:     It adds a menu with some WooCommerce basic links on the WP Admin Bar.
 * Author:          Felipe Santos
 * Author URI:      https://felipels.com
 * Text Domain:     woo-admin-bar-menu
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         Admin_Bar_Menu_for_WooCommerce
 */

/**
 * Check if WooCommerce is activated and if the user has the 'manage_options' capability
 */

function wooabm_activate() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return; // Nothing is going to happen
    } elseif ( ! class_exists( 'woocommerce' ) ) {
        add_action( 'admin_notices', 'wooabm_admin_notice__error' ); // Adds notice letting them know that WooCommerce is required
    } else { 
        add_action( 'admin_bar_menu', 'wooabm_admin_bar_items', 500 ); // Load the WP Admin Bar with the menu
    }
}

add_action ( 'init' , 'wooabm_activate' ); // Load the function on admin

/**
 * Notice letting them know that WooCommerce is not activated
 */

function wooabm_admin_notice__error() {
    $class = 'notice notice-error';
    $message = __( 'WooCommerce is currently not activated, so the plugin "Admin Bar Menu for WooCommerce" will not work.', 'woo-admin-bar-menu' );
 
    printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) ); 
}

/**
 * WooCommmerce Menu items
 */

function wooabm_admin_bar_items ( WP_Admin_Bar $admin_bar ) {

    $items = [
        'settings' => [
            'main' => [
                'name' => 'Settings',
                'link' => 'admin.php?page=wc-settings',
            ],
            'general' => [
                'name' => 'General',
                'link' => 'admin.php?page=wc-settings',
            ],
            'products' => [
                'name' => 'Products',
                'link' => 'admin.php?page=wc-settings&tab=products',
            ],
            'tax' => [
                'name' => 'Tax',
                'link' => 'admin.php?page=wc-settings&tab=tax',
            ],
            'shipping' => [
                'name' => 'Shipping',
                'link' => 'admin.php?page=wc-settings&tab=shipping',
            ],
            'payments' => [
                'name' => 'Payments',
                'link' => 'admin.php?page=wc-settings&tab=checkout',
            ],
            'accounts' => [
                'name' => 'Accounts & Privacy',
                'link' => 'admin.php?page=wc-settings&tab=account',
            ],
            'emails' => [
                'name' => 'Emails',
                'link' => 'admin.php?page=wc-settings&tab=email',
            ],
            'integration' => [
                'name' => 'Integration',
                'link' => 'admin.php?page=wc-settings&tab=integration',
            ],
            'advanced' => [
                'name' => 'Advanced',
                'link' => 'admin.php?page=wc-settings&tab=advanced',
            ],
        ],
        'orders' => [
            'main' => [
                'name' => 'Orders',
                'link' => 'edit.php?post_type=shop_order',
            ],
        ],
        'products' => [
            'main' => [
                'name' => 'Products',
                'link' => 'edit.php?post_type=product',
            ],
        ],
        'customers' => [
            'main' => [
                'name' => 'Customers',
                'link' => 'admin.php?page=wc-admin&path=%2Fcustomers',
            ],
        ],
        'coupons' => [
            'main' => [
                'name' => 'Coupons',
                'link' => 'edit.php?post_type=shop_coupon',
            ],
        ],
        'reports' => [
            'main' => [
                'name' => 'Reports',
                'link' => 'admin.php?page=wc-reports',
            ],
        ],
        'status' => [
            'main' => [
                'name' => 'Status',
                'link' => 'admin.php?page=wc-status',
            ],
        ],
    ];

    $admin_bar->add_menu( array(
        'id'    => 'wooabm',
        'parent' => null,
        'group'  => null,
        'title' => __( 'WooCommerce', 'woocommerce' ), //you can use img tag with image link. it will show the image icon Instead of the title.
        'href'  => admin_url('admin.php?page=wc-admin'),
        'meta' => [
            'title' => __( 'WooCommerce', 'woocommerce' ), //This title will show on hover
        ]
    ) );
    

    foreach( $items as $section => $item ) {

        $admin_bar->add_menu( array(
            'id'    => 'wooabm_' . $section,
            'parent' => 'wooabm',
            'group'  => null,
            'title' => __( $item['main']['name'], 'woocommerce' ),
            'href'  => admin_url( $item['main']['link'] ),
            'meta' => [
                'title' => __( $item['main']['name'], 'woocommerce' ), //This title will show on hover
            ]
        ) );

        for($i = 1; $i < count($item); $i++ ) {
            
            $menu_key = array_keys( $items[$section] )[$i];
            $menu_name = array_values( $item )[$i]['name'];
            $menu_link = array_values( $item )[$i]['link'];

            $admin_bar->add_menu( array(
                'id'    => 'wooabm_' . $section . '_' . $menu_key,
                'parent' => 'wooabm_' . $section,
                'group'  => null,
                'title' => __( $menu_name , 'woocommerce' ),
                'href'  => admin_url( $menu_link ),
                'meta' => [
                    'title' => __( $menu_name , 'woocommerce' ), //This title will show on hover
                ]
            ) );

        }
    }
}