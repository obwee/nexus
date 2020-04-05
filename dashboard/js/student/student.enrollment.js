var oEnrollment = (() => {

    let oTblEnrollment = $('#tbl_enrollment');

    let aEnrolledCourses = [];

    let aCoursesAvailable = [];

    let aInstructors = [];

    let oCourseDropdown = $('.courses');

    let oScheduleDropdown = $('.schedules');

    let oColumns = {
        aCourses: [
            {
                title: 'Course Code', className: 'text-center', data: 'courseCode'
            },
            {
                title: 'Schedule', className: 'text-center', render: (aData, oType, oRow) =>
                    oRow.fromDate + ' - ' + oRow.toDate
            },
            {
                title: 'Venue', className: 'text-center', data: 'venue'
            },
            {
                title: 'Instructor', className: 'text-center', data: 'instructorName'
            },
            {
                title: 'Amount', className: 'text-center', render: (aData, oType, oRow) =>
                    'P' + parseInt(oRow.coursePrice, 10).toLocaleString()
            },
            {
                title: 'Status', className: 'text-center', data: 'paymentStatus'
            },
            {
                title: 'Actions', className: 'text-center', render: (aData, oType, oRow) =>
                    `<button class="btn btn-success btn-sm" data-toggle="modal" id="viewPayment" data-id="${oRow.trainingId}">
                        <i class="fa fa-hand-holding-usd"></i>
                    </button>
                    <button class="btn btn-primary btn-sm" data-toggle="modal" id="printRegiForm" data-id="${oRow.trainingId}">
                        <i class="fa fa-print"></i>
                    </button>
                    <button class="btn btn-danger btn-sm" data-toggle="modal" id="cancelReservation" data-id="${oRow.trainingId}">
                        <i class="fa fa-times-circle"></i>
                    </button>`
            },
        ]
    };

    function init() {
        fetchCourses();
        setEvents();
    }

    function setEvents() {

        $('.modal').on('hidden.bs.modal', function () {
            let sFormId = `#${$(this).find('form').attr('id')}`;
            $(sFormId)[0].reset();
            $('.error-msg').css('display', 'none').html('');
        });

        $(document).on('click', '#viewPayment', function () {
            $('#viewPaymentModal').modal('show');
        });

        $(document).on('click', '.addPayment', function () {
            $('#addPaymentModal').modal('show');
        });

        $(document).on('click', '#cancelReservation', function () {
            $('#cancelReservationModal').modal('show');
        });

        $(document).on('click', '#enrollBtn', function () {
            populateCourseDropdown();
            $('#enrollModal').modal('show');
        });

        $(document).on('click', '#printRegiForm', function () {
            const oDetails = aEnrolledCourses.filter(oCourse => oCourse.trainingId == $(this).attr('data-id'))[0];
            printRegiForm(oDetails);
        });

        $(document).on('change', '.courses', function () {
            populateScheduleDropdown($(this).val());
        });

        $(document).on('change', '.schedules', function () {
            populateRemainingInputs($(this).val());
        });

        $(document).on('submit', 'form', function (oEvent) {
            oEvent.preventDefault();

            const sFormId = `#${$(this).attr('id')}`;

            // Disable the form.
            oForms.disableFormState(sFormId, true);

            // Invoke the resetInputBorders method inside oForms utils for that form.
            oForms.resetInputBorders(sFormId);

            const oInputForms = {
                '#enrollForm': {
                    'validationMethod': oValidations.validateEnrollmentInputs(sFormId),
                    'requestClass': 'Student',
                    'requestAction': 'enrollForTraining',
                    'alertTitle': 'Enroll course?',
                    'alertText': 'This will add a new course to enroll.'
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

    function populateCourseDropdown() {
        oCourseDropdown.empty().append($('<option selected disabled hidden>Select Course</option>'));

        $.each(aCoursesAvailable, function (iKey, oCourse) {
            oCourseDropdown.append($('<option />').val(oCourse.courseId).text(`${oCourse.courseName} (${oCourse.courseCode})`));
        });
    }

    function populateScheduleDropdown(iCourseId) {
        let oFilteredCourse = aCoursesAvailable.filter(oCourse => oCourse.courseId == iCourseId)[0];

        oScheduleDropdown.empty().append($('<option selected disabled hidden>Select Schedule</option>'));

        $.each(oFilteredCourse.schedules, function (iScheduleId, sScheduleDate) {
            oScheduleDropdown.append($('<option />').val(iScheduleId).text(sScheduleDate));
        });
    }

    function populateRemainingInputs(iScheduleId) {
        let oFilteredSchedule = aCoursesAvailable.filter(oCourse => oCourse.schedules[iScheduleId])[0];

        $('.price').val(`P${parseInt(oFilteredSchedule['prices'][iScheduleId], 10).toLocaleString()}`);
        $('.venue').val(oFilteredSchedule['venues'][iScheduleId]);
        $('.slots').val(oFilteredSchedule['slots'][iScheduleId]);

        let iInstructorId = oFilteredSchedule['instructors'][iScheduleId];
        let oInstructor = aInstructors.filter(oInstructor => oInstructor.id == iInstructorId)[0];

        $('.instructor').val(`${oInstructor.firstName} ${oInstructor.lastName}`);
    }

    /**
     * executeSubmit
     * @param {string} sFormId
     * @param {string} sRequestClass
     * @param {string} sRequestAction
     */
    function executeSubmit(sFormId, sRequestClass, sRequestAction) {
        const oFormData = new FormData($(sFormId)[0])
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

    function printRegiForm(oDetails) {
        window.open('/Nexus/utils/ajax.php?class=Student&action=printRegiForm&tId=' + oDetails.trainingId);
        // $.ajax({
        //     url: `/Nexus/utils/ajax.php?class=Student&action=printRegiForm`,
        //     type: 'POST',
        //     data: oDetails,
        //     dataType: 'json',
        //     success: function (oResponse) {

        //     },
        //     error: function () {
        //         // oLibraries.displayAlertMessage('error', 'An error has occured. Please try again.');
        //     }
        // });  
    }

    function fetchCourses() {
        $.ajax({
            url: `/Nexus/utils/ajax.php?class=Courses&action=fetchCoursesToEnroll`,
            type: 'GET',
            dataType: 'json',
            success: function (oResponse) {
                aEnrolledCourses = oResponse.aEnrolledCourses;
                aCoursesAvailable = oResponse.aCoursesAvailable;
                aInstructors = oResponse.aInstructors;
                populateEnrollmentTable();
            },
            error: function () {
                oLibraries.displayAlertMessage('error', 'An error has occured. Please try again.');
            }
        });
    }

    function populateEnrollmentTable() {
        let aColumnDefs = [
            { orderable: false, targets: [3, 4, 5, 6] }
        ];

        loadTable(oTblEnrollment.attr('id'), oColumns.aCourses, aColumnDefs);
    }

    function loadTable(sTableName, aColumns, aColumnDefs) {
        $(`#${sTableName} > tbody`).empty().parent().DataTable({
            destroy: true,
            deferRender: true,
            data: aEnrolledCourses,
            responsive: true,
            pagingType: 'first_last_numbers',
            pageLength: 4,
            ordering: true,
            order: [[1, 'asc']],
            searching: true,
            lengthChange: true,
            lengthMenu: [[4, 8, 12, 16, 20, 24, -1], [4, 8, 12, 16, 20, 24, 'All']],
            info: true,
            columns: aColumns,
            columnDefs: aColumnDefs
        });
    }

    return {
        initialize: init
    }

})();

$(() => {
    oEnrollment.initialize();
});
