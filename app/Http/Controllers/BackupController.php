<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Storage;
use Artisan;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class BackupController extends Controller
{
    public function show(Request $request)
    {
        if (auth()->user()->role == "Administrator" && auth()->user()->login_id == "admin") {
            $app_backups = Storage::disk('backups')->files();
            $backups = [];

            foreach ($app_backups as $file) {
                if (!starts_with($file, '.')) {
                    $info = [];
                    $file_name = head(explode('.', $file));
                    $date = Storage::disk('backups')->lastModified($file);
                    $extension = last(explode('.', $file));

                    $info['name'] = $file;
                    $info['date'] = Carbon::createFromTimestamp($date)->format('d-m-Y h:i A');
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

            $backups = collect($backups)->sortByDesc('date')->values();
            $page = (int) $request->get('page') ?: 1;
            $perPage = 15;
            $slice = $backups->slice(($page - 1) * $perPage, $perPage);
            $paginator = new LengthAwarePaginator($slice, $backups->count(), $perPage, $page, [
                'path' => Paginator::resolveCurrentPath()
            ]);

            if ($request->ajax()) {
                return response()->json(['backups' => $paginator], 200);
            } else {
                return view('layouts.origin.backups');
            }
        }

        if ($request->ajax()) {
            return response()->json(['msg' => 'You are not authorized to view this page'], 200);
        } else {
            return redirect()->route('home')->with(['msg' => 'You are not authorized to view this page']);
        }
    }

    public function download(Request $request, $name)
    {
        $msg = 'You are not authorized to view this page';

        if (auth()->user()->role == "Administrator" && auth()->user()->login_id == "admin") {
            if ($name) {
                if (file_exists(storage_path('app/backups/' . $name . '.sql'))) {
                    return response()->download(storage_path('app/backups/' . $name . '.sql'));
                } elseif (file_exists(storage_path('app/backups/' . $name . '.zip'))) {
                    return response()->download(storage_path('app/backups/' . $name . '.zip'));
                } else {
                    $msg = 'No such backup exists';
                }
            } else {
                $msg = 'Please provide backup filename to download';
            }
        }

        if ($request->ajax()) {
            return response()->json(['msg' => $msg], 200);
        } else {
            return redirect()->route('home')->with(['msg' => $msg]);
        }
    }

    public function delete(Request $request, $name)
    {
        $msg = 'You are not authorized';
        $success = false;

        if (auth()->user()->role == "Administrator" && auth()->user()->login_id == "admin") {
            if ($name) {
                if (file_exists(storage_path('app/backups/' . $name . '.sql'))) {
                    Storage::delete(storage_path('app/backups/' . $name . '.sql'));
                    unlink(storage_path('app/backups/' . $name . '.sql'));

                    $msg = "Backup deleted successfully";
                    $success = true;
                } elseif (file_exists(storage_path('app/backups/' . $name . '.zip'))) {
                    Storage::delete(storage_path('app/backups/' . $name . '.zip'));
                    unlink(storage_path('app/backups/' . $name . '.zip'));

                    $msg = "Backup deleted successfully";
                    $success = true;
                } else {
                    $msg = 'No such backup exists';
                    $success = false;
                }
            } else {
                $msg = 'Please provide backup filename to delete';
            }
        }

        if ($request->ajax()) {
            return response()->json(compact('msg', 'success'), 200);
        } else {
            return redirect()->route('home')->with(compact('msg', 'success'));
        }
    }

    public function create(Request $request)
    {
        $msg = 'You are not authorized';
        $success = false;

        if (auth()->user()->role == "Administrator" && auth()->user()->login_id == "admin") {
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

            $msg = Artisan::output();

            if ($exit_code == "0") {
                $success = true;
            } else {
                $success = false;
            }
        }

        if ($request->ajax()) {
            return response()->json(compact('msg', 'success'), 200);
        } else {
            return redirect()->route('home')->with(compact('msg', 'success'));
        }
    }
}
