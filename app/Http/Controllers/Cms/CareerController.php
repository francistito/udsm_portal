<?php


namespace App\Http\Controllers\Cms;


use App\Http\Controllers\Controller;
use App\Models\Cms\Career;
use App\Models\Cms\Service;
use App\Models\System\CodeValue;
use App\Repositories\Cms\CareerRepository;
use App\Repositories\System\CodeValueRepository;
use App\Repositories\System\DocumentResourceRepository;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CareerController extends Controller
{

    protected $career_repo;

    public function __construct()
    {
        $this->career_repo = new CareerRepository();
    }


    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('cms.career.index');
    }

    public function create()
    {

        $service_types = (new CodeValueRepository())->getServiceForDirectory()->pluck('name','id');

        return view('cms.career.create.create')
            ->with('service_types',$service_types);
    }


    public function store(Request $request)
    {
        $input = $request->all();
        $career = $this->career_repo->store($input);
        return redirect()->route('cms.career.index');
    }


    public function display($career)
    {
        $career = Career::find($career);
        $careers = Career::all();
        $document_resource = $career->documents()->where('document_id', 8)->first();
        $document_resource_repo = new DocumentResourceRepository();
        $image = isset($document_resource) ? $document_resource_repo->getDocFullPathUrl($document_resource->pivot->id) : '';
        return view('system.career.career')
            ->with('career',$career)
            ->with('careers',$careers)
            ->with('image',$image);
    }

    public function profile($career)
    {
        $career = Career::find($career);
        $document_resource = $career->documents()->where('document_id', 8)->first();
        $document_resource_repo = new DocumentResourceRepository();
        $image = isset($document_resource) ? $document_resource_repo->getDocFullPathUrl($document_resource->pivot->id) : '';
        return view('cms.career.show.show')
            ->with('career',$career)
            ->with('image',$image);
    }

    public function edit($career)
    {
        $service_types = (new CodeValueRepository())->getServiceForDirectory()->pluck('name','id');

        $career = Career::find($career);
        return view('cms.career.edit.edit')
            ->with('service_types',$service_types)
            ->with('career',$career);
    }

    //update note
    public function update(Request $request,$career)
    {
        $input = $request->all();
        $career = Career::find($career);
        $blog = $this->career_repo->update($input,$career);
        return redirect()->route('cms.career.index')->withFlashSuccess(trans('alert.general.updated'));

    }


    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * list of all careers
     */
    public function careers()
    {
        $career_types = (new CodeValueRepository())->getServiceForDirectory();
        $careers = $this->career_repo->queryActive()->get();
        return view('system.career.all_careers')
            ->with('career_types',$career_types)
            ->with('careers',$careers);
    }

    /**
     * @param $career_type
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function getCareerByCareerType($career_type)
    {
        $career_types = (new CodeValueRepository())->getServiceForDirectory();
        $careers = $this->career_repo->queryActive()->where('career_type_cv_id',$career_type)->get();
        return view('system.career.all_careers')
            ->with('career_types',$career_types)
            ->with('careers',$careers);
    }
    /**
     *list all blog
     */
    public function getForAdminDatatable()
    {
        $result_list = $this->career_repo->getAllForDt();
        return DataTables::of($result_list)
            ->addIndexColumn()
            ->addColumn('type', function ($blog) {
                return isset($blog->service_type_cv_id) ? CodeValue::find($blog->service_type_cv_id)->name : '';
            })
            ->addColumn('category', function ($blog) {
                return isset($blog->category_id) ? $blog->category_id : '';
            })->addColumn('status', function ($blog) {
                return ($blog->isactive == 1) ? trans('label.active') : trans('label.inactive');
            })
            ->rawColumns(['status',])
            ->make(true);

    }


}
