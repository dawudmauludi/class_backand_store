<script lang="ts">
    import { router } from '@inertiajs/svelte';
    import ProductController from '@/actions/App/Http/Controllers/ProductController';
    import { Button } from '@/components/ui/button';
    import {
        Dialog,
        DialogContent,
        DialogDescription,
        DialogFooter,
        DialogTitle,
    } from '@/components/ui/dialog';
    import type { ProductItem } from '../types';

    let {
        open = $bindable(false),
        product = $bindable<ProductItem | null>(null),
    }: {
        open?: boolean;
        product?: ProductItem | null;
    } = $props();

    let isDeleting = $state(false);

    function confirmDelete(): void {
        if (!product || isDeleting) return;

        isDeleting = true;
        router.delete(ProductController.destroy.url(product.id), {
            preserveScroll: true,
            onSuccess: () => {
                open = false;
                product = null;
            },
            onFinish: () => {
                isDeleting = false;
            },
        });
    }
</script>

<Dialog bind:open>
    <DialogContent>
        <div class="space-y-2">
            <DialogTitle>Hapus Produk?</DialogTitle>
            <DialogDescription>
                Anda yakin ingin menghapus produk
                <span class="font-semibold text-foreground">{product?.name}</span>?
                Tindakan ini tidak dapat dibatalkan.
            </DialogDescription>
        </div>

        {#if product}
            <DialogFooter class="gap-2">
                <Button
                    type="button"
                    variant="secondary"
                    onclick={() => (open = false)}
                >
                    Batal
                </Button>
                <Button
                    type="button"
                    variant="destructive"
                    disabled={isDeleting}
                    onclick={confirmDelete}
                >
                    {isDeleting ? 'Menghapus...' : 'Ya, Hapus'}
                </Button>
            </DialogFooter>
        {/if}
    </DialogContent>
</Dialog>
