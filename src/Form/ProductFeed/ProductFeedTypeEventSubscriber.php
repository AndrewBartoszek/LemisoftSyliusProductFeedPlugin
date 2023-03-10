<?php

declare(strict_types=1);

namespace Lemisoft\SyliusProductFeedsPlugin\Form\ProductFeed;

use Lemisoft\SyliusProductFeedsPlugin\Entity\ProductFeed\ProductFeedInterface;
use Lemisoft\SyliusProductFeedsPlugin\Model\FeedType;
use Ramsey\Uuid\Uuid;
use Sylius\Component\Core\Model\ChannelInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

final class ProductFeedTypeEventSubscriber implements EventSubscriberInterface
{
    public function __construct(private FeedType $oldType, private ?ChannelInterface $oldChannel)
    {
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::PRE_SUBMIT => 'preSubmit',
        ];
    }

    public function preSubmit(FormEvent $event): void
    {
        $form = $event->getForm();
        /** @var ProductFeedInterface $entity */
        $entity = $form->getData();
        /** @var array $eventData */
        $eventData = $event->getData();

        if (null === $entity->getId()) {
            $entity->setCode(Uuid::uuid4()->toString());
        } else {
            //przy edycji nie pozwalamy na zmiane typu i kanalu
            $eventData['feedType'] = $this->oldType->value;
            $eventData['channel'] = $this->oldChannel?->getCode();
        }

        $form->setData($entity);
        $event->setData($eventData);
    }
}
