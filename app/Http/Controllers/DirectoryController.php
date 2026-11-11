<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DocumentType;
use App\Models\ReasonNonCertification;

class DirectoryController extends Controller
{

    

    public function reasonsNonCertification()
    {
        $reasons = ReasonNonCertification::orderBy('id')->get();
        return view('directories.reasons-non-certification.index', compact('reasons'));
    }

    public function storeReason(\Illuminate\Http\Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        ReasonNonCertification::create($validated);

        return redirect()->route('directories.reasons-non-certification')->with('success', 'Причина успешно добавлена.');
    }

    public function updateReason(\Illuminate\Http\Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $reason = ReasonNonCertification::findOrFail($id);
        $reason->update($validated);

        return redirect()->route('directories.reasons-non-certification')->with('success', 'Причина успешно обновлена.');
    }

    public function destroyReason($id)
    {
        $reason = ReasonNonCertification::findOrFail($id);
        $reason->delete();

        return redirect()->route('directories.reasons-non-certification')->with('success', 'Причина успешно удалена.');
    }

    public function courses() // +
    {
        return view('directories.courses.index');
    }

    public function cities() // +
    {
        return view('directories.cities.index');
    }

    public function professions() // +
    {
        return view('directories.professions.index');
    }

    public function qualifications() // -
    {
        return view('directories.qualifications.index');
    }

    public function employees() // -
    {
        return view('directories.employees.index');
    }

    public function departments() // -
    {
        return view('directories.departments.index');
    }

    public function trainingTypes() // +
    {
        return view('directories.training-types.index');
    }

    public function reasonsRejection() // -
    {
        return view('directories.reasons-rejection.index');
    }

    public function categories() // -
    {
        return view('directories.categories.index');
    }

    public function documentTypes() // +
    {
        $documentTypes = DocumentType::orderBy('id')->get();
        return view('directories.document-types.index', compact('documentTypes'));
    }

    public function storeDocumentType(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'sap_code' => 'nullable|string|max:50',
        ]);

        DocumentType::create($validated);

        return redirect()->route('directories.document-types')->with('success', 'Тип документа успешно добавлен.');
    }

    public function updateDocumentType(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sap_code' => 'nullable|string|max:50',
        ]);

        $type = DocumentType::findOrFail($id);
        $type->update($validated);

        return redirect()->route('directories.document-types')->with('success', 'Тип документа успешно обновлен.');
    }

    public function destroyDocumentType($id)
    {
        $type = DocumentType::findOrFail($id);
        $type->delete();

        return redirect()->route('directories.document-types')->with('success', 'Тип документа успешно удален.');
    }

    public function trainingType() // -
    {
        return view('directories.training-type.index');
    }

    public function courseAuthors() // -
    {
        return view('directories.course-authors.index');
    }

    public function trainingDirections() // -
    {
        return view('directories.training-directions.index');
    }

    public function costAllocation() // -
    {
        return view('directories.cost-allocation.index');
    }

    public function orders() // -
    {
        return view('directories.orders.index');
    }

    public function reasonsTraining() // +
    {
        return view('directories.reasons-training.index');
    }

    public function contracts() // -
    {
        return view('directories.contracts.index');
    }

    public function disciplines() // +
    {
        return view('directories.disciplines.index');
    }

    public function trainingAssessmentType() // +
    {
        return view('directories.training-assessment-type.index');
    }

    public function trainingAssessmentResources() // +
    {
        return view('directories.training-assessment-resources.index');
    }

    public function eventTypes() // +
    {
        return view('directories.event-types.index');
    }

    public function trainers() // +
    {
        return view('directories.trainers.index');
    }

    public function auditoriums() // +
    {
        return view('directories.auditoriums.index');
    }

    public function groupCurators() // +
    {
        return view('directories.group-curators.index');
    }

    public function absenceTypes() // -
    {
        return view('directories.absence-types.index');
    }
}
