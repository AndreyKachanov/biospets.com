<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminController;
use App\Http\Requests\AnalyseCategoryRequest;
use App\Models\Analyse;
use App\Models\AnalyseCategory;
use Illuminate\Support\Facades\Lang;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Auth;


class AnalyseCategoryController extends AdminController
{
    protected $folderName = 'analyse_category';
    protected $modelName = 'App\Models\AnalyseCategory';
    protected $entityName = 'analyseCategory';
    protected $successMessage = 'Категория успешно удалена из базы данных';
    protected $errorMessage = 'Категория отсутствует в базе данных';

    /**
     * Get data for analyses category index action table
     *
     * @return mixed
     * @throws \Exception
     */
    public function getData()
    {
        $entities = AnalyseCategory::orderBy('is_active', 'DESC')->orderBy('title', 'ASC')->whereNull('parent_id')
            ->whereNull('deleted_at');
        $entityGroup = 'analysescategory';

        return Datatables::of($entities)
            ->editColumn('is_active', function ($entities) {
                return ($entities->is_active) ? Lang::get('statuses.analise.active') : Lang::get('statuses.analise.not_active');
            })
            ->addColumn('action', function ($entities) use ($entityGroup) {

                return view('admin.buttons_action')
                    ->with('entities', $entities)
                    ->with('entityGroup', $entityGroup);
            })
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (view()->exists('admin.analyse_category.create')) {
            return view('admin.analyse_category.create');
        }

        abort(404);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(AnalyseCategoryRequest $request)
    {
        $input = $request->except('_token');

        $input['is_active'] = (int)$request->is_active;
        $input['created_by_user_id'] = Auth::id();
        $input['parent_id'] = ($request->parent_id != '0') ? $request->parent_id : null;

        $analyseCategory = new AnalyseCategory();
        $analyseCategory->fill($input);

        $redirectRout = ($request->parent_id) ? 'admin.analyse.subcategories' : 'admin.analysescategory.index';

        try {
            $analyseCategory->save();
        } catch (\Exception $e) {
            \Log::error("Error in save category" . __CLASS__ . " 80 line.");
            \Log::error($e->getMessage());
            return redirect()->route($redirectRout)->with('errors', 'Ошибка соединения с базой данных');
        }
        
        return redirect()->route($redirectRout)
            ->with('successMessage', ($request->parent_id) ? 'Подкатегория успешно добавлена' : 'Категория успешно добавлена');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $analyseCategory = AnalyseCategory::find($id);

        if ($analyseCategory) {

            return view('admin.analyse_category.edit', [
                'analyseCategory' => $analyseCategory,
            ]);
        }

        return redirect()->route('admin.analysescategory.index')->with('errors', 'Категория отсутствует в базе данных');

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(AnalyseCategoryRequest $request, $id)
    {
        $analyseCategory = AnalyseCategory::find($id);

        if ($analyseCategory) {

            //active subcategories in category
            $activeSubcategoriesInCategory = AnalyseCategory::where('parent_id', $id)
                ->where('is_active', 1)
                ->count();

            $activeAnalysesInCategory = Analyse::where('category_id', $id)
                ->where('is_active', 1)
                ->count();

            // If you set the status of Inactive + in the category there are active subcategories - do not let save!
            if ($request->is_active == 0 && $activeSubcategoriesInCategory > 0) {
                return redirect()->route('admin.analysescategory.index')
                    ->with('errors', 'Невозможно изменить статус на "Неактивный". В категории есть активные подкатегории.');
            }

            // If you set the status of Inactive + in the category there are active tests - do not let save!
            if ($request->is_active == 0 && $activeAnalysesInCategory > 0) {
                return redirect()->route('admin.analysescategory.index')
                    ->with('errors', 'Невозможно изменить статус на "Неактивный". В категории есть активные анализы.');
            }

            $input = $request->except('_token');

            $input['is_active'] = (int)$request->is_active;
            $input['updated_by_user_id'] = Auth::id();

            $analyseCategory = AnalyseCategory::find($id);

            $analyseCategory->fill($input);

            if ($analyseCategory->update()) {
                return redirect()->route('admin.analysescategory.index')
                    ->with('successMessage', 'Категория успешно обновлена');
            }
        }

        abort(404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $analyseCategory = AnalyseCategory::find($id);

        if ($analyseCategory) {

            $redirectRout = ($analyseCategory->parent_id) ? 'admin.analyse.subcategories' : 'admin.analysescategory.index';

            if (count($analyseCategory->rAnalyses) > 0) {
                return redirect()->route($redirectRout)->with('errors', 'Подкатегорию удалить невозможно, т.к. есть вложенные анализы');
            } elseif (AnalyseCategory::where('parent_id', $analyseCategory->id)->count() > 0) {
                return redirect()->route($redirectRout)->with('errors', 'Категорию удалить невозможно, т.к. есть дочерние подкатегории');
            } else {
                try {
                    $analyseCategory->delete();
                } catch (\Exception $e){
                    \Log::error("Error in destroy category" . __CLASS__ . " 185 line.");
                    \Log::error($e->getMessage());
                    return redirect()->route('admin.analysescategory.index')->with('errors', 'Категория отстутствует в базе данных');
                }

                return redirect()->route($redirectRout)->with('successMessage', 'Категория успешно удалена из базы данных');

            }
        }


    }

    /**
     * Subcategories main page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function subcategoriesList()
    {
        if (view()->exists('admin.analyse_category.subcategories_list')) {
            return view('admin.analyse_category.subcategories_list');
        }

        abort(404);
    }

    /**
     *Get subcategories list
     *
     * @return mixed
     * @throws \Exception
     */
    public function getDataSubcategories()
    {
        $entities = AnalyseCategory::orderBy('is_active', 'DESC')->orderBy('title', 'ASC')->whereNotNull('parent_id')
            ->whereNull('deleted_at');

        $entityGroup = 'analyse.subcategories';

        return Datatables::of($entities)
            ->editColumn('is_active', function ($entities) {
                return ($entities->is_active) ? Lang::get('statuses.analise.active') : Lang::get('statuses.analise.not_active');
            })
            ->addColumn('parent', function (AnalyseCategory $entities) {

                $parent = AnalyseCategory::find($entities->parent_id);
                return $parent->title ?? 'error Category';
            })
            ->addColumn('action', function ($entities) use ($entityGroup) {

                return view('admin.buttons_action_sub_categories')
                    ->with('entities', $entities)
                    ->with('entityGroup', $entityGroup);
            })
            ->make(true);
    }

    /**
     * Get subcategories create view
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function subcategoriesCreate()
    {
        if (!view()->exists('admin.analyse_category.subcategories_create')) {
            abort(404);
        }

        $selectCategory = AnalyseCategory::select(['id', 'title'])
            ->whereNull('parent_id')
            ->where('is_active', 1)
            ->whereNull('deleted_at')
            ->orderBy('title')
            ->get();

        $categoryToView = [];

        foreach ($selectCategory as $category) {
            $categoryToView[$category->id] = mb_substr($category->title, 0, 60);
        }

        return view('admin.analyse_category.subcategories_create', [
            'categoryToView' => $categoryToView
        ]);
    }

    /**
     * Edit analyse subcategory action
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function editSubCategories($id)
    {
        $analyseCategory = AnalyseCategory::find($id);

        if ($analyseCategory) {
            if (!view()->exists('admin.analyse_category.subcategories_edit')) {
                abort(404);
            }

            $selectCategory = AnalyseCategory::select(['id', 'title'])
                ->whereNull('parent_id')
                ->where('is_active', 1)
                ->whereNull('deleted_at')
                ->orderBy('title')
                ->get();

            $categoryToView = [];

            foreach ($selectCategory as $category) {
                $categoryToView[$category->id] = mb_substr($category->title, 0, 60);
            }

            return view('admin.analyse_category.subcategories_edit', [
                'analyseCategory' => $analyseCategory,
                'categoryToView'  => $categoryToView
            ]);
        }

        return redirect()->route('admin.analyse.subcategories')->with('errors', 'Подкатегория отсутствует в базе данных');

    }

    /**
     * Update subcategory in db
     *
     * @param AnalyseCategoryRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateSubCategory(AnalyseCategoryRequest $request, $id)
    {
        $analyseCategory = AnalyseCategory::find($id);

        if ($analyseCategory) {
            // active analysis in subcategory
            $activeAnalysesInSubCategory = Analyse::where('category_id', $id)
                ->where('is_active', 1)
                ->count();
            // If you set the status to Inactive and in the subcategory there are analyzes from the active statuses - do not let it save!
            if ($request->is_active == 0 && $activeAnalysesInSubCategory > 0) {
                return redirect()->route('admin.analyse.subcategories')
                    ->with('errors', 'Невозможно изменить статус на "Неактивный". В подкатегория есть активные анализы.');
            }

            $input = $request->except('_token');
            $input['is_active'] = (int)$request->is_active;
            $input['updated_by_user_id'] = Auth::id();
            $input['parent_id'] = $request->parent_id;

            $analyseCategory->fill($input);

            if ($analyseCategory->update()) {
                return redirect()->route('admin.analyse.subcategories')
                    ->with('successMessage', 'Подкатегория успешно обновлена');
            }

        }

        abort(404);
    }

    /**Check active analyse in subcategories
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkActiveAnalyses($id)
    {
        $analyseCategory = AnalyseCategory::find($id);
        if ($analyseCategory) {
            //active analysis in subcategory
            $activeAnalysesInSubCategory = Analyse::where('category_id', $id)
                ->where('is_active', 1)
                ->count();
            return response()->json(['analyses' => $activeAnalysesInSubCategory], 200);
        }

        return response()->json([]);

    }

    /**Check active analyse in subcategories
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkActiveSubcategories($id)
    {
        $analyseCategory = AnalyseCategory::find($id);

        if ($analyseCategory) {
            // active subcategories in category
            $activeSubcategoriesInCategory = AnalyseCategory::where('parent_id', $id)
                ->where('is_active', 1)
                ->count();
            // active analysis in category
            $activeAnalysesInCategory = Analyse::where('category_id', $id)
                ->where('is_active', 1)
                ->count();

            return response()->json(['subCategories' => $activeSubcategoriesInCategory, 'analyses' => $activeAnalysesInCategory], 200);
        }

        return response()->json([]);

    }

}
