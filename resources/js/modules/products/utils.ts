export function formatRupiah(amount: number): string {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    }).format(amount);
}

export function formatDate(value?: string): string {
    if (!value) {
        return '-';
    }

    return new Intl.DateTimeFormat('id-ID', {
        dateStyle: 'medium',
        timeStyle: 'short',
    }).format(new Date(value));
}

export function getStockBadgeClass(stock: number): string {
    if (stock > 10) {
        return 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400';
    }

    if (stock > 0) {
        return 'bg-amber-500/10 text-amber-600 dark:text-amber-400';
    }

    return 'bg-rose-500/10 text-rose-600 dark:text-rose-400';
}
