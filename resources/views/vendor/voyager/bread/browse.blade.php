@extends('voyager::master')

@section('page_title', __('voyager::generic.viewing').' '.$dataType->getTranslatedAttribute('display_name_plural'))
@section('head')
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
@endsection

@section('page_header')
    <div class="container-fluid">
        <h1 class="page-title">
            <i class="{{ $dataType->icon }}"></i> {{ $dataType->getTranslatedAttribute('display_name_plural') }}
        </h1>
        @can('add', app($dataType->model_name))
            <a href="{{ route('voyager.'.$dataType->slug.'.create') }}" class="btn btn-success btn-add-new">
                <i class="voyager-plus"></i> <span>{{ __('voyager::generic.add_new') }}</span>
            </a>
        @endcan
        @can('delete', app($dataType->model_name))
            @include('voyager::partials.bulk-delete')
        @endcan
        @can('edit', app($dataType->model_name))
            @if(!empty($dataType->order_column) && !empty($dataType->order_display_column))
                <a href="{{ route('voyager.'.$dataType->slug.'.order') }}" class="btn btn-primary btn-add-new">
                    <i class="voyager-list"></i> <span>{{ __('voyager::bread.order') }}</span>
                </a>
            @endif
        @endcan
        @can('delete', app($dataType->model_name))
            @if($usesSoftDeletes)
                <input type="checkbox" @if ($showSoftDeleted) checked @endif id="show_soft_deletes" data-toggle="toggle" data-on="{{ __('voyager::bread.soft_deletes_off') }}" data-off="{{ __('voyager::bread.soft_deletes_on') }}">
            @endif
        @endcan
        @foreach($actions as $action)
            @if (method_exists($action, 'massAction'))
                @include('voyager::bread.partials.actions', ['action' => $action, 'data' => null])
            @endif
        @endforeach
        @include('voyager::multilingual.language-selector')
    </div>
@stop

