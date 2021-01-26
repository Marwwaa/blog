<?php
namespace App\Modules\Categories\Repositories;

use App\Modules\Posts\Models\Post;
use App\Modules\Categories\{Models\Category as Model,
    Models\Category,
    Resources\Category as Resource,
    Filters\Category as Filter};

use HZ\Illuminate\Mongez\{
    Contracts\Repositories\RepositoryInterface,
    Managers\Database\MongoDB\RepositoryManager
};

class CategoriesRepository extends RepositoryManager implements RepositoryInterface
{
    /**
     * {@inheritDoc}
     */
    const NAME = 'categories';

    /**
     * {@inheritDoc}
     */
    const MODEL = Model::class;

    /**
     * {@inheritDoc}
     */
    const RESOURCE = Resource::class;

    /**
     * Set the columns of the data that will be auto filled in the model
     *
     * @const array
     */
    const DATA = ['name'];

    /**
     * Auto save uploads in this list
     * If it's an indexed array, in that case the request key will be as database column name
     * If it's associated array, the key will be request key and the value will be the database column name
     *
     * @const array
     */
    const UPLOADS = ['image'];

    /**
     * Auto fill the following columns as arrays directly from the request
     * It will encoded and stored as `JSON` format,
     * it will be also auto decoded on any database retrieval either from `list` or `get` methods
     *
     * @const array
     */
    const ARRAYBLE_DATA = [];

    /**
     * Set columns list of integers values.
     *
     * @cont array
     */
    const INTEGER_DATA = [];

    /**
     * Set columns list of float values.
     *
     * @cont array
     */
    const FLOAT_DATA = [];

    /**
     * Set columns of booleans data type.
     *
     * @cont array
     */
    const BOOLEAN_DATA = ['published'];

    /**
     * Set columns list of date values.
     *
     * @cont array
     */
    const DATE_DATA = [];

    /**
     * Set the columns will be filled with single record of collection data
     * i.e [country => CountryModel::class]
     *
     * @const array
     */
    const DOCUMENT_DATA = [
        'parent' => Category::class
    ];

    /**
     * Set the columns will be filled with array of records.
     * i.e [tags => TagModel::class]
     *
     * @const array
     */
    const MULTI_DOCUMENTS_DATA = [];

    /**
     * Add the column if and only if the value is passed in the request.
     *
     * @cont array
     */
    const WHEN_AVAILABLE_DATA = [];

    /**
     * Filter by columns used with `list` method only
     *
     * @const array
     */
    const FILTER_BY = [];

    /**
     * Set all filter class you will use in this module
     *
     * @const array
     */
    const FILTERS = [
        Filter::class
    ];

    /**
     * Determine wether to use pagination in the `list` method
     * if set null, it will depend on pagination configurations
     *
     * @const bool
     */
    const PAGINATE = null;

    /**
     * Number of items per page in pagination
     * If set to null, then it will taken from pagination configurations
     *
     * @const int|null
     */
    const ITEMS_PER_PAGE = null;

    /**
     * Set any extra data or columns that need more customizations
     * Please note this method is triggered on create or update call
     *
     * @param   mixed $model
     * @param   \Illuminate\Http\Request $request
     * @return  void
     */
    protected function setData($model, $request)
    {
        //
    }

    /**
     * @param $model
     * @param $request
     * @param $oldModel
     * @return void
     */
    public function onUpdate($model, $request, $oldModel)
    {
        $this->updateCategoryData($model);
        $this->postsRepository->updateCategoryData($model);
    }

    /**
     * update relative models' parent shared info
     * @param $category
     * @return void
     */
    public function updateCategoryData($category)
    {
        Model::where('parent.id', $category->id)->update([
            'parent' => $category->sharedInfo()
        ]);
    }

    /**
     * update user's shared info
     * @param $user
     * @return void
     */
    public function updateUserData($user)
    {
        Model::where('createdBy.id', $user->id)->update([
            'createdBy' => $user->sharedInfo(),
        ]);
        Model::where('updatedBy.id', $user->id)->update([
            'updatedBy' => $user->sharedInfo(),
        ]);
    }

    /**
     * get the published posts of specific category
     * @param int $id
     * @return Post
     */
    public function getCategoryPublishedPosts(int $id)
    {
        return Post::where('categoryTree.id', $id)->where('published', true)->get();
    }

    /**,
     * Do any extra filtration here
     *
     * @return  void
     */
    protected function filter()
    {
        //
    }
}
