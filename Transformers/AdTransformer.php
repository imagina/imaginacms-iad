<?php

namespace Modules\Iad\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

use Modules\Iprofile\Transformers\UserTransformer;
use Modules\Ilocations\Transformers\CountryTransformer;
use Modules\Ilocations\Transformers\ProvinceTransformer;
use Modules\Ilocations\Transformers\CityTransformer;
use Modules\Iad\Transformers\CategoryTransformer;
use Modules\Iad\Transformers\FieldTransformer;
use Modules\Iad\Transformers\ScheduleTransformer;

class AdTransformer extends JsonResource
{
  public function toArray($request)
  {
    $data = [
      'id' => $this->when(isset($this->id), $this->id),
      'title' => $this->when(isset($this->title), $this->title),
      'slug' => $this->when(isset($this->slug), $this->slug),
      'description' => $this->when(isset($this->description), $this->description),
      'userId' => $this->when(isset($this->userId), $this->userId),
      'status' => $this->when(isset($this->status), $this->status),
      'statusName' => $this->when(isset($this->statusName), $this->statusName),
      'minPrice' => $this->when(isset($this->min_price), $this->min_price),
      'maxPrice' => $this->when(isset($this->max_price), $this->max_price),
      'countryId' => $this->when(isset($this->country_id), $this->country_id),
      'provinceId' => $this->when(isset($this->province_id), $this->province_id),
      'cityId' => $this->when(isset($this->city_id), $this->city_id),
      'featured' => $this->when(isset($this->featured), $this->featured),
      'options' => $this->when(isset($this->options), $this->options),
      'user' => new UserTransformer($this->whenLoaded('user')),
      'categories' => CategoryTransformer::collection($this->whenLoaded('categories')),
      'fields' => FieldTransformer::collection($this->whenLoaded('fields')),
      'schedule' => ScheduleTransformer::collection($this->whenLoaded('schedule')),
      'country' => new CountryTransformer($this->whenLoaded('country')),
      'province' => new ProvinceTransformer($this->whenLoaded('province')),
      'city' => new CityTransformer($this->whenLoaded('city')),
      'mediaFiles' => $this->mediaFiles(),
      'createdAt' => $this->when($this->created_at, $this->created_at),
      'updatedAt' => $this->when($this->updated_at, $this->updated_at),
    ];

    // Return data with available translations
    $filter = json_decode($request->filter);
    if (isset($filter->allTranslations) && $filter->allTranslations) {
      // Get langs avaliables
      $languages = \LaravelLocalization::getSupportedLocales();
      foreach ($languages as $lang => $value) {
        $data[$lang]['title'] = $this->hasTranslation($lang) ? $this->translate("$lang")['title'] : '';
        $data[$lang]['slug'] = $this->hasTranslation($lang) ? $this->translate("$lang")['slug'] : '';
        $data[$lang]['description'] = $this->hasTranslation($lang) ? $this->translate("$lang")['description'] : '';
      }
    }

    return $data;
  }
}
