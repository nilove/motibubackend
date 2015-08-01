<?php

namespace Motibu\Controllers;


class ApiController extends \BaseController {
    protected $statusCode;

    /**
     * @param mixed $statusCode
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
    }

    /**
     * @return mixed
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function respondNotFound($message = 'Not found!'){
        return Response::json();
    }

} 