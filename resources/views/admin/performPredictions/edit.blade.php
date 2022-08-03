@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.performPrediction.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.perform-predictions.update", [$performPrediction->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label for="ten">{{ trans('cruds.performPrediction.fields.ten') }}</label>
                <input class="form-control {{ $errors->has('ten') ? 'is-invalid' : '' }}" type="text" name="ten" id="ten" value="{{ old('ten', $performPrediction->ten) }}">
                @if($errors->has('ten'))
                    <div class="invalid-feedback">
                        {{ $errors->first('ten') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.performPrediction.fields.ten_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="hinh_anh">{{ trans('cruds.performPrediction.fields.hinh_anh') }}</label>
                <input class="form-control {{ $errors->has('hinh_anh') ? 'is-invalid' : '' }}" type="text" name="hinh_anh" id="hinh_anh" value="{{ old('hinh_anh', $performPrediction->hinh_anh) }}" required>
                @if($errors->has('hinh_anh'))
                    <div class="invalid-feedback">
                        {{ $errors->first('hinh_anh') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.performPrediction.fields.hinh_anh_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required">{{ trans('cruds.performPrediction.fields.kqdd') }}</label>
                <select class="form-control {{ $errors->has('kqdd') ? 'is-invalid' : '' }}" name="kqdd" id="kqdd" required>
                    <option value disabled {{ old('kqdd', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\PerformPrediction::KQDD_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('kqdd', $performPrediction->kqdd) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('kqdd'))
                    <div class="invalid-feedback">
                        {{ $errors->first('kqdd') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.performPrediction.fields.kqdd_helper') }}</span>
            </div>
            <div class="form-group">
                <label>{{ trans('cruds.performPrediction.fields.kqtt') }}</label>
                <select class="form-control {{ $errors->has('kqtt') ? 'is-invalid' : '' }}" name="kqtt" id="kqtt">
                    <option value disabled {{ old('kqtt', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\PerformPrediction::KQTT_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('kqtt', $performPrediction->kqtt) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('kqtt'))
                    <div class="invalid-feedback">
                        {{ $errors->first('kqtt') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.performPrediction.fields.kqtt_helper') }}</span>
            </div>
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>



@endsection