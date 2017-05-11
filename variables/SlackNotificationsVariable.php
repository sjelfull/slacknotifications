<?php
/**
 * Slack Notifications plugin for Craft CMS
 *
 * Slack Notifications Variable
 *
 * @author    Superbig
 * @copyright Copyright (c) 2017 Superbig
 * @link      https://superbig.co
 * @package   SlackNotifications
 * @since     1.0.0
 */

namespace Craft;

class SlackNotificationsVariable
{
    /**
     */
    public function send($text = '')
    {
        return craft()->slackNotifications->send($text);
    }
}