@section('content')
    <div class="page-content browse container-fluid">
        @include('voyager::alerts')

        {{-- current --}}
        <div class="row" x-data="datatable()">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="col-md-12" style="margin: 20px 0; padding: 0 80px; display: flex; justify-content: space-between;">
                        <div class="col-md-2">
                            <label for="">Élements affichés</label>
                            <select x-model="size" name="" class="form-control" id="">
                                <template x-for="index in 5">
                                    <option :value="parseInt(index * 4)" x-text="index * 4"></option>
                                </template>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="">Rechercher</label>
                            <input
                                x-ref="searchField"
                                x-model="search"
                                x-on:click="viewPage(0)"
                                x-on:keydown.window.prevent.slash=" viewPage(0), $refs.searchField.focus()"
                                placeholder="Search for an employee..."
                                type="search"
                                class="form-control"
                            />
                        </div>
                    </div>
                    <style>
                        #panel-body {
                            display: flex;
                            flex-wrap: wrap;
                            gap: 5px;
                            justify-content: center;
                            align-items: center;
                        }
                    </style>
                    <div id="panel-body" class="panel-body">

                        {{-- listing  --}}
                        {{-- @foreach($dataTypeContent as $data)
                        <div class="card col-md-3" style="width: 18rem; border: 1px solid lightgrey; padding: 0;">
                            <img src="https://placehold.co/250x200"  class="card-img-top" alt="...">
                            <div class="card-body" style="padding: 10px 15px;">
                                @foreach($dataType->browseRows as $row)
                                    @php
                                    if ($data->{$row->field.'_browse'}) {
                                        $data->{$row->field} = $data->{$row->field.'_browse'};
                                    }
                                    @endphp
                                    <h5>
                                        {{ $data->{$row->field}  }}
                                    </h5>
                                @endforeach
                                <div class="button-group" style="display: flex; justify-content: space-around;">
                                    <a href="{{ route('voyager.'. $dataType->slug .'.show', $data->id) }}" title="Vue" class="btn btn-sm btn-success view">
                                        <i class="voyager-eye"></i>
                                    </a>
                                    <a href="{{ route('voyager.'. $dataType->slug .'.edit', $data->id) }}" title="Editer" class="btn btn-sm btn-primary edit">
                                        <i class="voyager-edit"></i>
                                    </a>
                                    <a href="javascript:;" title="Supprimer" class="btn btn-sm btn-danger delete" data-id="{{ $data->id }}" id="delete-{{ $data->id }}">
                                        <i class="voyager-trash"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach --}}

                        <template x-for="item in filteredData" :key="item">
                            <div class="card col-md-3" style="width: 18rem; border: 1px solid lightgrey; padding: 0;">
                                <img src="https://placehold.co/250x200"  class="card-img-top" alt="...">
                                <div class="card-body" style="padding: 10px 15px;">
                                    @foreach($dataType->browseRows as $row)
                                    <template x-if="!'{{ $row->field }}'.includes('relationship')">
                                        <h5>
                                            <span x-text="'{{ $row->field }} :'" style="text-transform: capitalize; font-weight: bold;"></span>
                                            <span x-text="item['{{ $row->field }}']"></span>
                                        </h5>
                                    </template>
                                    @endforeach

                                    {{-- <a href="#" class="btn btn-primary">Go somewhere</a> --}}
                                    <div class="button-group" style="display: flex; justify-content: space-around;">
                                        <a :href="`/admin/${slug}/${item.id}`" title="Vue" class="btn btn-sm btn-success view">
                                            <i class="voyager-eye"></i>
                                        </a>
                                        <a :href="`/admin/${slug}/${item.id}/edit`" title="Editer" class="btn btn-sm btn-primary edit">
                                            <i class="voyager-edit"></i>
                                        </a>
                                        <a href="javascript:;" title="Supprimer" class="btn btn-sm btn-danger delete" :data-id="item.id" :id="`delete-${item.id}`">
                                            <i class="voyager-trash"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </template>
                        {{-- end of listing --}}

                        {{-- pagination --}}
                        <div class="col-md-12">
                            <div x-show="pageCount() > 1" class="" style="width: max-content; margin: 0 auto; padding: 0 25px; display: flex; justify-content: space-between; align-items: center;">
                                <!--First Button-->
                                <button
                                    x-on:click="viewPage(0)"
                                    :disabled="pageNumber==0"
                                    :class="{ 'disabled cursor-not-allowed text-gray-600' : pageNumber==0 }"
                                >
                                    <svg
                                        class="h-8 w-8 text-indigo-600"
                                        width="24"
                                        height="24"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    >
                                        <polygon points="19 20 9 12 19 4 19 20"></polygon>
                                        <line x1="5" y1="19" x2="5" y2="5"></line>
                                    </svg>
                                </button>

                                <!--Previous Button-->
                                <button
                                    x-on:click="prevPage"
                                    :disabled="pageNumber==0"
                                    :class="{ 'disabled cursor-not-allowed text-gray-600' : pageNumber==0 }"
                                >
                                    <svg
                                        class="h-8 w-8 text-indigo-600"
                                        width="24"
                                        height="24"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    >
                                        <polyline points="15 18 9 12 15 6"></polyline>
                                    </svg>
                                </button>

                                <!-- Display page numbers -->
                                <template x-for="(page,index) in pages()" :key="index">
                                    <button
                                        class="px-3 py-2 rounded"
                                        :class="{ 'bg-indigo-600 text-white font-bold' : index === pageNumber }"
                                        type="button"
                                        x-on:click="viewPage(index)"
                                    >
                                        <span x-text="index+1"></span>
                                    </button>
                                </template>

                                <!--Next Button-->
                                <button
                                x-on:click="nextPage"
                                :disabled="pageNumber >= pageCount() -1"
                                :class="{ 'disabled cursor-not-allowed text-gray-600' : pageNumber >= pageCount() -1 }"
                                >
                                <svg
                                    class="h-8 w-8 text-indigo-600"
                                    width="24"
                                    height="24"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                >
                                    <polyline points="9 18 15 12 9 6"></polyline>
                                </svg>
                                </button>

                                <!--Last Button-->
                                <button
                                    x-on:click="viewPage(Math.ceil(total/size)-1)"
                                    :disabled="pageNumber >= pageCount() -1"
                                    :class="{ 'disabled cursor-not-allowed text-gray-600' : pageNumber >= pageCount() -1 }"
                                >
                                    <svg
                                        class="h-8 w-8 text-indigo-600"
                                        width="24"
                                        height="24"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    >
                                        <polygon points="5 4 15 12 5 20 5 4"></polygon>
                                        <line x1="19" y1="5" x2="19" y2="19"></line>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        {{-- end of pagination --}}

                        {{-- results details --}}
                        <div class="col-md-12">
                            <div style="margin-top: 12px; padding: 0 25px; font-size: 1.2rem; display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center;">
                                <div
                                    class="w-full sm:w-auto text-center sm:text-left"
                                    x-show="pageCount() > 1"
                                >
                                    Page <span x-text="pageNumber+1"> </span> of
                                    <span x-text="pageCount()"></span> | Showing
                                    <span x-text="startResults()"></span> to
                                    <span x-text="endResults()"></span>
                                </div>

                                <div
                                    class="w-full sm:w-auto text-center sm:text-right"
                                    x-show="total > 0"
                                >
                                    Total <span class="font-bold" x-text="total"></span> results
                                </div>

                                <!--Message to display when no results-->
                                <div
                                    class="mx-auto flex items-center font-bold text-red-500"
                                    x-show="total===0"
                                >
                                    <svg
                                        class="h-8 w-8 text-red-500"
                                        viewBox="0 0 24 24"
                                        stroke-width="2"
                                        stroke="currentColor"
                                        fill="none"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    >
                                        <path stroke="none" d="M0 0h24v24H0z" />
                                        <circle cx="12" cy="12" r="9" />
                                        <line x1="9" y1="10" x2="9.01" y2="10" />
                                        <line x1="15" y1="10" x2="15.01" y2="10" />
                                        <path d="M9.5 16a10 10 0 0 1 6 -1.5" />
                                    </svg>

                                    <span class="ml-4"> No results!!</span>
                                </div>
                            </div>
                        </div>
                        {{-- end of results details --}}

                    </div>

                </div>
            </div>
        </div>

        <script>
            var sourceData = {!! json_encode($dataTypeContent) !!}
            function datatable() {
                return {
                    search: "",
                    slug: '{{ $dataType->slug }}',
                    pageNumber: 0,
                    size: 4,
                    total: "",
                    myForData: sourceData,

                    get filteredData() {
                        const start = this.pageNumber * this.size, end = start + this.size;
                        // console.log(this.size)
                        if (this.search === "") {
                            this.total = this.myForData.length;
                            return this.myForData.slice(start, end);
                        }

                        //Return the total results of the filters
                        this.total = this.myForData.filter((item) => {
                            return item.firstname
                                .toLowerCase()
                                .includes(this.search.toLowerCase());
                        }).length;

                        //Return the filtered data
                        return this.myForData
                            .filter((item) => {
                                return item.firstname
                                .toLowerCase()
                                .includes(this.search.toLowerCase());
                        }).slice(start, end);
                    },
                    //Create array of all pages (for loop to display page numbers)
                    pages() {
                        return Array.from({
                        length: Math.ceil(this.total / this.size),
                        });
                    },
                    //Next Page
                    nextPage() {
                        this.pageNumber++;
                    },
                    //Previous Page
                    prevPage() {
                        this.pageNumber--;
                    },
                    //Total number of pages
                    pageCount() {
                        return Math.ceil(this.total / this.size);
                    },
                    //Return the start range of the paginated results
                    startResults() {
                        return this.pageNumber * this.size + 1;
                    },
                    //Return the end range of the paginated results
                    endResults() {
                        let resultsOnPage = (this.pageNumber + 1) * this.size;

                        if (resultsOnPage <= this.total) {
                        return resultsOnPage;
                        }

                        return this.total;
                    },
                    //Link to navigate to page
                    viewPage(index) {
                        this.pageNumber = index;
                    },
                };
            }
        </script>
    </div>

    {{-- Single delete modal --}}
    <div class="modal modal-danger fade" tabindex="-1" id="delete_modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('voyager::generic.close') }}"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="voyager-trash"></i> {{ __('voyager::generic.delete_question') }} {{ strtolower($dataType->getTranslatedAttribute('display_name_singular')) }}?</h4>
                </div>
                <div class="modal-footer">
                    <form action="#" id="delete_form" method="POST">
                        {{ method_field('DELETE') }}
                        {{ csrf_field() }}
                        <input type="submit" class="btn btn-danger pull-right delete-confirm" value="{{ __('voyager::generic.delete_confirm') }}">
                    </form>
                    <button type="button" class="btn btn-default pull-right" data-dismiss="modal">{{ __('voyager::generic.cancel') }}</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
