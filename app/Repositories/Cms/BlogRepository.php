<?php


namespace App\Repositories\Cms;



use App\Models\Cms\Blog;
use App\Repositories\BaseRepository;
use App\Repositories\System\DocumentResourceRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BlogRepository extends BaseRepository
{
    const MODEL = Blog::class;
    public function __construct()
    {

    }

    //store blog
    public function store(array $input)
    {
      return   DB::transaction(function ()use($input){
            $blog = $this->query()->create([
                'title' =>$input['title'],
                'content' =>$input['content'],
                'publish_date' => $input['publish_date'],
                'publish_time' => $input['publish_time'],
                'isactive' => $input['isactive'],
                'isscheduled' => $input['isscheduled'],
                'user_id' => Auth::id(),
            ]);



            //sync category for a blog
            $this->updateCategories($blog,$input);

            //save documents
          $this->saveDocuments($blog->id,$input);
          return $blog;
        });
    }


    //update blog
    public function update(array $input,$blog)
    {
//        $user = access()->user();
        return DB::transaction(function ()use($input,$blog){
            $blog->update([
                'title' =>$input['title'],
                'content' =>$input['content'],
                'publish_date' => $input['publish_date'],
                'publish_time' => $input['publish_time'],
                'isscheduled' => $input['isscheduled'],
                'user_id' => Auth::id(),
            ]);


            //sync category for a blog
            $this->updateCategories($blog,$input);
            //save documents
            $this->saveDocuments($blog->id,$input);
            return $blog;
        });

    }



    /*Update project task tags*/
    public function updateCategories(Model $blog, array $input)
    {
        if(isset($input['blog_categories'])){
            $categories = $input['blog_categories'];
            foreach ( $categories as $value) {
                $relation_where_input = ['blog_id' => $blog->id, 'category_id' => $value];
                $this->generalSyncPivot('blog_category',$relation_where_input, ['created_at' => Carbon::now(),'updated_at' => Carbon::now()]);
            };
        }


    }

    //delete blog
    public function delete($blog)
    {
        $blog->delete();
    }

    //publish
    public function publish($blog)
    {

        return DB::transaction(function ()use($blog){
            $blog->update([
                'status' => 1,
                'publish_date' => Carbon::now(),
                'publish_time' => Carbon::now()->format('h:m:s')
            ]);
            return $blog;
        });

    }

    /*Save document(s) attached on the form*/
    public function saveDocuments($module_functional_part_id, array $input)
    {
        foreach ($input as $key => $value) {
            if (strpos($key, 'document_file') !== false) {
                $file_id = substr($key, 13);
                $key_file_name = 'document_file'.$file_id;
                $document_id = 1;//task document id
                $document_title = $file_id;
//                $this->attachTaskDocuments($task, $document_id, $key_file_name,$document_title,$input);
                (new DocumentResourceRepository())->saveDocument($module_functional_part_id,$document_id,$key_file_name,$input);
            }
        };

    }

    //get all post
    public function getQueryAllBlogs()
    {
        return $this->query()
            ->select([
                DB::raw('blogs.id as id'),
                DB::raw('blogs.title as title'),
                DB::raw('blogs.content as content'),
                DB::raw('blogs.user_id as user_id'),
                DB::raw('blogs.isactive as isactive'),
                DB::raw('blogs.publish_date as publish_date'),
                DB::raw('blogs.publish_time as publish_time'),
                DB::raw('blogs.status as status'),
                DB::raw('blogs.created_at as created_at'),
                DB::raw('blogs.uuid as uuid'),
            ])
            ->leftjoin('users', 'users.id', '=', 'blogs.user_id');
//            ->leftjoin('regions', 'regions.id', '=', 'blogs.region_id');

    }

    //get latest posts
    public function getLatestPost()
    {
        return $this->queryActive()->where('status',1)->latest()->get();
    }



    /*Get all for Datatable Client*/
    public function getAllForDt()
    {
        $query = $this->getQueryAllBlogs();
        return $query;
    }

}
