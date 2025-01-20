<?php

namespace App\Http\Controllers;

use App\Contracts\UrlMappingServiceContract;
use App\Http\Requests\UrlEncodeDecodeRequest;
use App\Http\Resources\UrlMappingResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Psr\SimpleCache\InvalidArgumentException;
use Symfony\Component\HttpFoundation\Response;

class UrlMappingController extends Controller
{
    private UrlMappingServiceContract $urlMappingService;

    public function __construct(UrlMappingServiceContract $urlMappingService)
    {
        $this->urlMappingService = $urlMappingService;
    }

    /**
     * Handles the encoding of a URL and returns a short key upon success.
     *
     * @param  UrlEncodeDecodeRequest  $request  The request instance containing the URL to be encoded.
     * @return JsonResponse A JSON response containing the short key or an error message.
     */
    public function encode(UrlEncodeDecodeRequest $request): JsonResponse
    {
        try {
            $validatedRequest = $request->validated();

            // Let's check to see if the URL has already been mapped, if not, map it
            $urlMapping = $this->urlMappingService->mapUrl($validatedRequest['url']);

            $status = $urlMapping->wasRecentlyCreated ? Response::HTTP_CREATED : Response::HTTP_OK;

            return response()->json(new UrlMappingResource($urlMapping), $status);
        } catch (\Exception $e) {
            // Return an error response with a proper status code
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (InvalidArgumentException $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Decodes a shortened URL to retrieve the original URL.
     *
     * @param  UrlEncodeDecodeRequest  $request  The incoming request object containing the URL to decode.
     * @return JsonResponse The decoded URL resource or an error response in case of failure.
     *
     * @throws \Exception If an error occurs during the decoding process.
     */
    public function decode(UrlEncodeDecodeRequest $request): JsonResponse
    {
        try {
            $validatedRequest = $request->validated();

            $shortUrl = $validatedRequest['url'];

            $originalUrl = $this->urlMappingService->retrieveShortUrl($shortUrl);

            return response()->json(new UrlMappingResource($originalUrl), Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }
    }
}
