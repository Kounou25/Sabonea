<?php

namespace App\Http\Controllers;

use App\Models\SupplierApplication;
use App\SupplierForms\SupplierProfileDocument;
use Filament\Facades\Filament;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SupplierProfilePdfController extends Controller
{
    /**
     * Supplier profile PDF, shown inline (back-office preview) or downloaded. Back-office users only.
     * "documents": comma-separated ids of the supplier documents to show inside (absent: default of the version).
     */
    public function __invoke(Request $request, SupplierApplication $supplierApplication): Response
    {
        abort_unless((bool) auth()->user()?->canAccessPanel(Filament::getPanel('admin')), 403);

        $document = new SupplierProfileDocument(
            $supplierApplication,
            (string) $request->query('audience', SupplierProfileDocument::INTERNAL),
            (string) $request->query('locale', 'fr'),
            $request->has('documents') ? array_filter(explode(',', (string) $request->query('documents'))) : null,
        );

        $disposition = $request->boolean('download') ? 'attachment' : 'inline';

        return response($document->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => $disposition.'; filename="'.$document->filename().'"',
        ]);
    }
}
