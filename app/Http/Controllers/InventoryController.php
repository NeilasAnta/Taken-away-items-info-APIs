<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class InventoryController extends Controller
{
    public function show()
    {
        if(auth()->user()->isAdmin)
        {
            return response()->json(Inventory::latest()->filter(request(['search', 'status']))->get());
        }else{
            return response()->json(Inventory::where('user_id', null)->get());
        }
    }
    //
    public function store(Request $request)
    {
        $validator = $this->modelAndSerialNumberValidation($request);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => $validator->messages()
            ], 400);
        }

        Inventory::create(array_merge(
                        $validator->validated(),
                        ['comment' => $request->input('comment')],
                        ));
        return response()->json([
            'status'  => 'ok',
            'message' => 'Product successfully stored'
        ], 201);
    }

    public function getById($id)
    {
        return response()->json(Inventory::find($id));
    }

    public function update(Request $request, $id)
    {
        $inventory = Inventory::find($id);

        if($inventory == null)
        {
            return response()->json([
                'status'  => 'error',
                'message' => 'Item not exsits'
            ], 400);
        }

        $validator = $this->modelAndSerialNumberValidation($request);

        $inventory->update(array_merge(
                          $validator->validated(),
                          ['comment' => $request->input('comment')],
                          ));

        $inventory->save();

        return response()->json([
            'status' => 'ok',
            'message' => 'Product successfully updated'
        ], 200);
    }

    public function delete($id)
    {
        $inventory = Inventory::find($id);

        if($inventory == null)
        {
            return response()->json([
                'status'  => 'error',
                'message' => 'Item not exsits'
            ], 400);
        }

        if($inventory->user_id != null)
        {
            return response()->json([
                'status'  => 'error',
                'message' => 'Student taken this item'
            ], 400);
        }

        $inventory->delete();

        return response()->json([
            'status'  => 'ok',
            'message' => 'Product successfully deleted'
        ], 200);
    }

    private function modelAndSerialNumberValidation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'model' => ['required', Rule::unique('inventories', 'model')->where('serial_number', $request->all('serial_number'))],
            'serial_number' => ['required', 'min:6', Rule::unique('inventories', 'serial_number')->where('model', $request->all('model'))]
        ]);

        return $validator ;
    }
}
