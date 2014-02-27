<?php

namespace spec\Hofmann\FractalMagic;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use League\Fractal\TransformerAbstract as Transformer;
use App\Transformers\CommentTransformer;

class FractalSpec extends ObjectBehavior
{

	function let()
	{
		$this->beConstructedWith([
			'resource' => new ResourceTransformer 
		]);
	}

    function it_is_initializable()
    {
        $this->shouldHaveType('Hofmann\FractalMagic\Fractal');
    }

    function it_can_create_a_item_response()
    {
    	$this->resource(['head' => 'Hi!'])->shouldHaveType('Symfony\Component\HttpFoundation\JsonResponse');
    }

    function it_can_create_a_collection_response()
    {
        $this->resources([['head' => 'Hello!']])->shouldHaveType('Symfony\Component\HttpFoundation\JsonResponse');
    }

    function it_sets_the_data_on_json_response()
    {
    	$this->resource(['head' => 'Hi!'])->getContent()->shouldBeEqualTo('{"data":{"title":"Hi!"}}');
    }
}

class ResourceTransformer extends Transformer {

    public function transform($resource)
    {
        return [
            'title' => $resource['head']
        ];
    }
}
