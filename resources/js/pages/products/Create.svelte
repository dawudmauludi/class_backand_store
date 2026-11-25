<script module lang="ts">
    import { dashboard } from '@/routes';
    import { create as productsCreate, index as productsIndex } from '@/routes/products';

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
                title: 'Tambah Produk',
                href: productsCreate(),
            },
        ],
    };
</script>

<script lang="ts">
    import { Form, Link } from '@inertiajs/svelte';
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
    import { type ProductCategory, ProductFormFields } from '@/modules/products';
    import ArrowLeft from '@lucide/svelte/icons/arrow-left';

    let {
        categories = [],
    }: {
        categories?: ProductCategory[];
    } = $props();
</script>

<AppHead title="Tambah Produk" />

<div class="flex h-full flex-1 flex-col gap-6 overflow-x-auto p-4 md:p-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-foreground md:text-3xl">
                Tambah Produk
            </h1>
            <p class="text-sm text-muted-foreground">
                Tambahkan produk baru ke dalam katalog inventaris.
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
                Lengkapi detail produk di bawah ini. Kolom bertanda * wajib diisi.
            </CardDescription>
        </CardHeader>
        <CardContent>
            <Form {...ProductController.store.form()} class="space-y-5">
                {#snippet children({ errors, processing })}
                    <ProductFormFields {categories} {errors} />

                    <div class="flex items-center justify-end gap-3 pt-2">
                        <Button asChild variant="secondary">
                            {#snippet children(props)}
                                <Link href={toUrl(productsIndex())} class={props.class}>
                                    Batal
                                </Link>
                            {/snippet}
                        </Button>
                        <Button type="submit" disabled={processing}>
                            {processing ? 'Menyimpan...' : 'Simpan Produk'}
                        </Button>
                    </div>
                {/snippet}
            </Form>
        </CardContent>
    </Card>
</div>
