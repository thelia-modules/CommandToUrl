<?php

namespace CommandToUrl\Controller;

use CommandToUrl\Model\CommandUrlQuery;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpFoundation\RequestStack;
use Thelia\Controller\Front\BaseFrontController;
use Thelia\Core\Application;
use Thelia\Core\HttpFoundation\Request;
use Thelia\Core\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/command", name="comand_to_url_command")
 */
class CommandController extends BaseFrontController
{
    /**
     * @Route("", name="", methods="GET")
     */
    public function callCommand(RequestStack $requestStack)
    {
        $request = $requestStack->getCurrentRequest();
        $command = $request->get('command');
        $token = $request->get('token');

        $arguments = $request->get('arguments', []);

        $options = $request->get('options', []);

        $commandUrl = CommandUrlQuery::create()
            ->findOneByCommand($command);

        if (null == $commandUrl) {
            return new Response("This command is not allowed from URL !", 400);
        }

        if (!$commandUrl->getActive()) {
            return new Response("This command is not allowed from URL !", 400);
        }

        if (null !== $commandUrl->getToken() && $commandUrl->getToken() !== $token) {
            return new Response("Bad request !", 400);
        }

        if (!empty($commandUrl->getArrayAllowedIps()) && !in_array($request->getClientIp(), $commandUrl->getArrayAllowedIps())) {
            return new Response("Your not allowed to do this !", 400);
        }

        $kernel = $this->getContainer()->get('kernel');
        $application = new Application($kernel);
        $application->setAutoExit(false);

        $inputArray = [
            'command' => $command
        ];

        if (is_array($arguments)) {
            $inputArray = array_merge($inputArray, $arguments);
        }

        if (is_array($options)) {
            $inputArray = array_merge($inputArray, $options);
        }

        $input = new ArrayInput($inputArray);

        $output = new BufferedOutput();
        $application->run($input, $output);

        $content = $output->fetch();

        return new Response($content);
    }
}
