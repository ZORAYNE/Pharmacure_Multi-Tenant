<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AdminUpdaterController extends Controller
{
    protected $githubRepo = 'ZORAYNE/Pharmacure_Multi-Tenant'; // Replace with your GitHub repo

    public function showUpdater()
    {
        $currentVersion = config('app.version', 'unknown');
        $latestVersion = $this->getLatestReleaseVersion();

        return view('admin.updater', compact('currentVersion', 'latestVersion'));
    }

    public function checkForUpdates()
    {
        $latestVersion = $this->getLatestReleaseVersion();
        $currentVersion = config('app.version', 'unknown');

        if ($latestVersion && version_compare($latestVersion, $currentVersion, '>')) {
            return response()->json([
                'updateAvailable' => true,
                'latestVersion' => $latestVersion,
            ]);
        }

        return response()->json([
            'updateAvailable' => false,
            'latestVersion' => $latestVersion,
        ]);
    }

    public function performUpdate()
    {
        try {
            // Pull latest changes from GitHub repo
            $output = null;
            $returnVar = null;
            exec('git pull origin main 2>&1', $output, $returnVar);

            if ($returnVar !== 0) {
                Log::error('Git pull failed: ' . implode("\n", $output));
                return response()->json(['success' => false, 'message' => 'Update failed. Check logs for details.']);
            }

            // Run migrations and cache clear
            Artisan::call('migrate', ['--force' => true]);
            Artisan::call('config:cache');
            Artisan::call('route:cache');
            Artisan::call('view:cache');

            return response()->json(['success' => true, 'message' => 'Update completed successfully.']);
        } catch (\Exception $e) {
            Log::error('Update error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Update failed: ' . $e->getMessage()]);
        }
    }

    protected function getLatestReleaseVersion()
    {
        try {
            $response = Http::get("https://api.github.com/repos/{$this->githubRepo}/releases/latest");
            if ($response->successful()) {
                return $response->json()['tag_name'] ?? null;
            }
        } catch (\Exception $e) {
            Log::error('GitHub API error: ' . $e->getMessage());
        }
        return null;
    }
}
