import Plugin from 'src/plugin-system/plugin.class';
import HttpClient from 'src/service/http-client.service';

export default class ProductBundleCartPlugin extends Plugin {
    static options = {
        bundleProductsSelector: '[data-bundle-products]',
    };

    init() {
        this._client = new HttpClient();
        
        // Get bundle products data from the template
        const bundleProductsData = document.querySelector(this.options.bundleProductsSelector);
        if (!bundleProductsData) {
            return;
        }

        try {
            this.bundleProducts = JSON.parse(bundleProductsData.textContent);
        } catch (e) {
            console.error('Failed to parse bundle products data', e);
            return;
        }

        // Find the buy form and intercept its submission
        const buyForm = document.querySelector('form[action*="checkout/line-item/add"]');
        if (!buyForm) {
            return;
        }

        // Intercept form submission
        buyForm.addEventListener('submit', this._onFormSubmit.bind(this));
    }

    _onFormSubmit(event) {
        // Only intercept if we have bundle products
        if (!this.bundleProducts || this.bundleProducts.length === 0) {
            return; // Let default behavior proceed
        }

        event.preventDefault();
        event.stopPropagation();

        // Get the main product data from form
        const formData = new FormData(event.target);
        const mainProductId = formData.get('lineItems[][id]') || formData.get('id');
        const mainQuantity = formData.get('lineItems[][quantity]') || formData.get('quantity') || 1;

        // Prepare line items for cart - start with main product
        const lineItems = [];

        // Add main product first
        if (mainProductId) {
            lineItems.push({
                id: mainProductId,
                referencedId: mainProductId,
                type: 'product',
                quantity: parseInt(mainQuantity, 10),
            });
        }

        // Add bundle products
        this.bundleProducts.forEach(bundleProduct => {
            if (bundleProduct.productId && bundleProduct.quantity) {
                lineItems.push({
                    id: bundleProduct.productId,
                    referencedId: bundleProduct.productId,
                    type: 'product',
                    quantity: parseInt(bundleProduct.quantity, 10),
                });
            }
        });

        // Add all items to cart
        this._addToCart(lineItems, event.target.action);
    }

    _addToCart(lineItems, formAction) {
        const url = formAction || window.router['frontend.checkout.line-item.add'];
        
        if (!url) {
            console.error('Cart add URL not found');
            return;
        }

        const payload = {
            items: lineItems,
        };

        this._client.post(url, JSON.stringify(payload), (response) => {
            // Trigger cart refresh event
            const event = new CustomEvent('cart-refresh', { bubbles: true });
            document.dispatchEvent(event);
            
            // Reload the page to show updated cart
            window.location.reload();
        }, 'application/json', false);
    }
}

