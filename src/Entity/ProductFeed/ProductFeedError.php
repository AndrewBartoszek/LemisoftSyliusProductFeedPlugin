<?php

declare(strict_types=1);

namespace Lemisoft\SyliusProductFeedsPlugin\Entity\ProductFeed;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Sylius\Component\Resource\Model\ResourceInterface;

#[ORM\Entity]
#[ORM\Table(name: 'lemisoft_product_feed_error')]
class ProductFeedError implements ResourceInterface, ProductFeedErrorInterface
{
    #[Id]
    #[Column(type: "integer")]
    #[GeneratedValue()]
    private ?int $id = null;

    #[Column(
        name: "message",
        type: "string",
    )]
    private ?string $message = null;

    #[Column(
        name: "data",
        type: "text",
    )]
    private ?string $data = null;

    #[ORM\JoinColumn(
        name: "product_feed_id",
        referencedColumnName: 'id',
        nullable: false,
        onDelete: "CASCADE",
    )]
    #[ORM\ManyToOne(
        targetEntity: ProductFeed::class,
        cascade: ["persist"],
    )]
    private ?ProductFeedInterface $productFeed = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): void
    {
        $this->message = $message;
    }

    public function getData(): ?string
    {
        return $this->data;
    }

    public function setData(?string $data): void
    {
        $this->data = $data;
    }

    public function getProductFeed(): ?ProductFeedInterface
    {
        return $this->productFeed;
    }

    public function setProductFeed(?ProductFeedInterface $productFeed): void
    {
        $this->productFeed = $productFeed;
    }
}
