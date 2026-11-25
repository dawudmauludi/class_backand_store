<script lang="ts">
    import { useForm } from '@inertiajs/svelte';
    import CategoryProductController from '@/actions/App/Http/Controllers/CategoryProductController';
    import InputError from '@/components/InputError.svelte';
    import { Button } from '@/components/ui/button';
    import {
        Dialog,
        DialogContent,
        DialogDescription,
        DialogFooter,
        DialogTitle,
    } from '@/components/ui/dialog';
    import { Input } from '@/components/ui/input';
    import { Label } from '@/components/ui/label';
    import type { Category } from '../types';

    let {
        open = $bindable(false),
        category = $bindable<Category | null>(null),
    }: {
        open?: boolean;
        category?: Category | null;
    } = $props();

    const form = useForm({
        name: '',
    });

    $effect(() => {
        if (open && category) {
            form.name = category.name;
            form.clearErrors();
        }
    });

    function submit(e: SubmitEvent): void {
        e.preventDefault();
        if (!category) return;

        form.put(CategoryProductController.update.url(category.id), {
            preserveScroll: true,
            onSuccess: () => {
                open = false;
                category = null;
            },
        });
    }
</script>

<Dialog bind:open>
    <DialogContent>
        <div class="space-y-2">
            <DialogTitle>Edit Kategori Produk</DialogTitle>
            <DialogDescription>
                Perbarui nama kategori yang dipilih.
            </DialogDescription>
        </div>

        <form onsubmit={submit} class="space-y-4">
            <div class="grid gap-2">
                <Label for="edit-category-name">Nama Kategori</Label>
                <Input
                    id="edit-category-name"
                    name="name"
                    type="text"
                    bind:value={form.name}
                    placeholder="Nama kategori"
                    required
                />
                <InputError message={form.errors.name} />
            </div>

            <DialogFooter class="gap-2">
                <Button
                    type="button"
                    variant="secondary"
                    onclick={() => (open = false)}
                >
                    Batal
                </Button>
                <Button type="submit" disabled={form.processing}>
                    {form.processing ? 'Menyimpan...' : 'Perbarui'}
                </Button>
            </DialogFooter>
        </form>
    </DialogContent>
</Dialog>
