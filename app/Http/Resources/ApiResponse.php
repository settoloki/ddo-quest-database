<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;

class ApiResponse extends JsonResource
{
    protected string $message;
    protected int $code;

    /**
     * Create a new resource instance.
     */
    public function __construct($resource, $message = null, $code = Response::HTTP_OK)
    {
        parent::__construct($resource);
        $this->message = $message;
        $this->code = $code;
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'success' => $this->code >= 200 && $this->code < 300,
            'message' => $this->message,
            'data' => $this->resource,
            'timestamp' => now()->toISOString(),
        ];
    }

    /**
     * Customize the response for a request.
     */
    public function withResponse($request, $response)
    {
        $response->setStatusCode($this->code);
    }

    /**
     * Create a success response.
     */
    public static function success($data = null, string $message = 'Operation completed successfully', int $code = Response::HTTP_OK): self
    {
        return new self($data, $message, $code);
    }

    /**
     * Create an error response.
     */
    public static function error(string $message = 'An error occurred', $errors = null, int $code = Response::HTTP_INTERNAL_SERVER_ERROR): self
    {
        $data = $errors ? ['errors' => $errors] : null;
        return new self($data, $message, $code);
    }

    /**
     * Create a validation error response.
     */
    public static function validationError($errors, string $message = 'Validation failed'): self
    {
        return self::error($message, $errors, Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Create a not found response.
     */
    public static function notFound(string $message = 'Resource not found'): self
    {
        return self::error($message, null, Response::HTTP_NOT_FOUND);
    }

    /**
     * Create a created response.
     */
    public static function created($data, string $message = 'Resource created successfully'): self
    {
        return self::success($data, $message, Response::HTTP_CREATED);
    }

    /**
     * Create a no content response.
     */
    public static function noContent(string $message = 'Resource deleted successfully'): self
    {
        return self::success(null, $message, Response::HTTP_NO_CONTENT);
    }
}
