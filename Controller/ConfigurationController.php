<?php


namespace CommandToUrl\Controller;


use CommandToUrl\CommandToUrl;
use CommandToUrl\Form\ConfigurationForm;
use CommandToUrl\Model\CommandUrlQuery;
use CommandToUrl\Service\CommandService;
use Thelia\Controller\Admin\BaseAdminController;
use Thelia\Core\HttpFoundation\Request;
use Thelia\Core\Security\AccessManager;
use Thelia\Core\Security\Resource\AdminResources;
use Thelia\Core\Translation\Translator;
use Thelia\Tools\URL;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/module/CommandToUrl", name="comand_to_url_config")
 */
class ConfigurationController extends BaseAdminController
{
    /**
     * @Route("", name="", methods="GET")
     */
    public function viewAction(CommandService $commandService)
    {
        $commands = $commandService->getAllCommands();

        return $this->render("command-to-url/configuration", compact('commands'));
    }

    /**
     * @Route("/save", name="_save", methods="POST")
     */
    public function saveAction(Request $request)
    {
        if (null !== $response = $this->checkAuth(array(AdminResources::MODULE), 'CommandToUrl', AccessManager::VIEW)) {
            return $response;
        }

        $form = $this->createForm(ConfigurationForm::getName());

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

            return $this->generateRedirect(URL::getInstance()->absoluteUrl("/admin/module/CommandToUrl"));
        }
        return $this->generateRedirect(URL::getInstance()->absoluteUrl("/admin/module/CommandToUrl"));
    }

}
