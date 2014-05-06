<?php
/**
 *  News plugin
 *
 *  @package Monstra
 *  @subpackage Plugins
 *  @author Yudin Evgeniy / JINN
 *  @copyright 2012-2013 Yudin Evgeniy / JINN
 *  @version 1.0.5
 */

// Register plugin
Plugin::register( __FILE__,                    
                  __('News', 'news'),
                  __('News plugin for Monstra', 'news'),  
                  '1.0.5',
                  'JINN',
                  'http://monstra.promo360.ru/',
                  'news');

// Load News Admin for Editor and Admin
if (Session::exists('user_role') && in_array(Session::get('user_role'), array('admin', 'editor'))) {
    Plugin::admin('news');
}
    
Stylesheet::add('plugins/news/lib/style.css', 'frontend', 11);
Stylesheet::add('plugins/news/lib/admin.css', 'backend', 11);
    
Shortcode::add('news', 'News::shortcode');
    
class News extends Frontend {
        
    public static $news = null; // news table @object
    public static $meta = array(); // meta tags news @array
    public static $template = ''; // news template content @string
        
    public static function main() {
        News::$news = new Table('news');
        News::$meta['title'] = __('News', 'news');
        News::$meta['keywords'] = '';
        News::$meta['description'] = '';
                
        $uri = Uri::segments();
            
        if(empty($uri[1]) or ($uri[1] == 'page')) {
            News::all($uri);    
        } elseif (intval($uri[1]) > 0 and isset($uri[2])) {
            News::current($uri);
        }
    }
        
    /** 
     *  List news
     *
     *  count: numbers
     *  show: [last|hits], default - last
     *  view: [full|li], default - full
     */
    public static function _list($count=3, $show='last', $view='full') {
        News::$news = new Table('news');
            
        $sort = ($show == 'hits') ? 'hits' : 'date';
        $view = ($view == 'li')   ? 'li'   : 'full';
            
        $records_all = News::$news->select('[status="published"]', 'all', null, array('id','slug','name', 'hits', 'date'));
        $records_sort = Arr::subvalSort($records_all, $sort, 'DESC');
            
        if(count($records_sort)>0) {
            $records = array_slice($records_sort, 0, $count); 
                
            return View::factory('news/views/frontend/list-'.$view)
                ->assign('records', $records)
                ->assign('site_url', Site::url())
                ->render();
        }
    }
        
    /**
     *  Best views
     * 
     *  Example:
     *  <ul><?php News::hits(5, 'li');?></ul>
     *  <?php News::hits(5);?>
     */
    public static function hits($count=3, $view='full') {
        echo News::_list($count, 'hits', $view);
    }
        
    /**
     *  Last news
     * 
     *  Example:
     *  <ul><?php News::last(5, 'li');?></ul>
     *  <?php News::last(5);?>
     */
    public static function last($count=3, $view='full') {
        echo News::_list($count, 'last', $view);
    }
        
    /**
     *  Shortcode news
     *
     *  Example UL LI style
     *  {news show="last" count="3" view="li"}
     *  {news show="hits" count="5" view="li"}
     *
     *  Example FULL style
     *  {news show="last" count="3" view="full"}
     *  {news show="hits" count="5" view="full"}
     */
    public static function shortcode($attributes) {
        extract($attributes);
        
        $count = (isset($count)) ? (int)$count : 3;
        $show  = (isset($show))  ? $show       : 'last';
        $view  = (isset($view))  ? $view       : 'full';
            
        return News::_list($count, $show, $view);
    }
        
    /**
     *  get News List
     */
    public static function all($uri) {
        
        $records_all = News::$news->select('[status="published"]', 'all', null, array('id','slug','name', 'hits', 'date'));
            
        $count = count($records_all);
        $limit = Option::get('news_limit');
        $pages = ceil($count/$limit);
            
        $page = (isset($uri[1]) and isset($uri[2]) and $uri[1] == 'page') ? (int)$uri[2] : 1;
            
        if($page < 1 or $page > $pages) {
            News::error404();
                
        } else {
            $start = ($page-1)*$limit;

            $records_sort = Arr::subvalSort($records_all, 'date', 'DESC');
                
            if($count > 0) $records = array_slice($records_sort, $start, $limit);  
            else $records = array();
                
            News::$template = View::factory('news/views/frontend/index')
                ->assign('records', $records)
                ->assign('site_url', Site::url())
                ->assign('current_page', $page)
                ->assign('pages_count', $pages)
                ->render();
        }
    }
        
