<?php namespace Motibu\Models;

use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\Manager;

class Eloquent extends \Illuminate\Database\Eloquent\Model {

	public function hasMtm ($model, $method=null, $modelName=null)
	{
		if (!$model) {
			throw (new \Illuminate\Database\Eloquent\ModelNotFoundException);
		}
		$method = $method ?:(\Str::plural(\Str::lower($modelName)));

		return ! $this->{$method}->filter(function($nthModel) use ($model) {
	        return $nthModel->id == $model->id;
	    })->isEmpty();
	}

	/**
	 * Handle dynamic method calls into the method.
	 *
	 * @param  string  $method
	 * @param  array   $parameters
	 * @return mixed
	 */
	public function __call($method, $parameters)
	{
		if (substr($method, 0, 6) == "hasMtm") {
			$modelName = substr($method, 6);
			if (isset($parameters[1]))
				array_push($parameters, $modelName);
			else {
				array_push($parameters, null);
				array_push($parameters, $modelName);
			}

			return call_user_func_array(array($this, "hasMtm"), $parameters);
		}

		return parent::__call($method, $parameters);
	}

	/**
	 * Handle dynamic static method calls into the method.
	 *
	 * @param  string  $method
	 * @param  array   $parameters
	 * @return mixed
	 */
	public static function __callStatic($method, $parameters)
	{
		$instance = new static;

		if (substr($method, 0, 6) == "findBy") {
			$column = \Str::lower(substr($method, 6));
			return $instance::where($column, $parameters)->firstOrFail();
		}

		return call_user_func_array(array($instance, $method), $parameters);
	}

	public function getTransformed ($transformer, $includes = null)
	{
		$manager = new Manager;
        $resource = new Item($this, $transformer);
        if ($includes)
	        $manager->parseIncludes($includes);

        $rootScope = $manager->createData($resource);

        return $rootScope->toArray();
	}
}
