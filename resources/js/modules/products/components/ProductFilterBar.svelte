<script lang="ts">
    import { Input } from '@/components/ui/input';
    import Search from '@lucide/svelte/icons/search';
    import Tag from '@lucide/svelte/icons/tag';
    import X from '@lucide/svelte/icons/x';
    import type { ProductCategory } from '../types';

    let {
        searchQuery = $bindable(''),
        selectedCategory = $bindable('all'),
        categories = [],
        totalProducts = 0,
    }: {
        searchQuery?: string;
        selectedCategory?: string;
        categories?: ProductCategory[];
        totalProducts?: number;
    } = $props();
</script>

<div class="flex flex-col gap-4 rounded-xl border border-sidebar-border/70 bg-card p-4 shadow-sm dark:border-sidebar-border">
    <div class="relative max-w-md">
        <Search class="absolute left-3 top-1/2 size-4 -translate-y-1/2 text-muted-foreground" />
        <Input
            type="text"
            placeholder="Cari nama produk, kategori, atau deskripsi..."
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

    <div class="flex items-center gap-2 overflow-x-auto pb-1 pt-1">
        <span class="flex shrink-0 items-center gap-1 text-xs font-medium text-muted-foreground">
            <Tag class="size-3.5" />
            Kategori:
        </span>
        <button
            type="button"
            onclick={() => (selectedCategory = 'all')}
            class={`inline-flex shrink-0 items-center gap-1.5 rounded-full px-3 py-1 text-xs font-medium transition-colors ${
                selectedCategory === 'all'
                    ? 'bg-primary text-primary-foreground shadow-sm'
                    : 'bg-muted text-foreground hover:bg-muted/80'
            }`}
        >
            Semua
            <span
                class={`rounded-full px-1.5 text-[10px] ${
                    selectedCategory === 'all'
                        ? 'bg-primary-foreground/20 text-primary-foreground'
                        : 'bg-background text-muted-foreground'
                }`}
            >
                {totalProducts}
            </span>
        </button>
        {#each categories as category (category.id)}
            <button
                type="button"
                onclick={() => (selectedCategory = category.id)}
                class={`inline-flex shrink-0 items-center gap-1.5 rounded-full px-3 py-1 text-xs font-medium transition-colors ${
                    selectedCategory === category.id
                        ? 'bg-primary text-primary-foreground shadow-sm'
                        : 'bg-muted text-foreground hover:bg-muted/80'
                }`}
            >
                {category.name}
                {#if category.products_count !== undefined}
                    <span
                        class={`rounded-full px-1.5 text-[10px] ${
                            selectedCategory === category.id
                                ? 'bg-primary-foreground/20 text-primary-foreground'
                                : 'bg-background text-muted-foreground'
                        }`}
                    >
                        {category.products_count}
                    </span>
                {/if}
            </button>
        {/each}
    </div>
</div>
