<?php

namespace Mods\Backend\Block;

use Menu;
use Mods\View\Block as BaseBlock;

class SidebarLeft extends BaseBlock
{
	/**
     * Left sider-bar menu.
     *
     * @return array
     */
    public function getSideMenu()
    {

        $menu = \Menu::make();

        $menu->add('Dashboard', 'Dashboard', ['route'  => 'backend.dashboard'])
                ->prepend('<i class="material-icons">dashboard</i> ');

        $menu->add('System', 'System')->prepend('<i class="material-icons">settings_system_daydream</i> ');
        $menu->system->add('Settings', 'Settings', '/settings')->prepend('<i class="material-icons">settings</i> ');        
        $menu->settings->add('Users', 'Users', '/users')->prepend('<i class="material-icons">supervisor_account</i> ');
        $menu->system->add('Updates', 'Updates', '/system_update')->prepend('<i class="material-icons">system_update</i> ');

        app('events')->fire('backend.sidebar.menu.getSideMenu.before', [
            'menu'  => $menu
        ]);

        $html = $this->renderSideMenu($menu);

        app('events')->fire('admin.sidebar.menu.getSideMenu.after', [
            'menu' => $menu,
            'html' => $html,
        ]);

        return $html;
    }


    protected function renderSideMenu($menu)
    {
        return "<ul{$menu->attributes(['id'=>'open-left-menu', 'class'=>'side-nav fixed leftside'])}>{$this->render($menu ,'ul')}</ul>";
    }

    /**
     * Generate the menu items as list items, recursively.
     *
     * @param  string  $type
     * @param  int     $parent
     * @return string
     */
    protected function render($menu, $type = 'ul', $parent = null)
    {
        $items   = '';
        $itemTag = in_array($type, ['ul', 'ol']) ? 'li' : $type;
        foreach ($menu->where('parent', $parent) as $item) {
            $items .= "<{$itemTag}{$item->attributes()}>";
            $link =  $arrow = '';
            if ($item->link) {
                $item->link->attr(['class' => 'collapsible-header waves-effect waves-cyan']);
                $attribute = $menu->attributes($item->link->attr());
                $link = "href=\"{$item->url()}\"";
            } else {
                $attribute = 'collapsible-header waves-effect waves-cyan';
            }


            if ($item->hasChildren()) { 
                $items .= '<ul class="collapsible"><li>';
                $link = '';
                $arrow = '<div class="arrow-group right"> 
                    <i class="material-icons keyboard_arrow_right">keyboard_arrow_right</i> 
                    <i class="material-icons keyboard_arrow_down">keyboard_arrow_down</i> 
                </div>';
            }

            $items .= "<a{$attribute} {$link}>{$item->title} {$arrow} </a>";

            if ($item->hasChildren()) {
                $items .= "<div class='collapsible-body'> <{$type}>";
                $items .= $this->render($menu, $type, $item->id);
                $items .= "</{$type}></div>";
                $items .= '</li
                ></ul>';
            }
            $items .= "</{$itemTag}>";
            if ($item->divider) {
                $items .= "<{$itemTag}{$menu->attributes($item->divider)}></{$itemTag}>";
            }
        }
        return $items;
    }
}
