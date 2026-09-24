<?php

namespace App\SupplierForms;

use App\Models\SupplierApplication;
use Illuminate\Support\Collection;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Writer\XLSX\Writer;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * Export of the supplier forms: one column per technical key, values stored as keys
 * (not translated labels). Test answers are left out.
 */
class SupplierApplicationExporter
{
    public const CONTACT = 'contact';

    public const ONBOARDING = 'onboarding';

    public function download(string $form, string $format): BinaryFileResponse
    {
        $definition = $form === self::ONBOARDING ? new SupplierOnboardingForm : new SupplierContactForm;
        $rows = $this->rows($form, $definition);
        $path = tempnam(sys_get_temp_dir(), 'sabonea-export-');

        if ($format === 'csv') {
            $this->writeCsv($path, $rows);
        } else {
            $this->writeXlsx($path, $rows);
        }

        $name = ($form === self::ONBOARDING ? 'sabonea-dossiers-fournisseurs' : 'sabonea-contacts-fournisseurs').'-'.now()->format('Y-m-d').'.'.$format;

        return response()->download($path, $name)->deleteFileAfterSend();
    }

    /**
     * @return Collection<int, array<int, mixed>>
     */
    public function rows(string $form, SupplierForm $definition): Collection
    {
        $columns = $definition->exportColumns();
        $answersColumn = $form === self::ONBOARDING ? 'onboarding_answers' : 'contact_answers';

        $query = SupplierApplication::query()->real()->orderBy('id');

        if ($form === self::ONBOARDING) {
            $query->whereNotNull('onboarding_submitted_at');
        }

        $header = ['id', 'status', 'submitted_at', 'form_language', ...$columns];

        $rows = $query->get()->map(function (SupplierApplication $application) use ($form, $definition, $columns, $answersColumn): array {
            $answers = $application->{$answersColumn} ?? [];
            $submittedAt = $form === self::ONBOARDING ? $application->onboarding_submitted_at : $application->contact_submitted_at;

            return [
                $application->id,
                $application->status->value,
                $submittedAt?->format('Y-m-d H:i'),
                $form === self::ONBOARDING ? $application->onboarding_locale : $application->contact_locale,
                ...array_map(fn (string $column): string|int|null => $definition->exportValue($column, $answers), $columns),
            ];
        });

        return collect([$header])->concat($rows);
    }

    /**
     * @param  Collection<int, array<int, mixed>>  $rows
     */
    private function writeXlsx(string $path, Collection $rows): void
    {
        $writer = new Writer;
        $writer->openToFile($path);

        foreach ($rows as $row) {
            $writer->addRow(Row::fromValues(array_map(fn ($value) => $value ?? '', $row)));
        }

        $writer->close();
    }

    /**
     * Semicolon-separated with a UTF-8 BOM, so that Excel opens accents and Chinese correctly.
     *
     * @param  Collection<int, array<int, mixed>>  $rows
     */
    private function writeCsv(string $path, Collection $rows): void
    {
        $handle = fopen($path, 'w');
        fwrite($handle, "\xEF\xBB\xBF");

        foreach ($rows as $row) {
            fputcsv($handle, $row, ';', '"', '');
        }

        fclose($handle);
    }
}
