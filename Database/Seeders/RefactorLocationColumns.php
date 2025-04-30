<?php

namespace Modules\Iad\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class RefactorLocationColumns extends Seeder
{

  /**
   * Run the database seeds.
   */
  public function run()
  {
    $seedUniquesUse = DB::table('isite__seeds')->where('name', 'RefactorLocationColumns')->first();
    if (empty($seedUniquesUse)) {
      $ads = DB::table('iad__ads')->get();
      foreach ($ads as $ad) {
        if (!is_null($ad->country_id) || !is_null($ad->province_id) || !is_null($ad->city_id)) {
          DB::table('ilocations__locatables')->insert([
            'entity_type' => 'Modules\Iad\Entities\Ad',
            'entity_id' => $ad->id,
            'country_id' => $ad->country_id,
            'province_id' => $ad->province_id,
            'city_id' => $ad->city_id
          ]);
        }
      }
      Schema::table('iad__ads', function (Blueprint $table) {
        $table->dropForeign(['country_id']);
        $table->dropForeign(['province_id']);
        $table->dropForeign(['city_id']);

        $table->dropColumn('country_id');
        $table->dropColumn('province_id');
        $table->dropColumn('city_id');
      });
      DB::table('isite__seeds')->insert(['name' => 'RefactorLocationColumns']);
    }
  }
}