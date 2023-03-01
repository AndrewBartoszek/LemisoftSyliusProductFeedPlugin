<?php

declare(strict_types=1);

namespace Lemisoft\Tests\BoilerplatePlugin\Behat\Page\Shop;

use FriendsOfBehat\PageObjectExtension\Page\SymfonyPage;

class StaticWelcomePage extends SymfonyPage implements WelcomePageInterface
{
    public function getGreeting(): string
    {
        return $this->getElement('greeting')->getText();
    }

    public function getRouteName(): string
    {
        return 'lemisoft_boilerplate_static_welcome';
    }

    protected function getDefinedElements(): array
    {
        return array_merge(parent::getDefinedElements(), ['greeting' => '#greeting']);
    }
}
