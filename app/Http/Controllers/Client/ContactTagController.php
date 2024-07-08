<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\TagResource;
use App\Models\ContactTag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContactTagController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            return response()->json([
                'success' => 'Tag added successfully',
                'tags'    => TagResource::collection(ContactTag::latest()->get()),
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            ContactTag::create([
                'contact_id' => $request->contact_id,
                'title'      => $request->title,
                'status'     => 1,
            ]);

            return response()->json([
                'success' => 'Tag added successfully',
                'tags'    => TagResource::collection(ContactTag::where('contact_id', $request->contact_id)->latest()->get()),
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function changeStatus(Request $request): JsonResponse
    {
        try {
            ContactTag::whereIn('id', $request->ids)->update([
                'status' => 1,
            ]);
            ContactTag::whereNotIn('id', $request->ids)->update([
                'status' => 0,
            ]);

            return response()->json([
                'success' => 'Tag status changed successfully',
                'tags'    => TagResource::collection(ContactTag::where('contact_id', $request->contact_id)->latest()->get()),
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }
}
