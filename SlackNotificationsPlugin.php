<?php
/**
 * Slack Notifications plugin for Craft CMS
 *
 * Send notifications to Slack when someone places an order, a entry is created,  or something else happens.
 *
 * @author    Superbig
 * @copyright Copyright (c) 2017 Superbig
 * @link      https://superbig.co
 * @package   SlackNotifications
 * @since     1.0.0
 */

namespace Craft;

class SlackNotificationsPlugin extends BasePlugin
{
    /**
     * @return mixed
     */
    public function init ()
    {
        parent::init();

        $this->registerEvents();
    }

    /**
     * @return mixed
     */
    public function getName ()
    {
        return Craft::t('Slack Notifications');
    }

    /**
     * @return mixed
     */
    public function getDescription ()
    {
        return Craft::t('Send notifications to Slack when someone places an order, a entry is created,  or something else happens.');
    }

    /**
     * @return string
     */
    public function getDocumentationUrl ()
    {
        return 'https://github.com/sjelfull/slacknotifications/blob/master/README.md';
    }

    /**
     * @return string
     */
    public function getReleaseFeedUrl ()
    {
        return 'https://raw.githubusercontent.com/sjelfull/slacknotifications/master/releases.json';
    }

    /**
     * @return string
     */
    public function getVersion ()
    {
        return '1.0.0';
    }

    /**
     * @return string
     */
    public function getSchemaVersion ()
    {
        return '1.0.0';
    }

    /**
     * @return string
     */
    public function getDeveloper ()
    {
        return 'Superbig';
    }

    /**
     * @return string
     */
    public function getDeveloperUrl ()
    {
        return 'https://superbig.co';
    }

    /**
     * @return array
     */
    protected function defineSettings ()
    {
        return [];
    }

    private function registerEvents ()
    {
        craft()->slackNotifications->registerEvents();
    }

}