<?php

declare(strict_types=1);

namespace Lemisoft\SyliusProductFeedsPlugin\Model\ProductFeedGenerator;

use JMS\Serializer\Annotation as Serializer;
use Lemisoft\SyliusProductFeedsPlugin\Entity\ProductFeed\ProductFeedInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints as Assert;

class FacebookFeedXmlChannelModel
{
    #[Serializer\Type("string")]
    #[Assert\NotBlank(groups: [Constraint::DEFAULT_GROUP])]
    public ?string $title = null;

    #[Serializer\Type("string")]
    #[Assert\NotBlank(groups: [Constraint::DEFAULT_GROUP])]
    public ?string $link = null;

    #[Serializer\Type("string")]
    #[Assert\NotBlank(groups: [Constraint::DEFAULT_GROUP])]
    public ?string $description = null;

    /**
     * @var FeedItemModelInterface[]
     */
    #[Serializer\Type("array<Lemisoft\SyliusProductFeedsPlugin\Model\ProductFeedGenerator\FacebookFeedItemModel>")]
    #[Serializer\XmlList(entry: "item", inline: true)]
    public array $item = [];

    /**
     * @param FeedItemModelInterface[] $items
     */
    public function __construct(array $items, ProductFeedInterface $productFeed)
    {
        $this->title = $productFeed->getShopName();
        $this->description = $productFeed->getShopDescription();
        $this->item = $items;
    }

    public function setLink(string $link): void
    {
        $this->link = $link;
    }
}
