/**
 * Backend resources send the raw storage path for product/variant
 * images (e.g. "products/ruj-mat/main.jpg"), not a resolved URL.
 * Files live on the "public" disk, served from /storage/... once
 * `php artisan storage:link` has run (standard one-time Laravel setup
 * step). Already-absolute sources (http(s) URLs, root-relative paths,
 * data: URIs — used by the /test mock page) pass through unchanged.
 */
export function resolveMediaUrl(path?: string | null): string | undefined {
    if (!path) {
        return undefined;
    }

    if (/^(https?:)?\/\//.test(path) || path.startsWith('/') || path.startsWith('data:')) {
        return path;
    }

    return `/storage/${path}`;
}
