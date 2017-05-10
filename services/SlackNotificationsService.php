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
    protected $settings;
    protected $slackUri;
    protected $entryUri;
    protected $sectionName;
    protected $oldTemplatePath;

    public function init ()
    {
        parent::init();

        $this->settings = craft()->plugins->getPlugin('slacknotifications')->getSettings();
        $this->slackUri = $this->settings->webhook;
    }

    public function notifyOnEntry ($entry, $template)
    {
        $text = $this->render($template, [ 'entry' => $entry ]);
        $this->send($text);
    }

    public function notifyOnCharge ($model)
    {
        $text = $this->render('types/_charge', [ 'model' => $model ]);
        $this->send($text);
    }

    public function render ($template, $data = [ ])
    {
        $this->oldTemplatePath = craft()->templates->getTemplatesPath();
        craft()->templates->setTemplatesPath(CRAFT_PLUGINS_PATH . 'slacknotifications/templates/');

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

            $request = $client
                ->post($this->slackUri)
                ->setPostField('payload',
                    json_encode([
                        'icon_emoji' => ':monkey_face:',
                        'username'   => 'Craft',
                        'text'       => $text
                    ])
                );

            $request->send();

            return true;
        }
        catch (\Exception $e) {
            SlackNotificationsPlugin::log('Couldn\'t send notification to Slack: ' . $e->getMessage(), LogLevel::Error);
        }

        return false;
    }

}