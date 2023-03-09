<?php

declare(strict_types=1);

namespace Lemisoft\SyliusProductFeedsPlugin\Model\ProductFeedGenerator;

use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints as Assert;

class CeneoFeedImagesModel
{
    #[Serializer\Type("Lemisoft\SyliusProductFeedsPlugin\Model\ProductFeedGenerator\CeneoFeedImageModel")]

    #[Assert\NotBlank(groups: [Constraint::DEFAULT_GROUP])]
    public ?CeneoFeedImageModel $main = null;

    /**
     * @var CeneoFeedImageModel[]
     */
    #[Serializer\Type("array<Lemisoft\SyliusProductFeedsPlugin\Model\ProductFeedGenerator\CeneoFeedImageModel>")]
    #[Serializer\XmlList(entry: 'i', inline: true)]
    public array $i = [];

    public function setMainImage(string $url): void
    {
        $this->main = new CeneoFeedImageModel($url);
    }

    public function addAdditionalImage(string $url): void
    {
        $this->i[] = new CeneoFeedImageModel($url);
    }
}
