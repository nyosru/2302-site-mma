<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\PermissionService;
use Illuminate\Contracts\Support\Renderable;
use Spatie\Backup\BackupDestination\Backup;
use Spatie\Backup\Helpers\Format;
use Spatie\Backup\Tasks\Monitor\BackupDestinationStatus;
use Spatie\Backup\Tasks\Monitor\BackupDestinationStatusFactory;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class BackupsController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return Renderable
     */
    public function index()
    {
        if (!PermissionService::allowed(
            $this->getVisitor()->id,
            PermissionService::O_BACKUP, PermissionService::A_VIEW
        )
        ) {
            throw new AccessDeniedHttpException();
        }
        $statuses = BackupDestinationStatusFactory::createForMonitorConfig(config('backup.monitor_backups'));
        $headers = ['Name', 'Disk', 'Reachable', 'Healthy', '# of backups', 'Newest backup', 'Used storage'];

        $rows = $statuses->map(
            function (BackupDestinationStatus $backupDestinationStatus) {
                return $this->convertToRow($backupDestinationStatus);
            }
        );


        //        dd($rows);
        return view('backups', compact(['rows']));
    }

    public function convertToRow(BackupDestinationStatus $backupDestinationStatus): array
    {
        $destination = $backupDestinationStatus->backupDestination();

        $row = [
            $destination->backupName(),
            'disk' => $destination->diskName(),
            Format::emoji($destination->isReachable()),
            Format::emoji($backupDestinationStatus->isHealthy()),
            'amount' => $destination->backups()->count(),
            'newest' => $this->getFormattedBackupDate($destination->newestBackup()),
            'usedStorage' => Format::humanReadableSize($destination->usedStorage()),
        ];

        if (!$destination->isReachable()) {
            foreach (['amount', 'newest', 'usedStorage'] as $propertyName) {
                $row[$propertyName] = '/';
            }
        }

        // TODO бесполезный код
        /*if ($backupDestinationStatus->getHealthCheckFailure() !== null) {
            $row['disk'] = $row['disk'];
        }*/

        return $row;
    }

    protected function getFormattedBackupDate(Backup $backup = null)
    {
        return is_null($backup)
            ? 'No backups present'
            : Format::ageInDays($backup->date());
    }
}
