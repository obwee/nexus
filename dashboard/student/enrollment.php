<?php
require_once "template/studentHeader.php";
?>

<div class="container">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <p class="h3">Enrollment</p>
    </div>

    <div align="right">
        <button type="button" id="enrollBtn" class="btn btn-primary btn-lg">Enroll</button>
    </div>

    <div class="container">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h5>Reserved Trainings</h5>
        </div>

        <div class="table-responsive-sm table-responsive-md table-responsive-lg table-responsive-xl">
            <table id="tbl_enrollment" style="width:100%" class="table table-striped table-bordered table-hover table-responsive-sm">
                <thead></thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="enrollModal" role="dialog" data-backdrop="static">
        <div class="modal-dialog enrollModal modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #605ca8;">
                    <h5 align="center" style="color: white;"><i class="fas fa-university"></i> Enrollment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" style="color:white">&times;</span>
                    </button>
                </div>
                <form action="POST" id="enrollForm">
                    <div class="modal-body">
                        <div class="alert alert-danger error-msg" role="alert" style="display: none;"></div>
                        <div class="form-group">
                            <label><i class="fas fa-book"></i> Course</label>
                            <select class="form-control courses" name="courses">
                                <option selected disabled hidden>Select Course</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label><i class="fas fa-calendar-alt"></i> Schedule</label>
                            <select class="form-control schedules" name="schedules">
                                <option selected disabled hidden>Select Schedule</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label><i class="fas fa-map"></i> Venue</label>
                            <input type="text" class="form-control venue" readonly>
                        </div>
                        <div class="form-group">
                            <label><i class="fas fa-money"></i> Price</label>
                            <input type="text" class="form-control price" readonly>
                        </div>
                        <div class="form-group">
                            <label><i class="fas fa-users"></i> Available Slots</label>
                            <input type="text" class="form-control slots" readonly>
                        </div>
                        <div class="form-group">
                            <label><i class="fas fa-chalkboard"></i> Instructor</label>
                            <input type="text" class="form-control instructor" readonly>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center">
                        <div class="spinner-border spinner" role="status" style="display:none;">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Submit</button>
                        <button type="button" class="btn btn-info" data-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="cancelReservationModal" role="dialog" data-backdrop="static">
        <div class="modal-dialog cancelReservationModal modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #605ca8;">
                    <h5 align="center" style="color:white;">Cancel Reservation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" style="color:white">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger error-msg" role="alert" style="display: none;"></div>
                    <div style="border:1px solid #d5d5d5; padding:5px 5px;border-radius:3px 3px;text-align: justify">
                        <p><i class="fas fa-exclamation-circle" style="color:red;"></i> To RESCHEDULE your training, please contact us immediately.</p>
                        <p><i class="fas fa-exclamation-circle" style="color:red;"></i> Refunds requests should be submitted atleast three (3) days before your reserved schedule.</p>
                        <p><i class="fas fa-exclamation-circle" style="color:red;"></i> Refunds are not allowed if the student decides to backout on the first day of class.</p>
                        <p><i class="fas fa-exclamation-circle" style="color:red;"></i> Upon receiving your request, an admin will contact you regarding your refund.</p>
                        <p><i class="fas fa-exclamation-circle" style="color:red;"></i> Please give us one (1) week to process your request.</p>
                    </div>
                    <p>To view the complete terms and conditions, click <a href="">here</a>.</p>
                    <div class="form-group">
                        <label for="refundReason"><i class="fas fa-comments"></i> Refund reason:</label>
                        <textarea class="form-control" id="refundReason" rows="4"></textarea>
                    </div>
                    <div>
                        <div class="custom-control custom-checkbox mr-sm-2">
                            <input type="checkbox" class="custom-control-input" id="customControlAutosizing">
                            <label class="custom-control-label" for="customControlAutosizing" style="text-align:justify;">I have read, understood and agreed to the terms and conditions stated above. I understand that submitting this request does not guarantee the request to be accepted and processed immediately.</label>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center">
                        <div class="spinner-border spinner" role="status" style="display:none;">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Submit</button>
                        <button type="button" class="btn btn-info" data-dismiss="modal">Cancel</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="viewPaymentModal" role="dialog" data-backdrop="static">
        <div class="modal-dialog modal-lg viewPaymentModal modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #605ca8;">
                    <h5 align="center" style="color:white;">Payment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" style="color:white">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div style="border: 3px solid #d5d5d5;padding-top:5px;padding-left:5px;padding-right:5px;padding-bottom:0;border-radius: 4px 4px;margin-bottom:5px;">
                        <b>Training Details:</b>
                        <div class="form-group row" style="margin-left:15px;">
                            <label for="course" class="col-sm-3 col-form-label"><span class="fas fa-book"></span> <b>Course</b></label>
                            <div class="col-sm-9">
                                <input type="text" readonly class="form-control-plaintext" id="course" value="Ethical Hacking with Penetration Testing">
                            </div>

                            <label for="sched" class="col-sm-3 col-form-label"><span class="fas fa-calendar-alt"></span> <b>Schedule</b></label>
                            <div class="col-sm-9">
                                <input type="text" readonly class="form-control-plaintext" id="sched" value="April 29 - 30, 2020">
                            </div>

                            <label for="venue" class="col-sm-3 col-form-label"><span class="fas fa-map"></span> <b>Venue</b></label>
                            <div class="col-sm-9">
                                <input type="text" readonly class="form-control-plaintext" id="venue" value="Makati">
                            </div>

                            <label for="instructor" class="col-sm-3 col-form-label"><span class="fas fa-chalkboard-teacher"></span> <b>Instructor</b></label>
                            <div class="col-sm-9">
                                <input type="text" readonly class="form-control-plaintext" id="course" value="Richard Reblando">
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive-sm table-responsive-md table-responsive-lg table-responsive-xl">
                        <table id="tbl_paymentDetails" style="width:100%" class="table table-striped table-bordered table-hover table-responsive-sm">
                            <thead>
                                <tr style="white-space:nowrap;text-align:center;">
                                    <th>Date Paid</th>
                                    <th>MOP</th>
                                    <th>Training Fee</th>
                                    <th>Amount Paid</th>
                                    <th>Remaining Balance</th>
                                    <th>Payment Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr style="text-align: center;">
                                    <td>April 1, 2020</td>
                                    <td>BDO</td>
                                    <td>P3,000.00</td>
                                    <td>P1,500.00</td>
                                    <td>P0.00</td>
                                    <td>Partial</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success addPayment">Add Payment</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addPaymentModal" data-backdrop="static" role="dialog">
        <div class="modal-dialog modal-lg modal-dialog-centered addPaymentModal">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #605ca8;">
                    <h5 align="center" style="color: white;"><i class="fas fa-upload"></i> Add Payment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" style="color:white">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div>
                        <ol>
                            <li>We accept CASH, BDO DEPOSIT, BDO BANK TRANSFER and CHEQUE payments.</li>
                            <li>Pay the training fee by depositing at any BDO branch.</li>
                            <ul>
                                <li>Account Name: Nexus IT Training Center</li>
                                <li>BDO Account Number: 002810078994</li>
                            </ul>
                            <li>Upload a picture or PDF file of the proof of payment below.</li>
                        </ol>
                    </div>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="customFile">
                        <label class="custom-file-label" for="customFile">Upload File</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Submit</button>
                    <button type="button" class="btn btn-info" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

</div>

<?php
require_once "template/scripts.php";
?>

<script src="/Nexus/utils/js/utils.Libraries.js"></script>
<script src="/Nexus/utils/js/utils.Validations.js"></script>
<script src="/Nexus/utils/js/utils.Forms.js"></script>

<script src="/Nexus/dashboard/js/student/student.enrollment.js"></script>

<?php
require_once "template/studentFooter.php";
?>