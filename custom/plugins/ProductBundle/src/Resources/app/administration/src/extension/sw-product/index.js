// Import the files we just created
import './page/sw-product-detail';
import './view/sw-product-detail-bundle';

// We extend the "sw-product" module to add a child route
Shopware.Module.register('sw-product-bundle-route', {
  routeMiddleware(next, currentRoute) {
    // If the user is on the Product Detail page...
    if (currentRoute.name === 'sw.product.detail') {
      // ...add a new child route for our Bundle tab
      currentRoute.children.push({
        name: 'sw.product.detail.bundle',
        path: '/sw/product/detail/:id/bundle', // The URL path
        component: 'sw-product-detail-bundle', // The component we created in Step 2
        meta: {
          parentPath: 'sw.product.index' // Back button goes to product list
        }
      });
    }
    next(currentRoute);
  }
});
