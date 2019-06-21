<?php


namespace CommandToUrl\Controller;


use ClassicRide\ClassicRide;
use CommandToUrl\CommandToUrl;
use CommandToUrl\Model\CommandUrlQuery;
use CommandToUrl\Service\CommandService;
use Thelia\Controller\Admin\BaseAdminController;
use Thelia\Core\HttpFoundation\Request;
use Thelia\Core\Security\AccessManager;
use Thelia\Core\Security\Resource\AdminResources;
use Thelia\Core\Translation\Translator;
use Thelia\Tools\URL;

class ConfigurationController extends BaseAdminController
{
    public function viewAction()
    {
        /** @var CommandService $commandService */
        $commandService = $this->container->get('command_to_url.command.service');

        $commands = $commandService->getAllCommands();

        return $this->render("command-to-url/configuration", compact('commands'));
    }

    public function saveAction(Request $request)
    {
        if (null !== $response = $this->checkAuth(array(AdminResources::MODULE), 'CommandToUrl', AccessManager::VIEW)) {
            return $response;
        }

        $form = $this->createForm("command_to_url_configuration_form");

        try {
            $data = $this->validateForm($form)->getData();
            $commands = [];

            foreach ($data as $fieldName => $fieldData) {
                $fieldNameParts = explode(':-:', $fieldName);

                if (count($fieldNameParts) !== 2) {
                    continue;
                }

                $commands[$fieldNameParts[0]][$fieldNameParts[1]] = $fieldData;

            }

            foreach ($commands as $command => $values) {
                $commandUrl = CommandUrlQuery::create()
                    ->filterByCommand($command)
                    ->findOneOrCreate();

                $commandUrl->setToken($values['token'])
                    ->setAllowedIps($values['allowed_ips'])
                    ->setActive($values['active'])
                    ->save();
            }
        } catch (\Exception $e) {
            $this->setupFormErrorContext(
                Translator::getInstance()->trans(
                    "Error",
                    [],
                    CommandToUrl::DOMAIN_NAME
                ),
                $e->getMessage(),
                $form
            );

            return $this->viewAction();
        }
        return $this->generateRedirect(URL::getInstance()->absoluteUrl("/admin/module/CommandToUrl"));
    }

}