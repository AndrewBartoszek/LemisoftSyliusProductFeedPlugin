<?php

declare(strict_types=1);

namespace Lemisoft\BoilerplatePlugin\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

final class GreetingController extends AbstractController
{
    public function staticallyGreetAction(?string $name): Response
    {
        return $this->render(
            '@LemisoftBoilerplatePlugin/static_greeting.html.twig',
            ['greeting' => $this->getGreeting($name)],
        );
    }

    public function dynamicallyGreetAction(?string $name): Response
    {
        return $this->render(
            '@LemisoftBoilerplatePlugin/dynamic_greeting.html.twig',
            ['greeting' => $this->getGreeting($name)],
        );
    }

    private function getGreeting(?string $name): string
    {
        return match ($name) {
            null => 'Lemisoft Boilerplate!',
            default => sprintf('Lemisoft Boilerplate, %s!', $name),
        };
    }
}
