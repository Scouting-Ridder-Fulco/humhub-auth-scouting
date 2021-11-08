<?php

use humhub\modules\user\authclient\Collection;

return [
    'id' => 'auth-scouting',
    'class' => 'ridderfulco\auth\scouting\Module',
    'namespace' => 'ridderfulco\auth\scouting',
    'events' => [
        [Collection::class, Collection::EVENT_AFTER_CLIENTS_SET, ['ridderfulco\auth\scouting\Events', 'onAuthClientCollectionInit']]
    ],
];
