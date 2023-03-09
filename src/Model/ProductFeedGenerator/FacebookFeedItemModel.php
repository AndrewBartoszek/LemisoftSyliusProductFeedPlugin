<?php

declare(strict_types=1);

namespace Lemisoft\SyliusProductFeedsPlugin\Model\ProductFeedGenerator;

use JMS\Serializer\Annotation as Serializer;
use Lemisoft\SyliusProductFeedsPlugin\Entity\ProductFeed\ProductFeedInterface;
use Lemisoft\SyliusProductFeedsPlugin\Model\Facebook\FacebookAvailabilityType;
use Lemisoft\SyliusProductFeedsPlugin\Model\Facebook\FacebookProductConditionType;
use Lemisoft\SyliusProductFeedsPlugin\Model\FeedXmlNamespaceType;
use Sylius\Component\Core\Model\Product;
use Sylius\Component\Core\Model\ProductVariantInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints as Assert;

#[Serializer\XmlRoot("item")]
final class FacebookFeedItemModel extends AbstractFeedItemModel implements FeedItemModelInterface
{
    const EMPTY_LENGTH = 0;
    const ONE_LENGTH = 1;
    const DESCRIPTION_LENGTH = 5000;
    const TITLE_MAX_LENGTH = 150;

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
    #[Serializer\XmlList(entry: "additional_image_link", inline: true, namespace: FeedXmlNamespaceType::FACEBOOK->value,)]
    #[Serializer\XmlElement(cdata: false)]
    public array $additional_image_link = [];

    public function fromVariant(
        ProductVariantInterface $variant,
        ProductFeedInterface $productFeed
    ): FacebookFeedItemModel {
        /** @var Product $product */
        $product = $variant->getProduct();

        $this->id = $variant->getId();
        $this->item_group_id = count($product->getVariants()) > self::ONE_LENGTH ? $product->getId() : null;
        $this->title = $this->getName($product, $variant, $productFeed);
        /** //$this->gtin = $variant->getEan(); */
        $this->gtin = null;
        $this->description = $this->getDescription($product, $variant);
        $this->condition = FacebookProductConditionType::NEW->value;
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

    public function setImage(?string $image): void
    {
        $this->image = $image;
    }

    protected function getAvailability(Product $product, ProductVariantInterface $variant): string
    {
        return !$variant->isTracked() || $variant->isInStock() ?
            FacebookAvailabilityType::IN_STOCK : FacebookAvailabilityType::OUT_OF_STOCK;
    }
}
