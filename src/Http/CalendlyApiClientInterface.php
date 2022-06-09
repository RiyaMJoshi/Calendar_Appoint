<?php

namespace App\Http;

interface CalendlyApiClientInterface
{
    public function fetchUserProfile($calendlyToken);
}
