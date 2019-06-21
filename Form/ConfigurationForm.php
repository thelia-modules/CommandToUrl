<?php


namespace CommandToUrl\Form;


use CommandToUrl\Model\CommandUrlQuery;
use CommandToUrl\Service\CommandService;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Kernel;
use Thelia\Form\BaseForm;

class ConfigurationForm extends BaseForm
{
    /**
     * @return string the name of you form. This name must be unique
     */
    public function getName()
    {
        return "command_to_url_configuration_form";
    }

    protected function buildForm()
    {
        $form = $this->formBuilder;

        $commands = $this->container->get('command_to_url.command.service')->getAllCommands();

        foreach ($commands as $command) {
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