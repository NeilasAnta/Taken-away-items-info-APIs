<?php

namespace App\Http\Controllers;

use App\Mail\AssignEmail;
use App\Mail\PasswordEmail;
use Illuminate\Http\Request;
use App\Models\Inventory;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{

    public function showResponsibleInventory()
    {
        return response()->json(Inventory::where('user_id', auth()->id())->get());
    }

    public function showAllStudents()
    {
        return response()->json(User::where('isAdmin', false)->get());
    }
}
