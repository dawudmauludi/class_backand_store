<script lang="ts">
    import { Link } from '@inertiajs/svelte';
    import { Badge } from '@/components/ui/badge';
    import { Button } from '@/components/ui/button';
    import {
        Dialog,
        DialogContent,
        DialogDescription,
        DialogFooter,
        DialogTitle,
    } from '@/components/ui/dialog';
    import { toUrl } from '@/lib/utils';
    import { edit as productsEdit } from '@/routes/products';
    import Pencil from '@lucide/svelte/icons/pencil';
    import type { ProductItem } from '../types';
    import { formatDate, formatRupiah, getStockBadgeClass } from '../utils';

    let {
        open = $bindable(false),
        product = null,
    }: {
        open?: boolean;
        product?: ProductItem | null;
    } = $props();
</script>

<Dialog bind:open>
    <DialogContent class="sm:max-w-lg">
        {#if product}
            <div class="space-y-2">
                <div class="flex flex-wrap items-center gap-2">
                    {#if product.category}
                        <Badge variant="secondary" class="text-xs font-normal">
                            {product.category.name}
                        </Badge>
                    {/if}
                    <span
                        class={`inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium ${getStockBadgeClass(product.stock)}`}
                    >
                        {#if product.stock > 10}
                            Stok Aman ({product.stock} unit)
                        {:else if product.stock > 0}
                            Stok Menipis ({product.stock} unit)
                        {:else}
                            Stok Habis
                        {/if}
                    </span>
                </div>
                <DialogTitle class="text-xl">{product.name}</DialogTitle>
                <DialogDescription>
                    Detail informasi produk dan inventaris.
                </DialogDescription>
            </div>

            <div class="space-y-4 py-2">
                <div class="grid grid-cols-2 gap-3 rounded-lg border border-border/70 bg-muted/30 p-3.5">
                    <div>
                        <p class="text-xs text-muted-foreground">Harga Satuan</p>
                        <p class="mt-0.5 text-lg font-bold text-foreground">
                            {formatRupiah(product.price)}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-muted-foreground">Total Nilai Stok</p>
                        <p class="mt-0.5 text-lg font-semibold text-foreground">
                            {formatRupiah(product.price * product.stock)}
                        </p>
                    </div>
                </div>

                <div class="space-y-1.5">
                    <p class="text-xs font-medium uppercase tracking-wider text-muted-foreground">
                        Deskripsi
                    </p>
                    <p class="whitespace-pre-line text-sm leading-relaxed text-foreground">
                        {product.description || 'Tidak ada deskripsi untuk produk ini.'}
                    </p>
                </div>

                {#if product.created_at || product.updated_at}
                    <div class="grid grid-cols-2 gap-3 border-t border-border/60 pt-3 text-xs text-muted-foreground">
                        <div>
                            <span>Dibuat:</span>
                            <span class="block font-medium text-foreground">
                                {formatDate(product.created_at)}
                            </span>
                        </div>
                        <div>
                            <span>Diperbarui:</span>
                            <span class="block font-medium text-foreground">
                                {formatDate(product.updated_at)}
                            </span>
                        </div>
                    </div>
                {/if}
            </div>

            <DialogFooter class="gap-2">
                <Button
                    type="button"
                    variant="secondary"
                    onclick={() => (open = false)}
                >
                    Tutup
                </Button>
                <Button asChild class="gap-1.5">
                    {#snippet children(props)}
                        <Link href={toUrl(productsEdit(product.id))} class={props.class}>
                            <Pencil class="size-3.5" />
                            Edit Produk
                        </Link>
                    {/snippet}
                </Button>
            </DialogFooter>
        {/if}
    </DialogContent>
</Dialog>
