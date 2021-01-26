<?php
namespace App\Modules\Posts\Controllers\Admin;

use App\Modules\Posts\Filters\Post;
use HZ\Illuminate\Mongez\Managers\AdminApiController;
use Illuminate\Http\JsonResponse;

class PostsController extends AdminApiController
{
    /**
     * Controller info
     *
     * @var array
     */
    protected $controllerInfo = [
        'repository' => 'posts',
        'listOptions' => [
            'select' => [],
            'filterBy' => [],
            'paginate' => null, // if set null, it will be automated based on repository configuration option
        ],
        'rules' => [
            'all' => [],
            'store' => [],
            'update' => [],
        ],
    ];

        /**
         * get published comments
         * @param int $id
         * @return JsonResponse
         */
    public function getPostPublishedCommentsWithPagination(int $id)
    {
        $comments = $this->repository->getPostPublishedComments($id);
        $paginationInfo = $this->repository->getPaginateInfo();

        return response()->json(array('comments' => $comments, 'pagination' => $paginationInfo));
    }

    public function getLatestPublishedPosts()
    {
       return Post::where('published', true)->orderBy('createdAt', 'desc')->take(10)->get();
    }
}
