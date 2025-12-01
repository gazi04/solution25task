import template from './sw-product-detail-bundle.html.twig';

Shopware.Component.register('sw-product-detail-bundle', {
  template,

  inject: ['repositoryFactory'],

  mixins: [
    Shopware.Mixin.getByName('notification'),
  ],

  data() {
    return {
      isLoading: false,
      bundle: null,
      assignedProducts: [],
      newProductId: null,
      newQuantity: 1,
    };
  },

  computed: {
    product() {
      // Try to access product from parent component (sw-product-detail)
      let current = this.$parent;
      while (current) {
        if (current.product) {
          return current.product;
        }
        current = current.$parent;
      }
      // Fallback: try to get from route params and load it
      if (this.$route && this.$route.params && this.$route.params.id) {
        // Return a placeholder object with the ID so we can load it
        return { id: this.$route.params.id };
      }
      return null;
    },

    productRepository() {
      return this.repositoryFactory.create('product');
    },

    bundleRepository() {
      return this.repositoryFactory.create('product_bundle');
    },

    assignedProductsRepository() {
      return this.repositoryFactory.create('product_bundle_assigned_products');
    },

    productBundle() {
      return this.bundle;
    },

    hasBundle() {
      return this.bundle !== null && this.bundle.id !== null;
    },

    productCriteria() {
      const criteria = new Shopware.Data.Criteria();
      if (this.product && this.product.id) {
        criteria.addFilter(
          Shopware.Data.Criteria.not('and', [
            Shopware.Data.Criteria.equals('id', this.product.id)
          ])
        );
      }
      return criteria;
    },

    assignedProductsColumns() {
      return [
        {
          property: 'product',
          label: 'Product',
          rawData: true,
        },
        {
          property: 'quantity',
          label: 'Quantity',
          inlineEdit: 'number',
        }
      ];
    }
  },

  created() {
    // Initialize bundle immediately so form can be shown
    this.bundle = this.bundleRepository.create(Shopware.Context.api);
    this.assignedProducts = [];
    this.createdComponent();
  },

  mounted() {
    // Try to load bundle when component is mounted
    this.$nextTick(() => {
      if (this.product && this.product.id) {
        this.bundle.productId = this.product.id;
        this.loadBundle();
      }
    });
  },

  watch: {
    product: {
      immediate: true,
      handler(newProduct) {
        if (newProduct && newProduct.id) {
          if (this.bundle) {
            this.bundle.productId = newProduct.id;
          }
          this.loadBundle();
        }
      }
    },
    '$route.params.id': {
      handler() {
        // Reload bundle when route changes (switching products)
        if (this.product && this.product.id) {
          if (this.bundle) {
            this.bundle.productId = this.product.id;
          }
          this.loadBundle();
        }
      }
    }
  },

  methods: {
    createdComponent() {
      // Wait for parent component to be ready
      this.$nextTick(() => {
        if (this.product && this.product.id) {
          this.bundle.productId = this.product.id;
          this.loadBundle();
        } else {
          // Try again after a short delay
          setTimeout(() => {
            if (this.product && this.product.id) {
              this.bundle.productId = this.product.id;
              this.loadBundle();
            }
          }, 500);
        }
      });
    },

    async loadBundle() {
      if (!this.product || !this.product.id) {
        // Initialize empty bundle so form can be shown
        if (!this.bundle) {
          this.bundle = this.bundleRepository.create(Shopware.Context.api);
          this.bundle.productId = this.product?.id || null;
          this.assignedProducts = [];
        }
        return;
      }

      this.isLoading = true;

      try {
        const criteria = new Shopware.Data.Criteria();
        criteria.addFilter(
          Shopware.Data.Criteria.equals('productId', this.product.id)
        );
        criteria.addAssociation('assignedProducts.product');
        criteria.addAssociation('translations');

        const result = await this.bundleRepository.search(criteria, Shopware.Context.api);

        if (result.total > 0) {
          this.bundle = result.first();
          this.assignedProducts = this.bundle.assignedProducts || [];
        } else {
          // Create new bundle entity in memory
          this.bundle = this.bundleRepository.create(Shopware.Context.api);
          this.bundle.productId = this.product.id;
          this.assignedProducts = [];
        }
      } catch (error) {
        this.createNotificationError({
          message: this.$t('product-bundle.detail.error.load'),
        });
        console.error('Error loading bundle:', error);
        // Even on error, create empty bundle so form can be shown
        if (!this.bundle) {
          this.bundle = this.bundleRepository.create(Shopware.Context.api);
          this.bundle.productId = this.product.id;
          this.assignedProducts = [];
        }
      } finally {
        this.isLoading = false;
      }
    },

    async saveBundle() {
      if (!this.bundle || !this.product || !this.product.id) {
        return;
      }

      this.isLoading = true;

      try {
        // Ensure productId is set
        this.bundle.productId = this.product.id;

        // Save the bundle
        await this.bundleRepository.save(this.bundle, Shopware.Context.api);

        // Reload to get the ID if it was a new bundle
        await this.loadBundle();

        this.createNotificationSuccess({
          message: this.$t('product-bundle.detail.success.save'),
        });
      } catch (error) {
        this.createNotificationError({
          message: this.$t('product-bundle.detail.error.save'),
        });
        console.error('Error saving bundle:', error);
      } finally {
        this.isLoading = false;
      }
    },

    async addProduct() {
      if (!this.newProductId || !this.bundle) {
        return;
      }

      // Check if product is already in the bundle
      const existing = this.assignedProducts.find(
        item => item.productId === this.newProductId
      );

      if (existing) {
        this.createNotificationWarning({
          message: this.$t('product-bundle.detail.warning.productExists'),
        });
        return;
      }

      // Create new assigned product
      const assignedProduct = this.assignedProductsRepository.create(Shopware.Context.api);
      assignedProduct.productBundleId = this.bundle.id || null;
      assignedProduct.productId = this.newProductId;
      assignedProduct.quantity = this.newQuantity;

      this.assignedProducts.push(assignedProduct);
      this.newProductId = null;
      this.newQuantity = 1;
    },

    async removeProduct(assignedProduct) {
      const index = this.assignedProducts.indexOf(assignedProduct);
      if (index > -1) {
        this.assignedProducts.splice(index, 1);
      }
    },

    onInlineEditSave(item) {
      // Quantity is already updated in the item object
      // Just ensure it's a valid number
      if (item.quantity && item.quantity < 1) {
        item.quantity = 1;
      }
    },

    onInlineEditCancel(item) {
      // Reset to original value if needed
      // For now, we'll just reload the bundle
      this.loadBundle();
    },

    async saveAll() {
      if (!this.bundle || !this.product || !this.product.id) {
        return;
      }

      this.isLoading = true;

      try {
        console.log("The bundle product id is: ", this.product.id)
        console.log("The bundle bundle title is: ", this.bundle.title)
        this.bundle.productId = this.product.id;
        this.bundle.title = this.bundle.title || null;
        await this.bundleRepository.save(this.bundle, Shopware.Context.api);

        // Reload to get the bundle ID
        await this.loadBundle();

        if (!this.bundle || !this.bundle.id) {
          throw new Error('Failed to get bundle ID after save');
        }

        // Now save assigned products
        if (this.assignedProducts.length > 0) {
          const productsToSave = this.assignedProducts.map(item => {
            return {
              id: item.id || Shopware.Utils.createId(),
              productBundleId: this.bundle.id,
              productId: item.productId,
              quantity: parseInt(item.quantity) || 1,
            };
          });

          // Get existing assigned products to delete ones that were removed
          const criteria = new Shopware.Data.Criteria();
          criteria.addFilter(
            Shopware.Data.Criteria.equals('productBundleId', this.bundle.id)
          );
          const existing = await this.assignedProductsRepository.search(criteria, Shopware.Context.api);

          const existingIds = existing.map(item => item.id);
          const newIds = productsToSave.map(item => item.id);
          const toDelete = existingIds.filter(id => !newIds.includes(id));

          // Delete removed products
          if (toDelete.length > 0) {
            await this.assignedProductsRepository.delete(toDelete, Shopware.Context.api);
          }

          // Save/update assigned products
          await this.assignedProductsRepository.upsert(productsToSave, Shopware.Context.api);
        } else {
          // If no products, delete all existing ones
          const criteria = new Shopware.Data.Criteria();
          criteria.addFilter(
            Shopware.Data.Criteria.equals('productBundleId', this.bundle.id)
          );
          const existing = await this.assignedProductsRepository.search(criteria, Shopware.Context.api);
          if (existing.total > 0) {
            await this.assignedProductsRepository.delete(existing.getIds(), Shopware.Context.api);
          }
        }

        // Reload bundle with all associations
        await this.loadBundle();

        this.createNotificationSuccess({
          message: this.$t('product-bundle.detail.success.save'),
        });
      } catch (error) {
        this.createNotificationError({
          message: this.$t('product-bundle.detail.error.save'),
        });
        console.error('Error saving bundle:', error);
      } finally {
        this.isLoading = false;
      }
    }
  }
});
