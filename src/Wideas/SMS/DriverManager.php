<?php

namespace Wideas\SMS;

use GuzzleHttp\Client;
use Illuminate\Support\Manager;
use Wideas\SMS\Drivers\KavenegarSMS;
use Wideas\SMS\Drivers\MelipayamakSMS;
use Wideas\SMS\Drivers\TwilioSMS;


class DriverManager extends Manager
{
    /**
     * Get the default sms driver name.
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        return $this->app['config']['sms.driver'];
    }

    /**
     * Set the default sms driver name.
     *
     * @param string $name
     */
    public function setDefaultDriver($name)
    {
        $this->app['config']['sms.driver'] = $name;
    }

    /**
     * Create an instance of the Twillo driver.
     *
     * @return TwilioSMS
     */
    protected function createTwilioDriver()
    {
        $config = $this->app['config']->get('sms.twilio', []);

        return new TwilioSMS(
            new \Services_Twilio($config['account_sid'], $config['auth_token']),
            $config['auth_token'],
            $this->app['request']->url(),
            $config['verify']
        );
    }

    /**
     * Create an instance of the MelipayamakSMS driver.
     *
     * @return MelipayamakSMS
     */
    protected function createMelipayamakDriver()
    {
        $config = $this->app['config']->get('sms.melipayamak', []);
        
        $provider = new MelipayamakSMS(
            new Client(),
            $config['username'],
            $config['password'],
            $config['lineNumbers']
        );

        return $provider;
    }

    /**
     * Create an instance of the MelipayamakSMS driver.
     *
     * @return MelipayamakSMS
     */
    protected function createKavenegarDriver()
    {
        $provider = new KavenegarSMS(
            new Client(),
            true
        );

        return $provider;
    }
}
