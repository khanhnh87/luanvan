<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyPerformPredictionRequest;
use App\Http\Requests\StorePerformPredictionRequest;
use App\Http\Requests\UpdatePerformPredictionRequest;
use App\Models\PerformPrediction;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PerformPredictionController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('perform_prediction_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $performPredictions = PerformPrediction::with(['created_by'])->where('created_by_id', auth()->user()->id)->get();
        if (auth()->user()->isAdministrator()) {
            $performPredictions = PerformPrediction::with(['created_by'])->get();
        }
        return view('admin.performPredictions.index', compact('performPredictions'));
    }

    public function uploadImage(Request $request)
    {
        $validatedData = $request->validate([
            'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg',

        ]);

        $name = $request->file('image')->getClientOriginalName();

        $path = $request->file('image')->store('public/images');


        // $save = new Photo;

        // $save->name = $name;
        // $save->path = $path;

        // $save->save();
        $fullPath = url(str_replace('public', 'storage', $path));
        return response()->json($fullPath);
    }

    public function create()
    {
        abort_if(Gate::denies('perform_prediction_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.performPredictions.create');
    }

    public function store(StorePerformPredictionRequest $request)
    {
        $performPrediction = PerformPrediction::create($request->all());

        return redirect()->route('admin.perform-predictions.index');
    }

    public function edit(PerformPrediction $performPrediction)
    {
        abort_if(Gate::denies('perform_prediction_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $performPrediction->load('created_by');

        return view('admin.performPredictions.edit', compact('performPrediction'));
    }

    public function update(UpdatePerformPredictionRequest $request, PerformPrediction $performPrediction)
    {
        $performPrediction->update($request->all());

        return redirect()->route('admin.perform-predictions.index');
    }

    public function show(PerformPrediction $performPrediction)
    {
        abort_if(Gate::denies('perform_prediction_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $performPrediction->load('created_by');

        return view('admin.performPredictions.show', compact('performPrediction'));
    }

    public function destroy(PerformPrediction $performPrediction)
    {
        abort_if(Gate::denies('perform_prediction_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $performPrediction->delete();

        return back();
    }

    public function massDestroy(MassDestroyPerformPredictionRequest $request)
    {
        PerformPrediction::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
