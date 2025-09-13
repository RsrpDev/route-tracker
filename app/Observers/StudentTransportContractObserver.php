<?php

namespace App\Observers;

use App\Models\StudentTransportContract;
use App\Models\Student;

class StudentTransportContractObserver
{
    /**
     * Handle the StudentTransportContract "created" event.
     */
    public function created(StudentTransportContract $studentTransportContract): void
    {
        $this->updateStudentTransportStatus($studentTransportContract);
    }

    /**
     * Handle the StudentTransportContract "updated" event.
     */
    public function updated(StudentTransportContract $studentTransportContract): void
    {
        $this->updateStudentTransportStatus($studentTransportContract);
    }

    /**
     * Handle the StudentTransportContract "deleted" event.
     */
    public function deleted(StudentTransportContract $studentTransportContract): void
    {
        $this->updateStudentTransportStatus($studentTransportContract);
    }

    /**
     * Update the student's transport status based on active contracts
     */
    private function updateStudentTransportStatus(StudentTransportContract $contract): void
    {
        $student = $contract->student;

        if ($student) {
            // Verificar si tiene contratos activos
            $hasActiveContract = $student->transportContracts()
                ->where('contract_status', 'active')
                ->exists();

            // Actualizar el campo has_transport en la base de datos
            $student->updateQuietly(['has_transport' => $hasActiveContract]);
        }
    }
}
