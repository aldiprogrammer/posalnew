<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

trait HasStore
{
    /**
     * Auto-isi id_store saat creating dan filter data per store saat auth.
     * Tipe varchar, value = (string) Auth::id() (id user yang login = id_store).
     */
    protected static function bootHasStore(): void
    {
        static::creating(function ($model) {
            if (empty($model->id_store) && auth()->check()) {
                $model->id_store = (string) auth()->id();
            }
        });

        static::addGlobalScope('store', function (Builder $builder) {
            if (auth()->check()) {
                $builder->where($builder->getModel()->getTable().'.id_store', (string) auth()->id());
            }
        });
    }

    /**
     * Scope manual: Produk::forCurrentStore()->get()
     */
    public function scopeForCurrentStore(Builder $query): Builder
    {
        return auth()->check()
            ? $query->where('id_store', (string) auth()->id())
            : $query;
    }
}
