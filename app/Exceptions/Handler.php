<?php


namespace App\Exceptions;


use Exception;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;


class Handler extends ExceptionHandler

{

    public function report(Exception $exception)

    {

        parent::report($exception);

    }


    public function render($request, Exception $exception)

    {

        if($this->isHttpException($exception)){

            switch ($exception->getStatusCode()) {

                case 404:

                    return redirect()->route('404');

                    break;

                case 405:

                    return redirect()->route('405');

                    break;

            }

        }


        return parent::render($request, $exception);

    }

}