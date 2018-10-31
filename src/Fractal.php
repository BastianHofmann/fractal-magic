<?php namespace Hofmann\FractalMagic;

use Symfony\Component\HttpFoundation\JsonResponse;
use Illuminate\Support\Str;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\Manager;

class Fractal {

	protected $bindings;

    protected $manager;

    protected $scopes;

    public function __construct($bindings = [], $scopes = [])
    {
    	$this->bindings = $bindings;

        $this->scopes = $scopes;

        $this->manager = new Manager;
    }

    protected function createData($method, $data)
    {
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

        $this->manager->parseFieldsets($this->scopes);

        return $this->manager->createData($resource)->toArray();
    }

    public function getBindings()
    {
        return $this->bindings;
    }

    public function setBindings(array $bindings)
    {
        $this->bindings = $bindings;
    }

    public function getManager()
    {
        return $this->manager;
    }

    public function setManager($manager)
    {
        $this->manager = $manager;
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
