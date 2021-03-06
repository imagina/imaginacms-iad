<?php

namespace Modules\Iad\Entities;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Modules\Media\Support\Traits\MediaRelation;
use Modules\Iad\Entities\Category;
use Modules\Iad\Entities\Field;
use Modules\Iad\Entities\Schedule;
use Modules\Ilocations\Entities\Country;
use Modules\Ilocations\Entities\Province;
use Modules\Ilocations\Entities\City;
use Modules\Iad\Entities\AdStatus;

class Ad extends Model
{
  use Translatable, MediaRelation;

  protected $table = 'iad__ads';
  public $translatedAttributes = ['title', 'description', 'slug'];
  protected $fillable = [
    'user_id',
    'status',
    'min_price',
    'max_price',
    'country_id',
    'province_id',
    'city_id',
    'featured',
    'options',
  ];
  protected $fakeColumns = ['options'];
  protected $casts = ['options' => 'array'];

  public function user()
  {
    $driver = config('asgard.user.config.driver');
    return $this->belongsTo("Modules\\User\\Entities\\{$driver}\\User");
  }

  public function categories()
  {
    return $this->belongsToMany(Category::class, 'iad__ad_category');
  }

  public function fields()
  {
    return $this->hasMany(Field::class);
  }

  public function schedule()
  {
    return $this->hasMany(Schedule::class);
  }

  public function country()
  {
    return $this->belongsTo(Country::class);
  }

  public function province()
  {
    return $this->belongsTo(Province::class);
  }

  public function city()
  {
    return $this->belongsTo(City::class);
  }

  public function getStatusNameAttribute(){
    $adStatuses = new AdStatus();
    return collect($adStatuses->get())->where('id', $this->status)->pluck('name')->first();
  }
}
