<?php

    /**
     *  Maps plugin
     *
     *  @package Monstra
     *  @subpackage Plugins
     *  @author Yudin Evgeniy / JINN
     *  @copyright 2012 Yudin Evgeniy / JINN
     *  @version 2.0.3
     *
     */

    // Register plugin
    Plugin::register( __FILE__,                    
                    __('Maps', 'maps'),
                    __('Maps plugin for Monstra', 'maps'),  
                    '2.0.3',
                    'JINN',                 
                    'http://monstra.promo360.ru/');

    if (Session::exists('user_role') && in_array(Session::get('user_role'), array('admin', 'editor'))) {
        Plugin::admin('maps');
    }
    
    Action::add('theme_footer', 'Maps::_header');
    Shortcode::add('maps', 'Maps::show');
 
    Javascript::add('plugins/maps/maps/admin.js', 'backend', 11);
    Javascript::add('plugins/maps/maps/maps.js', 'frontend', 11);
    Stylesheet::add('plugins/maps/maps/maps.css', 'frontend', 11);

    class Maps {
        
        public static function _header() {
            echo '<script src="http://api-maps.yandex.ru/2.0-stable/?load=package.standard&lang=ru-RU"></script>';
        }
        
        public static function show() {
            $maps = new Table('maps');
            $records = $maps->select(null, 'all');
            
            $mapZoom = Option::get('map_zoom');
            $mapZoomc = Option::get('map_zoomc');
            $mapWidth = Option::get('map_width');
            
            $mapWidth = (empty($mapWidth) or $mapWidth == '100%') ? '100%' : $mapWidth.'px';
            
            $coords = array();
            $count = count($records);
            $sum_lat = $sum_lon = 0;
                
            if($count>0) {
            
                $html = '<div id="map" style="width:'.$mapWidth.'; height: '.Option::get('map_height').'px"></div>';
                $html.= '<div id="maps">';
            
                foreach($records as $row) {
                    $html.= '<div id="map-'.$row['id'].'" class="maps-address"><b>'.$row['address'].'</b>'.$row['phones'].'</div>';
                    $coords[] = "[{$row['id']}, {$row['lon']}, {$row['lat']}, '{$row['address']}', '{$row['phones']}']";
                    $sum_lon += $row['lon'];
                    $sum_lat += $row['lat'];
                }
                
                $html.= '</div>';
            
                $center_lat = $sum_lat / $count;
                $center_lon = $sum_lon / $count;
            
                $html.= '<script type="text/javascript">
                    var mapCoords = [ '.implode(', ', $coords).' ];
                    var armapCenter = [ '.$center_lon.', '.$center_lat.' ];
                    var mapZoom = '.$mapZoom.';
                    var mapZoomc = '.$mapZoomc.';
                    var mapCountAddress = '.$count.';
                </script>';
    
                return $html;
            }
        }
    }