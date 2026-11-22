@extends('layouts.admin')

@section('title', 'Edit Field Formulir')

@section('content')
<div class="mx-auto max-w-5xl space-y-6 px-6 py-8">

    <div>
        <a href="{{ route('admin.competitions.fields.index', $competition) }}"
           class="text-sm font-medium text-slate-500 transition hover:text-emerald-700">
            ← Kembali ke daftar field
        </a>

        <h1 class="mt-3 text-2xl font-bold text-slate-900">
            Edit Field Formulir
        </h1>

        <p class="mt-1 text-sm text-slate-500">
            Kompetisi: {{ $competition->name }}
        </p>
    </div>

    @if ($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
            <p class="mb-2 font-semibold">Periksa kembali data berikut:</p>
            <ul class="list-inside list-disc space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.competitions.fields.update', [$competition, $field]) }}"
          method="POST"
          id="field-form"
          class="space-y-6">

        @csrf
        @method('PUT')

        {{-- Informasi Field --}}
        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="mb-5 text-lg font-semibold text-slate-900">
                Informasi Field
            </h2>

            <div class="grid gap-5 md:grid-cols-2">

                <div class="md:col-span-2">
                    <label for="label" class="mb-2 block text-sm font-medium text-slate-700">
                        Label Pertanyaan <span class="text-red-500">*</span>
                    </label>

                    <input type="text"
                           id="label"
                           name="label"
                           value="{{ old('label', $field->label) }}"
                           placeholder="Contoh: Nama Sekolah"
                           required
                           class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100">
                </div>

                <div>
                    <label for="name" class="mb-2 block text-sm font-medium text-slate-700">
                        Nama Field <span class="text-red-500">*</span>
                    </label>

                    <input type="text"
                           id="name"
                           name="name"
                           value="{{ old('name', $field->name) }}"
                           placeholder="nama_sekolah"
                           required
                           pattern="[a-zA-Z][a-zA-Z0-9_]*"
                           class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100">

                    <p class="mt-1 text-xs text-slate-500">
                        Gunakan huruf, angka, dan underscore tanpa spasi.
                    </p>
                </div>

                <div>
                    <label for="type" class="mb-2 block text-sm font-medium text-slate-700">
                        Jenis Input <span class="text-red-500">*</span>
                    </label>

                    <select id="type"
                            name="type"
                            required
                            class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100">

                        @foreach ($fieldTypes as $type)
                            <option value="{{ $type }}"
                                @selected(old('type', $field->type) === $type)>
                                {{ strtoupper($type) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label for="placeholder" class="mb-2 block text-sm font-medium text-slate-700">
                        Placeholder
                    </label>

                    <input type="text"
                           id="placeholder"
                           name="placeholder"
                           value="{{ old('placeholder', $field->placeholder) }}"
                           placeholder="Contoh: Masukkan nama sekolah"
                           class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100">
                </div>

            </div>
        </div>

        {{-- Pilihan Jawaban --}}
        <div id="options-section"
             class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">

            <h2 class="text-lg font-semibold text-slate-900">
                Pilihan Jawaban
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Digunakan untuk jenis input select, radio, dan checkbox.
                Satu pilihan ditulis pada setiap baris.
            </p>

            <div class="mt-5">
                <label for="options_text" class="mb-2 block text-sm font-medium text-slate-700">
                    Daftar Pilihan
                </label>

                <textarea id="options_text"
                          rows="6"
                          placeholder="Web Development&#10;Desain Grafis&#10;UI/UX Design"
                          class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100">{{ old('options_text', implode("\n", $field->options ?? [])) }}</textarea>

                <p class="mt-2 text-xs text-slate-500">
                    Pilihan lama akan ditampilkan di sini dan bisa kamu ubah.
                </p>
            </div>
        </div>

        {{-- Pengaturan --}}
        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="mb-5 text-lg font-semibold text-slate-900">
                Pengaturan Field
            </h2>

            <div class="grid gap-5 md:grid-cols-2">

                <div>
                    <label for="sort_order" class="mb-2 block text-sm font-medium text-slate-700">
                        Urutan Pertanyaan
                    </label>

                    <input type="number"
                           id="sort_order"
                           name="sort_order"
                           min="0"
                           value="{{ old('sort_order', $field->sort_order) }}"
                           class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100">
                </div>

                <div class="space-y-4 pt-1">

                    <label class="flex cursor-pointer items-center gap-3">
                        <input type="hidden" name="is_required" value="0">

                        <input type="checkbox"
                               name="is_required"
                               value="1"
                               @checked(old('is_required', $field->is_required) == '1')
                               class="h-4 w-4 cursor-pointer rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">

                        <span class="text-sm font-medium text-slate-700">
                            Wajib diisi peserta
                        </span>
                    </label>

                    <label class="flex cursor-pointer items-center gap-3">
                        <input type="hidden" name="is_active" value="0">

                        <input type="checkbox"
                               name="is_active"
                               value="1"
                               @checked(old('is_active', $field->is_active) == '1')
                               class="h-4 w-4 cursor-pointer rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">

                        <span class="text-sm font-medium text-slate-700">
                            Aktifkan field
                        </span>
                    </label>

                </div>
            </div>
        </div>

        {{-- Tombol --}}
        <div class="flex flex-col-reverse justify-end gap-3 sm:flex-row">
            <a href="{{ route('admin.competitions.fields.index', $competition) }}"
               class="cursor-pointer rounded-lg border border-slate-300 bg-white px-5 py-3 text-center text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                Batal
            </a>

            <button type="submit"
                    class="cursor-pointer rounded-lg bg-emerald-700 px-6 py-3 text-sm font-semibold text-white transition hover:bg-emerald-800">
                Simpan Perubahan
            </button>
        </div>

    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const typeInput = document.getElementById('type');
    const optionsSection = document.getElementById('options-section');
    const optionsTextarea = document.getElementById('options_text');
    const form = document.getElementById('field-form');

    const optionTypes = ['select', 'radio', 'checkbox'];

    function toggleOptions() {
        const needsOptions = optionTypes.includes(typeInput.value);

        optionsSection.classList.toggle('hidden', !needsOptions);
        optionsTextarea.required = needsOptions;
    }

    typeInput.addEventListener('change', toggleOptions);

    toggleOptions();

    form.addEventListener('submit', function () {
        form.querySelectorAll('input[name="options[]"]').forEach(function (input) {
            input.remove();
        });

        if (!optionTypes.includes(typeInput.value)) {
            return;
        }

        const options = optionsTextarea.value
            .split('\n')
            .map(option => option.trim())
            .filter(option => option.length > 0);

        options.forEach(function (option) {
            const input = document.createElement('input');

            input.type = 'hidden';
            input.name = 'options[]';
            input.value = option;

            form.appendChild(input);
        });
    });
});
</script>
@endsection
