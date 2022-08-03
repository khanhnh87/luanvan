@extends('layouts.admin')
@section('content')
    @can('perform_prediction_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('admin.perform-predictions.create') }}">
                    {{ trans('global.add') }} {{ trans('cruds.performPrediction.title_singular') }}
                </a>
            </div>
        </div>
    @endcan
    <div class="card">
        <div class="card-header">
            {{ trans('cruds.performPrediction.title_singular') }} {{ trans('global.list') }}
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class=" table table-bordered table-striped table-hover datatable datatable-PerformPrediction">
                    <thead>
                        <tr>
                            <th width="10">

                            </th>
                            <th>
                                {{ trans('cruds.performPrediction.fields.id') }}
                            </th>
                            <th>
                                {{ trans('cruds.performPrediction.fields.ten') }}
                            </th>
                            <th>
                                {{ trans('cruds.performPrediction.fields.hinh_anh') }}
                            </th>
                            <th>
                                {{ trans('cruds.performPrediction.fields.kqdd') }}
                            </th>
                            <th>
                                {{ trans('cruds.performPrediction.fields.kqtt') }}
                            </th>
                            <th>
                                {{ trans('cruds.performPrediction.fields.created_by') }}
                            </th>
                            <th>
                                &nbsp;
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($performPredictions as $key => $performPrediction)
                            <tr data-entry-id="{{ $performPrediction->id }}">
                                <td>

                                </td>
                                <td>
                                    {{ $performPrediction->id ?? '' }}
                                </td>
                                <td>
                                    {{ $performPrediction->ten ?? '' }}
                                </td>
                                <td>
                                    <a href="{{ $performPrediction->hinh_anh ?? '' }}" target="_blank"><img
                                            src="{{ $performPrediction->hinh_anh ?? '' }}" alt=""
                                            style="max-height: 50px;"></a>
                                </td>
                                <td>
                                    {{ App\Models\PerformPrediction::KQDD_SELECT[$performPrediction->kqdd] ?? '' }}
                                </td>
                                <td>
                                    {{ App\Models\PerformPrediction::KQTT_SELECT[$performPrediction->kqtt] ?? '' }}
                                </td>
                                <td>
                                    {{ $performPrediction->created_by->name ?? '' }}
                                </td>
                                <td>
                                    @can('perform_prediction_show')
                                        <a class="btn btn-xs btn-primary"
                                            href="{{ route('admin.perform-predictions.show', $performPrediction->id) }}">
                                            {{ trans('global.view') }}
                                        </a>
                                    @endcan

                                    @can('perform_prediction_edit')
                                        <a class="btn btn-xs btn-info"
                                            href="{{ route('admin.perform-predictions.edit', $performPrediction->id) }}">
                                            {{ trans('global.edit') }}
                                        </a>
                                    @endcan

                                    @can('perform_prediction_delete')
                                        <form
                                            action="{{ route('admin.perform-predictions.destroy', $performPrediction->id) }}"
                                            method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');"
                                            style="display: inline-block;">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <input type="submit" class="btn btn-xs btn-danger"
                                                value="{{ trans('global.delete') }}">
                                        </form>
                                    @endcan

                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    @parent
    <script>
        $(function() {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
            @can('perform_prediction_delete')
                let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
                let deleteButton = {
                    text: deleteButtonTrans,
                    url: "{{ route('admin.perform-predictions.massDestroy') }}",
                    className: 'btn-danger',
                    action: function(e, dt, node, config) {
                        var ids = $.map(dt.rows({
                            selected: true
                        }).nodes(), function(entry) {
                            return $(entry).data('entry-id')
                        });

                        if (ids.length === 0) {
                            alert('{{ trans('global.datatables.zero_selected') }}')

                            return
                        }

                        if (confirm('{{ trans('global.areYouSure') }}')) {
                            $.ajax({
                                    headers: {
                                        'x-csrf-token': _token
                                    },
                                    method: 'POST',
                                    url: config.url,
                                    data: {
                                        ids: ids,
                                        _method: 'DELETE'
                                    }
                                })
                                .done(function() {
                                    location.reload()
                                })
                        }
                    }
                }
                dtButtons.push(deleteButton)
            @endcan

            $.extend(true, $.fn.dataTable.defaults, {
                orderCellsTop: true,
                order: [
                    [1, 'desc']
                ],
                pageLength: 100,
            });
            let table = $('.datatable-PerformPrediction:not(.ajaxTable)').DataTable({
                buttons: dtButtons
            })
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        })
    </script>
@endsection
