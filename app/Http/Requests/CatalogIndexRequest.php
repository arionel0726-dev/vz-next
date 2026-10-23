<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CatalogIndexRequest extends FormRequest
{
    public const SORT_POPULAR = 'popular';

    public const SORT_NEWEST = 'newest';

    public const SORT_PRICE_ASC = 'price_asc';

    public const SORT_PRICE_DESC = 'price_desc';

    public const SORTS = [
        self::SORT_POPULAR,
        self::SORT_NEWEST,
        self::SORT_PRICE_ASC,
        self::SORT_PRICE_DESC,
    ];

    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'max:255'],
            'brand' => ['nullable', 'integer', 'exists:brands,id'],
            'category' => ['nullable', 'integer', 'exists:categories,id'],
            'in_stock' => ['nullable', 'boolean'],
            'is_new' => ['nullable', 'boolean'],
            'on_sale' => ['nullable', 'boolean'],
            'sort' => ['nullable', Rule::in(self::SORTS)],
            'page' => ['nullable', 'integer', 'min:1'],
        ];
    }

    /**
     * Normalized, typed filter values for the controller to apply.
     *
     * @return array{
     *     search: string|null,
     *     brand: int|null,
     *     category: int|null,
     *     inStock: bool,
     *     isNew: bool,
     *     onSale: bool,
     *     sort: string,
     * }
     */
    public function filters(): array
    {
        $search = $this->validated('search');
        $brand = $this->validated('brand');
        $category = $this->validated('category');

        return [
            'search' => $search !== null && $search !== '' ? (string) $search : null,
            'brand' => $brand !== null ? (int) $brand : null,
            'category' => $category !== null ? (int) $category : null,
            'inStock' => $this->boolean('in_stock'),
            'isNew' => $this->boolean('is_new'),
            'onSale' => $this->boolean('on_sale'),
            'sort' => $this->validated('sort') ?? self::SORT_POPULAR,
        ];
    }
}
