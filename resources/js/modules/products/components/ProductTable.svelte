<script lang="ts">
    import { Link } from '@inertiajs/svelte';
    import { Badge } from '@/components/ui/badge';
    import { Button } from '@/components/ui/button';
    import {
        Card,
        CardContent,
        CardDescription,
        CardHeader,
        CardTitle,
    } from '@/components/ui/card';
    import { toUrl } from '@/lib/utils';
    import { create as productsCreate, edit as productsEdit } from '@/routes/products';
    import Eye from '@lucide/svelte/icons/eye';
    import Package from '@lucide/svelte/icons/package';
    import Pencil from '@lucide/svelte/icons/pencil';
    import Plus from '@lucide/svelte/icons/plus';
    import Trash2 from '@lucide/svelte/icons/trash-2';
    import X from '@lucide/svelte/icons/x';
    import type { ProductItem } from '../types';
    import { formatRupiah, getStockBadgeClass } from '../utils';

    let {
        products = [],
        totalCount = 0,
        hasActiveFilter = false,
        onViewDetail,
        onDelete,
        onResetFilters,
    }: {
        products?: ProductItem[];
        totalCount?: number;
        hasActiveFilter?: boolean;
        onViewDetail: (product: ProductItem) => void;
        onDelete: (product: ProductItem) => void;
        onResetFilters: () => void;
    } = $props();
</script>

{#if products.length === 0}
    <Card class="border-dashed border-sidebar-border/70 dark:border-sidebar-border">
        <CardContent class="flex flex-col items-center justify-center py-12 text-center">
            <div class="flex size-14 items-center justify-center rounded-full bg-muted text-muted-foreground">
                <Package class="size-6" />
            </div>
            <h3 class="mt-4 text-lg font-semibold text-foreground">
                {hasActiveFilter ? 'Tidak Ada Produk Ditemukan' : 'Belum Ada Produk'}
            </h3>
            <p class="mt-1 max-w-md text-sm text-muted-foreground">
                {hasActiveFilter
                    ? 'Tidak ada produk yang cocok dengan filter yang dipilih.'
                    : 'Tambahkan produk pertama Anda ke dalam katalog.'}
            </p>
            {#if hasActiveFilter}
                <Button
                    type="button"
                    variant="outline"
                    size="sm"
                    onclick={onResetFilters}
                    class="mt-4 gap-1.5"
                >
                    <X class="size-3.5" />
                    Reset Filter
                </Button>
            {:else}
                <Button asChild size="sm" class="mt-4 gap-1.5">
                    {#snippet children(props)}
                        <Link href={toUrl(productsCreate())} class={props.class}>
                            <Plus class="size-3.5" />
                            Tambah Produk
                        </Link>
                    {/snippet}
                </Button>
            {/if}
        </CardContent>
    </Card>
{:else}
    <Card class="overflow-hidden border-sidebar-border/70 shadow-sm dark:border-sidebar-border">
        <CardHeader class="border-b border-border/60 bg-muted/20 pb-4">
            <CardTitle class="text-base">Daftar Produk</CardTitle>
            <CardDescription>
                Menampilkan {products.length} dari {totalCount} produk terdaftar.
            </CardDescription>
        </CardHeader>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="border-b border-border/70 bg-muted/40 text-xs uppercase text-muted-foreground">
                    <tr>
                        <th scope="col" class="px-4 py-3 font-semibold">Nama Produk</th>
                        <th scope="col" class="px-4 py-3 font-semibold">Kategori</th>
                        <th scope="col" class="px-4 py-3 text-right font-semibold">Harga</th>
                        <th scope="col" class="px-4 py-3 text-center font-semibold">Stok</th>
                        <th scope="col" class="px-4 py-3 text-right font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border/60">
                    {#each products as product (product.id)}
                        <tr class="transition-colors hover:bg-muted/30">
                            <td class="px-4 py-3">
                                <button
                                    type="button"
                                    onclick={() => onViewDetail(product)}
                                    class="text-left font-medium text-foreground hover:text-primary hover:underline"
                                >
                                    {product.name}
                                </button>
                                {#if product.description}
                                    <div class="max-w-md truncate text-xs text-muted-foreground">
                                        {product.description}
                                    </div>
                                {/if}
                            </td>
                            <td class="px-4 py-3">
                                {#if product.category}
                                    <Badge variant="outline" class="text-xs font-normal">
                                        {product.category.name}
                                    </Badge>
                                {:else}
                                    <span class="text-xs text-muted-foreground">-</span>
                                {/if}
                            </td>
                            <td class="px-4 py-3 text-right font-semibold text-foreground">
                                {formatRupiah(product.price)}
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span
                                    class={`inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium ${getStockBadgeClass(product.stock)}`}
                                >
                                    {product.stock > 0 ? product.stock : 'Habis'}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="inline-flex items-center justify-end gap-2">
                                    <Button
                                        type="button"
                                        variant="ghost"
                                        size="sm"
                                        onclick={() => onViewDetail(product)}
                                        class="h-8 gap-1.5 px-2.5 text-xs"
                                    >
                                        <Eye class="size-3.5" />
                                        Detail
                                    </Button>
                                    <Button
                                        asChild
                                        variant="outline"
                                        size="sm"
                                        class="h-8 gap-1.5 px-2.5 text-xs"
                                    >
                                        {#snippet children(props)}
                                            <Link href={toUrl(productsEdit(product.id))} class={props.class}>
                                                <Pencil class="size-3.5" />
                                                Edit
                                            </Link>
                                        {/snippet}
                                    </Button>
                                    <Button
                                        type="button"
                                        variant="destructive"
                                        size="sm"
                                        onclick={() => onDelete(product)}
                                        class="h-8 gap-1.5 px-2.5 text-xs"
                                    >
                                        <Trash2 class="size-3.5" />
                                        Hapus
                                    </Button>
                                </div>
                            </td>
                        </tr>
                    {/each}
                </tbody>
            </table>
        </div>
    </Card>
{/if}
