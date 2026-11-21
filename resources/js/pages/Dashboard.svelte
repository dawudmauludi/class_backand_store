<script module lang="ts">
    import { dashboard } from '@/routes';

    export const layout = {
        breadcrumbs: [
            {
                title: 'Dashboard',
                href: dashboard(),
            },
        ],
    };
</script>

<script lang="ts">
    import AppHead from '@/components/AppHead.svelte';
    import { Badge } from '@/components/ui/badge';
    import { Button } from '@/components/ui/button';
    import {
        Card,
        CardContent,
        CardDescription,
        CardHeader,
        CardTitle,
    } from '@/components/ui/card';
    import { Input } from '@/components/ui/input';
    import Boxes from '@lucide/svelte/icons/boxes';
    import Grid from '@lucide/svelte/icons/grid';
    import Layers from '@lucide/svelte/icons/layers';
    import List from '@lucide/svelte/icons/list';
    import Package from '@lucide/svelte/icons/package';
    import Search from '@lucide/svelte/icons/search';
    import ShoppingBag from '@lucide/svelte/icons/shopping-bag';
    import Tag from '@lucide/svelte/icons/tag';
    import TrendingUp from '@lucide/svelte/icons/trending-up';
    import X from '@lucide/svelte/icons/x';

    interface Category {
        id: string;
        name: string;
        products_count?: number;
        created_at?: string;
        updated_at?: string;
    }

    interface ProductItem {
        id: string;
        name: string;
        description: string | null;
        price: number;
        stock: number;
        category_id: string;
        created_at?: string;
        updated_at?: string;
        category?: Category | null;
    }

    interface Stats {
        total_products: number;
        total_categories: number;
        total_stock: number;
        total_value: number;
    }

    let {
        categories = [],
        products = [],
        stats = {
            total_products: 0,
            total_categories: 0,
            total_stock: 0,
            total_value: 0,
        },
    }: {
        categories?: Category[];
        products?: ProductItem[];
        stats?: Stats;
    } = $props();

    let searchQuery = $state('');
    let selectedCategory = $state<string>('all');
    let viewMode = $state<'grid' | 'table'>('grid');
    let sortBy = $state<'name' | 'price-asc' | 'price-desc' | 'stock-desc'>('name');

    function formatRupiah(amount: number): string {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0,
        }).format(amount);
    }

    let filteredProducts = $derived(
        products
            .filter((product) => {
                const matchCategory =
                    selectedCategory === 'all' || product.category_id === selectedCategory;
                const query = searchQuery.toLowerCase().trim();
                const matchQuery =
                    !query ||
                    product.name.toLowerCase().includes(query) ||
                    (product.description && product.description.toLowerCase().includes(query)) ||
                    (product.category && product.category.name.toLowerCase().includes(query));

                return matchCategory && matchQuery;
            })
            .sort((a, b) => {
                if (sortBy === 'price-asc') return a.price - b.price;
                if (sortBy === 'price-desc') return b.price - a.price;
                if (sortBy === 'stock-desc') return b.stock - a.stock;
                return a.name.localeCompare(b.name);
            }),
    );

    function resetFilters(): void {
        searchQuery = '';
        selectedCategory = 'all';
        sortBy = 'name';
    }
</script>

<AppHead title="Dashboard Katalog Produk" />

