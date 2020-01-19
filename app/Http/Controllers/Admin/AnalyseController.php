<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminController;
use App\Http\Requests\AnalyseRequest;
use App\Jobs\ClearGoogleSheets;
use App\Models\Analyse;
use App\Models\AnalyseCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Auth;
use App\Services\GoogleSheets;
use App\Jobs\SendAnalysesFromGoogleSheets;





class AnalyseController extends AdminController
{

    protected $folderName = 'analyse';
    protected $modelName = 'App\Models\Analyse';
    protected $entityName = 'analyse';
    protected $successMessage = 'Анализ успешно удален из базы данных';
    protected $errorMessage = 'Анализ отсутствует в базе данных';

    /**
     * Get data for analyses index action table
     *
     * @return mixed
     * @throws \Exception
     */
    public function getData()
    {
        $entities = Analyse::orderBy('is_active', 'DESC')->orderBy('title', 'ASC');
        $entityGroup = 'analyses';

        return Datatables::of($entities)
            ->editColumn('is_active', function ($entities) {
                return ($entities->is_active) ? Lang::get('statuses.analise.active') : Lang::get('statuses.analise.not_active');
            })
            ->editColumn('title', function ($entities) {
                $titleLength = iconv_strlen($entities->title);
                if ($titleLength > 254) {
                    return  mb_substr($entities->title, 0, 254) . "...";
                } else {
                    return $entities->title;
                }
            })
            ->addColumn('category', function (Analyse $entities) {

                return (isset($entities->rAnalysesCategories))
                    ? (isset($entities->rAnalysesCategories->parent)) ? $entities->rAnalysesCategories->parent->title : $entities->rAnalysesCategories->title
                    : "";
            })
            ->addColumn('action', function ($entities) use ($entityGroup) {
                return view('admin.buttons_action')
                    ->with('entities', $entities)
                    ->with('entityGroup', $entityGroup);
            })
            ->make(true);
    }

