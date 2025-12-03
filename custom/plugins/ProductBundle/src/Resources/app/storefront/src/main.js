// Import all necessary Storefront plugins
import ExamplePlugin from './example-plugin/example-plugin.plugin';
import ProductBundleCartPlugin from './product-bundle-cart.plugin';

// Register your plugin via the existing PluginManager
const PluginManager = window.PluginManager;

PluginManager.register('ExamplePlugin', ExamplePlugin, '[data-example-plugin]');
PluginManager.register('ProductBundleCart', ProductBundleCartPlugin, '[data-product-bundle-cart]');
