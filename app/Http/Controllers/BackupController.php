<?php

namespace App\Http\Controllers;

use Artisan;
use Storage;
use Str;
use Carbon\Carbon;
use App\Http\Controllers\CommonController;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class BackupController extends Controller
{
    use CommonController;

    public function show(Request $request)
    {
        if (auth()->user()->role == "Administrator" && auth()->user()->username == "admin") {
            $app_backups = Storage::disk('backups')->files();
            $backups = [];

            foreach ($app_backups as $file) {
                if (!Str::startsWith($file, '.')) {
                    $info = [];
                    $file_name = head(explode('.', $file));
                    $date = Storage::disk('backups')->lastModified($file);
                    $extension = last(explode('.', $file));

                    $info['name'] = $file;
                    $info['date'] = $date;
                    $info['size'] = nice_filesize(Storage::disk('backups')->size($file));

                    if ($extension == "sql") {
                        $info['type'] = "Database";
                    } elseif ($extension == "zip") {
                        $zip = new \ZipArchive();

                        if ($zip->open(storage_path('app/backups/' . $file))) {
                            $info['type'] = str_replace("&", "+", $zip->getArchiveComment());
                            $zip->close();
                        } else {
                            $info['type'] = "Database + Files";
                        }
                    }

                    $info['download'] = route('download.app.backups', ['name' => $file_name]);
                    $info['delete'] = route('delete.app.backups', ['name' => $file_name]);

                    array_push($backups, $info);
                }
            }

            usort($backups, function($a, $b) {
                return $b['date'] <=> $a['date'];
            });

            foreach ($backups as $idx => $backup) {
                $backups[$idx]['date'] = Carbon::parse($backup['date'], 'UTC')->setTimezone(auth()->user()->time_zone)->format('Y-m-d H:i:s');
            }

            $backups = collect($backups)->values();
            $page = (int) $request->get('page') ?: 1;
            $perPage = 20;
            $slice = $backups->slice(($page - 1) * $perPage, $perPage);
            $paginator = new LengthAwarePaginator($slice, $backups->count(), $perPage, $page, [
                'path' => Paginator::resolveCurrentPath()
            ]);

            if ($request->ajax()) {
                return response()->json(['backups' => $paginator], 200);
            } else {
                return view('admin.layouts.origin.backups');
            }
        }

        $data = ['message' => __('You are not authorized to view this page')];

        if ($request->ajax()) {
            return response()->json($data, 200);
        } else {
            return redirect()->route('home')->with($data);
        }
    }

    public function download(Request $request, $name)
    {
        $msg = __('You are not authorized to download backups');

        if (auth()->user()->role == "Administrator" && auth()->user()->username == "admin") {
            if ($name) {
                $activity_data = [
                    'module' => 'Backup',
                    'icon' => 'fas fa-hdd'
                ];

                if (file_exists(storage_path('app/backups/' . $name . '.sql'))) {
                    $activity_data['form_title'] = $name . '.sql';
                    $this->saveActivity($activity_data, "Download");

                    return response()->download(storage_path('app/backups/' . $name . '.sql'));
                } elseif (file_exists(storage_path('app/backups/' . $name . '.zip'))) {
                    $activity_data['form_title'] = $name . '.zip';
                    $this->saveActivity($activity_data, "Download");

                    return response()->download(storage_path('app/backups/' . $name . '.zip'));
                } else {
                    $msg = __('No such backup exists');
                }
            } else {
                $msg = __('Please provide backup filename to download');
            }
        }

        if ($request->ajax()) {
            return response()->json(['message' => $msg], 200);
        } else {
            return redirect()->route('home')->with(['message' => $msg]);
        }
    }

    public function delete(Request $request, $name)
    {
        $message = __('You are not authorized to delete backups');
        $success = false;

        if (auth()->user()->role == "Administrator" && auth()->user()->username == "admin") {
            if ($name) {
                $activity_data = [
                    'module' => 'Backup',
                    'icon' => 'fas fa-hdd'
                ];

                if (file_exists(storage_path('app/backups/' . $name . '.sql'))) {
                    Storage::delete(storage_path('app/backups/' . $name . '.sql'));
                    unlink(storage_path('app/backups/' . $name . '.sql'));

                    $message = __('Backup deleted successfully');
                    $success = true;

                    $activity_data['form_title'] = $name . '.sql';
                    $this->saveActivity($activity_data, "Delete");
                } elseif (file_exists(storage_path('app/backups/' . $name . '.zip'))) {
                    Storage::delete(storage_path('app/backups/' . $name . '.zip'));
                    unlink(storage_path('app/backups/' . $name . '.zip'));

                    $message = __('Backup deleted successfully');
                    $success = true;

                    $activity_data['form_title'] = $name . '.zip';
                    $this->saveActivity($activity_data, "Delete");
                } else {
                    $message = __('No such backup exists');
                    $success = false;
                }
            } else {
                $message = __('Please provide backup filename to delete');
            }
        }

        if ($request->ajax()) {
            return response()->json(compact('message', 'success'), 200);
        } else {
            return redirect()->route('home')->with(compact('message', 'success'));
        }
    }

    public function create(Request $request)
    {
        $message = __('You are not authorized to create backups');
        $success = false;

        if (auth()->user()->role == "Administrator" && auth()->user()->username == "admin") {
            if ($request->filled('type') && in_array($request->get('type'), ["db", "files"])) {
                $backup_type = $request->get('type');

                if ($backup_type == "db") {
                    $exit_code = Artisan::call('origin:backup', ['--only-db' => true]);
                } elseif ($backup_type == "files") {
                    $exit_code = Artisan::call('origin:backup', ['--only-files' => true]);
                }
            } else {
                $exit_code = Artisan::call('origin:backup');
            }

            $message = Artisan::output();

            if ($exit_code == "0") {
                $type = (isset($backup_type) && $backup_type) ? $backup_type : "Database + Files";

                if ($type == "db") {
                    $type = "Database";
                } elseif ($type == "files") {
                    $type = "Files";
                }

                $activity_data = [
                    'module' => 'Backup',
                    'icon' => 'fas fa-hdd',
                    'form_title' => $type
                ];

                $this->saveActivity($activity_data, "Create");
                $success = true;
            } else {
                $success = false;
            }
        }

        if ($request->ajax()) {
            return response()->json(compact('message', 'success'), 200);
        } else {
            return redirect()->route('home')->with(compact('message', 'success'));
        }
    }
}
