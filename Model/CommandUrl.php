<?php

namespace CommandToUrl\Model;

use CommandToUrl\Model\Base\CommandUrl as BaseCommandUrl;

class CommandUrl extends BaseCommandUrl
{


    /**
     * Get the [allowed_ip] column value.
     *
     * @return array
     */
    public function getArrayAllowedIps()
    {
        if ("" == $this->getAllowedIps()) {
            return [];
        }

        return explode(',', $this->getAllowedIps());
    }
}
