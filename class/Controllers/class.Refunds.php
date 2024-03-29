<?php

class Refunds extends BaseController
{
    /**
     * @var RefundsModel $oRefundsModel
     * Class instance for Refunds model.
     */
    private $oRefundsModel;

    /**
     * @var CourseModel $oCourseModel
     * Class instance for Course model.
     */
    private $oCourseModel;

    /**
     * @var $aPaymentMethods
     * Holder of payment methods.
     */
    private $aPaymentMethods;

    /**
     * Refunds constructor.
     * @param array $aPostVariables
     */
    public function __construct($aPostVariables)
    {
        $this->aParams = $aPostVariables;
        $this->oRefundsModel = new RefundsModel();
        $this->oCourseModel = new CourseModel();

        parent::__construct();
        $this->aPaymentMethods = $this->oPaymentModel->fetchModeOfPayments();
    }

    /**
     * requestRefund
     * Request a refund for a reservation.
     */
    public function requestRefund()
    {
        Utils::unsetKeys($this->aParams, ['agreementCheckbox']);
        Utils::sanitizeData($this->aParams);
        $this->aParams['dateRequested'] = dateNow();

        $iQuery = $this->oRefundsModel->requestRefund($this->aParams);

        if ($iQuery > 0) {
            $aTrainingData = $this->oTrainingModel->getTrainingDataByTrainingId($this->aParams['trainingId']);

            $aParams = array(
                'studentId'  => $this->getUserId(),
                'courseId'   => $aTrainingData['courseId'],
                'scheduleId' => $aTrainingData['scheduleId'],
                'type'       => 5,
                'hasAccount' => 1,
                'receiver'   => 'admin',
                'date'       => dateNow()
            );
            $this->oNotificationModel->insertNotification($aParams);
            
            $aResult = array(
                'bResult' => true,
                'sMsg'    => 'Refund requested!'
            );
        } else {
            $aResult = array(
                'bResult' => false,
                'sMsg'    => 'An error has occured.'
            );
        }

        echo json_encode($aResult);
    }

    /**
     * checkIfAlreadyRequestedForRefund
     */
    public function checkIfAlreadyRequestedForRefund()
    {
        Utils::sanitizeData($this->aParams);

        $iQuery = $this->oRefundsModel->checkIfAlreadyRequestedForRefund($this->aParams['iTrainingId']);

        if ($iQuery == 0) {
            $aResult = array(
                'bResult' => true
            );
        } else {
            $aResult = array(
                'bResult' => false,
                'sMsg'    => 'Refund for this reservation has been already requested.'
            );
        }

        echo json_encode($aResult);
    }

    /**
     * fetchAllRefundRequests
     */
    public function fetchAllRefundRequests()
    {
        $aRefundRequests = $this->oRefundsModel->fetchAllRefundRequests();

        foreach ($aRefundRequests as $iKey => $aDetails) {
            $aRefundRequests[$iKey]['refundStatus'] = $this->aApprovalStatus[$aDetails['refundStatus']];
        }

        echo json_encode($aRefundRequests);
    }

    /**
     * fetchAllApprovedRefunds
     */
    public function fetchAllApprovedRefunds()
    {
        $aRefundRequests = $this->oRefundsModel->fetchAllApprovedRefunds();

        foreach ($aRefundRequests as $iKey => $aDetails) {
            $aRefundRequests[$iKey]['refundStatus'] = $this->aApprovalStatus[$aDetails['refundStatus']];
        }

        echo json_encode($aRefundRequests);
    }

    /**
     * fetchRefundDetails
     */
    public function fetchRefundDetails()
    {
        Utils::sanitizeData($this->aParams);
        $aRefundDetails = $this->oRefundsModel->fetchRefundDetails($this->aParams);

        $iTotalPayment = 0;
        foreach ($aRefundDetails as $iKey => $aRefundData) {
            $iTotalPayment += $aRefundData['paymentAmount'];
        }

        foreach ($aRefundDetails as $iKey => $aRefundData) {
            if ($aRefundData['paymentMethod'] === null) {
                $aResult[$iKey]['paymentMethod'] = 'N/A';
            } else {
                // Get payment method index.
                $iMopIndex = Utils::searchKeyByValueInMultiDimensionalArray($aRefundData['paymentMethod'], $this->aPaymentMethods, 'id');
                $aResult[$iKey]['paymentMethod'] = $this->aPaymentMethods[$iMopIndex]['methodName'];
            }

            $aResult[$iKey]['trainingId']       = $aRefundData['trainingId'];
            $aResult[$iKey]['refundId']         = $aRefundData['refundId'];
            $aResult[$iKey]['refundReason']     = $aRefundData['refundReason'];
            $aResult[$iKey]['dateRequested']    = Utils::formatDate($aRefundData['dateRequested']);
            $aResult[$iKey]['coursePrice']      = Utils::toCurrencyFormat($aRefundData['coursePrice']);
            $aResult[$iKey]['paymentAmount']    = Utils::toCurrencyFormat($aRefundData['paymentAmount']);
            $aResult[$iKey]['remainingBalance'] = Utils::getRemainingBalance($aRefundData);
            $aResult[$iKey]['paymentImage']     = '..' . DS . 'payments' . DS . $aRefundData['paymentFile'];
            $aResult[$iKey]['paymentApproval']  = $this->aApprovalStatus[$aRefundData['isApproved']];
            $aResult[$iKey]['paymentStatus']    = $this->aPaymentStatus[$aRefundData['paymentStatus']];
            $aResult[$iKey]['totalBalance']     = Utils::toCurrencyFormat($aRefundData['coursePrice'] - $iTotalPayment);
            $aResult[$iKey]['approvedBy']       = $aRefundData['approvedBy'] ?? 'N/A';

            if ($aResult[$iKey]['paymentStatus'] === 'Fully Paid') {
                $aResult[$iKey]['paymentAmount'] = $aResult[$iKey]['coursePrice'];
                break;
            }
        }

        echo json_encode(array_values($aResult));
    }

