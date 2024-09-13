<?php
/*
Plugin Name: Mastercard Gateway WooCommerce
Description: A WooCommerce payment gateway integration with Mastercard.
Version: 1.0.0
Author: Mohammed Alfaris
*/

// Exit if accessed directly.
if (! defined('ABSPATH')) {
    exit;
}

// Include the gateway class.
add_action('plugins_loaded', 'init_mastercard_gateway');

function init_mastercard_gateway()
{
    // Check if WooCommerce is active.
    if (!class_exists('WC_Payment_Gateway')) return;

    class WC_Gateway_Mastercard extends WC_Payment_Gateway
    {
        // Declare properties.
        private $api_key;
        private $api_secret;
        private $environment;
        private $api_url;

        public function __construct()
        {
            $this->id = 'mastercard_gateway';
            $this->icon = ''; // Optional: URL to an icon image.
            $this->has_fields = true; // If you have custom fields for your payment method.
            $this->method_title = 'Mastercard Gateway';
            $this->method_description = 'Pay with Mastercard through the Mastercard Gateway.';

            // Load the settings.
            $this->init_form_fields();
            $this->init_settings();

            // Define user settings.
            $this->title = $this->get_option('title');
            $this->description = $this->get_option('description');
            $this->enabled = $this->get_option('enabled');
            $this->api_key = $this->get_option('api_key');
            $this->api_secret = $this->get_option('api_secret');
            $this->environment = $this->get_option('environment');

            // Initialize API URL based on the environment.
            $this->api_url = ($this->environment === 'sandbox')
                ? 'https://test-gateway.mastercard.com/api/rest'
                : 'https://gateway.mastercard.com/api/rest';

            // Actions and Filters.
            add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
        }

        // Initialize settings form fields.
        public function init_form_fields()
        {
            $this->form_fields = array(
                'enabled' => array(
                    'title' => 'Enable/Disable',
                    'label' => 'Enable Mastercard Gateway',
                    'type' => 'checkbox',
                    'default' => 'yes'
                ),
                'title' => array(
                    'title' => 'Title',
                    'type' => 'text',
                    'description' => 'This controls the title the user sees during checkout.',
                    'default' => 'Credit Card (Mastercard)',
                    'desc_tip' => true,
                ),
                'description' => array(
                    'title' => 'Description',
                    'type' => 'textarea',
                    'description' => 'Payment method description that the customer will see on your checkout.',
                    'default' => 'Pay securely using your credit card.',
                ),
                'api_key' => array(
                    'title' => 'API Key',
                    'type' => 'text',
                    'description' => 'Get your API keys from your Mastercard account.',
                ),
                'api_secret' => array(
                    'title' => 'API Secret',
                    'type' => 'password',
                    'description' => 'Get your API secret from your Mastercard account.',
                ),
                'environment' => array(
                    'title' => 'Environment',
                    'type' => 'select',
                    'description' => 'Select Sandbox for testing or Production for live transactions.',
                    'default' => 'sandbox',
                    'options' => array(
                        'sandbox' => 'Sandbox',
                        'production' => 'Production'
                    ),
                ),
            );
        }

        // Process payment method.
        public function process_payment($order_id)
        {
            $order = wc_get_order($order_id);
            $amount = $order->get_total();
            $currency = get_woocommerce_currency();

            // Create payment payload for Mastercard API.
            $payload = array(
                'apiOperation' => 'PAY',
                'order' => array(
                    'amount' => $amount,
                    'currency' => $currency,
                ),
                'sourceOfFunds' => array(
                    'provided' => array(
                        'card' => array(
                            'number' => sanitize_text_field($_POST['mastercard_card_number']),
                            'expiry' => array(
                                'month' => sanitize_text_field($_POST['mastercard_expiry_month']),
                                'year' => sanitize_text_field($_POST['mastercard_expiry_year']),
                            ),
                            'securityCode' => sanitize_text_field($_POST['mastercard_cvc']),
                        ),
                    ),
                ),
            );

            // Perform the API request to Mastercard.
            $response = wp_remote_post($this->api_url . '/order', array(
                'method' => 'POST',
                'headers' => array(
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Basic ' . base64_encode($this->api_key . ':' . $this->api_secret),
                ),
                'body' => json_encode($payload),
                'timeout' => 45,
            ));

            // Check for errors.
            if (is_wp_error($response)) {
                wc_add_notice('Payment error: ' . $response->get_error_message(), 'error');
                return array('result' => 'failure');
            }

            $body = json_decode($response['body'], true);

            // Check for a successful response.
            if (isset($body['result']) && $body['result'] === 'SUCCESS') {
                // Mark order as complete.
                $order->payment_complete();
                $order->add_order_note('Payment successful via Mastercard.');
                return array(
                    'result' => 'success',
                    'redirect' => $this->get_return_url($order),
                );
            } else {
                wc_add_notice('Payment error: ' . ($body['error']['explanation'] ?? 'An error occurred'), 'error');
                return array('result' => 'failure');
            }
        }
    }

    // Register the gateway with WooCommerce.
    add_filter('woocommerce_payment_gateways', 'add_mastercard_gateway_class');

    function add_mastercard_gateway_class($methods)
    {
        $methods[] = 'WC_Gateway_Mastercard';
        return $methods;
    }
}
