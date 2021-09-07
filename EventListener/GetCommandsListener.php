<?php


namespace CommandToUrl\EventListener;


use CommandToUrl\Events\GetAllCommandEvent;
use CommandToUrl\Service\CommandService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class GetCommandsListener implements EventSubscriberInterface
{
    protected $commandService;

    /**
     * GetCommandsListener constructor.
     * @param CommandService $commandService
     */
    public function __construct(CommandService $commandService)
    {
        $this->commandService = $commandService;
    }


    public function getAllCommands(GetAllCommandEvent $event)
    {
        $event->setCommands($this->commandService->getAllCommands());
    }

    public static function getSubscribedEvents()
    {
        return [
            GetAllCommandEvent::GET_ALL_COMMAND => ['getAllCommands', 128]
        ];
    }
}
