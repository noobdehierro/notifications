<?php

use App\Models\Configuration;

function example()
{
    $configuration = Configuration::wherein('code', [
        'is_sandbox',
        'bip_token',
        'bip_token_sandbox',
        'bip_endpoint',
        'bip_endpoint_sandbox'
    ])->get();
}
