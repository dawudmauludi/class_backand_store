<script lang="ts">
    import { Link } from '@inertiajs/svelte';
    import InputError from '@/components/InputError.svelte';
    import { Input } from '@/components/ui/input';
    import { Label } from '@/components/ui/label';
    import { toUrl } from '@/lib/utils';
    import { index as categoriesIndex } from '@/routes/categories';
    import type { ProductCategory, ProductFormErrors } from '../types';

    let {
        categories = [],
        errors = {},
        name = $bindable(''),
        categoryId = $bindable(''),
        price = $bindable<string | number>(''),
        stock = $bindable<string | number>(0),
        description = $bindable(''),
    }: {
        categories?: ProductCategory[];
        errors?: ProductFormErrors;
        name?: string;
        categoryId?: string;
        price?: string | number;
        stock?: string | number;
        description?: string;
    } = $props();
</script>

<div class="grid gap-2">
    <Label for="name">Nama Produk *</Label>
    <Input
        id="name"
        name="name"
        type="text"
        bind:value={name}
        placeholder="Contoh: Kemeja Flannel Premium"
        required
    />
    <InputError message={errors.name} />
</div>

<div class="grid gap-2">
    <div class="flex items-center justify-between">
        <Label for="category_id">Kategori Produk *</Label>
        <Link
            href={toUrl(categoriesIndex())}
            class="text-xs text-primary hover:underline"
        >
            Kelola Kategori
        </Link>
    </div>
    <select
        id="category_id"
        name="category_id"
        bind:value={categoryId}
        required
        class="flex h-9 w-full rounded-md border border-input bg-background px-3 py-1 text-sm text-foreground shadow-xs transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50"
    >
        <option value="" disabled>Pilih kategori...</option>
        {#each categories as category (category.id)}
            <option value={category.id}>{category.name}</option>
        {/each}
    </select>
    <InputError message={errors.category_id} />
</div>

<div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
    <div class="grid gap-2">
        <Label for="price">Harga (IDR) *</Label>
        <Input
            id="price"
            name="price"
            type="number"
            min="0"
            step="1"
            bind:value={price}
            placeholder="150000"
            required
        />
        <InputError message={errors.price} />
    </div>

    <div class="grid gap-2">
        <Label for="stock">Stok *</Label>
        <Input
            id="stock"
            name="stock"
            type="number"
            min="0"
            step="1"
            bind:value={stock}
            placeholder="10"
            required
        />
        <InputError message={errors.stock} />
    </div>
</div>

<div class="grid gap-2">
    <Label for="description">Deskripsi</Label>
    <textarea
        id="description"
        name="description"
        rows={4}
        bind:value={description}
        placeholder="Deskripsi singkat mengenai produk (opsional)..."
        class="flex min-h-[80px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm text-foreground shadow-xs placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50"
    ></textarea>
    <InputError message={errors.description} />
</div>
