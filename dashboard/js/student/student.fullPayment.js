var oPaidReservations = (() => {

    let oTblFullPayment = $('#tbl_fullPayment');

    let oTblPaymentDetails = $('#tbl_paymentDetails');

    let aReservations = [];

    let oEnrollmentDetails = {};

    let oColumns = {
        aCourses: [
            {
                title: 'Course Code', className: 'text-center', data: 'courseCode'
            },
            {
                title: 'Schedule', className: 'text-center', data: 'schedule'
            },
            {
                title: 'Venue', className: 'text-center', data: 'venue'
            },
            {
                title: 'Instructor', className: 'text-center', data: 'instructorName'
            },
            {
                title: 'Amount', className: 'text-center', render: (aData, oType, oRow) =>
                    'P' + parseInt(oRow.coursePrice, 10).toLocaleString(undefined, { minimumFractionDigits: 2 })
            },
            {
                title: 'Actions', className: 'text-center', render: (aData, oType, oRow) =>
                    `<button class="btn btn-success btn-sm" data-toggle="modal" id="viewPayment" data-id="${oRow.trainingId}">
                        <i class="fa fa-eye"></i>
                    </button>
                    <button class="btn btn-danger btn-sm" data-toggle="modal" id="cancelReservation" data-id="${oRow.trainingId}">
                        <i class="fa fa-times-circle"></i>
                    </button>`
            },
        ],
        aPaymentDetails: [
            {
                title: 'Date Paid', className: 'text-center', data: 'paymentDate'
            },
            {
                title: 'MOP', className: 'text-center', data: 'paymentMethod'
            },
            {
                title: 'Training Fee', className: 'text-center', data: 'coursePrice'
            },
            {
                title: 'Amount Paid', className: 'text-center sum', data: 'paymentAmount'
            },
            {
                title: 'Actions', className: 'text-center', render: (aData, oType, oRow) =>
                    `<a href="${oRow.paymentImage}" data-lightbox="payment-image">
                        <button class="btn btn-primary btn-sm" id="viewPaymentImage" data-id="${oRow.paymentId}">
                            <i class="fa fa-eye"></i>
                        </button>
                    </a>`
            },
        ]
    };

    function init() {
        fetchPaidReservations();
        setEvents();
    }

    function setEvents() {

        oForms.prepareDomEvents();

        $('.modal').on('hidden.bs.modal', function () {
            let sFormId = `#${$(this).find('form').attr('id')}`;
            $(sFormId)[0].reset();
            $(sFormId).find('.custom-file-label').text('Select File');
            $('.error-msg').css('display', 'none').html('');
        });

        $(document).on('click', '#viewPayment', function () {
            oEnrollmentDetails = aReservations.filter(aData => aData.trainingId == $(this).attr('data-id'))[0];
            preparePaymentDetails();
            $('#viewPaymentModal').modal('show');
        });

        $(document).on('click', '.addPayment', function () {
            $('#addPaymentModal').modal('show');
        });

        $(document).on('click', '#cancelReservation', function () {
            const oCourseDetails = aReservations.filter(oCourse => oCourse.trainingId == $(this).attr('data-id'))[0];
            checkIfAlreadyRequestedForRefund(oCourseDetails.trainingId)
                .then((oResponse) => {
                    if (oResponse.bResult === false) {
                        return oLibraries.displayAlertMessage('error', oResponse.sMsg);
                    }
                    $('#cancelReservationModal').find('.trainingId').val(oCourseDetails.trainingId);
                    $('#cancelReservationModal').modal('show');
                });
        });

        $(document).on('submit', 'form', function (oEvent) {
            oEvent.preventDefault();

            const sFormId = `#${$(this).attr('id')}`;

            // Disable the form.
            oForms.disableFormState(sFormId, true);

            // Invoke the resetInputBorders method inside oForms utils for that form.
            oForms.resetInputBorders(sFormId);

            const oInputForms = {
                '#cancelReservationForm': {
                    'validationMethod': oValidations.validateCancelReservationForm(sFormId),
                    'requestClass': 'Refunds',
                    'requestAction': 'requestRefund',
                    'alertTitle': 'Request Refund?',
                    'alertText': 'This will request a refund before cancelling the reservation.'
                }
            }

            // Validate the inputs of the submitted form and store the result inside oValidateInputs variable.
            let oValidateInputs = oInputForms[sFormId].validationMethod;

            if (oValidateInputs.result === true) {
                Swal.fire({
                    title: oInputForms[sFormId].alertTitle,
                    text: oInputForms[sFormId].alertText,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes',
                }).then((bIsConfirm) => {
                    if (bIsConfirm.value === true) {
                        executeSubmit(sFormId, oInputForms[sFormId].requestClass, oInputForms[sFormId].requestAction);
                    }
                });
            } else {
                oLibraries.displayErrorMessage(sFormId, oValidateInputs.msg, oValidateInputs.element);
            }
            // Enable the form.
            oForms.disableFormState(sFormId, false);
        });
    }

    async function checkIfAlreadyRequestedForRefund(iTrainingId) {
        const oRequest = await axios.post('/Nexus/utils/ajax.php?class=Refunds&action=checkIfAlreadyRequestedForRefund', { iTrainingId });
        return oRequest.data;
    }

    /**
     * executeSubmit
     * @param {string} sFormId
     * @param {string} sRequestClass
     * @param {string} sRequestAction
     */
    function executeSubmit(sFormId, sRequestClass, sRequestAction) {
        const oFormData = new FormData($(sFormId)[0]);
        if (sRequestAction === 'addPayment') {
            for ([sName, mValue] of Object.entries(oEnrollmentDetails)) {
                oFormData.append(sName, mValue);
            }
        }

        // Execute AJAX.
        $.ajax({
            url: `/Nexus/utils/ajax.php?class=${sRequestClass}&action=${sRequestAction}`,
            type: 'POST',
            data: oFormData,
            dataType: 'json',
            contentType: false,
            processData: false,
            beforeSend: () => {
                $('.spinner').css('display', 'block');
            },
            success: (oResponse) => {
                if (oResponse.bResult === true) {
                    fetchCourses();
                    oLibraries.displayAlertMessage('success', oResponse.sMsg);
                    $('.modal').modal('hide');
                } else {
                    oLibraries.displayErrorMessage(sFormId, oResponse.sMsg, oResponse.sElement);
                }
            },
            complete: () => {
                $('.spinner').css('display', 'none');
            }
        });
    }

    function preparePaymentDetails() {
        $('.viewPaymentModal').find('#courseName').val(oEnrollmentDetails.courseName);
        $('.viewPaymentModal').find('#schedule').val(oEnrollmentDetails.schedule);
        $('.viewPaymentModal').find('#venue').val(oEnrollmentDetails.venue);
        $('.viewPaymentModal').find('#instructor').val(oEnrollmentDetails.instructorName);

        loadPaymentDetailsTable();
    }

    function loadPaymentDetailsTable() {
        $.ajax({
            url: '/Nexus/utils/ajax.php?class=Payment&action=fetchPaymentDetails',
            type: 'POST',
            data: { trainingId: oEnrollmentDetails.trainingId },
            dataType: 'json',
            async: 'false',
            success: function (aResponse) {
                let oFooterCallback = function () {
                    let oApi = this.api();

                    // Remove the formatting to get integer data for summation.
                    let intVal = (mValue) => {
                        return typeof mValue === 'string' ?
                            mValue.replace(/[P,]/g, '') * 1 :
                            typeof mValue === 'number' ?
                                mValue : 0;
                    };

                    // Get the sum of all the columns with a class named 'sum'.
                    oApi.columns('.sum').every(function () {
                        let iTotalPaid = oApi
                            .cells(null, this.index())
                            .render('display')
                            .reduce((iAccumulator, iCurrentValue) => {
                                return intVal(iAccumulator) + intVal(iCurrentValue);
                            }, 0);

                        // const iCoursePrice = aEnrolledCourses.filter(oCourse => oCourse.trainingId = oEr)

                        const iBalance = oEnrollmentDetails.coursePrice.replace(/[P,]/g, '') - iTotalPaid;

                        $(this.footer()).text(`P${iBalance.toLocaleString()}`);
                    });
                };

                const aColumnDefs = [
                    { orderable: false, targets: [1, 2, 3] }
                ];
                const aOrder = [[0, 'asc']];
                const aDetails = aResponse.filter(oData => oData.paymentStatus == 'Fully Paid');

                loadTable(oTblPaymentDetails.attr('id'), aDetails, oColumns.aPaymentDetails, aColumnDefs, aOrder, false, oFooterCallback);
            },
        });
    }

    /**
     * executeSubmit
     * @param {string} sFormId
     * @param {string} sRequestClass
     * @param {string} sRequestAction
     */
    function executeSubmit(sFormId, sRequestClass, sRequestAction) {
        const oFormData = new FormData($(sFormId)[0]);
        if (sRequestAction === 'addPayment') {
            for ([sName, mValue] of Object.entries(oEnrollmentDetails)) {
                oFormData.append(sName, mValue);
            }
        }

        // Execute AJAX.
        $.ajax({
            url: `/Nexus/utils/ajax.php?class=${sRequestClass}&action=${sRequestAction}`,
            type: 'POST',
            data: oFormData,
            dataType: 'json',
            contentType: false,
            processData: false,
            beforeSend: () => {
                $('.spinner').css('display', 'block');
            },
            success: (oResponse) => {
                if (oResponse.bResult === true) {
                    fetchPaidReservations();
                    oLibraries.displayAlertMessage('success', oResponse.sMsg);
                    $('.modal').modal('hide');
                } else {
                    oLibraries.displayErrorMessage(sFormId, oResponse.sMsg, oResponse.sElement);
                }
            },
            complete: () => {
                $('.spinner').css('display', 'none');
            }
        });
    }

    function printRegiForm(oDetails) {
        window.open('/Nexus/utils/ajax.php?class=Student&action=printRegiForm&tId=' + oDetails.trainingId);
    }

    function fetchPaidReservations() {
        $.ajax({
            url: `/Nexus/utils/ajax.php?class=Training&action=fetchPaidReservations`,
            type: 'GET',
            dataType: 'json',
            success: function (oResponse) {
                aReservations = oResponse;

                const aColumnDefs = [
                    { orderable: false, targets: [3, 4, 5] }
                ];
                const aOrder = [[1, 'asc']];

                loadTable(oTblFullPayment.attr('id'), aReservations, oColumns.aCourses, aColumnDefs, aOrder);
            },
            error: function () {
                oLibraries.displayAlertMessage('error', 'An error has occured. Please try again.');
            }
        });
    }

    function loadTable(sTableName, aData, aColumns, aColumnDefs, aOrder, bSearching = true, oFooterCallback = () => { }) {
        $(`#${sTableName} > tbody`).empty().parent().DataTable({
            destroy: true,
            deferRender: true,
            data: aData,
            responsive: true,
            pagingType: 'first_last_numbers',
            pageLength: 4,
            ordering: true,
            order: aOrder,
            searching: bSearching,
            lengthChange: true,
            lengthMenu: [[4, 8, 12, 16, 20, 24, -1], [4, 8, 12, 16, 20, 24, 'All']],
            info: true,
            columns: aColumns,
            columnDefs: aColumnDefs,
            footerCallback: oFooterCallback
        });
    }

    return {
        initialize: init
    }

})();

$(() => {
    oPaidReservations.initialize();
});
