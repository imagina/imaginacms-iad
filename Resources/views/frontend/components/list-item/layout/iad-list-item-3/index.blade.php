<div class="pins">
  <div class="card-pin {{$item->featured ? 'featured' : ''}}">
    <div class="card-pin-id">
      <a href="{{$item->url}}" class="d-block">{{$item->id}}</a>
    </div>
    <figure class="figure">
      <x-media::single-image :alt="$item->title ?? $item->name"
                             :title="$item->title ?? $item->name"
                             :url="$item->url ?? null" :isMedia="true"
                             imgClasses="d-block"
                             :mediaFiles="$item->mediaFiles()"/>
    </figure>
    <a href="{{$item->url}}" class="d-block">
    <div class="card-pin-body p-0">
      <div class="card-pin-title-main">
        {{$item->title}}
      </div>
      <div class="row no-gutters">
        @if(isset($item->locatable->city->name))
        <div class="col-12">
          <div class="card-pin-location">
              <i class="fa fa-map-marker"></i>
              {{$item->locatable->city->name}}, {{$item->locatable->province->name ?? ""}}
          </div>
        </div>
        @endif
        @foreach($item->categories as $category)
        @if($category->parent_id == 2)
          <div class="col-auto mr-2">
            <div class="icon">
              @if(isset($category->mediaFiles()->mainimage) &&
                      !empty($category->mediaFiles()->mainimage) &&
                       strpos($category->mediaFiles()->mainimage->extraLargeThumb, 'default.jpg') == false)
                  <x-media::single-image
                          imgClasses="icon"
                          :mediaFiles="$category->mediaFiles()"
                          :isMedia="true" :alt="$category->title"
                  />
              @else
                  <x-media::single-image
                          imgClasses="icon"
                          setting="icustom::imageDefault"/>
              @endif
            </div>
            <div class="card-pin-category" > {{$category->title}}</div>
          </div>
        @endif
        @endforeach
        <div class="col col-extra">
          @foreach($item->fields as $field)
            @if(isset($field->name) && ($field->name == 'bpin'))
                <div class="card-pin-title">
                  {{ trans('icustom::common.crudFields.bpni') }}
                </div>
                <div class="card-pin-text" title="{{$field->value}}">
                  {{$field->value}}
                </div>
            @endif
          @endforeach
          @if(!empty($item->min_price))
            <div class="card-pin-title">{{ trans('icustom::common.crudFields.worth') }}</div>
            <div class="card-pin-text text-color">{{"$" . number_format($item->min_price, 0, ",", ".")}}</div>
          @endif
        </div>
      </div>
    </div>
    </a>
  </div>

</div>
