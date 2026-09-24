<?php

namespace App\Http\Controllers;

use App\Models\SupplierApplication;
use App\SupplierForms\SupplierOnboardingForm;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SupplierDocumentController extends Controller
{
    /**
     * Download a document of a supplier file (private disk, back-office users only).
     */
    public function __invoke(SupplierApplication $supplierApplication, string $field, int $index): StreamedResponse
    {
        abort_unless((bool) auth()->user()?->canAccessPanel(Filament::getPanel('admin')), 403);
        abort_unless(in_array($field, SupplierOnboardingForm::DOCUMENT_KEYS, true), 404);

        $file = $supplierApplication->onboarding_answers[$field][$index] ?? null;

        abort_if($file === null || ! Storage::disk('local')->exists($file['path']), 404);

        return Storage::disk('local')->download($file['path'], $file['name']);
    }
}
