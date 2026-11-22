<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use App\Models\CompetitionFormField;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CompetitionFormFieldController extends Controller
{
    /**
     * Jenis input yang didukung formulir pendaftaran.
     */
    private const FIELD_TYPES = [
        'text',
        'textarea',
        'tel',
        'email',
        'number',
        'url',
        'date',
        'select',
        'radio',
        'checkbox',
    ];

    /**
     * Menampilkan seluruh field milik kompetisi.
     */
    public function index(Competition $competition)
    {
        $fields = $competition->formFields()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return view('admin.competitions.fields.index', compact(
            'competition',
            'fields'
        ));
    }

    /**
     * Menampilkan formulir pembuatan field.
     */
    public function create(Competition $competition)
    {
        $fieldTypes = self::FIELD_TYPES;

        return view('admin.competitions.fields.create', compact(
            'competition',
            'fieldTypes'
        ));
    }

    /**
     * Menyimpan field baru.
     */
    public function store(Request $request, Competition $competition)
    {
        $validated = $this->validateField($request, $competition);

        $options = $this->normalizeOptions(
            $validated['options'] ?? null
        );

        $competition->formFields()->create([
            'label' => $validated['label'],
            'name' => $validated['name'],
            'type' => $validated['type'],
            'placeholder' => $validated['placeholder'] ?? null,
            'options' => $options,
            'is_required' => $validated['is_required'] ?? false,
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => $validated['is_active'] ?? true,
        ]);

        return redirect()
            ->route('admin.competitions.fields.index', $competition)
            ->with('success', 'Field formulir berhasil ditambahkan.');
    }

    /**
     * Menampilkan formulir edit field.
     */
    public function edit(
        Competition $competition,
        CompetitionFormField $field
    ) {
        $this->ensureFieldBelongsToCompetition($competition, $field);

        $fieldTypes = self::FIELD_TYPES;

        return view('admin.competitions.fields.edit', compact(
            'competition',
            'field',
            'fieldTypes'
        ));
    }

    /**
     * Memperbarui field.
     */
    public function update(
        Request $request,
        Competition $competition,
        CompetitionFormField $field
    ) {
        $this->ensureFieldBelongsToCompetition($competition, $field);

        $validated = $this->validateField(
            $request,
            $competition,
            $field
        );

        $field->update([
            'label' => $validated['label'],
            'name' => $validated['name'],
            'type' => $validated['type'],
            'placeholder' => $validated['placeholder'] ?? null,
            'options' => $this->normalizeOptions(
                $validated['options'] ?? null
            ),
            'is_required' => $validated['is_required'] ?? false,
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => $validated['is_active'] ?? true,
        ]);

        return redirect()
            ->route('admin.competitions.fields.index', $competition)
            ->with('success', 'Field formulir berhasil diperbarui.');
    }

    /**
     * Menghapus field.
     */
    public function destroy(
        Competition $competition,
        CompetitionFormField $field
    ) {
        $this->ensureFieldBelongsToCompetition($competition, $field);

        $field->delete();

        return redirect()
            ->route('admin.competitions.fields.index', $competition)
            ->with('success', 'Field formulir berhasil dihapus.');
    }

    /**
     * Validasi data field.
     */
    private function validateField(
        Request $request,
        Competition $competition,
        ?CompetitionFormField $field = null
    ): array {
        $optionTypes = ['select', 'radio', 'checkbox'];

        $isOptionType = in_array(
            $request->input('type'),
            $optionTypes,
            true
        );

        $rules = [
            'label' => [
                'required',
                'string',
                'max:255',
            ],

            'name' => [
                'required',
                'string',
                'max:100',
                'regex:/^[a-zA-Z][a-zA-Z0-9_]*$/',
                Rule::unique('competition_form_fields', 'name')
                    ->where('competition_id', $competition->id)
                    ->ignore($field?->id),
            ],

            'type' => [
                'required',
                Rule::in(self::FIELD_TYPES),
            ],

            'placeholder' => [
                'nullable',
                'string',
                'max:255',
            ],

            'is_required' => [
                'sometimes',
                'boolean',
            ],

            'sort_order' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'is_active' => [
                'sometimes',
                'boolean',
            ],
        ];

        if ($isOptionType) {
            $rules['options'] = [
                'required',
                'array',
                'min:1',
                'max:50',
            ];

            $rules['options.*'] = [
                'required',
                'string',
                'max:255',
                'distinct',
            ];
        } else {
            $rules['options'] = [
                'nullable',
                'prohibited',
            ];
        }

        return $request->validate($rules, [
            'label.required' => 'Label field wajib diisi.',
            'name.required' => 'Nama field wajib diisi.',
            'name.regex' => 'Nama field harus diawali huruf dan hanya boleh berisi huruf, angka, serta underscore.',
            'name.unique' => 'Nama field sudah digunakan dalam kompetisi ini.',
            'type.required' => 'Jenis input wajib dipilih.',
            'type.in' => 'Jenis input tidak valid.',
            'options.required' => 'Pilihan jawaban wajib diisi.',
            'options.min' => 'Minimal harus ada satu pilihan jawaban.',
            'options.max' => 'Maksimal terdapat 50 pilihan jawaban.',
            'options.*.required' => 'Pilihan jawaban tidak boleh kosong.',
            'options.*.distinct' => 'Pilihan jawaban tidak boleh duplikat.',
            'sort_order.integer' => 'Urutan field harus berupa angka.',
            'sort_order.min' => 'Urutan field tidak boleh negatif.',
        ]);
    }

    /**
     * Membersihkan pilihan jawaban.
     */
    private function normalizeOptions(?array $options): ?array
    {
        if ($options === null) {
            return null;
        }

        $options = array_map(
            fn ($option) => trim($option),
            $options
        );

        return array_values(array_filter(
            $options,
            fn ($option) => $option !== ''
        )) ?: null;
    }

    /**
     * Memastikan field merupakan milik kompetisi yang sedang dibuka.
     */
    private function ensureFieldBelongsToCompetition(
        Competition $competition,
        CompetitionFormField $field
    ): void {
        abort_unless(
            (int) $field->competition_id === (int) $competition->id,
            404
        );
    }
}
