<?php
require_once "template/studentHeader.php";
?>

<div class="container">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <p class="h2">Quotations</p>
    </div>

    <div class="table-responsive-sm table-responsive-md table-responsive-lg table-responsive-xl">
        <div align="right">
            <button type="button" id="addNewQuoteRequest" data-toggle="modal" data-target="#insertNewRequestModal" class="btn btn-info btn-lg">Add a Request</button>
            <br><br>
        </div>
        <table id="quotationRequests" style="width:100%" class="table table-striped table-bordered table-hover table-responsive-sm">
            <thead></thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="insertNewRequestModal" role="dialog">
    <div class="modal-dialog insertNewRequestModal">
        <div class="modal-content">
            <div class="modal-header">
                <h5 align="center">Insert New Request</h5>
            </div>
            <form action="post" id="insertNewRequestForm">
                <div class="modal-body">
                    <div class="alert alert-danger error-msg" role="alert" style="display: none;"></div>
                    <div class="form-group">
                        <label for="quoteCompanyName"><span class="far fa-building"></span> Company Name</label>
                        <div class="input-group">
                            <input type="text" class="form-control quoteCompanyName" placeholder="Company Name" name="quoteCompanyName" maxlength="50">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <input type="checkbox" name="quoteBillToCompany" class="quoteBillToCompany">&nbsp;Bill to Company?
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="courseAndScheduleDiv-new" style="display: none;">
                        <div class="form-group">
                            <label for="quoteCourse"><span class="fas fa-book"></span> Course</label>
                            <select class="form-control quoteCourse" name="quoteCourse[]"></select>
                        </div>
                        <div class="form-group">
                            <label for="quoteSchedule"><span class="fas fa-calendar-week"></span> Schedule</label>
                            <select class="form-control quoteSchedule" name="quoteSchedule[]" disabled>
                                <option value="" selected disabled hidden>Select Course First</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="numPax"><span class="fas fa-user-friends"></span> PAX</label>
                            <input type="number" class="form-control numPax" placeholder="Number of Persons" name="numPax[]" min="1" max="100" value="1">
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12 text-center" style="display: none;">
                                    <button type="button" class="btn btn-warning deleteCourseBtn">&nbsp;&nbsp;&nbsp;Delete Course&nbsp;&nbsp;&nbsp;</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-12 text-center">
                                <button type="button" class="btn btn-primary addCourseBtn">Add New Course</button>
                            </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Add</button>
                    <button type="button" class="btn btn-info" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="viewDetailsModal" role="dialog" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog modal-dialog-centered modal-xl viewDetailsModal">
		<div class="modal-content">
			<div class="modal-header">
				<h5 align="center"></span>Quotation Details</h5>
			</div>
			<div class="modal-body">
				<div class="table-responsive-sm table-responsive-md table-responsive-lg table-responsive-xl">
					<br><br>
					<table id="quotationDetails" style="width:100%" class="table table-striped table-bordered table-hover table-responsive-sm">
						<thead></thead>
						<tbody></tbody>
					</table>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="editRequestModal" role="dialog">
    <div class="modal-dialog editRequestModal">
        <div class="modal-content">
            <div class="modal-header">
                <h5 align="center">Edit Request Details</h5>
            </div>
            <form action="post" id="editRequestForm">
                <div class="modal-body">
                    <div class="alert alert-danger error-msg" role="alert" style="display: none;"></div>
                    <div class="form-group">
                        <label for="quoteCompanyName"><span class="far fa-building"></span> Company Name</label>
                        <div class="input-group">
                            <input type="text" class="form-control quoteCompanyName" placeholder="Company Name" name="quoteCompanyName" maxlength="50">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <input type="checkbox" name="quoteBillToCompany" class="quoteBillToCompany">&nbsp;Bill to Company?
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="template">
                        <div class="courseAndScheduleDiv-edit" style="display: none;">
                            <div class="form-group">
                                <label for="quoteCourse"><span class="fas fa-book"></span> Course</label>
                                <select class="form-control quoteCourse" name="quoteCourse[]"></select>
                            </div>
                            <div class="form-group">
                                <label for="quoteSchedule"><span class="fas fa-calendar-week"></span> Schedule</label>
                                <select class="form-control quoteSchedule" name="quoteSchedule[]">
                                    <option value="" selected disabled hidden>Select Course First</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="numPax"><span class="fas fa-user-friends"></span> PAX</label>
                                <input type="number" class="form-control numPax" placeholder="Number of Persons" name="numPax[]" min="1" max="100" value="1">
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12 text-center" style="display: none;">
                                    <button type="button" class="btn btn-warning deleteCourseBtn">&nbsp;&nbsp;&nbsp;Delete Course&nbsp;&nbsp;&nbsp;</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12 text-center">
                            <button type="button" class="btn btn-primary addCourseBtn">Add New Course</button>
                        </div>
                    </div>
                    <div class="form-group">
                        <p class="h6">To see available course and schedule, <a href="courses.php" target="_blank">Click here</a></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Update</button>
                    <button type="button" class="btn btn-info" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
require_once "template/scripts.php";
?>

<script src="/Nexus/utils/js/utils.Libraries.js"></script>
<script src="/Nexus/utils/js/utils.Validations.js"></script>
<script src="/Nexus/utils/js/utils.Forms.js"></script>

<script src="/Nexus/dashboard/js/student/student.quotationRequest.js"></script>

<?php
require_once "template/studentFooter.php";
?>