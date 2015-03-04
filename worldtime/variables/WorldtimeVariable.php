<?php
namespace Craft;

class WorldtimeVariable
{
    public function ipToTime($ip = null)
    {
        $info = craft()->worldtime->ipToTime($ip);
        return ! empty( $info ) ? $info : false;
    }

}