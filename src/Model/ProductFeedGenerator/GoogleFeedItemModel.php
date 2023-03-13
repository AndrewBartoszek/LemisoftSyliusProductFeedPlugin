<?php

declare(strict_types=1);

namespace Lemisoft\SyliusProductFeedsPlugin\Model\ProductFeedGenerator;

use JMS\Serializer\Annotation as Serializer;

#[Serializer\XmlRoot("item")]
class GoogleFeedItemModel extends AbstractGoogleTypeFeedItemModel
{
}
