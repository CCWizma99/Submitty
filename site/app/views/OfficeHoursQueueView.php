<?php

namespace app\views;

use app\models\OfficeHoursQueueModel;
use app\libraries\Utils;

class OfficeHoursQueueView extends AbstractView {
    /*
     * @param OfficeHoursQueueModel $viewer
     * @param User[] $students
     */
    public function showTheQueue($viewer, array $students) {
        $this->core->getOutput()->addBreadcrumb("Office Hours Queue");
        $this->core->getOutput()->addInternalCss('officeHoursQueue.css');
        $this->core->getOutput()->addInternalJs('office-hours-queue.js');
        $this->core->getOutput()->addInternalJs('websocket.js');
        $this->core->getOutput()->addInternalJs('notification-sound.js');
        $this->core->getOutput()->enableMobileViewport();

        $student_full = Utils::getAutoFillData($students);

        return $this->renderPart($viewer, "officeHoursQueue/QueueHeader.twig", $student_full);
    }

    /*
     * @param OfficeHoursQueueModel $viewer
     * @param string $student_full
     */
    public function renderCurrentQueue($viewer, string $student_full) {
        return $this->renderPart($viewer, "officeHoursQueue/CurrentQueue.twig", $student_full);
    }

    /*
     * @param OfficeHoursQueueModel $viewer
     * @param string $student_full
     */
    public function renderQueueHistory($viewer, string $student_full) {
        return $this->renderPart($viewer, "officeHoursQueue/QueueHistory.twig", $student_full);
    }

    /*
     * @param OfficeHoursQueueModel $viewer
     * @param string $student_full
     */
    public function renderNewStatus($viewer, string $student_full) {
        return $this->renderPart($viewer, "officeHoursQueue/QueueStatus.twig", $student_full);
    }

    public function showQueueStats($overallData, $todayData, $weekDayThisWeekData, $weekDayData, $queueData, $weekNumberData): string {
        $this->core->getOutput()->addBreadcrumb("Office Hours/Lab Queue", $this->core->buildCourseUrl(["office_hours_queue"]));
        $this->core->getOutput()->addBreadcrumb("Statistics");
        $this->core->getOutput()->enableMobileViewport();
        return $this->core->getOutput()->renderTwigTemplate('officeHoursQueue/QueueStats.twig', [
          'csrf_token' => $this->core->getCsrfToken(),
          'access_full_grading' => $this->core->getUser()->accessFullGrading(),
          'overall_data' => $overallData,
          'today_data' => $todayData,
          'week_day_this_week_data' => $weekDayThisWeekData,
          'week_day_data' => $weekDayData,
          'queue_data' => $queueData,
          'week_number_data' => $weekNumberData,
          'viewer' => new OfficeHoursQueueModel($this->core),
          'base_url' => $this->core->buildCourseUrl() . '/office_hours_queue'
        ]);
    }

    public function showQueueStudentStats($studentData) {
        $this->core->getOutput()->addBreadcrumb("Office Hours/Lab Queue", $this->core->buildCourseUrl(["office_hours_queue"]));
        $this->core->getOutput()->addBreadcrumb("Statistics");
        $this->core->getOutput()->enableMobileViewport();
        return $this->core->getOutput()->renderTwigTemplate("officeHoursQueue/QueueStatsStudents.twig", [
          'csrf_token' => $this->core->getCsrfToken(),
          'access_full_grading' => $this->core->getUser()->accessFullGrading(),
          'student_data' => $studentData,
          'viewer' => new OfficeHoursQueueModel($this->core),
          'base_url' => $this->core->buildCourseUrl() . '/office_hours_queue'
        ]);
    }

    /*
     * @param OfficeHoursQueueModel $viewer
     * @param string $student_full
     */
    public function renderNewAnnouncement($viewer, string $student_full) {
        return $this->renderPart($viewer, "officeHoursQueue/AnnouncementMsg.twig", $student_full);
    }

    /*
     * @param OfficeHoursQueueModel $viewer
     * @param string $twig_location
     * @param string $student_full
     */
    private function renderPart($viewer, string $twig_location, string $student_full) {
        return $this->core->getOutput()->renderTwigTemplate($twig_location, [
          'csrf_token' => $this->core->getCsrfToken(),
          'access_full_grading' => $this->core->getUser()->accessFullGrading(),
          'viewer' => $viewer,
          'base_url' => $this->core->buildCourseUrl() . '/office_hours_queue',
          'student_full' => $student_full
        ]);
    }
}
