<?php

namespace App\View\Composers\Woocommerce\Checkout;

use App\HT\Interfaces\ConsentInterface;
use Roots\Acorn\View\Composer;

class ThankYou extends Composer
{
    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected static $views = [
        'woocommerce.checkout.thankyou',
    ];

    private $consentService;

    public function __construct(ConsentInterface $consentService)
    {
        $this->consentService = $consentService;
    }

    /**
     * Data to be passed to view before rendering.
     *
     * @return array
     */
    public function with()
    {
        if ((WP_ENV == 'production' || WP_ENV == 'staging') && config('theme.consentApiEnabled', false)) {
            $this->consentService->create($this->data['order']);
        }

        return [];
    }
}
