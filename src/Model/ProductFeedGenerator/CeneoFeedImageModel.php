<?php

declare(strict_types=1);

namespace Lemisoft\SyliusProductFeedsPlugin\Model\ProductFeedGenerator;

use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints as Assert;

class CeneoFeedImageModel
{
    #[Serializer\Type("string")]
    #[Serializer\XmlAttribute()]
    #[Assert\NotBlank(groups: [Constraint::DEFAULT_GROUP])]
    public ?string $url = null;

    public function __construct(string $url)
    {
        $this->url = $url;
    }
}
