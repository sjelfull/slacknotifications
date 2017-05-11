<?php
return [
    // Webhook URL - get it by creating a incoming webhook at https://my.slack.com/services/new/incoming-webhook/
    'webhook'        => '',
    'notifyDisabled' => false,

    // Username
    'username'       => 'Craft',

    // Custom emoji for your webhook
    'emoji'          => ':monkey_face:',

    // Notifications, defined by event name => template (relative to your site templates path)
    // Your custom events will get any params passed to them.
    'notifications'  => [
        // By default, sections and charge.onCharge is built in
        // 'sections' => [
        // 'sectionHandle' => '_slack/my-section'
        // ],
        // 'charge'   => '_slack/charge'
        // Add any other, eg Commerce events
        // 'commerce_orders.onOrderComplete' => '_slack/commerce-order'
    ]
];