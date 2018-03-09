<?php
namespace MyApp\Handlers;

use Exception;
use MyApp\Error\ErrorDTO;
use MyApp\Exception\BadRequestException;
use MyApp\Response\ResponseStatus;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Body;
use stdClass;

class ErrorHandler
{
    /**
     * @var bool
     */
    private $displayErrorDetails;

    /**
     * @var string
     */
    private $defaultContentType;

    /**
     * @param bool $displayErrorDetails
     * @param string $defaultContentType
     */
    public function __construct(bool $displayErrorDetails, string $defaultContentType)
    {
        $this->displayErrorDetails = $displayErrorDetails;
        $this->defaultContentType = $defaultContentType;
    }

    public function __invoke(RequestInterface $request, ResponseInterface $response, Exception $e = null)
    {
        if ($e instanceof BadRequestException) {
            $response = $this->createBadRequestResponse($response, $e);
        } else {
            $response = $this->createInternalServerErrorResponse($response, $e);
        }

        return $response;
    }

    /**
     * @param ResponseInterface $response
     * @param Exception $e
     * @return ResponseInterface
     */
    private function createInternalServerErrorResponse(ResponseInterface $response, Exception $e)
    {
        $code = ResponseStatus::S500_INTERNAL_SERVER_ERROR;

        $error = new ErrorDTO('', 'Internal server error');

        if ($this->displayErrorDetails) {
            do {
                $detail = [
                    'type' => get_class($e),
                    'code' => $e->getCode(),
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => explode("\n", $e->getTraceAsString()),
                ];
                $error->setDetails($detail);
            } while ($e = $e->getPrevious());
        }

        return $this->createResponse($response, $code, [$error]);
    }

    /**
     * @param ResponseInterface $response
     * @param BadRequestException $e
     * @return ResponseInterface
     */
    private function createBadRequestResponse(ResponseInterface $response, BadRequestException $e)
    {
        $code = $e->getCode() !== 0 ? $e->getCode() : ResponseStatus::S400_BAD_REQUEST;

        return $this->createResponse($response, $code, $e->getErrors());
    }

    /**
     * @param ErrorDTO[] $errors
     * @return array
     */
    private function renderErrorsObj(array $errors): array
    {
        $errorsData = [];
        foreach ($errors as $error) {
            $errObj = new stdClass();
            $errObj->message = (string)$error;
            if ($error->getDetails() !== []) {
                $errObj->details = $error->getDetails();
            }
            $errorsData['errors'][] = $errObj;
        }

        return $errorsData;
    }

    /**
     * @param ResponseInterface $response
     * @param int $code
     * @param ErrorDTO[] $errors
     * @return ResponseInterface
     */
    private function createResponse(ResponseInterface $response, int $code, array $errors)
    {
        $body = new Body(fopen('php://temp', 'r+'));

        $body->write(
            \GuzzleHttp\json_encode(
                $this->renderErrorsObj($errors),
                JSON_PRETTY_PRINT
            )
        );

        return $response
            ->withStatus($code)
            ->withHeader('Content-type', $this->defaultContentType)
            ->withBody($body);
    }
}
