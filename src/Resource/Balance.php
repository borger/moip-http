<?php

namespace Prothos\Moip\Resource;

use stdClass;

/**
 * Class Balance.
 */
class Balance extends MoipResource
{
    /**
     * Path accounts API.
     *
     * @const string
     */
    const PATH = 'balances';

    /**
     * Initialize a new instance.
     */
    public function initialize()
    {
        // $this->data = new stdClass();
        // $this->data->email = new stdClass();
        // $this->data->person = new stdClass();
        // $this->data->type = self::ACCOUNT_TYPE;
    }

    /**
     * Get current balance.
     *
     * @return stdClass
     */
    public function get()
    {
        return $this->getByPath(sprintf('/%s/%s', MoipResource::VERSION, self::PATH));
    }
}