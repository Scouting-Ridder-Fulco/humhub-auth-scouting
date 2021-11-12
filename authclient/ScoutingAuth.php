<?php

namespace ridderfulco\auth\scouting\authclient;

use humhub\modules\user\authclient\interfaces\ApprovalBypass;
use yii\authclient\OpenId;
use Yii;

/**
 * OpenId allows authentication via OpenId 2.0.
 */
class ScoutingAuth extends OpenId implements ApprovalBypass
{

    public $authUrl = 'https://login.scouting.nl/provider/';

    public $requiredAttributes = [
        'namePerson/friendly',
        'contact/email',
        'namePerson',
    ];

    public $optionalAttributes = [
        'birthDate', 'person/gender', 'contact/postalCode/home', 'contact/country/home', 'pref/language', 'pref/timezone'
    ];

    /**
     * @inheritdoc
     */
    protected function defaultViewOptions()
    {
        return [
            'title' => 'Login met Scouting Nederland',
            'popupWidth' => 860,
            'popupHeight' => 480,
            'cssIcon' => 'icn-snl',
            'buttonBackgroundColor' => '#e0492f',
        ];
    }

    /**
     * @inheritdoc
     */
    protected function defaultNormalizeUserAttributeMap()
    {
        return [
            'username' => 'namePerson/friendly',
            'firstname' => function ($attributes) {
                if (!isset($attributes['namePerson'])) {
                    return '';
                }

                return substr($attributes['namePerson'], 0, strpos($attributes['namePerson'], " "));
            },
            'lastname' => function ($attributes) {
                if (!isset($attributes['namePerson'])) {
                    return '';
                }

                return substr($attributes['namePerson'], strpos($attributes['namePerson'], " ") + 1);
            },
            'title' => 'tagline',
            'email' => function ($attributes) {
                return $attributes['contact/email'];
            },
        ];
    }

    /**
     * Hack to disable discovery and install the correct configuration right away
     * @param string $url Identity URL.
     * @return array OpenID provider info, following keys will be available:
     *
     * @throws Exception on failure.
     */
    public function discover($url)
    {
        if (empty($url)) {
            throw new Exception('No identity supplied.');
        }

        $result = [
            'url' => "https://login.scouting.nl/provider/",
            'version' => 2,
            'identity' => $url,
            'identifier_select' => true,
            'ax' => false,
            'sreg' => true
        ];
        // $result = [
        //     'url' => $url,
        //     'version' => 2,
        //     'identity' => $this->data['openid_identity'] ?? $url,
        //     'identifier_select' => $identifier_select,
        //     'ax' => false,
        //     'sreg' => true
        // ];
        return $result;
    }

    /**
     * Composes SREG request parameters.
     */
    protected function buildSregParams()
    {
        $params = parent::buildSregParams();

        // Add new theme
        $params['openid.ns_theme'] = 'https://login.scouting.nl/ns/theme/1.0';
        $params['openid.theme_theme'] = 'TC3_earth';

        return $params;
    }
}
