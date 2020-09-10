<?php

namespace App\Http\Controllers;

use App\Http\Resources\QuoteResource;
use App\Http\Resources\QuoteResourceCollection;
use App\Part;
use App\Quote;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class QuoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $quotes = Quote::all();
        return (new QuoteResourceCollection($quotes))
            ->response();
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:100',
            'email' => 'required|max:100',
            'phone' => 'required|max:100',
            'part' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);

        }


        $part = $request->file('part');
        $partId = $this->saveAndStorePart($part);


        $formData = [
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'phone' => $request->get('phone'),
            'more_info' => $request->get('more_info'),
            'part_id' => $partId
        ];

        Quote::create($formData);

        return response()->json([], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param $quoteId
     * @return JsonResponse
     */
    public function show($quoteId): JsonResponse
    {
        $quote = Quote::find($quoteId);

        if (!$quote){
            return response()->json(
                [
                    'errors' =>
                        [
                            'quoteId' => 'Quote ' . $quoteId . ' not found.'
                        ]
                ]
                , 422);
        }

        return (new QuoteResource($quote))
            ->response();
    }


    /**
     * @param $part
     * @return int
     */
    private function saveAndStorePart($part): int
    {
        $extension = explode(".",$part->getClientOriginalName())[1];
        $newName = time() . '.' . $extension;
        $savedPath = Storage::disk('local')->putFileAs('cad_files',$part, $newName);
        return Part::create(['url' => $savedPath])->id;
    }


}
