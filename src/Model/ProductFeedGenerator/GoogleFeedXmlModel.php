<?php

declare(strict_types=1);

namespace Lemisoft\SyliusProductFeedsPlugin\Model\ProductFeedGenerator;

use JMS\Serializer\Annotation as Serializer;
use Lemisoft\SyliusProductFeedsPlugin\Entity\ProductFeed\ProductFeedInterface;
use Lemisoft\SyliusProductFeedsPlugin\Model\FeedXmlNamespaceType;
use Lemisoft\SyliusProductFeedsPlugin\Model\FeedXmlPrefixType;

#[Serializer\XmlRoot("rss")]
#[Serializer\XmlNamespace(uri: FeedXmlNamespaceType::FACEBOOK->value, prefix: FeedXmlPrefixType::G->value)]
class GoogleFeedXmlModel
{
    #[Serializer\Type("Lemisoft\SyliusProductFeedsPlugin\Model\ProductFeedGenerator\GoogleFeedXmlChannelModel")]
    public GoogleFeedXmlChannelModel $channel;

    /**
     * @param FeedItemModelInterface[] $items
     */
    public function __construct(array $items, ProductFeedInterface $productFeed)
    {
        $this->channel = new GoogleFeedXmlChannelModel($items, $productFeed);
    }

    public function setShopLink(string $shopLink): void
    {
        $this->channel->setLink($shopLink);
    }
}
