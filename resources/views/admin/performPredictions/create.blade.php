@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">
            {{ trans('global.create') }} {{ trans('cruds.performPrediction.title_singular') }}
        </div>

        <div class="card-body">
            <div class="form-group">
                <div class="alert alert-warning mt-1 mb-1">Lưu ý: Thêm file chuẩn đoán để máy đưa ra kết quả trước khi lưu.
                </div>
            </div>
            <form method="POST" enctype="multipart/form-data" id="image-upload" action="javascript:void(0)">
                <div class="form-group">
                    <label for="image">File chuẩn đoán</label>
                    <input class="form-control " type="file" name="image" id="image" accept="image/*">
                    <span class="help-block"> </span>
                </div>
            </form>
            <div class="form-group">
                <img id="preview-image-before-upload" src="http://via.placeholder.com/100x60" alt="preview image"
                    style="max-height: 250px;">
                <span class="help-block"> </span>
            </div>

            <form method="POST" action="{{ route('admin.perform-predictions.store') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="created_by_id" value="{{ auth()->user()->id }}">
                <div class="form-group">
                    <label for="ten">{{ trans('cruds.performPrediction.fields.ten') }}</label>
                    <input class="form-control {{ $errors->has('ten') ? 'is-invalid' : '' }}" type="text"
                        name="ten" id="ten" value="{{ old('ten', '') }}">
                    @if ($errors->has('ten'))
                        <div class="invalid-feedback">
                            {{ $errors->first('ten') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.performPrediction.fields.ten_helper') }}</span>
                </div>
                <div class="form-group">
                    <label class="required" for="hinh_anh">{{ trans('cruds.performPrediction.fields.hinh_anh') }}</label>
                    <input class="form-control {{ $errors->has('hinh_anh') ? 'is-invalid' : '' }}" type="text"
                        name="hinh_anh" id="hinh_anh" value="{{ old('hinh_anh', '') }}" required readonly>
                    @if ($errors->has('hinh_anh'))
                        <div class="invalid-feedback">
                            {{ $errors->first('hinh_anh') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.performPrediction.fields.hinh_anh_helper') }}</span>
                </div>
                <div class="form-group">
                    <label class="required">{{ trans('cruds.performPrediction.fields.kqdd') }}</label>
                    <select class="form-control {{ $errors->has('kqdd') ? 'is-invalid' : '' }}" name="kqdd"
                        id="kqdd" required>
                        <option value disabled {{ old('kqdd', null) === null ? 'selected' : '' }}>
                            Tự động điền khi có kết quả chuẩn đoán</option>
                        @foreach (App\Models\PerformPrediction::KQDD_SELECT as $key => $label)
                            <option value="{{ $key }}"
                                {{ old('kqdd', '') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('kqdd'))
                        <div class="invalid-feedback">
                            {{ $errors->first('kqdd') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.performPrediction.fields.kqdd_helper') }}</span>
                </div>
                <div class="form-group">
                    <label>{{ trans('cruds.performPrediction.fields.kqtt') }}</label>
                    <select class="form-control {{ $errors->has('kqtt') ? 'is-invalid' : '' }}" name="kqtt"
                        id="kqtt">
                        <option value disabled {{ old('kqtt', null) === null ? 'selected' : '' }}>
                            {{ trans('global.pleaseSelect') }}</option>
                        @foreach (App\Models\PerformPrediction::KQTT_SELECT as $key => $label)
                            <option value="{{ $key }}"
                                {{ old('kqtt', '0') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('kqtt'))
                        <div class="invalid-feedback">
                            {{ $errors->first('kqtt') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.performPrediction.fields.kqtt_helper') }}</span>
                </div>
                <div class="form-group">
                    <button class="btn btn-danger submit-button" type="submit" disabled>
                        {{ trans('global.save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('scripts')
    <script type="text/javascript">
        $(document).ready(function(e) {
            $('#kqdd').css('pointer-events', 'none');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('#image').change(function() {
                let reader = new FileReader();
                reader.onload = (e) => {
                    $('#preview-image-before-upload').attr('src', e.target.result);
                }
                reader.readAsDataURL(this.files[0]);
                $('#image-upload').submit();
            });
            $('#image-upload').submit(function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    type: 'POST',
                    url: "{{ route('admin.perform-prediction-demos.uploadImage') }}",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: (data) => {
                        this.reset();
                        $("#hinh_anh").val(data);
                        $("#kqdd").val(1).change();
                        $('.submit-button').prop("disabled", false);
                    },
                    error: function(data) {
                        console.log(data);
                    }
                });
            });
        });
    </script>
@endsection
