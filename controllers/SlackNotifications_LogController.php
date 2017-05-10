<?php
/**
 * Slack Notifications plugin for Craft CMS
 *
 * SlackNotifications_Log Controller
 *
 * @author    Superbig
 * @copyright Copyright (c) 2017 Superbig
 * @link      https://superbig.co
 * @package   SlackNotifications
 * @since     1.0.0
 */

namespace Craft;

class SlackNotifications_LogController extends BaseController
{

    /**
     * @var    bool|array Allows anonymous access to this controller's actions.
     * @access protected
     */
    protected $allowAnonymous = array('actionIndex',
        );

    /**
     */
    public function actionIndex()
    {
    }
}