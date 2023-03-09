<?php

declare(strict_types=1);

namespace Lemisoft\SyliusProductFeedsPlugin\Model\ProductFeedGenerator;

use JMS\Serializer\Annotation as Serializer;
use Lemisoft\SyliusProductFeedsPlugin\Entity\ProductFeed\ProductFeedInterface;
use Lemisoft\SyliusProductFeedsPlugin\Model\FeedXmlNamespaceType;
use Lemisoft\SyliusProductFeedsPlugin\Model\FeedXmlPrefixType;

#[Serializer\XmlRoot("rss")]
#[Serializer\XmlNamespace(uri: FeedXmlNamespaceType::FACEBOOK->value, prefix: FeedXmlPrefixType::G->value)]
class FacebookFeedXmlModel
{
    #[Serializer\Type("Lemisoft\SyliusProductFeedsPlugin\Model\ProductFeedGenerator\FacebookFeedXmlChannelModel")]
    public FacebookFeedXmlChannelModel $channel;

    /**
     * @param FacebookFeedItemModel[] $items
     */
    public function __construct(array $items, ProductFeedInterface $productFeed)
    {
        $this->channel = new FacebookFeedXmlChannelModel($items, $productFeed);
    }

    public function setShopLink(string $shopLink): void
    {
        $this->channel->setLink($shopLink);
    }
}
