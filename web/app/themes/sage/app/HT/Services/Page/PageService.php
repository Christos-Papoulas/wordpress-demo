<?php

namespace App\HT\Services\Page;

class PageService
{
    public $breadcrumbs_options;

    public $pageTitleOptions;

    public $showPageTitle;

    public function __construct()
    {
        $this->breadcrumbs_options = ht_get_field('page_breadcrumbs', get_queried_object_id());
        $this->pageTitleOptions = ht_get_field('page_title', get_queried_object_id());
        $this->showPageTitle = $this->pageTitleOptions['enabled'] ?? true;

        if(is_account_page() && !is_user_logged_in()){
            $this->showPageTitle = false;
        }
    }
}
