<?php declare(strict_types=1);

namespace ProductBundle\Storefront\Subscriber;

use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Storefront\Page\Product\ProductPageLoadedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Psr\Log\LoggerInterface;

class ProductPageSubscriber implements EventSubscriberInterface
{
    private EntityRepository $productBundleRepository;
    private LoggerInterface $logger;

    public function __construct(EntityRepository $productBundleRepository, LoggerInterface $logger)
    {
        $this->productBundleRepository = $productBundleRepository;
        $this->logger = $logger;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ProductPageLoadedEvent::class => 'onProductPageLoaded',
        ];
    }

    public function onProductPageLoaded(ProductPageLoadedEvent $event): void
    {
        try {
            // 🚨 DEBUG: Log that subscriber is executing
            $product = $event->getPage()->getProduct();
            $productId = $product->getId();
            
            $this->logger->critical('--- ProductBundle Subscriber HIT for product: ' . $productId);

            // 2. Build search criteria for the ProductBundle entity
            $criteria = new Criteria();
            $criteria->addFilter(new EqualsFilter('productId', $productId));
            // IMPORTANT: Load associations needed for the display
            $criteria->addAssociation('assignedProducts');
            $criteria->addAssociation('assignedProducts.product');
            $criteria->addAssociation('assignedProducts.product.translated');
            $criteria->addAssociation('translations');

            // 3. Search for the bundle
            $bundleResult = $this->productBundleRepository->search($criteria, $event->getContext());

            $this->logger->critical('--- ProductBundle search result count: ' . $bundleResult->count());

            // 4. Attach the bundle entity to the Page object's extensions (not product extensions)
            if ($bundleResult->count() > 0) {
                $bundle = $bundleResult->first();

                // Add to page extensions so it's accessible in Twig as page.extensions.productBundle
                $event->getPage()->addExtension('productBundle', $bundle);
                
                $assignedCount = $bundle->getAssignedProducts() ? $bundle->getAssignedProducts()->count() : 0;
                $this->logger->critical('--- ProductBundle found and added to page extensions. Assigned products: ' . $assignedCount);
            } else {
                $this->logger->critical('--- No ProductBundle found for product: ' . $productId);
            }
        } catch (\Throwable $e) {
            $this->logger->error('--- ProductBundle Subscriber ERROR: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}
