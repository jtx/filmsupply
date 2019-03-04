<?php

namespace App\Http\Controllers;

use App\DeveloperResume;
use App\Http\Requests\DeveloperResumeRequest;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Illuminate\Http\JsonResponse;

class DeveloperResumeController extends Controller
{
    /**
     * @param DeveloperResumeRequest $request
     * @return JsonResponse
     */
    public function store(DeveloperResumeRequest $request)
    {
        $developerResume = new DeveloperResume();
        $developerResume->resume_data = $request->getContent();
        $developerResume->save();

        return \response()->json('Your resume has been saved successfully.', SymfonyResponse::HTTP_CREATED);
    }
}