    /**
     * If have subcategories - get array subcategories and insert to session, else get empty array
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSubCategories(Request $request, $id)
    {
        $subCategories = AnalyseCategory::select(['id', 'title'])
            ->where('parent_id', $id)
            ->orderBy('title')
            ->get();

        if ($subCategories->count() > 0) {

            $subCategoriesToSession = [];
            $subCategoriesToSession['0'] = 'Выберите подкатегорию';

            foreach ($subCategories as $category) {
                $subCategoriesToSession[$category->id] = $category->title;
            }

            $request->session()->put('create_sub_categories.arr', $subCategoriesToSession);
            return response()->json(['sub_categories' => $subCategories], 200);
        }

        $request->session()->put('create_sub_categories.arr', []);
        return response()->json(['sub_categories' => []], 200);
    }

    /**
     * Create analyse action view
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        if (!view()->exists('admin.analyse.create')) {
            abort(404);
        }

        $selectCategory = AnalyseCategory::select(['id', 'title'])
            ->whereNull('deleted_at')
            ->whereNull('parent_id')
            ->orderBy('title')
            ->get();

        $categoryToView = [];

        foreach ($selectCategory as $category) {
            $categoryToView[$category->id] = mb_substr($category->title, 0, 90);
        }

        // All subcategories of the first category
        $selectSubCategory = AnalyseCategory::select(['id', 'title'])
            ->where('parent_id', key($categoryToView))
            ->orderBy('title')
            ->get();

        // if there are subcategories
        if ($selectSubCategory->count() > 0) {
            $subCategoryToView = [];
            $subCategoryToView[0] = 'Выберите подкатегорию';

            foreach ($selectSubCategory as $s) {
                $subCategoryToView[$s->id] = mb_substr($s->title, 0, 90);
            }
        }

        return view('admin.analyse.create', [
            'selectCategory'    => $categoryToView ?? null,
            'selectSubCategory' => $subCategoryToView ?? [],
        ]);
    }

    /**
     * Save analyse to db
     *
     * @param AnalyseRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(AnalyseRequest $request)
    {
        $input = $request->except('_token');

        $input['is_active'] = (int)$request->is_active;
        $input['discount'] = ($request->discount == null) ? NULL : replacePriceToDouble($request->discount);

        $input['price'] = replacePriceToDouble($request->price);
        $input['created_by_user_id'] = Auth::id();

        $input['category_id'] = (isset($request->sub_category_id) && $request->sub_category_id != '0') ? $request->sub_category_id : $request->category_id;

        $input['meta_title'] = (isset($request->meta_title)) ? $request->meta_title : mb_substr($request->title, 0, 80);
        $input['meta_description'] = (isset($request->meta_description)) ? $request->meta_description : mb_substr(strip_tags($request->description), 0, 180);

        $analyse = new Analyse();
        $analyse->first_letter = $request->title;
        $analyse->fill($input);

        if ($analyse->save()) {
            $request->session()->flash('successMessage', 'Анализ успешно добавлен в базу данных');
            return response()->json(['analyseId' => Analyse::all()->last()->id]);
        }

    }

    /**
     * Edit analyse action
     *
     * @param $id
     * @return AdminController|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function edit($id)
    {
        $analyse = Analyse::find($id);

        if ($analyse) {
            $mainCategories = AnalyseCategory::select(['id', 'title'])
                ->whereNull('deleted_at')
                ->whereNull('parent_id')
                ->orderBy('title')
                ->get();

            $mainCategoriesArray = [];
            $subCategoriesArray = [];

            foreach ($mainCategories as $c) {
                $mainCategoriesArray[$c->id] = mb_substr($c->title, 0, 90);
            }

            // id of parent category
            if (isset($analyse->rAnalysesCategories)) {
                $parentCategoryId = $analyse->rAnalysesCategories->parent_id;

                // If the analysis has a category with parent_id = null
                if ($parentCategoryId == null) {
                    $subCategories = AnalyseCategory::select(['id', 'title'])
                        ->where('parent_id', $analyse->rAnalysesCategories->id)
                        ->orderBy('title')
                        ->get();

                    // If this category has children
                    if ($subCategories->count() > 0) {
                        $subCategoriesArray[0] = "Выберите подкатегорию";
                    }

                    // otherwise the array will be empty
                } else {
                    // selects all subcategories from the main category where the analysis is located
                    $subCategories = AnalyseCategory::select(['id', 'title'])
                        ->where('parent_id', $parentCategoryId)
                        ->orderBy('title')
                        ->get();

                    $subCategoriesArray = [];
                    $subCategoriesArray[0] = "Выберите подкатегорию";
                }

                foreach ($subCategories as $s) {
                    $subCategoriesArray[$s->id] = mb_substr($s->title, 0, 90);
                }

            } else {
                // selects all subcategories from first main category
                $subCategories = AnalyseCategory::select(['id', 'title'])
                    ->where('parent_id', $mainCategories[0]->id)
                    ->orderBy('title')
                    ->get();

                $subCategoriesArray = [];
                $subCategoriesArray[0] = "Выберите подкатегорию";

                foreach ($subCategories as $s) {
                    $subCategoriesArray[$s->id] = mb_substr($s->title, 0, 90);
                }

            }

            return view('admin.analyse.edit', [
                'analyse'             => $analyse,
                'mainCategoriesArray' => $mainCategoriesArray ?? null,
                'subCategoriesArray'  => $subCategoriesArray
            ]);
        }

        return redirect()->route('admin.analyses.index')->with('errors', 'Анализ отсутствует в базе данных');
    }

    /**
     * Update analyse in database
     *
     * @param AnalyseRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(AnalyseRequest $request, $id)
    {
        $analyse = Analyse::find($id);

        if ($analyse) {
            $input = $request->except('_token');

            $input['is_active'] = (int)$request->is_active;
            $input['price'] = replacePriceToDouble($request->price);
            $input['discount'] = ($request->discount == null) ? NULL : replacePriceToDouble($request->discount);

            $input['updated_by_user_id'] = Auth::id();
            $input['category_id'] = (isset($request->sub_category_id) && $request->sub_category_id != '0') ? $request->sub_category_id : $request->category_id;

            $input['meta_title'] = (isset($request->meta_title)) ? $request->meta_title : mb_substr($request->title, 0, 80);
            $input['meta_description'] = (isset($request->meta_description)) ? $request->meta_description : mb_substr(strip_tags($request->description), 0, 180);


            $analyse->first_letter = $request->title;
            $analyse->fill($input);

            if ($analyse->update()) {
                $request->session()->flash('successMessage', 'Анализ успешно обновлен');
                return response()->json(['analyseId' => $id]);
            }
        }

        abort(404);
    }

    /**
     * Destroy analyse
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $analyse = Analyse::find($id);

        if ($analyse) {
            // You can not delete an analysis if it is part of the Complex
            $checkAnalyseInComplex = Analyse::join('complexes_analyses', 'analyses.id', '=', 'complexes_analyses.analyse_id')
                ->join('complexes', 'complexes_analyses.complex_id', '=', 'complexes.id')
                ->select('complexes_analyses.analyse_id')
                ->whereNull('complexes.deleted_at')
                ->where('complexes_analyses.analyse_id', $id)->count();

            if ($checkAnalyseInComplex > 0) {
                return redirect()->route('admin.analyses.index')->with('errors', 'Нельзя удалить анализ, который входит в Комплекс');
            }

            try {
                $analyse->delete();
            } catch (\Exception $e) {
                \Log::error("Error in removing analysis" . __CLASS__ . " 284 line.");
                \Log::error($e->getMessage());
                return redirect()->route('admin.analyses.index')->with('errors', 'Анализ отстутствует в базе данных');
            }

            return redirect()->route('admin.analyses.index')->with('successMessage', 'Анализ успешно удален из базы данных');
        }
    }

    /**
     * Template for unloading analyzes in Google Sheets
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function uploadToGoogleSheets()
    {
        $categories = AnalyseCategory::whereParentId(null)->orderBy('title')->get();


        return view('admin.analyse.upload_gs', [
            'categories' => $categories,
        ]);
    }

    public function downloadFromGoogleSheets()
    {

        $lastUpdateRes = DB::table('analyses_last_update')->select('last_update')->orderByDesc('id')->limit(1)->get();
        if (isset($lastUpdateRes[0]->last_update)) {
            $dataForView = date("d-m-Y H:i:s", strtotime($lastUpdateRes[0]->last_update));
        }
        $countAdded = Analyse::withTrashed()->where('new', 1)->count();
        $countUpdate = Analyse::withTrashed()->where('updated', 1)->count();

        $countDeletedInAdded = Analyse::withTrashed()->where('new', 1)->whereNotNull('deleted_at')->count();
        $countDeletedInUpdated = Analyse::withTrashed()->where('updated', 1)->whereNotNull('deleted_at')->count();

        return view('admin.analyse.download_gs', [
            'dataForView' => $dataForView ?? null,
            'updated' => $countUpdate,
            'added' => $countAdded,
            'countDeletedInAdded' => $countDeletedInAdded,
            'countDeletedInUpdated' => $countDeletedInUpdated
        ]);
    }

    /**
     * Handler uploading analyses from Google Sheets
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadToGoogleSheetsHandler(Request $request, GoogleSheets $gs)
    {
        $items = $request->get('myData');

        if (count($items) > 0) {
            // if there is a request for data verification
            if (isset($items['check'])) {

                if (Analyse::count() == 0) {
                    return response()->json(['success' => 'empty_db']);
                }

                // если отметили галочку Без категорий - считаем сколько есть таких анализов
                if (in_array('not_categories', $items['array'])) {
                    $analysesWithoutCategory = Analyse::whereNull('category_id')
                    ->get();
                }

                // если отмечены остальные галочки - считаем сколько анализов входящих в категории
                $analysesInCategory = Analyse::whereIn('category_id', $items['array'])
                ->get();

                // если отмечена галочка Без категорий + нет анализов без катгорий + нет анализов с категориями
                if (in_array('not_categories', $items['array']) && $analysesWithoutCategory->count() == 0 && $analysesInCategory->count() == 0) {
                    return response()->json(['success' => 'empty_category']);
                    // если галочки Без категорй не стоит + нет анализов с категориями
                } elseif (!in_array('not_categories', $items['array']) && $analysesInCategory->count() == 0) {
                    return response()->json(['success' => 'empty_category']);
                }


                $countAnalysesFromGs = $gs->checkCountAnalysesFromGs();

                // write to session count analyses in GS
                session(['countAnalysesFromGs' => $countAnalysesFromGs]);

                if ($gs->checkCountAnalysesFromGs() > 0) {
                    return response()->json(['success' => false]);
                }

                return response()->json(['success' => true]);
            }

            // if there is a request to write analyzes in the GS
            if (isset($items['send'])) {

                // If in GS there are analyzed - we clear
                if (Session::pull('countAnalysesFromGs') > 0) {

                    // Adds clearing analyzes from google sheets to the queue

                    ClearGoogleSheets::dispatch();
                }

                $countAnalyses = Analyse::whereIn('category_id', $items['array'])
                    ->orWhereNull('category_id')
                    ->orderBy('code')
                    ->count();

                if ($countAnalyses > 0) {

                    // Adds unloading of analyzes to the queue
                    SendAnalysesFromGoogleSheets::dispatch($items);
                    sleep(6);
                    return response()->json(['success' => true]);
                }

                // analyzes in the GS are not saved
                return response()->json(['success' => false]);
            }
        }

        return response()->json([]);
    }

    /**
     * Handler downloading analyses from Google Sheets
     *
     * @param Request $request
     * @param GoogleSheets $gs
     * @return \Illuminate\Http\JsonResponse
     */
    public function downloadFromGoogleSheetsHandler(Request $request, GoogleSheets $gs)
    {
        $ajaxData = $request->get('myData');

        if ($ajaxData == 'check_gs_table') {

            $checkGsTable = $gs->checkCorrectStructureGsTable();

            // проверка на пустоту таблицы + на пустые ячейки
            if (isset($checkGsTable['error'])) {
                if ($checkGsTable['error'] == 'empty_gs_table') {
                    return response()->json(['success' => false, 'data' => 'empty_gs_table']);
                }

                if ($checkGsTable['error'] == 'number_cells') {
                    return response()->json([
                        'success' => false,
                        'data' => 'number_cells',
                        'row' => $checkGsTable['row']
                    ]);
                }

                if ($checkGsTable['error'] == 'error_spaces') {
                    return response()->json([
                        'success' => false,
                        'data' => 'error_spaces',
                        'row' => $checkGsTable['row']
                    ]);
                }

                if ($checkGsTable['error'] == 'duplicate_keys') {
                    return response()->json([
                        'success' => false,
                        'data' => 'duplicate_keys',
                        'duplicates' => $checkGsTable['duplicates']
                    ]);
                }
            }

            // проверка на корректно заполненные данные в ячейках
            if (isset($checkGsTable['error_field'])) {
                return response()->json([
                    'success' => false,
                    'data' => 'error_field',
                    'field' => $checkGsTable['error_field'],
                    'row' => $checkGsTable['row'],
                    'msg' => $checkGsTable['msg']
                ]);
            }

            return response()->json(['success' => true]);
        }

        if ($ajaxData == 'upload_db') {
            $analyses = Analyse::withTrashed()->get();

            // clear old statuses
            if ($analyses->count() > 0) {
                foreach ($analyses as $analyse) {
                    $analyse->new = null;
                    $analyse->updated = null;
                    $analyse->save();
                }
            }

            // get array with analyzes from GS
            $newArray = $gs->analysesToArray();

            foreach ($newArray as $row) {
                $code = $row[0];
                $isActiveInGs = ($row[1] == 'Активный') ? 1 : 0;
                $deletedAt = ($row[2] == 'Удаленный' ? time() : null);
                $title = $row[3];
                $price = $row[4];
                $discount = $row[5];
                $term = (int)$row[6];


                $countRemoveAnalyses = Analyse::withTrashed()
                    ->whereNotNull('deleted_at')
                    ->whereCode($code)
                    ->count();

                $countNotRemoveAnalyses = Analyse::whereCode($code)
                    ->count();

                // if in bd has not remove analyses
                if ($countNotRemoveAnalyses > 0) {
                    $analyse = Analyse::whereCode($code)->first();


                    if ($isActiveInGs == 1 && $this->checkRequiredFields($analyse) === false) {
                        $isActive = 0;
                    } else {
                        $isActive = $isActiveInGs;
                    }

                    $analyse->is_active = $isActive;
                    $analyse->deleted_at = $deletedAt;
                    $analyse->title = $title;
                    $analyse->meta_title = mb_substr($title, 0, 80);
                    $analyse->first_letter = $title;
                    $analyse->price = $price;
                    $analyse->discount = $discount;
                    $analyse->term = $term;
                    $analyse->updated = 1;
                    $analyse->save();

                    // иначе, если в бд только удаленные анализы
                    // так же обновляем статус у анализа с первым id
                } elseif ($countRemoveAnalyses > 0 && $countNotRemoveAnalyses == 0) {

                    $analyse = Analyse::withTrashed()
                        ->whereCode($code)->orderByDesc('deleted_at')->first();

                    if ($isActiveInGs == 1 && $this->checkRequiredFields($analyse) === false ) {
                        $isActive = 0;
                    } else {
                        $isActive = $isActiveInGs;
                    }

                    $analyse->is_active = $isActive;
                    $analyse->deleted_at = $deletedAt;
                    $analyse->title = $title;
                    $analyse->first_letter = $title;
                    $analyse->meta_title = mb_substr($title, 0, 80);
                    $analyse->price = $price;
                    $analyse->discount = $discount;
                    $analyse->term = $term;
                    $analyse->updated = 1;
                    $analyse->save();

                // иначе если нет ни активный, ни удаленных - добавляем новый анализ
                } elseif ($countRemoveAnalyses == 0 && $countNotRemoveAnalyses == 0) {
                    $analyse = new Analyse();
                    $analyse->code = $code;
                    $analyse->is_active = 0;
                    $analyse->is_promoted = 0;
                    $analyse->title = $title;
                    $analyse->deleted_at = $deletedAt;
                    $analyse->price = $price;
                    $analyse->discount = $discount;
                    $analyse->term = $term;
                    $analyse->is_complex = 0;
                    $analyse->first_letter = $title;
                    $analyse->meta_title = mb_substr($title, 0, 80);
                    $analyse->new = 1;
                    $analyse->save();
                }

            }

            date_default_timezone_set('Europe/Kiev');
            $currentDate = date("Y-m-d H:i:s");

            DB::insert("INSERT INTO `analyses_last_update` (`id`, `last_update`) VALUES (?, ?)", [NULL, $currentDate]);

            $lastUpdateRes = DB::table('analyses_last_update')->select('last_update')->orderByDesc('id')->limit(1)->get();
            $lastUpdate = date("d-m-Y H:i:s", strtotime($lastUpdateRes[0]->last_update));

            $countAdded = Analyse::withTrashed()->where('new', 1)->count();
            $countUpdate = Analyse::withTrashed()->where('updated', 1)->count();

            $countDeletedInAdded = Analyse::withTrashed()->where('new', 1)->whereNotNull('deleted_at')->count();
            $countDeletedInUpdated = Analyse::withTrashed()->where('updated', 1)->whereNotNull('deleted_at')->count();

            return response()->json([
                'success' => true,
                'data' => [
                    'date' => $lastUpdate,
                    'added' => $countAdded,
                    'updated' => $countUpdate,
                    'countDeletedInAdded' => $countDeletedInAdded,
                    'countDeletedInUpdated' => $countDeletedInUpdated
                ]
            ]);
        }

        return response()->json(['success' => false, 'error update statuses for new & updated analyses']);
    }

