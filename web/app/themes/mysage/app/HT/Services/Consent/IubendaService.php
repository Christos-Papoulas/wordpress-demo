<?php

namespace App\HT\Services\Consent;

use App\HT\Interfaces\ConsentInterface;

class IubendaService implements ConsentInterface
{
    public const ORDER = 'order';

    public const REGISTER = 'register';

    public const FORM = 'form';

    private $iubenda_private_key;

    private $content;

    private $content_type;

    private $consent_data;

    /**
     * Create consent for the object
     * Accepts WC_Order or WP_User
     */
    public function create($object)
    {
        $this->iubenda_private_key = config('theme.iubendaConsentApiKey', '');
        if (empty($this->iubenda_private_key)) {
            return;
        }

        $this->content = $object;
        $this->init();
        if ($this->consent_data === null) {
            return;
        }

        $req = curl_init();
        curl_setopt($req, CURLOPT_URL, 'https://consent.iubenda.com/consent');
        curl_setopt($req, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($req, CURLOPT_HTTPHEADER, [
            'ApiKey: '.$this->iubenda_private_key,
            'Content-Type: application/json',
        ]);
        curl_setopt($req, CURLOPT_POST, true);
        curl_setopt($req, CURLOPT_POSTFIELDS, json_encode($this->consent_data));
        $response = curl_exec($req);

        $http_status = curl_getinfo($req, CURLINFO_HTTP_CODE);

        if ($http_status == 200) {
            $response = json_decode($response);
            if ($this->content_type == self::ORDER) {
                // update order meta
                $this->content->update_meta_data('iubenda-consent-id', $response->id);
                $this->content->save();
            } elseif ($this->content_type == self::REGISTER) {
                update_user_meta($this->content->ID, 'iubenda-consent-id', $response->id);
            }
        }
        // do not throw error if consent request fails
        // else{
        //     // log error.
        //     $response = json_decode($response);
        //     try{
        //         file_put_contents(ABSPATH.'iubenda_logs.txt', PHP_EOL.'Consent Request Error for order with id: ' . $order->get_id() . ' . ' . $response->message, FILE_APPEND);
        //     } catch (\Exception $ex){
        //         error_log( $ex->getMessage() );
        //     }
        // }
    }

    /**
     * Initialize the consent data based on the content type
     */
    private function init()
    {
        if (is_a($this->content, 'WC_Order')) {
            $this->content_type = self::ORDER;
            $this->consent_data = $this->setupForOrder();
        } elseif (is_a($this->content, 'WP_User')) {
            $this->content_type = self::REGISTER;
            $this->consent_data = $this->setupForRegister();
        }
    }

    /**
     * Setup consent data for order
     * Returns null if order has already been consented
     */
    private function setupForOrder()
    {
        $order = $this->content;
        $consent_id = $order->get_meta('iubenda-consent-id');

        if ($consent_id === '') {
            return [
                'subject' => [
                    'email' => $order->get_billing_email(),
                    'first_name' => $order->get_billing_first_name(),
                    'last_name' => $order->get_billing_last_name(),
                ],
                'proofs' => [
                    [
                        'form' => 'checkout',
                        'content' => 'order-'.$order->get_id(),
                    ],
                ],
                'legal_notices' => [
                    [
                        'identifier' => 'privacy_policy',
                    ],
                    [
                        'identifier' => 'cookie_policy',
                    ],
                    [
                        'identifier' => 'term',
                    ],
                ],
                'preferences' => [
                    'newsletter' => false,
                    'privacy_policy' => true,
                ],
                'ip_address' => $_SERVER['REMOTE_ADDR'],
            ];
        }

        return null;
    }

    /**
     * Setup consent data for user registration
     * Returns null if registration has already been consented
     */
    private function setupForRegister()
    {
        $user = $this->content;
        $consent_id = get_user_meta($user->ID, 'iubenda-consent-id', true);

        if ($consent_id === '') {
            return [
                'subject' => [
                    'email' => $user->user_email,
                    'first_name' => $user->user_nicename,
                    'last_name' => $user->user_nicename,
                ],
                'proofs' => [
                    [
                        'form' => 'user_registration',
                        'content' => 'user-'.$user->ID,
                    ],
                ],
                'legal_notices' => [
                    [
                        'identifier' => 'privacy_policy',
                    ],
                    [
                        'identifier' => 'cookie_policy',
                    ],
                    [
                        'identifier' => 'term',
                    ],
                ],
                'preferences' => [
                    'newsletter' => false,
                    'privacy_policy' => true,
                ],
                'ip_address' => $_SERVER['REMOTE_ADDR'],
            ];
        }

        return null;
    }
}
