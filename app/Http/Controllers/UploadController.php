<?php

namespace App\Http\Controllers;

use App\Video;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use MuxPhp\Api\AssetsApi;
use MuxPhp\Configuration;
use MuxPhp\Models\CreateAssetRequest;
use MuxPhp\Models\InputSettings;
use MuxPhp\Models\PlaybackPolicy;

class UploadController extends Controller
{
    public function upload(Request $request)
    {
        $path = $request->file('file')->store('public/videos');
        $url = str_replace('public/',env('APP_URL').'/',$path);
        // Authentication Setup
        $config = Configuration::getDefaultConfiguration()
            ->setUsername(env('MUX_TOKEN_ID'))
            ->setPassword(env('MUX_TOKEN_SECRET'));

        // API Client Initialization
        $assetsApi = new AssetsApi(
            new Client(),
            $config
        );

        // Create Asset Request
        $input = new InputSettings(["url" => $url]);
        $createAssetRequest = new CreateAssetRequest(["input" => $input, "playback_policy" => [PlaybackPolicy::PUBLIC_PLAYBACK_POLICY] ]);

        // Ingest
        $result = $assetsApi->createAsset($createAssetRequest);

        // Print URL
        $video = new Video();
        $video->word = $request->name;
        $video->playback_url = "https://stream.mux.com/" . $result->getData()->getPlaybackIds()[0]->getId() . ".m3u8";
        $video->save();

        $ret = [
            'url' => env('APP_URL') . '/' . $video->word
        ];

        return response()->json($ret);
    }
}
