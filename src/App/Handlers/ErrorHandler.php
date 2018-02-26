<?php
namespace MyApp\Handlers;

use Exception;
use MyApp\Response\ResponseStatus;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Body;

class ErrorHandler
{
    /**
     * @var bool
     */
    private $displayErrorDetails;

    /**
     * @param bool $displayErrorDetails
     */
    public function __construct(bool $displayErrorDetails)
    {
        $this->displayErrorDetails = $displayErrorDetails;
    }

    public function __invoke(RequestInterface $request, ResponseInterface $response, Exception $e = null)
    {
        $body = new Body(fopen('php://temp', 'r+'));;
        $body->write($this->renderJsonErrorMessage($e));

        return $response
            ->withStatus(ResponseStatus::S500_INTERNAL_SERVER_ERROR)
            ->withHeader('Content-type', 'application/json')
            ->withBody($body);
    }

    /**
     * Render JSON error
     *
     * @param \Throwable $error
     *
     * @return string
     */
    protected function renderJsonErrorMessage(\Throwable $error)
    {
        $json = ['error' => ['message' => 'Internal server error']];

        if ($this->displayErrorDetails) {
            $json['details'] = [];

            do {
                $json['details'][] = [
                    'type' => get_class($error),
                    'code' => $error->getCode(),
                    'message' => $error->getMessage(),
                    'file' => $error->getFile(),
                    'line' => $error->getLine(),
                    'trace' => explode("\n", $error->getTraceAsString()),
                ];
            } while ($error = $error->getPrevious());
        }

        return \GuzzleHttp\json_encode($json, JSON_PRETTY_PRINT);
    }
}
