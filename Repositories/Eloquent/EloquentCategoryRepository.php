<?php

namespace Modules\Iad\Repositories\Eloquent;

use Modules\Core\Icrud\Repositories\Eloquent\EloquentCrudRepository;
use Modules\Iad\Repositories\CategoryRepository;
use Modules\Core\Repositories\Eloquent\EloquentBaseRepository;
use Modules\Ihelpers\Events\CreateMedia;
use Modules\Ihelpers\Events\DeleteMedia;
use Modules\Ihelpers\Events\UpdateMedia;

class EloquentCategoryRepository extends EloquentCrudRepository implements CategoryRepository
{
  /**
   * Filter names to replace
   * @var array
   */
  protected $replaceFilters = [];

  /**
   * Relation names to replace
   * @var array
   */
  protected $replaceSyncModelRelations = [];

  /**
   * Attribute to customize relations by default
   * @var array
   */
  protected $includeToQuery = ['files', 'translations'];


  /**
   * Filter query
   *
   * @param $query
   * @param $filter
   * @return mixed
   */
  public function filterQuery($query, $filter, $params)
  {
    //Filter search
    if (isset($filter->search)) {
      $query->where(function ($query) use ($filter) {
        $query->whereHas('translations', function ($q) use ($filter) {
          $q->where('title', 'like', "%{$filter->search}%");
        });
      })->orWhere('id', 'like', "%{$filter->search}%");
    }


    //Filter by parent ID
    if (isset($filter->parentId)) {
      if ($filter->parentId == 0) {
        $query->whereNull("parent_id");
      } else {
        $query->where("parent_id", $filter->parentId);
      }
    }

    //Filter by  IDs
    if (isset($filter->ids)) {
      is_array($filter->ids) ? true : $filter->ids = [$filter->ids];
      $query->whereIn('iad__categories.id', $filter->ids);
    }

    if (isset($params->setting) && isset($params->setting->fromAdmin) && $params->setting->fromAdmin) {

    } else {

      //Pre filters by default
      //pre-filter date_available
      //Pre filters by default
      $this->defaultPreFilters($query, $params);
    }

    //Order by "Sort order"
    if (!isset($params->filter->noSortOrder) || !$params->filter->noSortOrder) {
      $query->orderBy('sort_order', 'desc');//Add order to query
    }

    //Response
    return $query;
  }

  /**
   * Method to sync Model Relations
   *
   * @param $model ,$data
   * @return $model
   */
  public function syncModelRelations($model, $data)
  {
    //Get model relations data from attribute of model
    $modelRelationsData = ($model->modelRelations ?? []);

    /**
     * Note: Add relation name to replaceSyncModelRelations attribute before replace it
     *
     * Example to sync relations
     * if (array_key_exists(<relationName>, $data)){
     *    $model->setRelation(<relationName>, $model-><relationName>()->sync($data[<relationName>]));
     * }
     *
     */

    //Response
    return $model;
  }

  public function findBySlug($slug)
  {
    if (method_exists($this->model, 'translations')) {


      $query = $this->model->whereHas('translations', function (Builder $q) use ($slug) {
        $q->where('slug', $slug);
      })->with('translations', 'parent', 'children');

    } else
      $query = $this->model->where('slug', $slug)->with('translations', 'parent', 'children', 'vehicles');


    if (isset($this->model->tenantWithCentralData) && $this->model->tenantWithCentralData && isset(tenant()->id)) {
      $model = $this->model;
      $query->withoutTenancy();
      $query->where(function ($query) use ($model) {
        $query->where($model->qualifyColumn(BelongsToTenant::$tenantIdColumn), tenant()->getTenantKey())
          ->orWhereNull($model->qualifyColumn(BelongsToTenant::$tenantIdColumn));
      });
    }

    return $query->first();
  }

  public function defaultPreFilters($query, $params)
  {

    //Pre filters by default
    $query->where("status", 1);

  }
}