@stop

@section('css')
@if(!$dataType->server_side && config('dashboard.data_tables.responsive'))
    <link rel="stylesheet" href="{{ voyager_asset('lib/css/responsive.dataTables.min.css') }}">
@endif
@stop

@section('javascript')
    <!-- DataTables -->
    @if(!$dataType->server_side && config('dashboard.data_tables.responsive'))
        <script src="{{ voyager_asset('lib/js/dataTables.responsive.min.js') }}"></script>
    @endif
    <script>
        $(document).ready(function () {
            @if (!$dataType->server_side)
                var table = $('#dataTable').DataTable({!! json_encode(
                    array_merge([
                        "order" => $orderColumn,
                        "language" => __('voyager::datatable'),
                        "columnDefs" => [
                            ['targets' => 'dt-not-orderable', 'searchable' =>  false, 'orderable' => false],
                        ],
                    ],
                    config('voyager.dashboard.data_tables', []))
                , true) !!});
            @else
                $('#search-input select').select2({
                    minimumResultsForSearch: Infinity
                });
            @endif

            @if ($isModelTranslatable)
                $('.side-body').multilingual();
                //Reinitialise the multilingual features when they change tab
                $('#dataTable').on('draw.dt', function(){
                    $('.side-body').data('multilingual').init();
                })
            @endif
            $('.select_all').on('click', function(e) {
                $('input[name="row_id"]').prop('checked', $(this).prop('checked')).trigger('change');
            });
        });


        var deleteFormAction;
        $('td').on('click', '.delete', function (e) {
            $('#delete_form')[0].action = '{{ route('voyager.'.$dataType->slug.'.destroy', '__id') }}'.replace('__id', $(this).data('id'));
            $('#delete_modal').modal('show');
        });

        @if($usesSoftDeletes)
            @php
                $params = [
                    's' => $search->value,
                    'filter' => $search->filter,
                    'key' => $search->key,
                    'order_by' => $orderBy,
                    'sort_order' => $sortOrder,
                ];
            @endphp
            $(function() {
                $('#show_soft_deletes').change(function() {
                    if ($(this).prop('checked')) {
                        $('#dataTable').before('<a id="redir" href="{{ (route('voyager.'.$dataType->slug.'.index', array_merge($params, ['showSoftDeleted' => 1]), true)) }}"></a>');
                    }else{
                        $('#dataTable').before('<a id="redir" href="{{ (route('voyager.'.$dataType->slug.'.index', array_merge($params, ['showSoftDeleted' => 0]), true)) }}"></a>');
                    }

                    $('#redir')[0].click();
                })
            })
        @endif
        $('input[name="row_id"]').on('change', function () {
            var ids = [];
            $('input[name="row_id"]').each(function() {
                if ($(this).is(':checked')) {
                    ids.push($(this).val());
                }
            });
            $('.selected_ids').val(ids);
        });
    </script>
@stop
