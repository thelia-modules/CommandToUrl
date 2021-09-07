<?php


namespace CommandToUrl\Form;


use CommandToUrl\Events\GetAllCommandEvent;
use CommandToUrl\Model\CommandUrlQuery;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Thelia\Form\BaseForm;

class ConfigurationForm extends BaseForm
{
    /**
     * @return string the name of you form. This name must be unique
     */
    public static function getName()
    {
        return "command_to_url_configuration_form";
    }

    protected function buildForm()
    {
        $form = $this->formBuilder;

        $commandsEvent = new GetAllCommandEvent();

        $this->dispatcher->dispatch($commandsEvent, GetAllCommandEvent::GET_ALL_COMMAND);

        foreach ($commandsEvent->getCommands() as $command) {
            $data = CommandUrlQuery::create()
                ->filterByCommand($command->name)
                ->findOneOrCreate();

            $form
                ->add(
                    $command->name.":-:token",
                    TextType::class,
                    [
                        'data' => $data->getToken()
                    ]
                )
                ->add(
                    $command->name.":-:allowed_ips",
                    TextType::class,
                    [
                        'data' => $data->getAllowedIps()
                    ]
                )
                ->add(
                    $command->name.":-:active",
                    CheckboxType::class,
                    [
                        'data' => boolval($data->getActive())
                    ]
                );
        }
    }

}
