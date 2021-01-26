<?php
namespace App\Modules\Categories\Controllers\Admin;

use App\Modules\Categories\Models\Category;
use HZ\Illuminate\Mongez\Managers\AdminApiController;
use Illuminate\Http\JsonResponse;

class CategoriesController extends AdminApiController
{
    /**
     * Controller info
     *
     * @var array
     */
    protected $controllerInfo = [
        'repository' => 'categories',
        'listOptions' => [
            'select' => [],
            'filterBy' => [],
            'paginate' => null, // if set null, it will be automated based on repository configuration option
        ],
        'rules' => [
            'all' => [
                'name' => 'required',
            ],
            'store' => [],
            'update' => [],
        ],
    ];

    /**
     * get published comments
     * @param int $id
     * @return JsonResponse
     */
    public function getCategoryPostsWithPagination(int $id)
    {
        $posts = $this->repository->getCategoryPublishedPosts($id);
        $paginationInfo = $this->repository->getPaginateInfo();

        return response()->json(array('posts' => $posts, 'pagination' => $paginationInfo));
    }

    /**
     * get all published category in desc order
     * @return Category
     */
    public function getPublishedCategories()
    {
        return Category::where('published', true)->orderBy('id', 'desc')->get();
    }
}