    /**
     * Template for downloaded analyses from gs
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function downloadedAnalyses()
    {
        $analyses = [];
        return view('admin.analyse.downloaded_analyses', [
            'analyses' => $analyses
        ]);
    }

    /**
     * Get to view updated & new analyses
     *
     * @return mixed
     * @throws \Exception
     */
    public function getDownloadingData()
    {
        $entities = Analyse::where('new', 1)
            ->orWhere('updated', 1)
            ->orderByDesc('new')->orderBy('code', 'ASC');
        $entityGroup = 'analyses';

        return Datatables::of($entities)
            ->addColumn('new', function (Analyse $entities) {
                return ($entities->new) ? 'Да' : 'Нет';
            })
            ->addColumn('updated', function (Analyse $entities) {
                return ($entities->updated) ? 'Да' : 'Нет';
            })
            ->editColumn('is_active', function ($entities) {
                return ($entities->is_active) ? Lang::get('statuses.analise.active') : Lang::get('statuses.analise.not_active');
            })
            ->editColumn('title', function ($entities) {
                $titleLength = iconv_strlen($entities->title);
                if ($titleLength > 100) {
                    return  mb_substr($entities->title, 0, 100) . "...";
                } else {
                    return $entities->title;
                }
            })
            ->addColumn('category', function (Analyse $entities) {
                return (isset($entities->rAnalysesCategories))
                    ? (isset($entities->rAnalysesCategories->parent)) ? $entities->rAnalysesCategories->parent->title : $entities->rAnalysesCategories->title
                    : "";
            })
            ->addColumn('action', function ($entities) use ($entityGroup) {
                return view('admin.buttons_action')
                    ->with('entities', $entities)
                    ->with('entityGroup', $entityGroup);
            })
            ->make(true);
    }

    /**
     * Check required field from analyse
     *
     * @param $analyse
     * @return bool
     */
    private function checkRequiredFields($analyse)
    {
        if ($analyse->title == '' || $analyse->material == null ||
            $analyse->result == null || $analyse->term == null ||
            $analyse->price == null || $analyse->category_id == null
        )
        {
            return false;
        }

        return true;
    }

}
