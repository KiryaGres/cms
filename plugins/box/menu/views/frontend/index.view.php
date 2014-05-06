<?php
    
    $anchor_active = '';
    $li_active = '';
    $target = '';

    if (count($items) > 0) {
        foreach ($items as $item) {

            $item['link'] = Html::toText($item['link']);
            $item['name'] = Html::toText($item['name']);
            $item['branch'] = Html::toText($item['branch']);

            $pos = strpos($item['link'], 'http://');
            if ($pos === false) {
                $link = Option::get('siteurl').$item['link'];
            } else {
                $link = $item['link'];
            }

            if (isset($uri[1])) {
                $child_link = explode("/",$item['link']);
                if (isset($child_link[1])) {
                    if (in_array($child_link[1], $uri)) {
                        $anchor_active = ' class="current" ';
                        $li_active = ' class="active"';
                    }
                }
            }

            if (isset($uri[0]) && $uri[0] !== '') {
                if (in_array($item['link'], $uri)) {
                    $anchor_active = ' class="current" ';
                    $li_active = ' class="active"';
                } else if (trim($item['branch']) !== '') {
                    $branch_items = Menu::branch($item['branch']);
                    if ($branch_items) {
                        foreach($branch_items as $branch_item) {
                            if (in_array($branch_item['link'], $uri)) {
                                $anchor_active = ' class="current" ';
                                $li_active = ' class="active"';
                                break;
                            }
                        }
                    }
                }
            } else {
                if ($defpage == trim($item['link'])) {
                    $anchor_active = ' class="current" ';
                    $li_active = ' class="active"';
                }
            }

            if (trim($item['target']) !== '') {
                $target = ' target="'.$item['target'].'" ';
            }

            // get the dropdown branch if set ...
            if (trim($item['branch']) !== '') {

                if (trim($li_active) !== '') {
                    $li_active = ' class="active dropdown"';
                } else {
                    $li_active = ' class="dropdown"';
                }

                if (trim($anchor_active) !== '') {
                    $anchor_active = ' class="current dropdown-toggle" data-toggle="dropdown" ';
                } else {
                    $anchor_active = ' class="dropdown-toggle" data-toggle="dropdown" ';
                }

                echo '<li'.$li_active.'>'.'<a href="'.$link.'"'.$anchor_active.$target.'>'.$item['name'].'<b class="caret"></b></a>';
                echo '<ul class="dropdown-menu">';
                echo Menu::get($item['branch']);
                echo '</ul></li>';
            } else {
                echo '<li'.$li_active.'>'.'<a href="'.$link.'"'.$anchor_active.$target.'>'.$item['name'].'</a>'.'</li>';
            }

            $anchor_active = '';
            $li_active = '';
            $target = '';
        }
    }
