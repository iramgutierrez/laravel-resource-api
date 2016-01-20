<?php

namespace CMS\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use CMS\Repositories\BaseRepository as Repository;
use CMS\Managers\BaseManager as Manager;
use CMS\Entities\BaseEntity as Entity;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\MessageBag;



/**
 * Class BaseController
 * @package App\Http\Controllers
 */
class BaseController extends Controller
{
    /**
     * @var Repository
     */
    protected $repository;

    /**
     * @var Manager
     */
    protected $manager;

    /**
     * @param Repository $Repository
     * @param Manager $Manager
     */
    public function __construct(Repository $Repository, Manager $Manager)
    {
        $this->repository = $Repository;

        $this->manager = $Manager;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        return response()->json($this->repository->all());
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $id)
    {
        $resource = $this->repository->findById($id);

        if (!$resource) {
            return response()->json(['error' => 'Entity not found'], 404);
        }
        return response()->json($resource);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $data = Input::all();

        $response = $this->manager->save($data);

        if ($response instanceof Entity) {

            return response()->json($response, 200);
        } else if ($response instanceof MessageBag) {
            return response()->json($response, 400);
        }

        return response()->json(['error' => 'Server error. Try Again'], 500);
    }

    public function update(Request $request, $id)
    {
        $resource = $this->repository->findById($id);

        if (!$resource) {
            return response()->json(['error' => 'Entity not found'], 404);
        }

        $data = Input::all();

        $this->manager->setEntity($resource);

        $response = $this->manager->update($data);

        if ($response instanceof Entity) {

            return response()->json($response, 200);
        } else if ($response instanceof MessageBag) {
            return response()->json($response, 400);
        }

        return response()->json(['error' => 'Server error. Try Again'], 500);
    }

    public function destroy(Request $request , $id)
    {
        $resource = $this->repository->findById($id);

        if(!$resource)
        {
            return response()->json(['error' => 'Entity not found'] , 404);
        }

        $this->manager->setEntity($resource);

        $response = $this->manager->delete();

        if($response){

            return response()->json(['success' => 'Entity deleted'], 200);
        }

        return response()->json(['error' => 'Server error. Try Again' ],500);
    }
}
