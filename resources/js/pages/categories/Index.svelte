<script module lang="ts">
    import { dashboard } from '@/routes';
    import { index as categoriesIndex } from '@/routes/categories';

    export const layout = {
        breadcrumbs: [
            {
                title: 'Dashboard',
                href: dashboard(),
            },
            {
                title: 'Kategori Produk',
                href: categoriesIndex(),
            },
        ],
    };
</script>

<script lang="ts">
    import AppHead from '@/components/AppHead.svelte';
    import { Button } from '@/components/ui/button';
    import { Input } from '@/components/ui/input';
    import {
        type Category,
        CategoryCreateDialog,
        CategoryDeleteDialog,
        CategoryEditDialog,
        CategoryTable,
    } from '@/modules/categories';
    import Plus from '@lucide/svelte/icons/plus';
    import Search from '@lucide/svelte/icons/search';
    import X from '@lucide/svelte/icons/x';

    let {
        categories = [],
    }: {
        categories?: Category[];
    } = $props();

    let searchQuery = $state('');
    let createModalOpen = $state(false);
    let editModalOpen = $state(false);
    let deleteModalOpen = $state(false);
    let selectedCategory = $state<Category | null>(null);

    let filteredCategories = $derived(
        categories.filter((category) => {
            const query = searchQuery.toLowerCase().trim();
            return !query || category.name.toLowerCase().includes(query);
        }),
    );

    function openEditModal(category: Category): void {
        selectedCategory = category;
        editModalOpen = true;
    }

    function openDeleteModal(category: Category): void {
        selectedCategory = category;
        deleteModalOpen = true;
    }
</script>

<AppHead title="Kategori Produk" />

<div class="flex h-full flex-1 flex-col gap-6 overflow-x-auto p-4 md:p-6">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-foreground md:text-3xl">
                Kategori Produk
            </h1>
            <p class="text-sm text-muted-foreground">
                Kelola daftar kategori untuk mengelompokkan produk Anda.
            </p>
        </div>
        <Button type="button" onclick={() => (createModalOpen = true)} class="gap-1.5">
            <Plus class="size-4" />
            Tambah Kategori
        </Button>
    </div>

    <div class="flex flex-col gap-4 rounded-xl border border-sidebar-border/70 bg-card p-4 shadow-sm dark:border-sidebar-border">
        <div class="relative max-w-md">
            <Search class="absolute left-3 top-1/2 size-4 -translate-y-1/2 text-muted-foreground" />
            <Input
                type="text"
                placeholder="Cari nama kategori..."
                bind:value={searchQuery}
                class="pl-9 pr-8"
            />
            {#if searchQuery}
                <button
                    type="button"
                    onclick={() => (searchQuery = '')}
                    class="absolute right-2.5 top-1/2 -translate-y-1/2 text-muted-foreground hover:text-foreground"
                    aria-label="Hapus pencarian"
                >
                    <X class="size-4" />
                </button>
            {/if}
        </div>
    </div>

    <CategoryTable
        categories={filteredCategories}
        totalCount={categories.length}
        {searchQuery}
        onCreate={() => (createModalOpen = true)}
        onEdit={openEditModal}
        onDelete={openDeleteModal}
        onResetSearch={() => (searchQuery = '')}
    />
</div>

<CategoryCreateDialog bind:open={createModalOpen} />
<CategoryEditDialog bind:open={editModalOpen} bind:category={selectedCategory} />
<CategoryDeleteDialog bind:open={deleteModalOpen} bind:category={selectedCategory} />
