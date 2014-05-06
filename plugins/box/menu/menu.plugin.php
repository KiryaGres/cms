<?php

    /**
     *  Menu plugin
     *
     *  @package Monstra
     *  @subpackage Plugins
     *  @author Romanenko Sergey / Awilum
     *  @copyright 2012 Romanenko Sergey / Awilum
     *  @version 1.0.2
     *
     */


    // Register plugin
    Plugin::register( __FILE__,                    
                    __('Menu', 'menu'),
                    __('Menu manager', 'menu'),  
                    '1.0.2',
                    'Awilum',                 
                    'http://monstra.org/',
                    null,
                    'box');


    if (Session::exists('user_role') && in_array(Session::get('user_role'), array('admin'))) {
        
        // Include Admin
        Plugin::admin('menu', 'box');
        
    }


    // Add Plugin Javascript
    Javascript::add('plugins/box/menu/js/menu.js', 'backend');


    /**
     * Menu Class
     */
    class Menu {
        

        /**
         * Display menu
         *
         * @param string $category Category name
         */
        public static function get($category = '') {
            
            // Get menu table
            $menu = new Table('menu');

            // Display view
            View::factory('box/menu/views/frontend/index')
                    ->assign('items', $menu->select('[category="'.$category.'"]', 'all', null, array('id', 'name', 'description', 'link', 'target', 'branch', 'order', 'category'), 'order', 'ASC'))
                    ->assign('uri', Uri::segments())
                    ->assign('defpage', Option::get('defaultpage'))                    
                    ->display();
                    
        }


        /**
         * Get menu branch items
         *
         * @param string $branch Branch name
         * @param return menu items array(id,link) or null
         */
        public static function branch($branch) {

            // Get menu table
            $menu = new Table('menu');

            // return all menu (id,link) items with a specified branch as category
            return $menu->select('[category="'.$branch.'"]', 'all', null, array('id', 'link'));

        }

    }
