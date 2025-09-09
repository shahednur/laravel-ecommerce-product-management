export interface Auth {
    user: User;
}

import { route as ziggyRoute } from 'ziggy-js';

declare module '@vue/runtime-core' {
  interface ComponentCustomProperties {
    $route: typeof ziggyRoute;
    route: typeof ziggyRoute;
  }
}

export type AppPageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
};

export interface User {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
}

export interface Product {
  id: number
  name: string
  slug: string
  sku: string | null
  price: number
  stock: number
  description: string | null
  active: boolean
  image: string | null
  categories: Array<{ id: number; name: string }>
}

export type PageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
    auth: {
        user: User;
    };
    ziggy: {
        location: string;
        query: Record<string, unknown>;
    };
    flash: {
        message?: string;
        error?: string;
    };
};