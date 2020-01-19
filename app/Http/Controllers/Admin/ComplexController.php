<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminController;
use App\Http\Requests\ComplexRequest;
use App\Models\Analyse;
use App\Models\Complex;
use Illuminate\Support\Facades\Lang;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class ComplexController extends AdminController
{

    protected $folderName = 'complex';
    protected $modelName = 'App\Models\Complex';
    protected $entityName = 'complex';
    protected $successMessage = 'Комплекс успешно удален из базы данных';
    protected $errorMessage = 'Комплекс отсутствует в базе данных';


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (view()->exists('admin.complex.create')) {

            $analyses = Analyse::whereIsActive(true)
                ->orderBy('title')
                ->get();

            $analysesToView = [];
            foreach ($analyses as $a) {
                $analysesToView[$a->id] = mb_substr($a->title, 0, 60);
            }

            return view('admin.complex.create', [
                'analyses' => $analysesToView
            ]);
        }

        abort(404);
    }

    /**
     * Save a complex to db
     *
     * @param ComplexRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ComplexRequest $request)
    {

        DB::beginTransaction();
        try {
            $complex = Complex::create([
                'is_active'                  => (int)$request->is_active,
                'title' => $request->title,
                'code' => $request->code,
                'term' => $request->term,
                'discount' => ($request->discount == null) ? null : replacePriceToDouble($request->discount),
                'price' => replacePriceToDouble($request->price),
                'first_letter' => $request->title,
                'created_by_user_id' => Auth::id(),
            ]);

            $complex->rAnalyses()->sync($request->analyse_id);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error("Error in creating complex." . __CLASS__ . " 62 line.");
            \Log::error($e->getMessage());
            return redirect()->route('admin.complexes.index')->with('errors', 'Ошибка соединения с базой данных');
        }

        DB::commit();
        $request->session()->flash('successMessage', 'Комплекс успешно добавлен в базу данных');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $complex = Complex::findOrFail($id);
        if ($complex) {

            $selectAnalyse = [];
            $analyses = Analyse::whereIsActive(true)
                ->orderBy('title')
                ->get();

            $analysesInComplex = $complex->rAnalyses()->get();
            $analysesForSelect = $analyses->diff($analysesInComplex);

            foreach ($analysesForSelect as $analyse) {
                $selectAnalyse[$analyse->id] = mb_substr($analyse->title, 0, 60);
            }

            return view('admin.complex.edit', [
                'complex' => $complex,
                'selectAnalyse' => $selectAnalyse,
            ]);
        }

        return redirect()->route('admin.complexes.index')->with('errors', 'Комплекс отсутствует в базе данных');
    }

    /**
     * Update complex to db
     *
     * @param ComplexRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ComplexRequest $request, $id)
    {
        $complex = Complex::find($id);

        if ($complex) {

            DB::beginTransaction();
            try {
                $complex->update([
                    'is_active' => (int)$request->is_active,
                    'title' => $request->title,
                    'code' => $request->code,
                    'term' => $request->term,
                    'discount' => ($request->discount == null) ? null : replacePriceToDouble($request->discount),
                    'price' => replacePriceToDouble($request->price),
                    'first_letter' => $request->title,
                    'updated_by_user_id' => Auth::id(),
                ]);

                $complex->rAnalyses()->sync($request->analyse_id);
            } catch (\Exception $e) {
                DB::rollBack();
                \Log::error("Error in update complex." . __CLASS__ . " 131 line.");
                \Log::error($e->getMessage());
                return redirect()->route('admin.complexes.index')->with('errors', 'Ошибка соединения с базой данных');
            }

            DB::commit();
            $request->session()->flash('successMessage', 'Комплекс успешно обновлен');
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $complex = Complex::find($id);
        if ($complex) {
            try {
                $complex->delete();
            } catch (\Exception $e) {
                \Log::error("Error in destroy complex. " . __CLASS__ . " 166 line.");
                \Log::error($e->getMessage());
                return redirect()->route('admin.complexes.index')->with('errors', 'Комплекс отстутствует в базе данных');
            }

            return redirect()->route('admin.complexes.index')->with('successMessage', 'Комплекс успешно удален из базы данных');
        }
    }

    /**
     * Get data for complexes index action table
     *
     * @return mixed
     * @throws \Exception
     */
    public function complexesGetData()
    {
        $entities = Complex::orderBy('is_active', 'DESC')->orderBy('title', 'ASC');
        $entityGroup = 'complexes';

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

}
