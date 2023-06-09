<?php

declare(strict_types=1);

namespace Lemisoft\SyliusProductFeedsPlugin\Model\ProductFeedGenerator;

use JMS\Serializer\Annotation as Serializer;
use Lemisoft\SyliusProductFeedsPlugin\Entity\ProductFeed\ProductFeedInterface;
use Lemisoft\SyliusProductFeedsPlugin\Model\Facebook\GoogleAvailabilityType;
use Lemisoft\SyliusProductFeedsPlugin\Model\Facebook\GoogleProductConditionType;
use Lemisoft\SyliusProductFeedsPlugin\Model\FeedXmlNamespaceType;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints as Assert;

abstract class AbstractGoogleTypeFeedItemModel extends AbstractFeedItemModel implements FeedItemModelInterface
{
    public const EMPTY_LENGTH = 0;
    public const ONE_LENGTH = 1;
    public const DESCRIPTION_LENGTH = 5000;
    public const TITLE_MAX_LENGTH = 150;

    #[Serializer\Type("integer")]
    #[Serializer\XmlElement(cdata: false, namespace: FeedXmlNamespaceType::FACEBOOK->value)]
    #[Assert\NotBlank(groups: [Constraint::DEFAULT_GROUP])]
    public ?int $id = null;

    #[Serializer\Type("integer")]
    #[Serializer\XmlElement(cdata: false, namespace: FeedXmlNamespaceType::FACEBOOK->value)]
    #[Assert\NotBlank(groups: [Constraint::DEFAULT_GROUP])]
    public ?int $item_group_id = null;

    #[Serializer\Type("string")]
    #[Serializer\XmlElement(cdata: true, namespace: FeedXmlNamespaceType::FACEBOOK->value)]
    #[Assert\NotBlank(groups: [Constraint::DEFAULT_GROUP])]
    #[Assert\Length(max: self::TITLE_MAX_LENGTH, groups: [Constraint::DEFAULT_GROUP])]
    public ?string $title = null;

    #[Serializer\Type("string")]
    #[Serializer\XmlElement(cdata: false, namespace: FeedXmlNamespaceType::FACEBOOK->value)]
    #[Assert\NotBlank(groups: [Constraint::DEFAULT_GROUP])]
    public ?string $gtin = null;

    #[Serializer\Type("string")]
    #[Serializer\XmlElement(cdata: true, namespace: FeedXmlNamespaceType::FACEBOOK->value)]
    #[Assert\NotBlank(groups: [Constraint::DEFAULT_GROUP])]
    public ?string $description = null;

    #[Serializer\Type("string")]
    #[Serializer\XmlElement(cdata: false, namespace: FeedXmlNamespaceType::FACEBOOK->value)]
    #[Assert\NotBlank(groups: [Constraint::DEFAULT_GROUP])]
    public ?string $link = null;

    #[Serializer\Type("string")]
    #[Serializer\XmlElement(cdata: false, namespace: FeedXmlNamespaceType::FACEBOOK->value)]
    #[Assert\NotBlank(groups: [Constraint::DEFAULT_GROUP])]
    public ?string $condition = null;

    #[Serializer\Type("string")]
    #[Serializer\XmlElement(cdata: false, namespace: FeedXmlNamespaceType::FACEBOOK->value)]
    #[Assert\NotBlank(groups: [Constraint::DEFAULT_GROUP])]
    public ?string $availability = null;

    #[Serializer\Type("integer")]
    #[Serializer\XmlElement(cdata: false, namespace: FeedXmlNamespaceType::FACEBOOK->value)]
    public ?int $stockCount = null;

    #[Serializer\Type("string")]
    #[Serializer\XmlElement(cdata: false, namespace: FeedXmlNamespaceType::FACEBOOK->value)]
    #[Assert\NotBlank(groups: [Constraint::DEFAULT_GROUP])]
    public ?string $price = null;

    #[Serializer\Type("string")]
    #[Serializer\XmlElement(cdata: false, namespace: FeedXmlNamespaceType::FACEBOOK->value)]
    #[Assert\NotBlank(groups: [Constraint::DEFAULT_GROUP])]
    public ?string $image = null;

    /**
     * @var string[]
     */
    #[Serializer\Type("array")]
    #[Serializer\XmlList(entry: "additional_image_link", inline: true, namespace: FeedXmlNamespaceType::FACEBOOK->value)]
    #[Serializer\XmlElement(cdata: false)]
    public array $additional_image_link = [];

    public function fromVariant(
        ProductVariantInterface $variant,
        ProductFeedInterface $productFeed,
    ): FeedItemModelInterface {
        /** @var ProductInterface $product */
        $product = $variant->getProduct();
        /** @var int|null $productId */
        $productId = $product->getId();

        /** @var int|null $id */
        $id = $variant->getId();
        $this->id = $id;
        $this->item_group_id = count($product->getVariants()) > self::ONE_LENGTH ? $productId : null;
        $this->title = $this->getName($product, $variant, $productFeed);
        /** //$this->gtin = $variant->getEan(); */
        $this->gtin = null;
        $this->description = $this->getDescription($product, $variant);
        $this->condition = GoogleProductConditionType::NEW->value;
        $this->availability = $this->getAvailability($product, $variant);
        $this->stockCount = $this->getStockCount($variant);

        return $this;
    }

    public function addAdditionalImage(string $url): void
    {
        $this->additional_image_link[] = $url;
    }

    public function setProductLink(?string $link): void
    {
        $this->link = $link;
    }

    public function setPrice(?string $price): void
    {
        $this->price = $price;
    }

    public function setImage(?string $url): void
    {
        $this->image = $url;
    }

    protected function getAvailability(ProductInterface $product, ProductVariantInterface $variant): string
    {
        return !$variant->isTracked() || $variant->isInStock() ?
            GoogleAvailabilityType::IN_STOCK->value : GoogleAvailabilityType::OUT_OF_STOCK->value;
    }
}
