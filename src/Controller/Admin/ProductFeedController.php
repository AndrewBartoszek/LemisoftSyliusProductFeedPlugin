<?php

declare(strict_types=1);

namespace Lemisoft\SyliusProductFeedsPlugin\Controller\Admin;

use Lemisoft\SyliusProductFeedsPlugin\Model\FeedStateType;
use Lemisoft\SyliusProductFeedsPlugin\Service\ProductFeed\ProductFeedService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class ProductFeedController extends AbstractController
{
    public function __construct(protected ProductFeedService $productFeedService)
    {
    }

    public function generateAction(int $id): RedirectResponse
    {
        $entity = $this->productFeedService->findProductFeedToGenerate($id);
        if (null === $entity) {
            throw new NotFoundHttpException('Feed nie istnieje');
        }

        $this->productFeedService->generate($entity);
        if (FeedStateType::READY === $entity->getState()) {
            $this->addFlash('success', 'Wygenerowano feed');
        } else {
            $this->addFlash('error', 'Wystąpił błąd podczas generowania feed-a');
        }

        return $this->redirectToRoute(
            'lemisoft_sylius_product_feeds_plugin_admin_product_feed_show',
            ['id' => $entity->getId()],
        );
    }

    public function getXmlAction(string $code): BinaryFileResponse
    {
        $entity = $this->productFeedService->findProductFeedByCode($code);
        if (null === $entity) {
            throw new NotFoundHttpException('Feed nie istnieje');
        }

        $available = $this->productFeedService->getAvailableProductFeeds();

        if (!in_array($entity->getFeedType(), $available, true) || !$entity->isEnabled()) {
            throw new NotFoundHttpException('Feed nie istnieje');
        }

        $file = $this->productFeedService->getXmlFile($entity);

        $response = new BinaryFileResponse($file->getPathname());
        $response->headers->set('Content-Type', 'text/xml');

        return $response;
    }
}
