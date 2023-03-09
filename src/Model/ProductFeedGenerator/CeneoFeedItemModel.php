<?php

declare(strict_types=1);

namespace Lemisoft\SyliusProductFeedsPlugin\Model\ProductFeedGenerator;

use JMS\Serializer\Annotation as Serializer;
use Lemisoft\SyliusProductFeedsPlugin\Entity\ProductFeed\ProductFeedInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints as Assert;

class CeneoFeedItemModel extends AbstractFeedItemModel implements FeedItemModelInterface
{
    #[Serializer\Type("integer")]
    #[Serializer\XmlAttribute()]
    #[Assert\NotBlank(groups: [Constraint::DEFAULT_GROUP])]
    public ?int $id = null;

    #[Serializer\Type("string")]
    #[Serializer\XmlAttribute()]
    #[Assert\NotBlank(groups: [Constraint::DEFAULT_GROUP])]
    public ?string $url = null;

    #[Serializer\Type("string")]
    #[Serializer\XmlAttribute()]
    #[Assert\NotBlank(groups: [Constraint::DEFAULT_GROUP])]
    public ?string $price = null;

    #[Serializer\Type("integer")]
    #[Serializer\XmlAttribute()]
    public ?int $stock = null;

    #[Serializer\Type("integer")]
    #[Serializer\XmlAttribute()]
    public ?int $avail = null;

    #[Serializer\Type("string")]
    #[Assert\NotBlank(groups: [Constraint::DEFAULT_GROUP])]
    public ?string $name = null;

    #[Serializer\Type("string")]
    #[Assert\NotBlank(groups: [Constraint::DEFAULT_GROUP])]
    public ?string $desc = null;

    #[Serializer\Type("Lemisoft\SyliusProductFeedsPlugin\Model\ProductFeedGenerator\CeneoFeedImagesModel")]
    #[Assert\NotBlank(groups: [Constraint::DEFAULT_GROUP])]
    #[Assert\Valid(groups: [Constraint::DEFAULT_GROUP])]
    public ?CeneoFeedImagesModel $imgs = null;

    public function fromVariant(ProductVariantInterface $variant, ProductFeedInterface $productFeed): CeneoFeedItemModel
    {
        /** @var ProductInterface $product */
        $product = $variant->getProduct();

        $this->id = $variant->getId();
        $this->stock = $this->getStockCount($variant);
        $this->name = $this->getName($product, $variant, $productFeed);
        $this->desc = $this->getDescription($product, $variant);

        return $this;
    }

    public function setProductLink(?string $link): void
    {
        $this->url = $link;
    }

    public function setPrice(?string $price): void
    {
        $this->price = $price;
    }

    public function setImage(string $url): void
    {
        $this->imgs = new CeneoFeedImagesModel();
        $this->imgs->setMainImage($url);
    }

    public function addAdditionalImage(string $url): void
    {
        $this->imgs?->addAdditionalImage($url);
    }
}
