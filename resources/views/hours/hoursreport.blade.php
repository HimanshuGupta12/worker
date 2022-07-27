@extends(Auth::user() ? 'layouts.user' : 'layouts.worker')
@section('head')
    @parent
    <link href="/skote/assets/libs/magnific-popup/magnific-popup.css" rel="stylesheet" type="text/css" />
    <style type="text/css">
	.table-nowrap td .line_down {
    	display: block;
	}
	.table-nowrap td strong.invoiced {
    color: #34c38f;
	}
	.table-nowrap td strong.notinvoiced {
    color: #f1b44c;
	}

	td.action .mt-1 .badge {
    font-size: 15px;
    border-radius: 50%;
	}
	img.bg_logo {
    position: absolute;
    width: 120px;
    height: 65px;
    right: 0;
    top: 0;
	}
	</style>
@endsection

@section('content')

<div class="container-fluid">

	<!-- Filters -->
	<div class="row">
	   <div class="col-lg-12">
	      <div class="card">
	         <div class="card-body">

	         	<form class="row gy-2 gx-3 align-items-center">
	         		<div class="col-sm-auto">
	         			<h5 class="font-size-18 mb-0"><i class="bx bx-slider-alt"></i>  Sort</h5>
				    </div>

	         		<div class="col-sm-auto">
				      	<select class="form-select" id="autoSizingSelect" name="project">
					         <option selected=""> <i class="bx bx-slider-alt">Select Project</option>
					         <option value="1">ff</option>
					         <option value="2">Two</option>
					         <option value="3">Three</option>
				      	</select>
				    </div>

				    <div class="col-sm-auto">
				      	<select class="form-select" id="autoSizingSelect" name="worker">
					         <option selected="">Select Worker</option>
					         <option value="1">One</option>
					         <option value="2">Two</option>
					         <option value="3">Three</option>
				      	</select>
				    </div>
				    <div class="col-sm-auto">
				 
				      	<select class="form-select" id="autoSizingSelect" name="date">
					         <option selected="">Select Date</option>
					         <option value="1">One</option>
					         <option value="2">Two</option>
					         <option value="3">Three</option>
				      	</select>
				    </div>

					<div class="col-sm-auto">
				      	<select class="form-select" id="autoSizingSelect" name="date">
					         <option selected="">Select Option</option>
					         <option value="1">Mark Invoiced</option>
					         <option value="2">Dekete</option>
				      	</select>
				    </div>
				   
				 
				   <div class="col-sm-auto">
				      <button type="submit" class="btn btn-primary w-md">Apply</button>
				   </div>
				</form>

	         </div>
	      </div>
	   </div>
	</div>
	<!-- Filters End-->	
	            

	
<!-- Report summry Worker -->
	<div class="row">
	   <div class="col-xl-9">
	      <div class="card">
	         <div class="card-body">
	            <h4 class="card-title mb-4">Worker Details</h4>
	            <div class="row">
		          	<div class="col-md-6">
		          		<ul class="list-group">

		          			<li class="list-group-item border-0">
		          				<div class="media">
										
										<div class="avatar-xs me-3">
											<span class="avatar-title rounded-circle bg-light">
											<i class="bx bx-user text-primary"></i>
											</span>
										</div>

										<div class="media-body">
											<h5 class="font-size-18">Mindaugas Kasp</h5>
										</div>
									</div>	
		          			</li>
		          			
		          			<li class="list-group-item border-0">
		          				<div class="media">
										
										<div class="avatar-xs me-3">
											<span class="avatar-title rounded-circle bg-light">
											<i class="bx bx-calendar text-primary"></i>
											</span>
										</div>

										<div class="media-body">
											<h5 class="font-size-18">01.12.2021 to 31.12.2021</h5>
										</div>
									</div>	
		          			</li>

		          		</ul>

		          	</div>




		          	<div class="col-md-6">
		          			

		          			<ul class="list-group">

			          			<li class="list-group-item border-0">
			          				<div class="media">
											
											<div class="avatar-xs me-3">
												<span class="avatar-title rounded-circle bg-light">
												<i class="bx bx-building-house text-primary"></i>
												</span>
											</div>

											<div class="media-body">
												<h5 class="font-size-18">6 , Projects</h5>
											</div>
										</div>	
			          			</li>
			          			
			          			<li class="list-group-item border-0">
			          				<div class="media">
											
											<div class="avatar-xs me-3">
												<span class="avatar-title rounded-circle bg-light">
												<i class="bx bx-calendar text-primary"></i>
												</span>
											</div>

											<div class="media-body">
												<h5 class="font-size-18">30, Days</h5>
											</div>
										</div>	
			          			</li>

		          		</ul>

		          	</div>

	          	</div>
	         </div>
	      </div>
	   </div>

	   <div class="col-xl-3">
	      <div class="card bg-primary">
	         <div class="card-body">
	            <h4 class="card-title mb-4 text-white">Hours Details</h4>
	            <div class="row">
	            <div class="col-md-6">
	            	<h1 class="text-white">160.83</h1>
	            	<span class="text-white">Total Hours</span>
	            </div>
	            <div class="col-md-6">
	            	<h1 class="text-white">3254</h1>
	            	<span class="text-white">Salary</span>
	            </div>
	            </div>
	          <img src="/img/trap-logo.png" alt="" height="40" class="bg_logo">
	         
	         </div>
	      </div>
	   </div>
	</div>

