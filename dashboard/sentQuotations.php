<?php
require_once "Template/header.php";
?>


	<div class="container">
		<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
			<p class="h2">Sent Quotations</p>

		</div>

		<div class="table-responsive-sm table-responsive-md table-responsive-lg table-responsive-xl">
			<div align="right">
			</div>
			<table id="tbl_quotations" style="width:100%" class="table table-striped table-bordered table-hover">
				<thead>
					<tr>
						<th style="white-space:nowrap;">Date Sent</th>
						<th style="white-space:nowrap;">Student Name</th>
						<th style="white-space:nowrap;">Company Name</th>
						<th style="white-space:nowrap;">E-mail Address</th>
						<th style="white-space:nowrap;">Contact No.</th>
						<th style="white-space:nowrap;">Actions</th>
					</tr>
				</thead>
				<tbody>
				<tr>
					<td>2020-Feb-21</td>
					<td>Aries Valenzuela Macandili</td>
					<td>Simplex Internet Philippines</td>
					<td>macandili.aries@gmail.com</td>
					<td>09161234567</td>
					<td>
						<button class="btn btn-info" data-toggle="modal" data-target="#viewQuoteModal"><i class="fa fa-eye"></i></button>
						<button class="btn btn-secondary" data-toggle="modal" data-target="#editQuoteModal"><i class="fa fa-pen"></i></button>
					</td>
				</tr>
				<tr>
					<td>2020-Feb-21</td>
					<td>Angelika Aubrey Arbiol</td>
					<td>Nexus Technologies</td>
					<td>obwee@gmail.com</td>
					<td>09261759750</td>
					<td>
						<button class="btn btn-info" data-toggle="modal" data-target="#viewQuoteModal"><i class="fa fa-eye"></i></button>
						<button class="btn btn-secondary" data-toggle="modal" data-target="#editQuoteModal"><i class="fa fa-pen"></i></button>
					</td>
				</tr>
				</tbody>
			</table>
		</div>
	</div>

	<div class="modal fade" id="viewQuoteModal" tabindex="-1" role="dialog">
    	<div class="modal-dialog viewQuoteModal">
    		<div class="modal-content">
    			<div class="modal-header" style="background-color: #A2C710;">
    				<h5 align="center">View Quotation</h5>
    			</div>
    			<div class="modal-body">
    				<div class="quoteBody">
    					
    				</div>
    			</div>
    			<div class="modal-footer">
    				<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
    			</div>
    		</div>
    	</div>
    </div>

    <!--Get Quote Modal-->

    <div class="modal fade" id="editQuoteModal" role="dialog">
        <div class="modal-dialog editQuoteModal">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #A2C710;">
                	<h5 align="center">Edit Quotation Request</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" id="quotationForm">
                        <div class="form-group">
                            <label for="quoteCourse"><span class="fas fa-book"></span> Course</label>
                            <select class="form-control">
                            	
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="scheduleType"><span class="fas fa-calendar-week"></span> Schedule </label>
                            <select class="form-control">
                            	
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#sendRequestModal">Update</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="sendRequestModal" role="dialog">
    	<div class="modal-dialog modal-lg sendRequestModal">
    		<div class="modal-content">
    			<div class="modal-header" style="background-color: #A2C710;">
    				<h5 align="center"><span class="glyphicon glyphicon-plane"></span>Send Updated Quotation Request</h5>
    			</div>
    			
    			<div class="modal-body">
    				<div class="form-group">
                        <label for="subjectQuote"><span class="fas fa-envelope"></span> Subject</label>
                        <input type="text" class="form-control" id="subjectQuote" name="subjectQuote" placeholder="Subject" autofocus maxlength="30">
                    </div>
                    <div class="form-group">
                    	<label for=quoteMessage><span class="fas fa-envelope-open-text"></span> Message</label>
                        <textarea class="form-control" id="emailMsg" name="emailMsg" rows="7" placeholder="Type your message here."></textarea>
                    </div>
    			</div>
    			<div class="modal-footer">
    				<button type="button" class="btn btn-success">Send</button>
    				<button type="button" class="btn btn-info" data-dismiss="modal">Cancel</button>
    			</div>
    		</div>
    	</div>
    </div>


<?php
require_once "template/scripts.php";
?>

<script src="js/dashboard.sentQuotations.js"></script>

<?php
require_once "Template/footer.php";
?>