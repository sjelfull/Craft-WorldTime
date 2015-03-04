<?php

namespace Craft;

class WorldtimePlugin extends BasePlugin {

    function getName()
    {
        return Craft::t('WorldTime');
    }

    function getVersion()
    {
        return '0.1';
    }

    function getDeveloper()
    {
        return 'Fred Carlsen';
    }

    function getDeveloperUrl()
    {
        return 'http://sjelfull.no';
    }

    function hasCpSection()
    {
        return ;
    }

    public function getSettingsHtml()
    {
        return craft()->templates->render('worldtime/_settings', array(
            'settings' => $this->getSettings()
        ));
    }

    protected function defineSettings()
    {
        return array(
            'apiKey' => array(AttributeType::String, 'label' => 'Worldtime.io/Mashape Key'),
        );
    }

}