<!-- Report summry Worker End  -->


<!-- Report summry Project -->
	<div class="row">
	   <div class="col-xl-9">
	      <div class="card">
	         <div class="card-body">
	            <h4 class="card-title mb-4">Project Details</h4>
	            <div class="row">
		          	<div class="col-md-6">
		          		<ul class="list-group">

		          			<li class="list-group-item border-0">
		          				<div class="media">
										
										<div class="avatar-xs me-3">
											<span class="avatar-title rounded-circle bg-light">
											<i class="bx bx-user text-primary"></i>
											</span>
										</div>

										<div class="media-body">
											<h5 class="font-size-18">House Renovation 13</h5>
										</div>
									</div>	
		          			</li>
		          			
		          			<li class="list-group-item border-0">
		          				<div class="media">
										
										<div class="avatar-xs me-3">
											<span class="avatar-title rounded-circle bg-light">
											<i class="bx bx-calendar text-primary"></i>
											</span>
										</div>

										<div class="media-body">
											<h5 class="font-size-18">01.12.2021 to 31.12.2021</h5>
										</div>
									</div>	
		          			</li>

		          		</ul>

		          	</div>




		          	<div class="col-md-6">
		          			

		          			<ul class="list-group">

			          			<li class="list-group-item border-0">
			          				<div class="media">
											
											<div class="avatar-xs me-3">
												<span class="avatar-title rounded-circle bg-warning bg-soft">
												<i class="bx bx-building-house text-warning"></i>
												</span>
											</div>

											<div class="media-body">
												<h5 class="font-size-18">Ahlmanns Alle 9, Hellerup, 2900, Dk</h5>
											</div>
										</div>	
			          			</li>
			          			
			          			<li class="list-group-item border-0">
			          				<div class="media">
											
											<div class="avatar-xs me-3">
												<span class="avatar-title rounded-circle bg-light">
												<i class="bx bx-calendar text-primary"></i>
												</span>
											</div>

											<div class="media-body">
												<h5 class="font-size-18">6 ,Members</h5>
											</div>
										</div>	
			          			</li>

		          		</ul>

		          	</div>

	          	</div>
	         </div>
	      </div>
	   </div>

	   <div class="col-xl-3">
	      <div class="card bg-primary">
	         <div class="card-body">
	            <h4 class="card-title mb-4 text-white">Hours Details</h4>
	            <div class="row">
	            <div class="col-md-6">
	            	<h1 class="text-white">160.83</h1>
	            	<span class="text-white">Total Hours</span>
	            </div>
	            <div class="col-md-6">
	            	<h1 class="text-white">3254</h1>
	            	<span class="text-white">Salary</span>
	            </div>
	            </div>
	          <img src="/img/trap-logo.png" alt="" height="40" class="bg_logo">
	         
	         </div>
	      </div>
	   </div>
	</div>

<!-- Report summry Project End  -->


	<!-- Entries --> 
