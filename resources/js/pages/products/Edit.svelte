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
            {
                title: 'Edit Produk',
                href: '#',
            },
        ],
    };
</script>

<script lang="ts">
    import { Link, useForm } from '@inertiajs/svelte';
    import ProductController from '@/actions/App/Http/Controllers/ProductController';
    import AppHead from '@/components/AppHead.svelte';
    import { Button } from '@/components/ui/button';
    import {
        Card,
        CardContent,
        CardDescription,
        CardHeader,
        CardTitle,
    } from '@/components/ui/card';
    import { toUrl } from '@/lib/utils';
    import {
        type ProductCategory,
        ProductFormFields,
        type ProductItem,
    } from '@/modules/products';
    import ArrowLeft from '@lucide/svelte/icons/arrow-left';

    let {
        product,
        categories = [],
    }: {
        product: ProductItem;
        categories?: ProductCategory[];
    } = $props();

    const form = useForm(() => ({
        name: product.name,
        category_id: product.category_id,
        price: product.price,
        stock: product.stock,
        description: product.description ?? '',
    }));

    function submit(e: SubmitEvent): void {
        e.preventDefault();
        form.put(ProductController.update.url(product.id));
    }
</script>

<AppHead title={`Edit Produk: ${product.name}`} />

<div class="flex h-full flex-1 flex-col gap-6 overflow-x-auto p-4 md:p-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-foreground md:text-3xl">
                Edit Produk
            </h1>
            <p class="text-sm text-muted-foreground">
                Perbarui informasi produk <span class="font-medium text-foreground">{product.name}</span>.
            </p>
        </div>
        <Button asChild variant="outline" class="gap-1.5">
            {#snippet children(props)}
                <Link href={toUrl(productsIndex())} class={props.class}>
                    <ArrowLeft class="size-4" />
                    Kembali
                </Link>
            {/snippet}
        </Button>
    </div>

    <Card class="max-w-2xl border-sidebar-border/70 shadow-sm dark:border-sidebar-border">
        <CardHeader>
            <CardTitle>Informasi Produk</CardTitle>
            <CardDescription>
                Ubah detail produk di bawah ini. Kolom bertanda * wajib diisi.
            </CardDescription>
        </CardHeader>
        <CardContent>
            <form onsubmit={submit} class="space-y-5">
                <ProductFormFields
                    {categories}
                    errors={form.errors}
                    bind:name={form.name}
                    bind:categoryId={form.category_id}
                    bind:price={form.price}
                    bind:stock={form.stock}
                    bind:description={form.description}
                />

                <div class="flex items-center justify-end gap-3 pt-2">
                    <Button asChild variant="secondary">
                        {#snippet children(props)}
                            <Link href={toUrl(productsIndex())} class={props.class}>
                                Batal
                            </Link>
                        {/snippet}
                    </Button>
                    <Button type="submit" disabled={form.processing}>
                        {form.processing ? 'Menyimpan...' : 'Perbarui Produk'}
                    </Button>
                </div>
            </form>
        </CardContent>
    </Card>
</div>
