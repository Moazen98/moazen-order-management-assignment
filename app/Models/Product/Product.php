<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;

class Product extends Model
{
    use HasFactory, SoftDeletes, Translatable;
    protected $table = 'products';
    protected $fillable = ['price', 'is_active','name','description'];
    public $translatedAttributes = ['name', 'description'];

    protected $translationModel = ProductTranslation::class;

    public function setTranslatedAttributes(Request $request)
    {
        foreach (config('translatable.locales') as $locale) {
            if ($request->has($locale)) {
                $this->getTranslationOrNew($locale)->name = $request->exists($locale) ? (isset($request->get($locale)['name']) ? $request->get($locale)['name'] : ($this->getTranslation($locale)->name ?? null)) : $this->getTranslation($locale)->name;
                $this->getTranslationOrNew($locale)->description = $request->exists($locale) ? (isset($request->get($locale)['description']) ? $request->get($locale)['description'] : ($this->getTranslation($locale)->description ?? null)) : $this->getTranslation($locale)->description;
            }

        }
    }
}