    public function rejectRefund()
    {
        Utils::sanitizeData($this->aParams);

        $aParams = array(
            ':trainingId' => $this->aParams['iTrainingId'],
            ':executor'   => Session::get('fullName')
        );

        // Perform update.
        $iQuery = $this->oRefundsModel->rejectRefund($aParams);

        if ($iQuery > 0) {
            // $this->sendEmailToStudent($aTrainingData, 'rejected');
            $aTrainingData = $this->oTrainingModel->getTrainingDataByTrainingId($this->aParams['iTrainingId']);

            $aParams = array(
                'studentId'  => $aTrainingData['studentId'],
                'courseId'   => $aTrainingData['courseId'],
                'scheduleId' => $aTrainingData['scheduleId'],
                'type'       => 7,
                'hasAccount' => 1,
                'receiver'   => 'student',
                'date'       => dateNow()
            );
            $this->oNotificationModel->insertNotification($aParams);

            $aResult = array(
                'bResult' => true,
                'sMsg'    => 'Refund rejected!'
            );
        } else {
            $aResult = array(
                'bResult' => false,
                'sMsg'    => 'An error has occured.'
            );
        }

        echo json_encode($aResult);
    }

    public function approveRefund()
    {
        Utils::sanitizeData($this->aParams);

        $aApproveRefundData = array(
            ':trainingId' => $this->aParams['iTrainingId'],
            ':executor'   => Session::get('fullName')
        );

        $aCancelReservationData = array(
            ':id'                 => $this->aParams['iTrainingId'],
            ':cancellationReason' => $this->aParams['sRefundReason']
        );

        // Perform update.
        $iApproveQuery = $this->oRefundsModel->approveRefund($aApproveRefundData);
        $iCancelQuery = $this->oTrainingModel->cancelReservation($aCancelReservationData);

        // Get the schedule ID associated with the training ID then mark as unreserved.
        $aTrainingData = $this->oTrainingModel->getTrainingDataByTrainingId($this->aParams['iTrainingId']);
        $this->oTrainingModel->markAsUnreserved($aTrainingData['scheduleId']);

        if ($iApproveQuery > 0 && $iCancelQuery > 0) {
            // $this->sendEmailToStudent($aTrainingData, 'approved');
            $aTrainingData = $this->oTrainingModel->getTrainingDataByTrainingId($this->aParams['iTrainingId']);

            $aParams = array(
                'studentId'  => $aTrainingData['studentId'],
                'courseId'   => $aTrainingData['courseId'],
                'scheduleId' => $aTrainingData['scheduleId'],
                'type'       => 6,
                'hasAccount' => 1,
                'receiver'   => 'student',
                'date'       => dateNow()
            );
            $this->oNotificationModel->insertNotification($aParams);

            $aResult = array(
                'bResult' => true,
                'sMsg'    => 'Refund approved!'
            );
        } else {
            $aResult = array(
                'bResult' => false,
                'sMsg'    => 'An error has occured.'
            );
        }

        echo json_encode($aResult);
    }

    private function sendEmailToStudent($aTrainingData, $sAction)
    {
        $aStudentDetails = $this->getUserDetails($aTrainingData['studentId']);
        $aStudentDetails['fullName'] = $aStudentDetails['firstName'] . ' ' . $aStudentDetails['lastName'];
        $aEnrollmentDetails = $this->oCourseModel->getCourseAndScheduleDetails($aTrainingData['scheduleId']);
        $aEnrollmentDetails['schedule'] = Utils::formatDate($aEnrollmentDetails['fromDate']) . ' - ' . Utils::formatDate($aEnrollmentDetails['toDate']) . ' (' . $this->getInterval($aEnrollmentDetails) . ')';

        $sMsg = 'Hello, ' . $aStudentDetails['fullName'] . '. Your refund has been ' . $sAction . ' for: ';
        $sMsg .= "\r\n\r\n";
        $sMsg .= 'Course Code: ' . $aEnrollmentDetails['courseCode'];
        $sMsg .= "\r\n";
        $sMsg .= 'Course Price: ' . Utils::toCurrencyFormat($aEnrollmentDetails['coursePrice']);
        $sMsg .= "\r\n";
        $sMsg .= 'Schedule: ' . $aEnrollmentDetails['schedule'];

        $oMail = new Email();
        $oMail->addSingleRecipient($aStudentDetails['email'], $aStudentDetails['fullName']);
        $oMail->setTitle('Refund ' . ucfirst($sAction));
        $oMail->setBody($sMsg);
        return $oMail->send();
    }
}
