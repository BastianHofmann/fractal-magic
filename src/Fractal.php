<?php namespace App\Support;

use Symfony\Component\HttpFoundation\JsonResponse;
use Illuminate\Support\Str;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\Manager;

class Fractal {

	protected $bindings;

    public function __construct($bindings = [])
    {
    	$this->bindings = $bindings;
    }

    protected function createData($method, $data)
    {
        if(is_null($data))
        {
            return $data;
        }

        $key = Str::singular($method);

        $transformer = $this->bindings[$key];

        if($key == $method)
        {
            $resource = new Item($data, $transformer);
        }
        else
        {
            $resource = new Collection($data, $transformer);
        }

        $manager = new Manager;
        
        return $manager->createData($resource)->toArray();
    }

    public function getBindings()
    {
        return $this->bindings;
    }

    public function setBindings(array $bindings)
    {
        $this->bindings = $bindings;
    }

    public function __call($method, $arguments)
    {
        if(array_key_exists(Str::singular($method), $this->bindings))
    	{
    		list($resource, $status, $headers) = array_pad($arguments, 3, null);

            $data = $this->createData($method, $resource);

    		return new JsonResponse($data, $status ?: 200, $headers ?: []);
    	}

        throw new \InvalidArgumentException('Binding for resource ' . $method . ' not set.');
    }

}
