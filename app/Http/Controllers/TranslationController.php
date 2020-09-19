<?php

namespace App\Http\Controllers;

use App\Audit;
use App\Language;
use Google\Cloud\Translate\V2\TranslateClient;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Jenssegers\Agent\Facades\Agent;
use TypeError;

class TranslationController extends Controller
{
    const ALLOWED_EXTENSIONS = [
        'txt',
    ];

    const ALLOWED_MIME_TYPES = [
       'text/plain',
    ];

    /**
     * @var array
     */
    private $languages;

    /**
     * @var TranslateClient
     */
    private $translate;

    /**
     * TranslationController constructor.
     */
    public function __construct()
    {
        $this->translate = new TranslateClient([
            'key'     => getenv('GOOGLE_CLOUD_API_KEY'),
            'keyFile' => getenv('GOOGLE_APPLICATION_CREDENTIALS'),
        ]);

        $this->languages = Language::all();
    }

    /**
     * Retrieve the current list of languages we can translate to.
     *
     * @return JsonResponse
     */
    public function languages(): JsonResponse
    {
        return response()->json($this->languages, 200);
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function translate(Request $request): JsonResponse
    {
        $response = [
            'rslt' => 'error',
            'msg'  => '',
        ];

        $responseCode = 422;

        // return a 422 Unprocessable Entity if the user did not submit a file (the request syntax is correct, meaning a 409 Bad Request is not quite right)
        if (empty($request->file('initialFile'))) {
            $response['msg'] = 'No file was submitted for translation.';
            return response()->json($response, $responseCode);
        }

        try {
            // default to German if the user does not provide a language (recommend referencing this in UI)
            $desiredLanguage = $request->request->get('chosenLanguage', 'de');
            $requestedFile = $request->file('initialFile');

            $extension = $requestedFile->getClientOriginalExtension();
            $mime = $requestedFile->getMimeType();

            // if the file is not a text file, we can't process it; kick it back with an error (422 again, for the same reasons as above)
            if (!in_array($extension, self::ALLOWED_EXTENSIONS) && !in_array($mime, self::ALLOWED_MIME_TYPES)) {
                $response['msg'] = 'Unfortunately, the file you submitted was not in an acceptable format, and could not be processed.';
                return response()->json($response, $responseCode);
            }

            $initialText = file_get_contents($requestedFile->path());

            $translated = $this->translate->translate($initialText, [
                'target' => $desiredLanguage,
            ]);

            // store the file to a temporary S3 bucket
            $response = [
                'rslt'            => 'success',
                'msg'             => $translated['text'],
                'sourceLanguage'  => strtoupper($translated['source']),
                'desiredLanguage' => strtoupper($desiredLanguage),
            ];
            $responseCode = 200;

            Audit::create([
                'mime_type'            => $mime,
                'file_size'            => File::size($requestedFile),
                'source_language'      => strtoupper($translated['source']),
                'destination_language' => strtoupper($desiredLanguage),
                'bot'                  => Agent::isRobot() ? Agent::robot() : '',
                'browser'              => Agent::browser(),
                'device'               => Agent::isDesktop() ? 'Desktop' : Agent::device(),
                'ip'                   => $request->ip(),
                'os'                   => Agent::platform(),
                'user_agent'           => $request->userAgent(),
            ]);

        } catch (TypeError $e) {
            $response['msg'] = 'Something went awry; please try again.';
            $responseCode = 500;
        }

        return response()->json($response, $responseCode);
    }
}
