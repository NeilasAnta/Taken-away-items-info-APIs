<?php

namespace App\Http\Controllers;

use App\Mail\AssignEmail;
use App\Models\Inventory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AssignmentController extends Controller
{
    //
    public function assignInventory(Request $request)
    {
        $data = $request->all();
        $userType = auth()->user()->isAdmin;
        $userData = auth()->user();

        $validator = ($userType) ? $this->adminValidation($data) : $this->studentValidation($data);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => $validator->messages()
            ], 400);
        }

        $inventory = Inventory::find($data['inventory_id']);

        if($inventory['user_id'] != null)
        {
            return response()->json([
                'status'  => 'error',
                'message' => 'Another student assign this item'
            ], 400);
        }

        if($userType)
        {
            $inventory->update(['user_id' => $data['user_id']]);
        }else
        {
            $inventory->update(['user_id' => auth()->id()]);
            $this->sendMailToAdmin($userData, $inventory);
        }
        $inventory->save();

        return response()->json([
            'status' => 'ok',
            'message' => 'Successfully assigned inventory'], 200);
    }

    public function unassignInventory(Request $request)
    {
        $data = $request->all();
        $userType = auth()->user()->isAdmin;

        $validator = Validator::make($data, [
            'inventory_id' => ['required', 'exists:inventories,id'],
        ]);

        if ($validator->fails()) {
            $this->errorResponse($validator->messages());
        }

        $inventory = Inventory::find($data['inventory_id']);

        if($inventory->user_id == auth()->id() || $userType)
        {
            $inventory->update(['user_id' => null]);
            $inventory->save();

            return response()->json([
                'status' => 'ok',
                'message' => 'Successfully unassign inventory'], 200);
        }else
        {
            return response()->json([
                'status'  => 'error',
                'message' => 'Student ID do not match'
            ], 400);
        }

    }

    private function sendMailToAdmin($userData, $inventory)
    {
        $data = [
            'name' => $userData->name,
            'surname' => $userData->surname,
            'email' => $userData->email,
            'inventory_name' => $inventory->name,
            'model' => $inventory->model,
            'serial_number' => $inventory->serial_number,
        ];

        Mail::to(User::where('isAdmin', true)->first()->email)->send(New AssignEmail($data));
    }


    private function adminValidation($data)
    {
        $validator = Validator::make($data, [
            'inventory_id' => ['required', 'exists:inventories,id'],
            'user_id' => ['required', 'exists:users,id']
        ]);

        return $validator;
    }

    private function studentValidation($data)
    {
        $validator = Validator::make($data, [
            'inventory_id' => ['required', 'exists:inventories,id'],
        ]);
        return $validator;
    }


}
