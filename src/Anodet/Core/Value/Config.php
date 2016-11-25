<?php

namespace Anodet\Core\Value;

class Config
{
    const CODE_LAST_FETCH = 'last_fetch';

    /** @var int */
    public $sourceId;

    /** @var \DateTime */
    public $lastFetch;
}
