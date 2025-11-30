<?php

arch('Billingo class is final')
    ->expect('Omisai\Billingo\Billingo')
    ->not->toBeFinal();

arch('Service provider extends Laravel ServiceProvider')
    ->expect('Omisai\Billingo\BillingoServiceProvider')
    ->toExtend('Illuminate\Support\ServiceProvider');

arch('Facade extends Laravel Facade')
    ->expect('Omisai\Billingo\Facades\Billingo')
    ->toExtend('Illuminate\Support\Facades\Facade');

arch('API classes are in Api namespace')
    ->expect('Omisai\Billingo\Api')
    ->toBeClasses();

arch('Model classes are in Model namespace')
    ->expect('Omisai\Billingo\Models')
    ->ignore(['Omisai\Billingo\Models\ModelInterface'])
    ->toBeClasses();

arch('ModelInterface is an interface')
    ->expect('Omisai\Billingo\Models\ModelInterface')
    ->toBeInterface();

arch('src does not use dd or dump')
    ->expect(['dd', 'dump', 'var_dump', 'print_r'])
    ->not->toBeUsed();

arch('Billingo has required methods')
    ->expect('Omisai\Billingo\Billingo')
    ->toHaveMethods([
        'getConfiguration',
        'getClient',
        'bankAccount',
        'currency',
        'document',
        'documentBlock',
        'documentExport',
        'organization',
        'partner',
        'product',
        'spending',
        'util',
    ]);