<div class="row">
   <div class="col-lg-12">
      <div class="card">
         <div class="card-body">
            <h4 class="card-title mb-4">Latest data</h4>
            <div class="table-responsive">
               <table class="table align-middle table-nowrap mb-0">
                  <thead class="table-light">
                     <tr>
                        <th style="width: 20px;">
                           <div class="form-check font-size-16 align-middle">
                              <input class="form-check-input" type="checkbox" id="transactionCheck01">
                              <label class="form-check-label" for="transactionCheck01"></label>
                           </div>
                        </th>
                        <th class="align-middle">Date</th>
                        <th class="align-middle">Worker</th>
                        <th class="align-middle">Work Hours</th>
                        <th class="align-middle">Lunch</th>
                        <th class="align-middle">Project / Comments</th>
                        <th class="align-middle">Invoice</th>
                        <th class="align-middle">Action</th>
                     </tr>
                  </thead>
                  <tbody>
                     <tr>
                        <td>
                           <div class="form-check font-size-16">
                              <input class="form-check-input" type="checkbox" id="transactionCheck02">
                              <label class="form-check-label" for="transactionCheck02"></label>
                           </div>
                        </td>
                        <td><strong>09.11.2021 </strong><span class="line_down">Tuesday - (45)</span> </td>
                        <td><strong>Mindaugas Kasparvicius </strong><span class="line_down">(Assistant Worker)</span> </td>
                        <td><strong>09:30 to 17:00</strong><span class="line_down">Total : 7 Hr</span></td>
                        <td><strong>Yes</strong><span class="line_down">30 Min</span></td>
                        <td><strong>Ahimanns alle 9</strong><span class="line_down">Demoition of the roof</span></td>
                        <td><strong class="invoiced">Invoiced</strong><span class="line_down">29.12.2021</span></td>
                        <td class="action"><div class="mt-1"> <span class="badge badge-soft-dark"><i class="bx bx-edit"></i></span> <span class="badge badge-soft-warning"><i class="bx bx-image-alt"></i></span><i class="bx bx-dots-vertical-rounded"></i> </div></td>
                    </tr>

					<tr>
                        <td>
                           <div class="form-check font-size-16">
                              <input class="form-check-input" type="checkbox" id="transactionCheck02">
                              <label class="form-check-label" for="transactionCheck02"></label>
                           </div>
                        </td>
                        <td><strong>09.11.2021 </strong><span class="line_down">Tuesday - (45)</span> </td>
                        <td><strong>Mindaugas Kasparvicius </strong><span class="line_down">(Assistant Worker)</span> </td>
                        <td><strong>09:30 to 17:00</strong><span class="line_down">Total : 7 Hr</span></td>
                        <td><strong>Yes</strong><span class="line_down">30 Min</span></td>
                        <td><strong>Ahimanns alle 9</strong><span class="line_down">Demoition of the roof</span></td>
                        <td><strong class="notinvoiced">Not</strong><span class="line_down">29.12.2021</span></td>
                        <td class="action"><div class="mt-1"> <span class="badge badge-soft-dark"><i class="bx bx-edit"></i></span> <span class="badge badge-soft-warning"><i class="bx bx-image-alt"></i></span><i class="bx bx-dots-vertical-rounded"></i> </div></td>
                    </tr>
            		
            		<tr>
                        <td>
                           <div class="form-check font-size-16">
                              <input class="form-check-input" type="checkbox" id="transactionCheck02">
                              <label class="form-check-label" for="transactionCheck02"></label>
                           </div>
                        </td>
                        <td><strong>09.11.2021 </strong><span class="line_down">Tuesday - (45)</span> </td>
                        <td><strong>Mindaugas Kasparvicius </strong><span class="line_down">(Assistant Worker)</span> </td>
                        <td><strong>09:30 to 17:00</strong><span class="line_down">Total : 7 Hr</span></td>
                        <td><strong>Yes</strong><span class="line_down">30 Min</span></td>
                        <td><strong>Ahimanns alle 9</strong><span class="line_down">Demoition of the roof</span></td>
                        <td><strong class="invoiced">Invoiced</strong><span class="line_down">29.12.2021</span></td>
                        <td class="action"><div class="mt-1"> <span class="badge badge-soft-dark"><i class="bx bx-edit"></i></span> <span class="badge badge-soft-warning"><i class="bx bx-image-alt"></i></span><i class="bx bx-dots-vertical-rounded"></i> </div></td>
                    </tr>

                     
                  </tbody>
               </table>
            </div>
         </div>
      </div>
   </div>
</div>





</div>

@endsection