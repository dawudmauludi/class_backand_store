export interface ProductCategory {
    id: string;
    name: string;
    products_count?: number;
}

export interface ProductItem {
    id: string;
    name: string;
    description: string | null;
    price: number;
    stock: number;
    category_id: string;
    created_at?: string;
    updated_at?: string;
    category?: ProductCategory | null;
}

export interface ProductFormErrors {
    name?: string;
    category_id?: string;
    price?: string;
    stock?: string;
    description?: string;
}
