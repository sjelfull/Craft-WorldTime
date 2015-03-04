<?php
namespace Craft;

use Guzzle\Http\Client as Guzzle;

class WorldtimeService extends BaseApplicationComponent {

    protected $settings;
    protected $apiKey;
    protected $client;
    protected $url;

    /**
     * Initalize service
     */
    public function init()
    {
        $plugin = craft()->plugins->getPlugin('worldtime');
        $this->settings = $plugin->getSettings();

        // Parse environment variables
        $this->parseSettingsForEnvVariables();

        // Setup
        $this->apiKey = $this->settings['apiKey'];
        $this->client = new Guzzle();
        $this->url = 'https://worldtimeiodeveloper.p.mashape.com';
    }

    /**
     * Gives you time information about an IP address
     *
     * @return String
     */
    public function ipToTime($ip = false)
    {
        // Set options
        $endpoint = $this->url . '/ip';

        // Lookup ip if none is provided (default mode)
        if ( empty($ip) ) {
            Craft::log('(WorldTime.io) Looking up IP address because none was provided.');
            $ip = $this->lookupIp();
        }

        // If we don't have a valid ip, exit early
        if (empty($ip))
        {
            Craft::log('(WorldTime.io) Didn\'t get a valid ip, exiting early.');

            return false;
        }

        Craft::log('(WorldTime.io) Fetching time for IP address ' . $ip . '.');

        // Send off request
        $request = $this->client->get($endpoint, [
            'X-Mashape-Key' => $this->apiKey,
        ], [
            'query' => [
                'ipaddress' => $ip
            ]
        ]);

        try
        {
            $response = $request->send();
        } catch (\Exception $e)
        {
            Craft::log('(WorldTime.io) Fetch error for ' . $ip . ': ' . $e->getResponse()->getBody(true), LogLevel::Error);

            return false;
        }

        // Return result
        $result = $response->json();

        return $result;
    }

    private function lookupIp()
    {
        $ip = craft()->request->getIpAddress();

        if ( ! $this->validIp($ip))
        {
            Craft::log('(WorldTime) Local ip not detected, trying to lookup from Telize');
            $request = $this->client->get('http://www.telize.com/ip');

            try
            {
                $response = $request->send();
            } catch (\Exception $e)
            {
                return false;
            }

            $ip = trim($response->getBody($asString = true));
            Craft::log('(WorldTime) Got ip from Telize: ' . $ip);

            return $this->validIp($ip) ? $ip : false;
        }
    }

    /**
     * Validate IP address format
     *
     * @param $ip
     * @return bool
     */
    private function validIp($ip)
    {
        return preg_match("/\\b(?:\\d{1,3}\\.){3}\\d{1,3}\\b/ui", $ip) === 1;
    }

    /**
     * Parses settings for environment variables
     */
    private function parseSettingsForEnvVariables()
    {
        foreach ($this->settings as $key => $value)
        {
            $this->settings[ $key ] = craft()->config->parseEnvironmentString($value);
        }
    }
}