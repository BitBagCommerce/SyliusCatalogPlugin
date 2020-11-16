<?php

declare(strict_types=1);

namespace BitBag\SyliusCatalogPlugin;

use Sylius\Bundle\CoreBundle\Application\SyliusPluginTrait;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class BitBagSyliusCatalogPlugin extends Bundle
{
    use SyliusPluginTrait;
}
