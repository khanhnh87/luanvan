<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyChangeModelRequest;
use App\Http\Requests\StoreChangeModelRequest;
use App\Http\Requests\UpdateChangeModelRequest;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class ChangeModelController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('change_model_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $files = Storage::disk('public')->allFiles('files');
        return view('admin.changeModels.index', compact('files'));
    }

    public function updateModelFiles(Request $request)
    {
        $validatedData = $request->validate([
            'files' => 'required',
        ]);
        if ($request->hasfile('files')) {
            $files =   Storage::allFiles('public/files');

            // Delete old Files
            Storage::delete($files);


            foreach ($request->file('files') as $key => $file) {
                $path = $file->store('public/files');
                $name = $file->getClientOriginalName();
            }
        }
        return redirect()->route('admin.change-models.index');
    }

    public function create()
    {
        abort_if(Gate::denies('change_model_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.changeModels.create');
    }

    public function store(StoreChangeModelRequest $request)
    {
        $changeModel = ChangeModel::create($request->all());

        return redirect()->route('admin.change-models.index');
    }

    public function edit(ChangeModel $changeModel)
    {
        abort_if(Gate::denies('change_model_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.changeModels.edit', compact('changeModel'));
    }

    public function update(UpdateChangeModelRequest $request, ChangeModel $changeModel)
    {
        $changeModel->update($request->all());

        return redirect()->route('admin.change-models.index');
    }

    public function show(ChangeModel $changeModel)
    {
        abort_if(Gate::denies('change_model_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.changeModels.show', compact('changeModel'));
    }

    public function destroy(ChangeModel $changeModel)
    {
        abort_if(Gate::denies('change_model_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $changeModel->delete();

        return back();
    }

    public function massDestroy(MassDestroyChangeModelRequest $request)
    {
        ChangeModel::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
