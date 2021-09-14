<?php


namespace CommandToUrl\Events;


use Thelia\Core\Event\ActionEvent;

class GetAllCommandEvent extends ActionEvent
{
    const GET_ALL_COMMAND = "command_to_url_get_all_commands";

    protected $commands;

    /**
     * @return mixed
     */
    public function getCommands()
    {
        return $this->commands;
    }

    /**
     * @param mixed $commands
     */
    public function setCommands($commands): void
    {
        $this->commands = $commands;
    }


}
