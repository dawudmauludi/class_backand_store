<script lang="ts">
    import { router } from '@inertiajs/svelte';
    import CategoryProductController from '@/actions/App/Http/Controllers/CategoryProductController';
    import { Button } from '@/components/ui/button';
    import {
        Dialog,
        DialogContent,
        DialogDescription,
        DialogFooter,
        DialogTitle,
    } from '@/components/ui/dialog';
    import type { Category } from '../types';

    let {
        open = $bindable(false),
        category = $bindable<Category | null>(null),
    }: {
        open?: boolean;
        category?: Category | null;
    } = $props();

    let isDeleting = $state(false);

    function confirmDelete(): void {
        if (!category || isDeleting) return;

        isDeleting = true;
        router.delete(CategoryProductController.destroy.url(category.id), {
            preserveScroll: true,
            onSuccess: () => {
                open = false;
                category = null;
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
            <DialogTitle>Hapus Kategori Produk?</DialogTitle>
            <DialogDescription>
                Anda yakin ingin menghapus kategori
                <span class="font-semibold text-foreground">{category?.name}</span>?
                {#if (category?.products_count ?? 0) > 0}
                    Semua ({category?.products_count}) produk di dalam kategori ini juga akan ikut terhapus secara permanen.
                {:else}
                    Tindakan ini tidak dapat dibatalkan.
                {/if}
            </DialogDescription>
        </div>

        {#if category}
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
