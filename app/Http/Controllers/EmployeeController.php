<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Models\Company;
use App\Models\Employee;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\Response as ResponseAlias;
use Illuminate\Support\Facades\Auth;

class EmployeeController extends Controller
{


    /**
     * Show the form for create employee
     * @param Request $request
     * @return Factory|View|Application
     */
    public function create(Request $request, Company $company): \Illuminate\Contracts\View\Factory|View|Application
    {
        $company = $request->get('company');
        return view('employees.create', compact('company'));

    }

    /**
     * Store a new created employee
     * @param \App\Http\Requests\StoreEmployeeRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreEmployeeRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();
        $employee = new Employee($data);
        $employee->save();

        return redirect()->route('companies.show', $employee->company_id)->with('status', ['text' => "{$employee->name} employee successfully created!", 'color' => 'success']);
    }

    /**
     * Show a form for editing employee data
     *
     * @param Employee $employee
     * @return Application|Factory|View|ResponseAlias
     */
    public function edit(Employee $employee): Factory|View|ResponseAlias|Application
    {
        return view('employees.edit', compact('employee'));
    }

    /**
     * Update the edited company data
     *
     * @param UpdateEmployeeRequest $request
     * @param Employee $employee
     * @return RedirectResponse|Response
     */
    public function update(UpdateEmployeeRequest $request, Employee $employee)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();
        $employee->update($data);
        return redirect()->route('companies.show', $employee->company_id)->with('status', ['text' => "{$employee->name} employee successfully updated!", 'color' => 'success']);
    }

    /**
     * Delete a company
     *
     * @param \App\Models\Employee $employee
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect()->route('companies.show', $employee->company_id)->with('status', ['text' => "{$employee->name} employee successfully deleted!", 'color' => 'danger']);
    }
}