    /**
     *  get Current news
     */
    public static function current($uri) {
        
        $id = intval($uri[1]);
        $slug = $uri[2];
                    
        $records = News::$news->select('[id='.$id.']', null);
                
        if($records) {
            if($records['slug'] == $slug) {
                
                if(empty($records['title'])) $records['title'] = $records['name'];
                if(empty($records['h1']))    $records['h1']    = $records['name'];
                
                News::$meta['title'] = $records['title'];
                News::$meta['keywords'] = $records['keywords'];
                News::$meta['description'] = $records['description'];
                        
                $records['hits'] = News::hitsplus($records['id'], $records['hits']);
                        
                News::$template = View::factory('news/views/frontend/current')
                    ->assign('row', $records)
                    ->assign('site_url', Site::url())
                    ->render();
            } else
                News::error404();
        } else 
            News::error404();
    }
        
    public static function title() {
        return News::$meta['title'];
    }

    public static function keywords() {
        return News::$meta['keywords'];
    }

    public static function description() {
        return News::$meta['description'];
    }

    public static function content() {
        return News::$template;
    }

    public static function template() {
        return Option::get('news_template');
    }
        
    public static function error404() {
        if (BACKEND == false) {
            News::$template = Text::toHtml(File::getContent(STORAGE . DS . 'pages' . DS . '1.page.txt'));
            News::$meta['title'] = 'error404';
            Response::status(404);
        }
    }
        
    public static function hitsplus($id, $hits) {
        if (Session::exists('hits'.$id) == false) {
            $hits++;
            if(News::$news->updateWhere('[id='.$id.']', array('hits' => $hits))) {
                Session::set('hits'.$id, 1);
            }
        }
            
        return $hits;
    }
        
    public static function getContentShort($id, $short=true, $full_news='') {
        $text = Text::toHtml(File::getContent(STORAGE . DS . 'news' . DS . $id . '.news.txt'));
            
        if($short) {
            $content_array = explode("{cut}", $text);
            $content = $content_array[0];
            if(count($content_array)>1) $content.= '<a href="'.$full_news.'" class="news-more">'.__('Read more', 'news').'</a>';
        } else {
            $content = strtr($text, array('{cut}' => ''));
        }
            
        return Filter::apply('content', $content);
    }
        
    /**
     *  current page
     *  pages all
     *  site_url
     *  limit pages
     */
    public static function paginator($current, $pages, $site_url, $limit_pages=10) {
            
        if ($pages > 1) {
            
            // pages count > limit pages
            if ($pages > $limit_pages) {
                $start = ($current <= 6) ? 1 : $current-3;
                $finish = (($pages-$limit_pages) > $current) ? ($start + $limit_pages - 1) : $pages;
            } else {
                $start = 1;
                $finish = $pages;
            }

            // pages title
            echo '<strong>'.__('Pages:', 'news').'</strong> &nbsp; < ';
                
            // prev
            if($current==1){ echo __('Prev', 'news');} 
            else { echo '<a href="'.$site_url.($current-1).'">'.__('Prev', 'news').'</a> '; } echo '&nbsp; ';
                
            // next
            if($current==$pages){ echo __('Next', 'news'); }
            else { echo '<a href="'.$site_url.($current+1).'">'.__('Next', 'news').'</a> '; } echo ' > ';
    
            // pages list
            echo '<div class="news-page">';
                
                if (($pages > $limit_pages) and ($current > 6)) {
                    echo '<a href="'.$site_url.'1">1</a>';
                    echo '<span>...</span>'; 
                }
                
                for ($i = $start; $i <= $finish; $i++) {
                    $class = ($i == $current) ? ' class="current"' : '';
                    echo '<a href="'.$site_url.$i.'"'.$class.'>'.$i.'</a>'; 
                }
                
                if (($pages > $limit_pages) and ($current < ($pages - $limit_pages))) {
                    echo '<span>...</span>'; 
                    echo '<a href="'.$site_url.$pages.'">'.$pages.'</a>';
                }
            echo '</div>';
        }
    }
}