<?php namespace Bootleg\Cms;

use Exception;
use Illuminate\Http\Response;
use Psr\Log\LoggerInterface;

class ExceptionHandler extends \App\Exceptions\Handler
{
    public function render($request, Exception $e)
    {
        if (config('app.debug')) {
            //we can target any error we like
            if ($e instanceof \Bootleg\Cms\CustomError) {
                //we can potentially return something different for ajax requests
                if ($request->ajax()) {
                    return $this->renderExceptionWithWhoops($request, $e);
                } else {
                    return $this->renderExceptionWithWhoops($request, $e);
                }
            }
        }
        if ($this->isHttpException($e)) {
            $statusCode = $e->getStatusCode();

            switch ($statusCode) {
                //different handling for different error codes
                case '404':
                    return $this->renderHttpExceptionView($e);
                    break;
                default:
                    return $this->renderExceptionWithWhoops($request, $e);
                    break;
            }
        } else {
            return parent::render($request, $e);
        }
    }

    /**
     * Render an exception using Whoops.
     *
     * @param  \Exception $e
     * @return \Illuminate\Http\Response
     */
    protected function renderExceptionWithWhoops($request, Exception $e)
    {
        $whoops = new \Whoops\Run;
        $whoops->pushHandler($request->ajax() ? new \Whoops\Handler\JsonResponseHandler : new \Whoops\Handler\PrettyPageHandler);
        $whoops->allowQuit(false);
        $whoops->writeToOutput(false);

        return response($whoops->handleException($e), $whoops->sendHttpCode());
    }

    /**
     * Render the error view that best fits that status code.
     *
     * @param Exception $e
     * @return \Illuminate\Http\Response
     */
    public function renderHttpExceptionView(Exception $e)
    {
        $status = $e->getStatusCode();

        if (view()->exists("errors.{$status}")) {
            return $this->toIlluminateResponse($this->renderHttpException($e), $e);
        }

        return response()->view("errors.default", ['exception' => $e], $status);

    }
}