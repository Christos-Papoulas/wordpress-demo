<?php

namespace App\View\Components;

use Illuminate\View\Component;

class LoginForm extends Component
{
    private $current_user;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->setupComponent();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.login-form', ['current_user' => $this->current_user]);
    }

    /**
     * Setup the private attributes
     */
    private function setupComponent(): void
    {
        $this->current_user = $this->getCurrentUser();
    }

    /**
     * Returns the current user
     */
    private function getCurrentUser(): mixed
    {
        if (! is_user_logged_in()) {
            return false;
        }
        $currentuser = wp_get_current_user();

        return $currentuser;
    }
}
