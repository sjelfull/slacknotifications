# Slack Notifications plugin for Craft CMS

Send notifications to Slack when someone places an order, a entry is created,  or something else happens.

![Screenshot](resources/icon.png)

## Installation

To install Slack Notifications, follow these steps:

1. Download & unzip the file and place the `slacknotifications` directory into your `craft/plugins` directory
2. Install plugin in the Craft Control Panel under Settings > Plugins
3. The plugin folder should be named `slacknotifications` for Craft to see it.

Slack Notifications works on Craft 2.4.x and Craft 2.5.x.

## Configuring Slack Notifications

First, setup a [Incoming Webhook](https://my.slack.com/services/new/incoming-webhook/).

Then, copy the example config file `slacknotifications.php` into your config directory, usually `craft/config`.

Then, modify your config and add your events.

```php
<?php
return [
    // Webhook URL - get it by creating a incoming webhook 
    // at https://my.slack.com/services/new/incoming-webhook/
    'webhook'        => '',

    // Enable notifications for entries that is disabled
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
```

## Sending a message from templates

You can also send messages from a template:

```twig
{{ craft.slackNotifications.send('Message from Craft') }}
```

Brought to you by [Superbig](https://superbig.co)