<div class="flex h-full flex-1 flex-col gap-6 overflow-x-auto p-4 md:p-6">
    <!-- Header -->
    <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-foreground md:text-3xl">
                Katalog Produk & Kategori
            </h1>
            <p class="text-sm text-muted-foreground">
                Ringkasan inventaris produk dan kategori dari database seeder.
            </p>
        </div>
        <div class="flex items-center gap-2 pt-2 sm:pt-0">
            <Badge variant="outline" class="gap-1.5 px-3 py-1 text-xs">
                <span class="inline-block size-2 rounded-full bg-emerald-500"></span>
                Database Terhubung
            </Badge>
        </div>
    </div>

    <!-- Stat Summary Cards -->
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Card 1: Total Produk -->
        <Card class="border-sidebar-border/70 dark:border-sidebar-border shadow-sm">
            <CardHeader class="flex flex-row items-center justify-between pb-2">
                <CardTitle class="text-sm font-medium text-muted-foreground">
                    Total Produk
                </CardTitle>
                <div class="flex size-9 items-center justify-center rounded-lg bg-primary/10 text-primary">
                    <Package class="size-4" />
                </div>
            </CardHeader>
            <CardContent>
                <div class="text-2xl font-bold">{stats.total_products}</div>
                <CardDescription class="mt-1 text-xs text-muted-foreground">
                    Item produk aktif terdaftar
                </CardDescription>
            </CardContent>
        </Card>

        <!-- Card 2: Total Kategori -->
        <Card class="border-sidebar-border/70 dark:border-sidebar-border shadow-sm">
            <CardHeader class="flex flex-row items-center justify-between pb-2">
                <CardTitle class="text-sm font-medium text-muted-foreground">
                    Total Kategori
                </CardTitle>
                <div class="flex size-9 items-center justify-center rounded-lg bg-blue-500/10 text-blue-600 dark:text-blue-400">
                    <Layers class="size-4" />
                </div>
            </CardHeader>
            <CardContent>
                <div class="text-2xl font-bold">{stats.total_categories}</div>
                <CardDescription class="mt-1 text-xs text-muted-foreground">
                    Kategori klasifikasi produk
                </CardDescription>
            </CardContent>
        </Card>

        <!-- Card 3: Total Stok -->
        <Card class="border-sidebar-border/70 dark:border-sidebar-border shadow-sm">
            <CardHeader class="flex flex-row items-center justify-between pb-2">
                <CardTitle class="text-sm font-medium text-muted-foreground">
                    Total Stok Barang
                </CardTitle>
                <div class="flex size-9 items-center justify-center rounded-lg bg-amber-500/10 text-amber-600 dark:text-amber-400">
                    <Boxes class="size-4" />
                </div>
            </CardHeader>
            <CardContent>
                <div class="text-2xl font-bold">{stats.total_stock} <span class="text-sm font-normal text-muted-foreground">unit</span></div>
                <CardDescription class="mt-1 text-xs text-muted-foreground">
                    Kuantitas keseluruhan stok
                </CardDescription>
            </CardContent>
        </Card>

        <!-- Card 4: Nilai Inventaris -->
        <Card class="border-sidebar-border/70 dark:border-sidebar-border shadow-sm">
            <CardHeader class="flex flex-row items-center justify-between pb-2">
                <CardTitle class="text-sm font-medium text-muted-foreground">
                    Nilai Inventaris
                </CardTitle>
                <div class="flex size-9 items-center justify-center rounded-lg bg-emerald-500/10 text-emerald-600 dark:text-emerald-400">
                    <TrendingUp class="size-4" />
                </div>
            </CardHeader>
            <CardContent>
                <div class="text-xl font-bold tracking-tight md:text-2xl">{formatRupiah(stats.total_value)}</div>
                <CardDescription class="mt-1 text-xs text-muted-foreground">
                    Estimasi nilai (harga × stok)
                </CardDescription>
            </CardContent>
        </Card>
    </div>

    <!-- Filter, Search, and Category Section -->
    <div class="flex flex-col gap-4 rounded-xl border border-sidebar-border/70 bg-card p-4 shadow-sm dark:border-sidebar-border">
        <!-- Search, Sort & View Mode Toolbar -->
        <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
            <!-- Search Bar -->
            <div class="relative flex-1">
                <Search class="absolute left-3 top-1/2 size-4 -translate-y-1/2 text-muted-foreground" />
                <Input
                    type="text"
                    placeholder="Cari berdasarkan nama produk, kategori, atau deskripsi..."
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

            <!-- Sort and View Mode Controls -->
            <div class="flex flex-wrap items-center gap-2">
                <!-- Sort Dropdown -->
                <div class="flex items-center gap-1.5 text-xs text-muted-foreground">
                    <span>Urutkan:</span>
                    <select
                        bind:value={sortBy}
                        class="h-9 rounded-md border border-input bg-background px-2.5 py-1 text-xs text-foreground shadow-sm focus:outline-none focus:ring-1 focus:ring-ring"
                    >
                        <option value="name">Nama (A - Z)</option>
                        <option value="price-asc">Harga: Termurah</option>
                        <option value="price-desc">Harga: Termahal</option>
                        <option value="stock-desc">Stok: Terbanyak</option>
                    </select>
                </div>

                <!-- View Toggle Buttons -->
                <div class="flex items-center rounded-lg border border-input bg-muted/40 p-0.5">
                    <Button
                        type="button"
                        size="sm"
                        variant={viewMode === 'grid' ? 'secondary' : 'ghost'}
                        onclick={() => (viewMode = 'grid')}
                        class="h-7 px-2.5 text-xs gap-1.5"
                    >
                        <Grid class="size-3.5" />
                        <span class="hidden sm:inline">Grid</span>
                    </Button>
                    <Button
                        type="button"
                        size="sm"
                        variant={viewMode === 'table' ? 'secondary' : 'ghost'}
                        onclick={() => (viewMode = 'table')}
                        class="h-7 px-2.5 text-xs gap-1.5"
                    >
                        <List class="size-3.5" />
                        <span class="hidden sm:inline">Tabel</span>
                    </Button>
                </div>
            </div>
        </div>

        <!-- Category Filter Pills -->
        <div class="flex items-center gap-2 overflow-x-auto pb-1 pt-1 scrollbar-thin">
            <span class="flex items-center gap-1 text-xs font-medium text-muted-foreground shrink-0">
                <Tag class="size-3.5" />
                Kategori:
            </span>
            <button
                type="button"
                onclick={() => (selectedCategory = 'all')}
                class={`inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-medium transition-colors shrink-0 ${
                    selectedCategory === 'all'
                        ? 'bg-primary text-primary-foreground shadow-sm'
                        : 'bg-muted hover:bg-muted/80 text-foreground'
                }`}
            >
                Semua
                <span class={`rounded-full px-1.5 py-0.2 text-[10px] ${
                    selectedCategory === 'all'
                        ? 'bg-primary-foreground/20 text-primary-foreground'
                        : 'bg-background text-muted-foreground'
                }`}>
                    {products.length}
                </span>
            </button>
            {#each categories as category (category.id)}
                <button
                    type="button"
                    onclick={() => (selectedCategory = category.id)}
                    class={`inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-medium transition-colors shrink-0 ${
                        selectedCategory === category.id
                            ? 'bg-primary text-primary-foreground shadow-sm'
                            : 'bg-muted hover:bg-muted/80 text-foreground'
                    }`}
                >
                    {category.name}
                    {#if category.products_count !== undefined}
                        <span class={`rounded-full px-1.5 py-0.2 text-[10px] ${
                            selectedCategory === category.id
                                ? 'bg-primary-foreground/20 text-primary-foreground'
                                : 'bg-background text-muted-foreground'
                        }`}>
                            {category.products_count}
                        </span>
                    {/if}
                </button>
            {/each}
        </div>
    </div>

    <!-- Product List / Cards -->
    {#if filteredProducts.length === 0}
        <div class="flex flex-col items-center justify-center rounded-xl border border-dashed border-sidebar-border/70 p-12 text-center dark:border-sidebar-border">
            <div class="flex size-14 items-center justify-center rounded-full bg-muted text-muted-foreground">
                <ShoppingBag class="size-6" />
            </div>
            <h3 class="mt-4 text-lg font-semibold text-foreground">Tidak Ada Produk Ditemukan</h3>
            <p class="mt-1 text-sm text-muted-foreground max-w-md">
                Tidak ada data produk yang cocok dengan pencarian "{searchQuery}" atau kategori yang dipilih.
            </p>
            <Button
                type="button"
                variant="outline"
                size="sm"
                onclick={resetFilters}
                class="mt-4 gap-1.5"
            >
                <X class="size-3.5" />
                Reset Filter
            </Button>
        </div>
    {:else if viewMode === 'grid'}
        <!-- Grid View -->
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            {#each filteredProducts as product (product.id)}
                <Card class="flex flex-col justify-between border-sidebar-border/70 shadow-sm transition-all hover:shadow-md dark:border-sidebar-border">
                    <CardHeader class="pb-3">
                        <div class="flex items-center justify-between gap-2">
                            {#if product.category}
                                <Badge variant="secondary" class="truncate text-[11px] font-normal">
                                    {product.category.name}
                                </Badge>
                            {:else}
                                <Badge variant="secondary" class="text-[11px] font-normal">
                                    Umum
                                </Badge>
                            {/if}

                            {#if product.stock > 10}
                                <span class="inline-flex items-center gap-1 rounded-full bg-emerald-500/10 px-2 py-0.5 text-[10px] font-medium text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">
                                    Stok: {product.stock}
                                </span>
                            {:else if product.stock > 0}
                                <span class="inline-flex items-center gap-1 rounded-full bg-amber-500/10 px-2 py-0.5 text-[10px] font-medium text-amber-600 dark:text-amber-400 border border-amber-500/20">
                                    Tersisa: {product.stock}
                                </span>
                            {:else}
                                <span class="inline-flex items-center gap-1 rounded-full bg-rose-500/10 px-2 py-0.5 text-[10px] font-medium text-rose-600 dark:text-rose-400 border border-rose-500/20">
                                    Habis
                                </span>
                            {/if}
                        </div>
                        <CardTitle class="mt-2 text-base font-semibold leading-tight line-clamp-2 text-foreground">
                            {product.name}
                        </CardTitle>
                        {#if product.description}
                            <CardDescription class="mt-1.5 text-xs text-muted-foreground line-clamp-3">
                                {product.description}
                            </CardDescription>
                        {/if}
                    </CardHeader>

                    <CardContent class="pt-0">
                        <div class="mt-3 flex items-baseline justify-between border-t border-border/50 pt-3">
                            <span class="text-xs text-muted-foreground">Harga</span>
                            <span class="text-lg font-bold text-foreground">
                                {formatRupiah(product.price)}
                            </span>
                        </div>
                    </CardContent>
                </Card>
            {/each}
        </div>
    {:else}
        <!-- Table View -->
        <div class="overflow-hidden rounded-xl border border-sidebar-border/70 bg-card shadow-sm dark:border-sidebar-border">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="border-b border-border/70 bg-muted/40 text-xs uppercase text-muted-foreground">
                        <tr>
                            <th scope="col" class="px-4 py-3 font-semibold">Nama Produk</th>
                            <th scope="col" class="px-4 py-3 font-semibold">Kategori</th>
                            <th scope="col" class="px-4 py-3 font-semibold text-right">Harga</th>
                            <th scope="col" class="px-4 py-3 font-semibold text-center">Stok</th>
                            <th scope="col" class="px-4 py-3 font-semibold">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border/60">
                        {#each filteredProducts as product (product.id)}
                            <tr class="transition-colors hover:bg-muted/30">
                                <td class="px-4 py-3">
                                    <div class="font-medium text-foreground">{product.name}</div>
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
                                <td class="px-4 py-3 text-center text-sm">
                                    {product.stock}
                                </td>
                                <td class="px-4 py-3">
                                    {#if product.stock > 10}
                                        <span class="inline-flex items-center gap-1 rounded-full bg-emerald-500/10 px-2.5 py-0.5 text-xs font-medium text-emerald-600 dark:text-emerald-400">
                                            Aman
                                        </span>
                                    {:else if product.stock > 0}
                                        <span class="inline-flex items-center gap-1 rounded-full bg-amber-500/10 px-2.5 py-0.5 text-xs font-medium text-amber-600 dark:text-amber-400">
                                            Menipis
                                        </span>
                                    {:else}
                                        <span class="inline-flex items-center gap-1 rounded-full bg-rose-500/10 px-2.5 py-0.5 text-xs font-medium text-rose-600 dark:text-rose-400">
                                            Habis
                                        </span>
                                    {/if}
                                </td>
                            </tr>
                        {/each}
                    </tbody>
                </table>
            </div>
            <div class="border-t border-border/70 bg-muted/20 px-4 py-2.5 text-xs text-muted-foreground">
                Menampilkan {filteredProducts.length} dari total {products.length} produk.
            </div>
        </div>
    {/if}
</div>
