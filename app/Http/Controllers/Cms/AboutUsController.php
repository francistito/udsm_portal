<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;

use App\Models\Cms\AboutUs;
use App\Repositories\Cms\AboutUSRepository;
use App\Repositories\Cms\FaqRepository;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AboutUsController extends Controller
{


    protected $about_us;

    public function __construct(){
        $this->about_us = new AboutUSRepository();

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //\
        $about_us = AboutUs::first();
        if ($about_us)
        {
            return view('cms.about_us.show.show')
                ->with('about_us',$about_us);

        }else{
            return view('cms.about_us.create.create');

        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('cms.about_us.create.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $about_us = $this->about_us->store($input);
        return view('cms.about_us.show.show')
            ->with('about_us',$about_us)
            ->withFlashSuccess(__('alert.system.about_us.created'));
    }





    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param AboutUs $about_us
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(AboutUs $about_us)
    {
        return view('cms.about_us.edit.edit')
            ->with('about_us', $about_us);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param FaqCreateRequest $request
     * @param Faq $about_us
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AboutUs $about_us)
    {
        //
        $this->about_us->update($about_us, $request->all());
        return view('cms.about_us.show.show')
            ->with('about_us',$about_us)
            ->withFlashSuccess(__('alert.system.about_us.updated'));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Faq $about_us
     * @return \Illuminate\Http\Response
     */
    public function destroy(Faq $about_us)
    {
        $user = access()->user();
        $this->about_us->delete($about_us);
        return redirect()->route('cms.about_us.index')->withFlashSuccess(__('alert.system.about_us.deleted'));
    }




    /*Get FAQS which are general*/
    public function getGeneralFaqs()
    {
        return view('system.about_us.search.search')->with([
            'about_us' => $this->about_us->getGeneralFaqs(),
            'category_name' => __('label.general'),
        ]);
    }



    public function getForAdminDataTable()
    {
        $about_us = $this->about_us->getForAdminDataTable();
        return DataTables::of($about_us)
            ->addIndexColumn()
            ->addColumn('actions', function ($about_us) {
                return '<a href="'.route('cms.about_us.profile', $about_us->uuid).'">'.trans('label.view').'</a>'. link_to_route('cms.about_us.edit', __('buttons.general.crud.edit'), [$about_us->uuid], ['class' => 'btn btn-warning btn-xs']) .' '. link_to_route('cms.about_us.delete',  __('buttons.general.crud.delete'), [$about_us->uuid], ['data-method' => 'delete', 'data-trans-button-cancel' => trans('buttons.general.cancel'), 'data-trans-button-confirm' => trans('buttons.general.confirm'), 'data-trans-title' => trans('label.warning'), 'data-trans-text' => trans('alert.system.about_us.warning.delete'), 'class' => 'btn btn-danger btn-xs']);
            })
            ->addColumn('status', function ($about_us) {
                return (isset($about_us->isactive) ? '<span>'.trans('label.active').'</span>' : trans('label.inactive'));
            })
            ->rawColumns(['actions','status'])
            ->make();
    }

}
