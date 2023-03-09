<?php

declare(strict_types=1);

namespace Lemisoft\SyliusProductFeedsPlugin\Model\ProductFeedGenerator;

use JMS\Serializer\Annotation as Serializer;
use Lemisoft\SyliusProductFeedsPlugin\Model\FeedXmlNamespaceType;
use Lemisoft\SyliusProductFeedsPlugin\Model\FeedXmlPrefixType;

#[Serializer\XmlRoot("offers")]
#[Serializer\XmlNamespace(uri: FeedXmlNamespaceType::CENEO->value, prefix: FeedXmlPrefixType::XSI->value)]
class CeneoFeedXmlModel
{
    #[Serializer\Type("array<Lemisoft\SyliusProductFeedsPlugin\Model\ProductFeedGenerator\CeneoFeedItemModel>")]
    #[Serializer\XmlList(entry: "o", inline: true)]
    public array $item = [];

    /**
     * @param CeneoFeedItemModel[] $items
     */
    public function __construct(array $items)
    {
        $this->item = $items;
    }
}
