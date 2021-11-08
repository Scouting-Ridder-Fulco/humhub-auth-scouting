<?php

namespace ridderfulco\auth\scouting;

use humhub\components\Event;
use humhub\modules\user\authclient\Collection;
use ridderfulco\auth\scouting\authclient\ScoutingAuth;
use ridderfulco\auth\scouting\models\ConfigureForm;

class Events
{
    /**
     * @param Event $event
     */
    public static function onAuthClientCollectionInit($event)
    {
        /** @var Collection $authClientCollection */
        $authClientCollection = $event->sender;

        if (!empty(ConfigureForm::getInstance()->enabled)) {
            $authClientCollection->setClient('scouting', [
                'class' => ScoutingAuth::class
            ]);
        }
    }
}
