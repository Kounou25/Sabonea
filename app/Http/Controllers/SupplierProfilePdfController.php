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
     */
    public function __invoke(Request $request, SupplierApplication $supplierApplication): Response
    {
        abort_unless((bool) auth()->user()?->canAccessPanel(Filament::getPanel('admin')), 403);

        $document = new SupplierProfileDocument(
            $supplierApplication,
            (string) $request->query('audience', SupplierProfileDocument::INTERNAL),
            (string) $request->query('locale', 'fr'),
        );

        $pdf = $document->pdf();

        return $request->boolean('download') ? $pdf->download($document->filename()) : $pdf->stream($document->filename());
    }
}
