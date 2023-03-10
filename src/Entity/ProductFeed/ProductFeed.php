<?php

declare(strict_types=1);

namespace Lemisoft\SyliusProductFeedsPlugin\Entity\ProductFeed;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Lemisoft\SyliusProductFeedsPlugin\Model\FeedStateType;
use Lemisoft\SyliusProductFeedsPlugin\Model\FeedType;
use Lemisoft\SyliusProductFeedsPlugin\Model\ProductNameModeType;
use Sylius\Component\Core\Model\Channel;
use Sylius\Component\Resource\Model\ResourceInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ORM\Table(name: 'lemisoft_product_feed')]
class ProductFeed implements ResourceInterface, ProductFeedInterface
{
    public const NAME_LENGTH = 50;

    #[Id]
    #[Column(type: "integer")]
    #[GeneratedValue()]
    private ?int $id = null;

    #[Column(
        name: "code",
        type: "string",
        unique: true,
        nullable: false,
    )]
    private ?string $code = null;

    #[Assert\NotBlank(groups: [Constraint::DEFAULT_GROUP])]
    #[Column(
        name: "feed_type",
        type: "feed_type",
        nullable: false,
    )]
    private FeedType $feedType = FeedType::GOOGLE;

    #[Assert\NotBlank(groups: [Constraint::DEFAULT_GROUP])]
    #[Column(
        name: "name",
        type: "string",
        length: self::NAME_LENGTH,
        nullable: false,
    )]
    private ?string $name = null;

    #[Assert\NotBlank(groups: [Constraint::DEFAULT_GROUP])]
    #[Column(
        name: "shop_name",
        type: "string",
        nullable: false,
    )]
    private ?string $shopName = null;

    #[Assert\NotBlank(groups: [Constraint::DEFAULT_GROUP])]
    #[Column(
        name: "shop_description",
        type: "string",
        nullable: false,
    )]
    private ?string $shopDescription = null;

    #[Assert\NotNull(groups: [Constraint::DEFAULT_GROUP])]
    #[Column(
        name: "is_https",
        type: "boolean",
        nullable: false,
        options: ["default" => false],
    )]
    private bool $isHttps = false;

    #[Assert\NotBlank(groups: [Constraint::DEFAULT_GROUP])]
    #[Column(
        name: "product_name_mode",
        type: "product_name_mode",
        nullable: false,
    )]
    private ProductNameModeType $productNameMode = ProductNameModeType::ONLY_PRODUCT_NAME;

    #[Column(
        name: "state",
        type: "feed_state",
        nullable: false,
    )]
    private FeedStateType $state = FeedStateType::NEW;

    #[Assert\NotNull(groups: [Constraint::DEFAULT_GROUP])]
    #[Column(
        name: "enabled",
        type: "boolean",
        nullable: false,
        options: ["default" => true],
    )]
    private bool $enabled = true;

    #[Assert\NotBlank(groups: [Constraint::DEFAULT_GROUP])]
    #[ORM\JoinColumn(
        name: "channel_id",
        referencedColumnName: 'id',
        nullable: false,
        onDelete: "CASCADE",
    )]
    #[ORM\ManyToOne(
        targetEntity: Channel::class,
        cascade: ["persist"],
    )]
    private ?Channel $channel = null;

    /**
     * @var Collection<int, ProductFeedError>
     */
    #[ORM\OneToMany(
        mappedBy: "productFeed",
        targetEntity: ProductFeedError::class,
        cascade: ["persist", "remove"],
    )]
    private Collection $productFeedErrors;

    public function __construct()
    {
        $this->productFeedErrors = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): void
    {
        $this->code = $code;
    }

    public function getFeedType(): FeedType
    {
        return $this->feedType;
    }

    public function setFeedType(FeedType $feedType): void
    {
        $this->feedType = $feedType;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getState(): FeedStateType
    {
        return $this->state;
    }

    public function setState(FeedStateType $state): void
    {
        $this->state = $state;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): void
    {
        $this->enabled = $enabled;
    }

    public function getChannel(): ?Channel
    {
        return $this->channel;
    }

    public function setChannel(?Channel $channel): void
    {
        $this->channel = $channel;
    }

    /**
     * @return Collection<int, ProductFeedError>
     */
    public function getProductFeedErrors(): Collection
    {
        return $this->productFeedErrors;
    }

    public function getShopName(): ?string
    {
        return $this->shopName;
    }

    public function setShopName(?string $shopName): void
    {
        $this->shopName = $shopName;
    }

    public function getShopDescription(): ?string
    {
        return $this->shopDescription;
    }

    public function setShopDescription(?string $shopDescription): void
    {
        $this->shopDescription = $shopDescription;
    }

    public function isHttps(): bool
    {
        return $this->isHttps;
    }

    public function setIsHttps(bool $isHttps): void
    {
        $this->isHttps = $isHttps;
    }

    public function getProductNameMode(): ProductNameModeType
    {
        return $this->productNameMode;
    }

    public function setProductNameMode(ProductNameModeType $productNameMode): void
    {
        $this->productNameMode = $productNameMode;
    }
}
