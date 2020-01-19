<?php

namespace App\Http\Controllers;

use App\Models\Analyse;
use App\Models\AnalyseCategory;
use App\Models\Complex;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Doctrine\DBAL\Query\QueryBuilder;

class AnalyseController extends Controller
{
    protected $modelName = [
        'Analyses'  => 'App\Models\Analyse',
        'Complexes' => 'App\Models\Complex'
    ];

    /**
     * Gets all analyses on first page load
     *
     * @return mixed
     */
    public function index()
    {
        // Extract categories that are active and in which there are active analyzes
        $categoriesWithAnalyses = AnalyseCategory::whereHas('rAnalyses', function ($query) {
            /** @var AnalyseCategory $query */
            $query->whereIsActive(true);
            $query->groupBy('category_id');
        })->whereIsActive(true)
            ->whereNull('parent_id')
            ->orderBy('title')
            ->get();

        // Extract categories in which there are active subcategories with active analyzes
        $categoriesWithSubcategories = AnalyseCategory::whereNull('parent_id')
            ->whereHas('children', function ($query) {
                /** @var AnalyseCategory $query */
                $query->whereIsActive(true);
                $query->whereHas('rAnalyses', function ($query1) {
                    /** @var AnalyseCategory $query1 */
                    $query1->whereIsActive(true);
                });
            })->get();

        $analysesCategories = $categoriesWithAnalyses->merge($categoriesWithSubcategories)->sortBy('title');

        $analyses = Analyse::whereIsActive(true)
            ->orderBy('title')
            ->paginate(config('app.pagination_default_value'));

        return view('analyses', [
            'analyses'           => $analyses,
            'analysesCategories' => $analysesCategories,
            'firstLetters'       => $this->getFirstLetters($this->modelName['Analyses'])
        ]);
    }

    /**
     * Gets first letters array for alphabet in table head
     * @param $source
     *
     * @return array
     */
    public function getFirstLetters($source)
    {
        if (in_array($source, $this->modelName)) {
            $firstLetters = $source::select('first_letter')
                ->where('is_active', '1')
                ->pluck('first_letter')
                ->toArray();
        } else {
            $firstLetters = $source->pluck('first_letter')
                ->toArray();
        }

        asort($firstLetters);
        $firstLetters = array_unique($firstLetters);

        if (in_array('A-Z', $firstLetters)) {
            $key = array_search('A-Z', $firstLetters);
            unset($firstLetters[$key]);
            array_push($firstLetters, 'A-Z');
        }

        if (in_array('0-9', $firstLetters)) {
            $key = array_search('0-9', $firstLetters);
            unset($firstLetters[$key]);
            array_push($firstLetters, '0-9');
        }

        sort($firstLetters);
        return $firstLetters;
    }

