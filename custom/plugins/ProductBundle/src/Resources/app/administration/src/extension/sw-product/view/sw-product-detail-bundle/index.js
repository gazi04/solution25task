import template from './sw-product-detail-bundle.html.twig';

// We register a new component named 'sw-product-detail-bundle'
Shopware.Component.register('sw-product-detail-bundle', {
    template,

    props: {
        // Shopware automatically passes the 'product' object to this component
        product: {
            type: Object,
            required: true
        }
    },

    computed: {
        // A helper to easily access your custom bundle data
        productBundle() {
            // If the extension doesn't exist yet (new product), return an empty object
            return this.product.extensions.productBundle || {};
        },
        
        isLoading() {
            return false;
        }
    }
});
