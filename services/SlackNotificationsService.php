<?php
/**
 * Slack Notifications plugin for Craft CMS
 *
 * SlackNotifications Service
 *
 * @author    Superbig
 * @copyright Copyright (c) 2017 Superbig
 * @link      https://superbig.co
 * @package   SlackNotifications
 * @since     1.0.0
 */

namespace Craft;

class SlackNotificationsService extends BaseApplicationComponent
{
    protected $slackUri;
    protected $oldTemplatePath;
    protected $notifications;
    protected $notifyDisabled;

    public function init ()
    {
        parent::init();

        $this->slackUri       = craft()->config->get('webhook', 'slacknotifications');
        $this->notifications  = craft()->config->get('notifications', 'slacknotifications');
        $this->notifyDisabled = craft()->config->get('notifyDisabled', 'slacknotifications');
    }

    public function registerEvents ()
    {
        $builtinNotifications = [ 'sections', 'charge' ];

        if ( $this->checkForNotification('sections') ) {

            craft()->on('entries.SaveEntry', function (Event $event) {
                $entry = $event->params['entry'];

                if ( (!$this->notifyDisabled && $entry->status == 'disabled') || empty($event->params['isNewEntry']) ) {
                    return;
                }

                $this->onSaveEntry($entry);
            });
        }

        if ( $this->checkForNotification('charge') ) {
            craft()->on('charge.onCharge', function (Event $event) {
                $model = $event->params['charge'];
                $this->onCharge($model);
            });
        }

        foreach ($this->notifications as $event => $template) {
            if ( in_array($event, $builtinNotifications) ) {
                continue;
            }

            craft()->on($event, function (Event $event) use ($template) {
                $this->onCustomEvent($event->params, $template);
            });

        }
    }

    public function onSaveEntry (EntryModel $entry)
    {

        if ( $this->notifications && !empty($this->notifications['sections']) ) {
            $sectionHandle  = $entry->section->handle;
            $sectionHandles = $this->notifications['sections'];

            if ( array_key_exists($sectionHandle, $sectionHandles) ) {
                $template = $sectionHandles[ $sectionHandle ];

                $text = $this->render($template, [ 'entry' => $entry ]);
                $this->send($text);
            }
        }
    }

    public function onCharge ($model)
    {
        $text = $this->render('types/_charge', [ 'model' => $model ]);
        $this->send($text);
    }

    private function onCustomEvent ($params, $template)
    {
        $text = $this->render($template, $params);

        if ( $text ) {
            $this->send($text);
        }
    }

    public function render ($template, $data = [ ])
    {
        $this->oldTemplatePath = craft()->templates->getTemplatesPath();
        craft()->templates->setTemplatesPath(CRAFT_TEMPLATES_PATH);
        //craft()->templates->setTemplatesPath(CRAFT_PLUGINS_PATH . 'slacknotifications/templates/');

        try {
            $result = craft()->templates->render($template, $data);

            return $result;
        }
        catch (\Exception $e) {
            SlackNotificationsPlugin::log('Couldn\'t render Slack Notifications template: ' . $e->getMessage(), LogLevel::Error);
        }

        craft()->templates->setTemplatesPath($this->oldTemplatePath);

        return null;
    }

    public function send ($text = '')
    {
        try {
            $client = new \Guzzle\Http\Client();

            if ( !empty($text) ) {
                $payload = [
                    'icon_emoji' => ':monkey_face:',
                    'username'   => 'Craft',
                    'text'       => $text
                ];

                if ( $emoji = craft()->config->get('emoji', 'slacknotifications') && !empty($emoji) ) {
                    $payload['icon_emoji'] = $emoji;
                }

                if ( $username = craft()->config->get('username', 'slacknotifications') && !empty($username) ) {
                    $payload['username'] = $username;
                }

                $request = $client
                    ->post($this->slackUri)
                    ->setPostField('payload',
                        json_encode($payload)
                    );

                $request->send();
            }

            return true;
        }
        catch (\Exception $e) {
            SlackNotificationsPlugin::log('Couldn\'t send notification to Slack: ' . $e->getMessage(), LogLevel::Error);
        }

        return false;
    }

    public function checkForNotification ($key)
    {
        return !empty($this->notifications) && !empty($this->notifications[ $key ]);
    }

}