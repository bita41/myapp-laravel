<?php

declare(strict_types=1);

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Support\DataTableEngine;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

final class DictionariesController extends Controller
{
    /** @var array<string, array<string, int>> e.g. ['settings_tab_1' => ['permission_r' => 1, 'permission_w' => 1]]. Set by middleware or parent. */
    protected array $roles = [];

    /**
     * Column titles for DataTables (order matches dictionariesServerSide dtColumns: actions, id, parameter, languages).
     *
     * @return array<int, string>
     */
    private function datatableColumnTitles(): array
    {
        $titles = ['', '', l('a-dictionary-parameter')];
        foreach (get_languages() as $lang) {
            $titles[] = (string) ($lang['name'] ?? $lang['file'] ?? '');
        }

        return $titles;
    }

    /**
     * Dictionaries index. Echivalent CI4 load_header: numLang, custom_css, custom_js, pageModules (doar aici se incarca dictionaries.js).
     */
    public function index(): View
    {
        $languages = get_languages();
        $datatableColumnTitles = $this->datatableColumnTitles();
        $numLang = is_countable($languages) ? count($languages) : 0;
        $custom_css = [];
        $custom_js = [];
        $pageModules = ['settings/dictionaries'];

        return view('settings.dictionaries.index', compact(
            'languages',
            'datatableColumnTitles',
            'numLang',
            'custom_css',
            'custom_js',
            'pageModules'
        ));
    }

    /** Add – returns HTML for modal (remote load). */
    public function add(): View
    {
        $languages = get_languages();

        return view('settings.dictionaries.create', compact('languages'));
    }

    /** Add Ajax – process add form. */
    public function addAjax(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'parameter' => 'required|string|max:255',
        ]);

        $data = [
            'parameter' => $validated['parameter'],
            'record_update_date' => now(),
        ];
        foreach (get_languages() as $lang) {
            $file = (string) ($lang['file'] ?? '');
            if ($file !== '' && Schema::hasColumn('dictionaries', $file)) {
                $data[$file] = $request->input("dictionar.{$file}", '');
            }
        }

        DB::table('dictionaries')->insert($data);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => l('a-save-succesfully')]);
        }

        return back()->with('success', l('a-save-succesfully'));
    }

    /** Edit – returns HTML for modal (remote load). */
    public function edit(string $id): View|JsonResponse
    {
        $row = DB::table('dictionaries')->where('dictionar_id', (int) $id)->first();
        if (!$row) {
            abort(404);
        }
        $languages = get_languages();

        return view('settings.dictionaries.edit', compact('row', 'languages'));
    }

    /** Edit Ajax – process edit form. */
    public function editAjax(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'dictionar_id' => 'required|integer|exists:dictionaries,dictionar_id',
            'parameter' => 'required|string|max:255',
        ]);

        $data = [
            'parameter' => $validated['parameter'],
            'record_update_date' => now(),
        ];
        foreach (get_languages() as $lang) {
            $file = (string) ($lang['file'] ?? '');
            if ($file !== '' && Schema::hasColumn('dictionaries', $file)) {
                $data[$file] = $request->input("dictionar.{$file}", '');
            }
        }

        DB::table('dictionaries')->where('dictionar_id', $validated['dictionar_id'])->update($data);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => l('a-edit-succesfully')]);
        }

        return back()->with('success', l('a-edit-succesfully'));
    }

    /** Dictionaries server-side – DataTables AJAX. */
    public function dictionariesServerSide(Request $request): JsonResponse
    {
        $languages = get_languages();

        $dtColumns = [
            0 => null,
            1 => 'd.parameter',
        ];
        foreach ($languages as $language) {
            $file = (string) ($language['file'] ?? '');
            if ($file === '') {
                continue;
            }
            if (Schema::hasColumn('dictionaries', $file)) {
                $dtColumns[] = 'd.' . $file;
            }
        }

        $baseQuery = DB::table('dictionaries as d')
            ->select(array_values(array_filter($dtColumns, static fn ($c) => $c !== null)))
            ->addSelect('d.dictionar_id')
            ->whereRaw('1=1');

        $permR = $this->roles['settings_tab_1']['permission_r'] ?? 1;
        $permW = $this->roles['settings_tab_1']['permission_w'] ?? 1;

        $editPermClass = $permW === 0 ? 'd-none' : '';

        $payload = DataTableEngine::make($request)
            ->query($baseQuery)
            ->columns($dtColumns)
            ->searchColumns(array_values(array_filter($dtColumns, static fn ($c) => is_string($c))))
            ->defaultOrder('d.dictionar_id', 'desc')
            ->map(function (object $row) use ($languages, $editPermClass): array {
                $cells = [];

                $editUrl = route('settings.dictionaries.edit', ['id' => $row->dictionar_id]);
                $cells[] = '<div class="button-list">'
                    . '<a href="#" class="btn btn-warning btn-sm btn-icon btn-modal-remote ' . e($editPermClass) . '"'
                    . ' data-remote="' . e($editUrl) . '" data-bs-target="#modal-remote" data-bs-toggle="modal"'
                    . ' title="' . e(l('a-simple-edit')) . '"><i class="fal fa-edit"></i></a>'
                    . '</div>';

                $cells[] = e((string) ($row->parameter ?? ''));

                foreach ($languages as $language) {
                    $file = (string) ($language['file'] ?? '');
                    if ($file === '') {
                        continue;
                    }
                    if (!property_exists($row, $file)) {
                        $cells[] = '';
                        continue;
                    }
                    $cells[] = e((string) ($row->{$file} ?? ''));
                }

                return $cells;
            })
            ->toArray();

        return response()->json($payload);
    }
}
