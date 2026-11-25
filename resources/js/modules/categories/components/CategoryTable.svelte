<script lang="ts">
    import { Badge } from '@/components/ui/badge';
    import { Button } from '@/components/ui/button';
    import {
        Card,
        CardContent,
        CardDescription,
        CardHeader,
        CardTitle,
    } from '@/components/ui/card';
    import Layers from '@lucide/svelte/icons/layers';
    import Pencil from '@lucide/svelte/icons/pencil';
    import Plus from '@lucide/svelte/icons/plus';
    import Trash2 from '@lucide/svelte/icons/trash-2';
    import X from '@lucide/svelte/icons/x';
    import type { Category } from '../types';

    let {
        categories = [],
        totalCount = 0,
        searchQuery = '',
        onCreate,
        onEdit,
        onDelete,
        onResetSearch,
    }: {
        categories?: Category[];
        totalCount?: number;
        searchQuery?: string;
        onCreate: () => void;
        onEdit: (category: Category) => void;
        onDelete: (category: Category) => void;
        onResetSearch: () => void;
    } = $props();
</script>

{#if categories.length === 0}
    <Card class="border-dashed border-sidebar-border/70 dark:border-sidebar-border">
        <CardContent class="flex flex-col items-center justify-center py-12 text-center">
            <div class="flex size-14 items-center justify-center rounded-full bg-muted text-muted-foreground">
                <Layers class="size-6" />
            </div>
            <h3 class="mt-4 text-lg font-semibold text-foreground">
                {searchQuery ? 'Kategori Tidak Ditemukan' : 'Belum Ada Kategori'}
            </h3>
            <p class="mt-1 max-w-md text-sm text-muted-foreground">
                {searchQuery
                    ? `Tidak ada kategori yang cocok dengan "${searchQuery}".`
                    : 'Tambahkan kategori pertama Anda untuk mulai mengelompokkan produk.'}
            </p>
            {#if searchQuery}
                <Button
                    type="button"
                    variant="outline"
                    size="sm"
                    onclick={onResetSearch}
                    class="mt-4 gap-1.5"
                >
                    <X class="size-3.5" />
                    Reset Pencarian
                </Button>
            {:else}
                <Button
                    type="button"
                    size="sm"
                    onclick={onCreate}
                    class="mt-4 gap-1.5"
                >
                    <Plus class="size-3.5" />
                    Tambah Kategori
                </Button>
            {/if}
        </CardContent>
    </Card>
{:else}
    <Card class="overflow-hidden border-sidebar-border/70 shadow-sm dark:border-sidebar-border">
        <CardHeader class="border-b border-border/60 bg-muted/20 pb-4">
            <CardTitle class="text-base">Daftar Kategori</CardTitle>
            <CardDescription>
                Menampilkan {categories.length} dari {totalCount} kategori terdaftar.
            </CardDescription>
        </CardHeader>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="border-b border-border/70 bg-muted/40 text-xs uppercase text-muted-foreground">
                    <tr>
                        <th scope="col" class="px-4 py-3 font-semibold">Nama Kategori</th>
                        <th scope="col" class="px-4 py-3 text-center font-semibold">Jumlah Produk</th>
                        <th scope="col" class="px-4 py-3 text-right font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border/60">
                    {#each categories as category (category.id)}
                        <tr class="transition-colors hover:bg-muted/30">
                            <td class="px-4 py-3 font-medium text-foreground">
                                {category.name}
                            </td>
                            <td class="px-4 py-3 text-center">
                                <Badge variant="secondary" class="font-normal">
                                    {category.products_count ?? 0} produk
                                </Badge>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="inline-flex items-center justify-end gap-2">
                                    <Button
                                        type="button"
                                        variant="outline"
                                        size="sm"
                                        onclick={() => onEdit(category)}
                                        class="h-8 gap-1.5 px-2.5 text-xs"
                                    >
                                        <Pencil class="size-3.5" />
                                        Edit
                                    </Button>
                                    <Button
                                        type="button"
                                        variant="destructive"
                                        size="sm"
                                        onclick={() => onDelete(category)}
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
