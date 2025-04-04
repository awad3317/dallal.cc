<?php

namespace App\Http\Controllers\Api;

use Exception;
use Illuminate\Http\Request;
use App\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Repositories\ContactRepository;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    /**
     * Create a new class instance.
     */
    public function __construct(private ContactRepository $ContactRepository)
    {
        //
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            if (!Auth::user()->has_role('admin')) {
                return ApiResponseClass::sendError('ليس لديك صلاحية لاطلاع على رسائل التواصل', 403);
            }
            $contacts=$this->ContactRepository->index();
            return ApiResponseClass::sendResponse($contacts,'All Contacts retrieved successfully.');
        } catch (Exception $e) {
            return ApiResponseClass::sendError('Error retrieving Contacts: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'full_name' =>  ['required','string','max:255'],
            'email' =>  ['required','email','max:255'],
            'subject' => ['required','string','max:255'],
            'message' =>  ['required','string'],
        ],[
            'full_name.required' => 'يجب كتابة الاسم بالكامل',
            'full_name.string' => 'الاسم يجب أن يكون نصًا',
            'full_name.max' => 'الاسم لا يجب أن يتجاوز 255 حرفًا',
            'email.required' => 'يجب كتابة البريد الإلكتروني',
            'email.email' => 'البريد الإلكتروني غير صالح',
            'email.max' => 'البريد الإلكتروني لا يجب أن يتجاوز 255 حرفًا',
            'subject.required' => 'يجب كتابة الموضوع',
            'subject.string' => 'الموضوع يجب أن يكون نصًا',
            'subject.max' => 'الموضوع لا يجب أن يتجاوز 255 حرفًا',
            'message.required' => 'يجب كتابة الرسالة',
            'message.string' => 'الرسالة يجب أن تكون نصًا',
        ]);
        if ($validator->fails()) {
            return ApiResponseClass::sendValidationError($validator->errors()->first(), $validator->errors());
        }
        try {
            $fields=$request->only(['full_name','email','subject','message']);
            $contact=$this->ContactRepository->store($fields);
            return ApiResponseClass::sendResponse($contact,'تم إرساله الرسالة بنجاح');
        } catch (Exception $e) {
            return ApiResponseClass::sendError('Error sent  message: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $contacts=$this->ContactRepository->index();
            return ApiResponseClass::sendResponse($contacts,'All Contacts retrieved successfully.');
        } catch (Exception $e) {
            return ApiResponseClass::sendError('Error retrieving Contacts: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
