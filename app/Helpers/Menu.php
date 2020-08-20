<?php


namespace App\Helpers;


use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class Menu
{
    private $html;
    private $icons;
    private $user;

    public function __construct()
    {
    }

    public function make($menus, $icons)
    {
        $this->icons = $icons;
        $this->user = Auth::user();

        $this->each($menus);

        return $this->html;
    }

    public function each($array)
    {
        foreach ($array as $item)
        {
            if ( ! empty($item['permission']) && $this->user->cannot($item['permission']) ) {
                continue;
            }

            $type = $this->type($item);
            $this->$type($item);
        }
    }

    /**
     * Return type:
     * Heading - Menu group heading
     * Parent - Menu parent without link
     * Menu - Linked menu
     *
     * @param $item
     * @return string
     */
    public function type($item)
    {
        return Arr::has($item, 'route') ? (Arr::has($item, 'icon') ? 'menu' : 'submenu') : ( Arr::has($item, 'child') ? 'parent' : 'heading' );
    }

    public function heading($menu)
    {
        $this->html .= "<li class='kt-menu__section '>
                            <h4 class='kt-menu__section-text'>{$menu['name']}</h4>
                            <i class='kt-menu__section-icon flaticon-more-v2'></i>
                        </li>";
    }

    public function parent($menu)
    {
        $this->html .= "<li class='kt-menu__item {$this->parentActive($menu['active'])} kt-menu__item--submenu' aria-haspopup='true' data-ktmenu-submenu-toggle='hover'>
                            <a href='javascript:;' class='kt-menu__link kt-menu__toggle'>
                                <span class='kt-menu__link-icon'>
                                    {$this->icons[$menu['icon']]}
                                </span>
                                <span class='kt-menu__link-text'>{$menu['name']}</span>
                                <i class='kt-menu__ver-arrow la la-angle-right'></i>
                            </a>
                            <div class='kt-menu__submenu '>
                                <span class='kt-menu__arrow'></span>
                                <ul class='kt-menu__subnav'>
                                    <li class='kt-menu__item {$this->active($menu['active'])} kt-menu__item--parent' aria-haspopup='true'>
                                        <span class='kt-menu__link'>
                                            <span class='kt-menu__link-text'>{$menu['name']}</span>
                                        </span>
                                    </li>";

        $this->each($menu['child']);

        $this->html .= "</ul></div></li>";
    }

    public function menu($menu)
    {
        $this->html .= "<li class='kt-menu__item {$this->active($menu['active'])}' aria-haspopup='true'>
                            <a href='{$this->route($menu['route'])}' class='kt-menu__link shoot'>
                                <span class='kt-menu__link-icon'>
                                    {$this->icons[$menu['icon']]}
                                </span>
                                <span class='kt-menu__link-text'>{$menu['name']}</span>
                            </a>
                        </li>";
    }

    public function submenu($menu)
    {
        $this->html .= "<li class='kt-menu__item {$this->active($menu['active'])}' aria-haspopup='true'>
                            <a href='{$this->route($menu['route'])}' class='kt-menu__link shoot'>
                                <i class='kt-menu__link-bullet kt-menu__link-bullet--dot'><span></span></i>
                                <span class='kt-menu__link-text'>{$menu['name']}</span>
                            </a>
                        </li>";
    }

    public function route($route)
    {
        return route($route);
    }

    public function active($active)
    {
        return request()->routeIs($active) ? 'kt-menu__item--active' : '';
    }

    public function parentActive($active)
    {
        return request()->routeIs($active) ? 'kt-menu__item--open' : '';
    }


}
