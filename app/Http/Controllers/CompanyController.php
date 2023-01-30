<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Models\Company;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response as ResponseAlias;
use Illuminate\Support\Facades\Auth;

class CompanyController extends Controller
{

    /**
     *
     */
    public function __construct()
    {
        $this->middleware(['auth'])->except(['index', 'show']);
    }


    /**
     * Show all the list of companies
     * @return Application|Factory|View
     */
    public function index(): View|Factory|Application
    {
        $companies = Company::all();
        return view('companies.index', compact('companies'));
    }

    /**
     * Show the form for create company
     *
     * @return Application|Factory|View
     */
    public function create(): View|Factory|Application
    {
        return view('companies.create');
    }


    /**
     * Store a new created company
     *
     * @param StoreCompanyRequest $request
     * @return RedirectResponse|ResponseAlias
     */
    public function store(StoreCompanyRequest $request): ResponseAlias|RedirectResponse
    {

        $data = $request->validated();
        $logo = $data['logo'] ?? null;

        if (!is_null($logo)) {
            $path = $logo->store('logos', 'public');
            $data['logo'] = $path;
        }
        $data['user_id'] = Auth::id();

        $company = new Company($data);
        $company->save();

        return redirect()->route('companies.index')->with('status', ['text' => "{$company->name} company successfully created!", 'color' => 'success']);
    }

    /**
     * Display company data
     *
     * @param Company $company
     * @return Application|Factory|View|ResponseAlias
     */
    public function show(Company $company): Factory|View|ResponseAlias|Application
    {
        $company::with('employees')->get();
        return view('companies.show', compact('company'));
    }

    /**
     * Show a form for editing company data
     *
     * @param Company $company
     * @return Application|Factory|View|ResponseAlias
     */
    public function edit(Company $company): Factory|View|ResponseAlias|Application
    {
        return view('companies.edit', compact('company'));
    }

    /**
     * Update the edited company data
     *
     * @param Request $request
     * @param Company $company
     * @return RedirectResponse|ResponseAlias
     */
    public function update(UpdateCompanyRequest $request, Company $company): ResponseAlias|RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('logo')) {
            $file = $data['logo'];
            $path = $file->store('logos', 'public');
            $data['logo'] = $path;
        }
        $data['user_id'] = Auth::id();
        $company->update($data);

        return redirect()->route('companies.index')->with('status', ['text' => "{$company->name} company successfully updated!", 'color' => 'success']);
    }

    /**
     * Delete a company
     *
     * @param Company $company
     * @return RedirectResponse|ResponseAlias
     */
    public function destroy(Company $company): ResponseAlias|RedirectResponse
    {
        $company->delete();
        return redirect()->route('companies.index')->with('status', ['text' => "{$company->name} company successfully deleted!", 'color' => 'danger']);
    }
}
