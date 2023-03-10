<?php

declare(strict_types=1);

namespace Lemisoft\SyliusProductFeedsPlugin\Form\ProductFeed;

use Exception;
use Lemisoft\SyliusProductFeedsPlugin\Entity\ProductFeed\ProductFeedInterface;
use Lemisoft\SyliusProductFeedsPlugin\Model\FeedType;
use Lemisoft\SyliusProductFeedsPlugin\Model\ProductNameModeType;
use Lemisoft\SyliusProductFeedsPlugin\Service\ProductFeed\AvailableProductFeedTypeService;
use Sylius\Bundle\ChannelBundle\Form\Type\ChannelChoiceType;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

final class ProductFeedType extends AbstractResourceType
{
    /**
     * @param string[] $validationGroups
     */
    public function __construct(
        private AvailableProductFeedTypeService $availableProductFeedTypeService,
        protected string $dataClass,
        protected array $validationGroups = [],
    ) {
        parent::__construct($dataClass, $validationGroups);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if (!$this->availableProductFeedTypeService->hasAvailable()) {
            throw new Exception('Nie można zarzadzać feedami. Funkcjonalność nie jest dostępna');
        }

        /** @var ProductFeedInterface $object */
        $object = $options['data'];
        $oldType = $object->getFeedType();

        $oldChannel = $object->getChannel();

        $builder
            ->add('name', TextType::class, [
                'required'    => true,
                'label'       => 'lemisoft_sylius_product_feeds_plugin.form.product_feed.name',
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('shopName', TextType::class, [
                'required'    => true,
                'label'       => 'lemisoft_sylius_product_feeds_plugin.form.product_feed.shop_name',
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('shopDescription', TextareaType::class, [
                'required'    => true,
                'label'       => 'lemisoft_sylius_product_feeds_plugin.form.product_feed.shop_description',
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('feedType', ChoiceType::class, [
                'choices'     => $this->availableProductFeedTypeService->getAvailableToFormSelect(),
                'required'    => true,
                'label'       => 'lemisoft_sylius_product_feeds_plugin.form.product_feed.type',
                'constraints' => [
                    new NotBlank(),
                    new Callback([$this, 'validateFeedType']),
                ],
                'attr'        => ['disabled' => null !== $object->getId()],
            ])
            ->add('productNameMode', ChoiceType::class, [
                'choices'     => ProductNameModeType::getAvailableToFormSelect(),
                'required'    => true,
                'label'       => 'lemisoft_sylius_product_feeds_plugin.form.product_feed.product_name_mode',
                'constraints' => [
                    new NotBlank(),
                    new Callback([$this, 'validateProductNameMode']),
                ],
            ])
            ->add('enabled', CheckboxType::class, [
                'required'    => true,
                'label'       => 'lemisoft_sylius_product_feeds_plugin.form.product_feed.enabled',
                'constraints' => [
                    new NotNull(),
                ],
            ])
            ->add('isHttps', CheckboxType::class, [
                'required'    => true,
                'label'       => 'lemisoft_sylius_product_feeds_plugin.form.product_feed.is_https',
                'constraints' => [
                    new NotNull(),
                ],
            ])
            ->add('channel', ChannelChoiceType::class, [
                'required'    => true,
                'label'       => 'lemisoft_sylius_product_feeds_plugin.form.product_feed.channel',
                'constraints' => [
                    new NotBlank(),
                ],
                'attr'        => ['disabled' => null !== $object->getId()],
            ])
            ->addEventSubscriber(new ProductFeedTypeEventSubscriber($oldType, $oldChannel));

        $builder
            ->get('feedType')
            ->addModelTransformer(
                new CallbackTransformer(
                    static fn (?FeedType $data): ?string => $data?->value,
                    static fn (?string $data): ?FeedType => null === $data ? null : FeedType::tryFrom($data),
                ),
            );
        $builder
            ->get('productNameMode')
            ->addModelTransformer(
                new CallbackTransformer(
                    static fn (?ProductNameModeType $data): ?string => $data?->value,
                    static fn (?string $data): ?ProductNameModeType => null === $data ? null : ProductNameModeType::tryFrom($data),
                ),
            );
    }

    public function validateFeedType(?FeedType $value, ExecutionContextInterface $context): void
    {
        if (null === $value) {
            return;
        }

        if (!in_array($value, $this->availableProductFeedTypeService->getAvailableProductFeeds(), true)) {
            $context
                ->buildViolation('lemisoft_sylius_product_feeds_plugin.form.product_feed.inaccessible_type')
                ->addViolation();
        }
    }

    public function validateProductNameMode(?ProductNameModeType $value, ExecutionContextInterface $context): void
    {
        if (null === $value) {
            return;
        }

        if (!in_array($value, ProductNameModeType::cases(), true)) {
            $context
                ->buildViolation(
                    'lemisoft_sylius_product_feeds_plugin.form.product_feed.inaccessible_product_name_mode_type',
                )
                ->addViolation();
        }
    }

    public function getBlockPrefix(): string
    {
        return 'lemisoft_sylius_product_feeds_product_feed_form';
    }
}
