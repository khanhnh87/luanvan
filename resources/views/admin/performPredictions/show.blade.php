@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.performPrediction.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.perform-predictions.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.performPrediction.fields.id') }}
                        </th>
                        <td>
                            {{ $performPrediction->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.performPrediction.fields.ten') }}
                        </th>
                        <td>
                            {{ $performPrediction->ten }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.performPrediction.fields.hinh_anh') }}
                        </th>
                        <td>
                            {{ $performPrediction->hinh_anh }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.performPrediction.fields.kqdd') }}
                        </th>
                        <td>
                            {{ App\Models\PerformPrediction::KQDD_SELECT[$performPrediction->kqdd] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.performPrediction.fields.kqtt') }}
                        </th>
                        <td>
                            {{ App\Models\PerformPrediction::KQTT_SELECT[$performPrediction->kqtt] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.performPrediction.fields.created_by') }}
                        </th>
                        <td>
                            {{ $performPrediction->created_by->name ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.perform-predictions.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection