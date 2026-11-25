<script lang="ts">
    import { Form } from '@inertiajs/svelte';
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

    let {
        open = $bindable(false),
    }: {
        open?: boolean;
    } = $props();
</script>

<Dialog bind:open>
    <DialogContent>
        <div class="space-y-2">
            <DialogTitle>Tambah Kategori Produk</DialogTitle>
            <DialogDescription>
                Masukkan nama kategori baru untuk mengelompokkan produk.
            </DialogDescription>
        </div>

        <Form
            {...CategoryProductController.store.form()}
            resetOnSuccess
            options={{ preserveScroll: true }}
            onSuccess={() => {
                open = false;
            }}
            class="space-y-4"
        >
            {#snippet children({ errors, processing })}
                <div class="grid gap-2">
                    <Label for="create-category-name">Nama Kategori</Label>
                    <Input
                        id="create-category-name"
                        name="name"
                        type="text"
                        placeholder="Contoh: Elektronik, Fashion, Makanan"
                        required
                    />
                    <InputError message={errors.name} />
                </div>

                <DialogFooter class="gap-2">
                    <Button
                        type="button"
                        variant="secondary"
                        onclick={() => (open = false)}
                    >
                        Batal
                    </Button>
                    <Button type="submit" disabled={processing}>
                        {processing ? 'Menyimpan...' : 'Simpan Kategori'}
                    </Button>
                </DialogFooter>
            {/snippet}
        </Form>
    </DialogContent>
</Dialog>
