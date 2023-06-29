<?php

$router = $di->getRouter();

// Define your routes here

$router->handle();

$router->add('/clients/logout', [
    'controller' => 'clients',
    'action' => 'logout',
])->setName('clients-logout');