    /**
     * Shows analyse detail
     *
     * @param $slug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showAnalyse($slug)
    {
        $analyse = Analyse::where('slug', $slug)
            ->where('is_active', 1)
            ->firstOrFail();

        if (mb_substr($analyse->description, 0, 3) != '<p>') {
            $analyse->description = '<p>' . $analyse->description;
        }

        if (mb_substr($analyse->description, -4) != '</p>') {
            $analyse->description = $analyse->description . '</p>';
        }

        return view('analysesDetail', [
            'analise' => $analyse
        ]);
    }

    /**
     * Shows complex detail
     *
     * @param $slug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showComplex($slug)
    {
        if (!view()->exists('analysesComplexDetail')) {
            abort(404);
        }

        $complex = Complex::where('slug', $slug)
            ->where('is_active', 1)
            ->firstOrFail();

        return view('analysesComplexDetail', [
            'complex' => $complex
        ]);
    }

    /**
     * Generates view answer for ajax call
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function ajaxGetAnalyses(Request $request)
    {
        //If "Все категории" selected
        if ($request->data_category_id == 0) {
            $analyses = Analyse::fetchAllAnalyses($request->letter);
            $firstLetters = $this->getFirstLetters($this->modelName['Analyses']);

            $view = view('analysesTables.analysesTable')
                ->with('analyses', $analyses)
                ->with('firstLetters', $firstLetters)
                ->render();
        }
        //If "Комлексы" selected
        if ($request->data_category_id == -1) {
            $complexes = Complex::fetchAllComplexes($request->letter);
            $firstLetters = $this->getFirstLetters($this->modelName['Complexes']);

            $view = view('analysesTables.complexesTable')
                ->with('complexes', $complexes)
                ->with('firstLetters', $firstLetters)
                ->render();

            return response()->json(['html' => $view]);
        }
        // If chosen category has subcategories
        if ($request->has('data_subcategory_id')) {
            $analyses = $this->fetchAnalysesBySubcategory($request->letter, $request);

            $view = view('analysesTables.analysesTable')
                ->with('analyses', $analyses['analyses'])
                ->with('firstLetters', $analyses['analysesLetters'])
                ->with('subCategories', $analyses['subcategories'])
                ->render();

            return response()->json(['html' => $view]);
        }

        $category = AnalyseCategory::find($request->data_category_id);
        if ($category != null && $category->parent_id == null) {

            $subcategories = AnalyseCategory::where('parent_id', $category->id)
                ->whereIsActive(true)
                ->whereHas('rAnalyses', function ($query) {
                    /** @var Analyse $query */
                    $query->whereIsActive(true);

            })->orderBy('title')->get();

            if ($subcategories->count() > 0) {

                $analyses = Analyse::fetchAnalysesWithCategoryAndSubcategories($request->letter, $category);

                $analysesLetters = Analyse::select('first_letter')
                    ->whereHas('rAnalysesCategories', function ($query) use ($category) {
                    /** @var AnalyseCategory $query */
                    $query->whereParentId($category->id)
                        ->orWhere('id', $category->id);
                })->whereIsActive(true)
                    ->get();

                $view = view('analysesTables.analysesTable')->with('analyses', $analyses)
                    ->with('firstLetters', $this->getFirstLetters($analysesLetters))
                    ->with('subCategories', $subcategories)
                    ->with('categoryDescription', $category->description)
                    ->render();
            } else {
                $analyses = Analyse::fetchAnalysesWithoutSubcategories($request->letter, $category);

                // Sampling of letters if there are no subcategories in the category
                $analysesLetters = Analyse::select('first_letter')->whereIsActive(true)
                    ->whereCategoryId($category->id)
                    ->get();

                $view = view('analysesTables.analysesTable')->with('analyses', $analyses)
                    ->with('firstLetters', $this->getFirstLetters($analysesLetters))
                    ->with('categoryDescription', $category->description)
                    ->render();
            }
        }

        return response()->json(['html' => $view]);
    }


    /**
     * Fetch analyses with sub categories
     *
     * @param $letter
     * @param $subcategories
     * @return array
     */
    public function fetchAnalysesWithSubCategories($letter, $subcategories)
    {
        $analysesCollection = collect([]);
        $letterCollection = collect([]);
        if ($letter != 'all') {
            foreach ($subcategories as $subcategory) {
                $analyses = Analyse::where('is_active', 1)
                    ->where('category_id', $subcategory->id)
                    ->where('first_letter', $letter)
                    ->get();
                $analysesCollection = $analysesCollection->concat($analyses);
            }
        } else {
            foreach ($subcategories as $subcategory) {
                $analyses = Analyse::where('is_active', 1)
                    ->where('category_id', $subcategory->id)
                    ->get();
                $analysesCollection = $analysesCollection->concat($analyses);
            }
        }

        $analysesCollection = $analysesCollection->sortBy('title')->values();
        $analysesCollection = $this->paginate($analysesCollection);

        foreach ($subcategories as $subcategory) {
            $analysesLetters = Analyse::select('first_letter')
                ->where('is_active', 1)
                ->where('category_id', $subcategory->id)
                ->get();
            $letterCollection = $letterCollection->concat($analysesLetters);
        }
        $data = [
            'analyses'        => $analysesCollection,
            'analysesLetters' => $this->getFirstLetters($letterCollection)
        ];

        return $data;
    }

    /**
     * Fetch analyses by subcategories
     *
     * @param $letter
     * @param $request
     * @return array
     */
    public function fetchAnalysesBySubcategory($letter, $request)
    {
        if ($letter != 'all') {
            $analyses = Analyse::where('category_id', $request->data_subcategory_id)
                ->where('is_active', 1)
                ->where('first_letter', $letter)
                ->orderBy('title')
                ->paginate(config('app.pagination_default_value'));
        } else {
            $analyses = Analyse::where('category_id', $request->data_subcategory_id)
                ->where('is_active', 1)
                ->orderBy('title')
                ->paginate(config('app.pagination_default_value'));
        }

        $analysesLetters = Analyse::select('first_letter')->where('is_active', 1)
            ->where('category_id', $request->data_subcategory_id)
            ->get();
        $category = AnalyseCategory::find($request->data_category_id);
        $subcategories = AnalyseCategory::where('parent_id', $category->id)
            ->orderBy('title')
            ->get();

        $data = [
            'analyses'        => $analyses,
            'analysesLetters' => $this->getFirstLetters($analysesLetters),
            'subcategories'   => $subcategories
        ];

        return $data;
    }

    /**
     * Paginate method
     *
     * @param $items
     * @param int $perPage
     * @param null $page
     * @param array $options
     * @return LengthAwarePaginator
     */
    public function paginate($items, $perPage = 15, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);

        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }

    /**
     * Search analyses using ajax queries
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajaxSearchAnalyses(Request $request)
    {
        if ($request->searchData == null) {
            return response()->json([]);
        }

        if ($request->is_code == 0) {

            $val = $request->searchData;

            $resultAnalyses = Analyse::select(['title', 'is_complex', 'slug'])
                ->where(function ($query) use ($val) {
                    /** @var Analyse $query */
                    $query->where('title', 'like', $val . "%");
                    $query->orWhere('title', 'like', "%" . $val . "%");

                })->whereIsActive(true)->orderBy('title')->take(3)->get()->toArray();

            $resultAnalyses = $this->transformSlugToUrl($resultAnalyses, 'analyses');


            $resultComplexes = Complex::select(['title', 'is_complex', 'slug'])
                ->where(function ($query) use ($val) {
                    /** @var Complex $query */
                    $query->where('title', 'like', $val . "%");
                    $query->orWhere('title', 'like', "%" . $val . "%");

                })->whereIsActive(true)->orderBy('title')->take(3)->get()->toArray();

            $resultComplexes = $this->transformSlugToUrl($resultComplexes, 'analyseComplexes');

            $resultArray = array_merge($resultAnalyses, $resultComplexes);
        }

        if ($request->is_code == 1) {

            $resultAnalyses = Analyse::select(['title', 'is_complex', 'slug'])
                ->where('is_active', 1)
                ->where('code', 'like', "%" . $request->searchData . "%")
                ->take(3)
                ->get()
                ->toArray();
            $resultAnalyses = $this->transformSlugToUrl($resultAnalyses, 'analyses');
            $resultComplexes = Complex::select(['title', 'is_complex', 'slug'])
                ->where('is_active', 1)
                ->where('code', 'like', "%" . $request->searchData . "%")
                ->take(3)
                ->get()
                ->toArray();
            $resultComplexes = $this->transformSlugToUrl($resultComplexes, 'analyseComplexes');
            $resultArray = array_merge($resultAnalyses, $resultComplexes);
        }

        return response()->json($resultArray);
    }

    /**
     * Search analyses through the search form by button click
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function ajaxSearchAnalyseButtonClick(Request $request)
    {
        if ($request->searchData == null) {
            return response()->json([]);
        }

        $val = $request->searchData;
        $firstLetters = $this->getFirstLettersByButtonClick($val);
        $letter = $request->letter;

        // if send request with letter
        if ($letter != null) {
            if ($letter != 'all') {
                $items = $this->getDataWithLetter($val, $letter);
                // letter = all
            } else {
                $items = $this->getDataWithoutLetter($val);
            }
            // if send $request without letter
        } else {
            $items = $this->getDataWithoutLetter($val);
        }

        $resultView = view('analysesTables.searchTable', [
            'complexes'    => $items['resultComplex'],
            'analyses'     => $items['resultAnalyse'],
            'firstLetters' => $firstLetters,
            'currentPage'  => $items['currentPage'],
            'lastPage'     => $items['lastPage']
        ])->render();

        return response()->json(['html' => $resultView]);
    }

    /**
     * Transform slug to url
     *
     * @param $array
     * @param $routePart
     * @return mixed
     */
    public function transformSlugToUrl($array, $routePart)
    {
        foreach ($array as $key => $value) {
            $url = route($routePart . '.show', ['slug' => $value['slug']]);
            $array[$key]['url'] = $url;
            unset($array[$key]['slug']);
        }

        return $array;
    }

    /**
     * Get Complexes & Analyses if click letter
     *
     * @param $val
     * @param $letter
     * @return array
     */
    private function getDataWithLetter($val, $letter)
    {
        $resultComplex = Complex::where(function ($query) use ($val, $letter) {
            /** @var Complex $query */
            $query->whereFirstLetter($letter);
            $query->where('title', 'like', "%" . $val . "%");
            $query->orWhere('code', 'like', "%" . $val . "%");

        })->whereIsActive(true)->orderBy('title')
            ->get();

        $resultAnalyse = Analyse::where(function ($query) use ($val, $letter) {
            /** @var Analyse $query */
            $query->whereFirstLetter($letter);
            $query->where('title', 'like', "%" . $val . "%");
            $query->orWhere('code', 'like', "%" . $val . "%");

        })->whereIsActive(true)->orderBy('title')
            ->get();

        return $this->getResultByOnePage($resultComplex, $resultAnalyse);
    }

    /**
     * Get All Complexes & Analyses
     *
     * @param $val
     * @return array
     */
    private function getDataWithoutLetter($val)
    {
        $resultComplex = Complex::where(function ($query) use ($val) {
            /** @var Complex $query */
            $query->where('title', 'like', "%" . $val . "%");
            $query->orWhere('code', 'like', "%" . $val . "%");

        })->whereIsActive(true)
            ->orderBy('title')
            ->get();

        $resultAnalyse = Analyse::where(function ($query) use ($val) {
            /** @var Analyse $query */
            $query->where('title', 'like', "%" . $val . "%");
            $query->orWhere('code', 'like', "%" . $val . "%");
        })->whereIsActive(true)
            ->orderBy('title')
            ->get();

        return $this->getResultByOnePage($resultComplex, $resultAnalyse);
    }

    /**
     * Get the first letters of analysis
     *
     * @param $val
     * @return array
     */
    private function getFirstLettersByButtonClick($val)
    {
        $resultComplexLetters = Complex::select('first_letter')->where(function ($query) use ($val) {
            /** @var Complex $query */
            $query->where('title', 'like', "%" . $val . "%");
            $query->orWhere('code', 'like', "%" . $val . "%");

        })->whereIsActive(true)
            ->get();

        $resultAnalyseLetters = Analyse::where(function ($query) use ($val) {
            /** @var Analyse $query */
            $query->where('title', 'like', "%" . $val . "%");
            $query->orWhere('code', 'like', "%" . $val . "%");
        })->whereIsActive(true)
            ->get();

        $allLetters = array_unique(array_merge($this->fetchFirstLetters($resultComplexLetters), $this->fetchFirstLetters($resultAnalyseLetters)));
        sort($allLetters);

        return $allLetters;
    }

    /**
     * Fetch first letters from result array
     *
     * @param $arr
     * @return array
     */
    private function fetchFirstLetters($arr)
    {
        $resultArray = [];
        foreach ($arr as $a) {
            $resultArray[] = $a->first_letter;
        }

        return $resultArray;
    }

    /**
     * Get found complexes and analyzes for a specific page of pagination
     *
     * @param Collection $resultComplex
     * @param Collection $resultAnalyse
     * @return array
     */
    private function getResultByOnePage(Collection $resultComplex, Collection $resultAnalyse)
    {
        if (count($resultComplex) > 0) {
            $resultCollection = $this->mergeCollections($resultComplex, $resultAnalyse);
        } else {
            $resultCollection = $resultAnalyse;
        }

        $perPage = config('app.pagination_default_value');
        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        if ($currentPage == 1) {
            $start = 0;
        } else {
            $start = ($currentPage - 1) * $perPage;
        }

        $currentPageCollection = $resultCollection->slice($start, $perPage)->all();

        $result = new LengthAwarePaginator($currentPageCollection, count($resultCollection), $perPage);

        $result->setPath(LengthAwarePaginator::resolveCurrentPath());

        $comp = [];
        $anal = [];

        foreach ($result as $item) {
            if ($item->getTable() == 'complexes') {
                $comp[] = $item;
            }

            if ($item->getTable() == 'analyses') {
                $anal[] = $item;
            }
        }

        return [
            'resultComplex' => $comp,
            'resultAnalyse' => $anal,
            'currentPage'   => $result->currentPage(),
            'lastPage'      => $result->lastPage()
        ];
    }

    /**
     * Merged 2 collections by 1
     *
     * @param Collection $collection1
     * @param Collection $collection2
     * @return Collection
     */
    private function mergeCollections(Collection $collection1, Collection $collection2)
    {
        foreach ($collection2 as $item) {
            $collection1->push($item);
        }

        return $collection1;
    }
}
