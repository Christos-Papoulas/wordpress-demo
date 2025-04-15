<?php

namespace App\Options;

use App\Fields\Partials\Options\Benefits;
use App\Fields\Partials\Options\Checkout;
// use App\Fields\Partials\Options\Topbar;
use App\Fields\Partials\Options\Contact;
use App\Fields\Partials\Options\Footer;
use App\Fields\Partials\Options\Header;
use App\Fields\Partials\Options\Pages;
use Log1x\AcfComposer\Builder;
use Log1x\AcfComposer\Options as Field;

class Options extends Field
{
    /**
     * The option page menu name.
     *
     * @var string
     */
    public $name = 'Ρυθμίσεις Theme';

    /**
     * The option page menu slug.
     *
     * @var string
     */
    public $slug = 'options';

    /**
     * The option page document title.
     *
     * @var string
     */
    public $title = 'Ρυθμίσεις Theme';

    /**
     * The option page permission capability.
     *
     * @var string
     */
    public $capability = 'edit_theme_options';

    /**
     * The option page menu position.
     *
     * @var int
     */
    public $position = PHP_INT_MAX;

    /**
     * The option page visibility in the admin menu.
     *
     * @var bool
     */
    public $menu = true;

    /**
     * The slug of another admin page to be used as a parent.
     *
     * @var string
     */
    public $parent = null;

    /**
     * The option page menu icon.
     *
     * @var string
     */
    public $icon = null;

    /**
     * Redirect to the first child page if one exists.
     *
     * @var bool
     */
    public $redirect = true;

    /**
     * The post ID to save and load values from.
     *
     * @var string|int
     */
    public $post = 'options';

    /**
     * The option page autoload setting.
     *
     * @var bool
     */
    public $autoload = true;

    /**
     * The additional option page settings.
     *
     * @var array
     */
    public $settings = [];

    /**
     * Localized text displayed on the submit button.
     */
    public function updateButton(): string
    {
        return __('Update', 'acf');
    }

    /**
     * Localized text displayed after form submission.
     */
    public function updatedMessage(): string
    {
        return __('Options Updated', 'acf');
    }

    /**
     * The option page field group.
     */
    public function fields(): array
    {
        $fields = Builder::make('options');

        $fields
            ->addTab('pages_tab', ['label' => 'Σελίδες'])
            ->addFields($this->get(Pages::class))
            ->addTab('header_tab', ['label' => 'Header'])
            ->addFields($this->get(Header::class))
            ->addTab('footer_tab', ['label' => 'Footer'])
            ->addFields($this->get(Footer::class))
            ->addTab('contact_tab', ['label' => 'Επικοινωνία'])
            ->addFields($this->get(Contact::class))
            ->addTab('globals', ['label' => 'Global Blocks'])
            ->addAccordion('accordion_benefits', ['label' => 'Benefits'])
            ->addFields($this->get(Benefits::class))
            ->addAccordion('accordion_benefits_end')->endpoint()
            ->addTab('checkout_tab', ['label' => 'Checkout'])
            ->addFields($this->get(Checkout::class));

        return $fields->build();
    }
}
