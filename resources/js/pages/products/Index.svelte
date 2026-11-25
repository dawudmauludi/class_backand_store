<script module lang="ts">
    import { dashboard } from '@/routes';
    import { index as productsIndex } from '@/routes/products';

    export const layout = {
        breadcrumbs: [
            {
                title: 'Dashboard',
                href: dashboard(),
            },
            {
                title: 'Produk',
                href: productsIndex(),
            },
        ],
    };
</script>

<script lang="ts">
    import { Link } from '@inertiajs/svelte';
    import AppHead from '@/components/AppHead.svelte';
    import { Button } from '@/components/ui/button';
    import { toUrl } from '@/lib/utils';
    import {
        type ProductCategory,
        ProductDeleteDialog,
        ProductDetailDialog,
        ProductFilterBar,
        type ProductItem,
        ProductTable,
    } from '@/modules/products';
    import { create as productsCreate } from '@/routes/products';
    import Plus from '@lucide/svelte/icons/plus';

    let {
        products = [],
        categories = [],
    }: {
        products?: ProductItem[];
        categories?: ProductCategory[];
    } = $props();

    let searchQuery = $state('');
    let selectedCategory = $state<string>('all');
    let detailModalOpen = $state(false);
    let selectedProduct = $state<ProductItem | null>(null);
    let deleteModalOpen = $state(false);
    let productToDelete = $state<ProductItem | null>(null);

    let filteredProducts = $derived(
        products.filter((product) => {
            const matchCategory =
                selectedCategory === 'all' || product.category_id === selectedCategory;
            const query = searchQuery.toLowerCase().trim();
            const matchQuery =
                !query ||
                product.name.toLowerCase().includes(query) ||
                (product.description && product.description.toLowerCase().includes(query)) ||
                (product.category && product.category.name.toLowerCase().includes(query));

            return matchCategory && matchQuery;
        }),
    );

    function openDetailModal(product: ProductItem): void {
        selectedProduct = product;
        detailModalOpen = true;
    }

    function openDeleteModal(product: ProductItem): void {
        productToDelete = product;
        deleteModalOpen = true;
    }

    function resetFilters(): void {
        searchQuery = '';
        selectedCategory = 'all';
    }
</script>

<AppHead title="Kelola Produk" />

<div class="flex h-full flex-1 flex-col gap-6 overflow-x-auto p-4 md:p-6">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-foreground md:text-3xl">
                Kelola Produk
            </h1>
            <p class="text-sm text-muted-foreground">
                Tambah, ubah, dan hapus data produk serta stok inventaris Anda.
            </p>
        </div>
        <Button asChild class="gap-1.5">
            {#snippet children(props)}
                <Link href={toUrl(productsCreate())} class={props.class}>
                    <Plus class="size-4" />
                    Tambah Produk
                </Link>
            {/snippet}
        </Button>
    </div>

    <ProductFilterBar
        bind:searchQuery
        bind:selectedCategory
        {categories}
        totalProducts={products.length}
    />

    <ProductTable
        products={filteredProducts}
        totalCount={products.length}
        hasActiveFilter={Boolean(searchQuery) || selectedCategory !== 'all'}
        onViewDetail={openDetailModal}
        onDelete={openDeleteModal}
        onResetFilters={resetFilters}
    />
</div>

<ProductDetailDialog bind:open={detailModalOpen} product={selectedProduct} />
<ProductDeleteDialog bind:open={deleteModalOpen} bind:product={productToDelete} />
